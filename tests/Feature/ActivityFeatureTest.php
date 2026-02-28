<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $peksos;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Dinsos',
            'email' => 'admin@dinsos.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'jenis_user' => 'Admin',
        ]);

        $this->peksos = User::create([
            'name' => 'Peksos 1',
            'email' => 'peksos1@dinsos.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'jenis_user' => 'Peksos',
        ]);
    }

    // =========================================================================
    // AUTHENTICATION
    // =========================================================================

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/activities')->assertRedirect('/login');
        $this->get('/activities/create')->assertRedirect('/login');
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertStatus(200)->assertSee('Selamat Datang');
    }

    public function test_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@dinsos.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@dinsos.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout(): void
    {
        $this->actingAs($this->admin)
            ->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    // =========================================================================
    // DASHBOARD
    // =========================================================================

    public function test_dashboard_loads_with_stats(): void
    {
        $this->actingAs($this->admin)
            ->get('/')
            ->assertStatus(200)
            ->assertSee('Dashboard')
            ->assertSee('Total Kegiatan')
            ->assertSee('Selesai')
            ->assertSee('Sedang Proses');
    }

    public function test_dashboard_ajax_returns_chart_json(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/?period=7hari', ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJsonStructure(['chartLabels', 'chartDatasets', 'chartSubtitle']);
    }

    // =========================================================================
    // ACTIVITY CRUD
    // =========================================================================

    public function test_create_form_accessible(): void
    {
        $this->actingAs($this->peksos)
            ->get('/activities/create')
            ->assertStatus(200)
            ->assertSee('Buat Laporan Baru');
    }

    public function test_store_activity_with_valid_data(): void
    {
        $response = $this->actingAs($this->peksos)->post('/activities', [
            'nama' => 'Test Klien',
            'tanggal' => '2026-02-28 10:00',
            'kegiatan' => 'Pendampingan sosial',
            'status' => 'Sedang Proses',
            'kategori' => 'ODGJ',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $response->assertRedirect(route('activities.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('activities', [
            'nama' => 'Test Klien',
            'kategori' => 'ODGJ',
            'user_id' => $this->peksos->id,
        ]);
    }

    public function test_store_activity_validation_fails(): void
    {
        $response = $this->actingAs($this->peksos)->post('/activities', [
            // Missing required fields
        ]);

        $response->assertSessionHasErrors(['nama', 'tanggal', 'kegiatan', 'status', 'kategori', 'jenis_kelamin']);
    }

    public function test_index_page_shows_activities(): void
    {
        $activity = Activity::create([
            'user_id' => $this->peksos->id,
            'nama' => 'Budi Santoso',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Pendampingan ODGJ',
            'status' => 'Sedang Proses',
            'kategori' => 'ODGJ',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $this->actingAs($this->peksos)
            ->get('/activities')
            ->assertStatus(200)
            ->assertSee('Budi Santoso')
            ->assertSee('ODGJ');
    }

    // =========================================================================
    // ADMIN: CAN DELETE & PROCESS STATUS
    // =========================================================================

    public function test_admin_can_delete_any_activity(): void
    {
        $activity = Activity::create([
            'user_id' => $this->peksos->id,
            'nama' => 'To Delete',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Selesai',
            'kategori' => 'Anak',
            'jenis_kelamin' => 'Perempuan',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete("/activities/{$activity->id}");

        $response->assertRedirect(route('activities.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }

    public function test_admin_can_update_status_sedang_proses_to_selesai(): void
    {
        $activity = Activity::create([
            'user_id' => $this->admin->id,
            'nama' => 'Admin Activity',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Sedang Proses',
            'kategori' => 'Terlantar',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch("/activities/{$activity->id}/status", ['status' => 'Selesai']);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_admin_can_revert_status_selesai_to_sedang_proses(): void
    {
        $activity = Activity::create([
            'user_id' => $this->admin->id,
            'nama' => 'Admin Revert',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Selesai',
            'kategori' => 'Lansia',
            'jenis_kelamin' => 'Perempuan',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch("/activities/{$activity->id}/status", ['status' => 'Sedang Proses']);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'Sedang Proses',
        ]);
    }

    // =========================================================================
    // USER (PEKSOS): CANNOT DELETE, LIMITED STATUS UPDATE
    // =========================================================================

    public function test_user_cannot_delete_activity(): void
    {
        $activity = Activity::create([
            'user_id' => $this->peksos->id,
            'nama' => 'Peksos Activity',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Sedang Proses',
            'kategori' => 'Tuna Susila',
            'jenis_kelamin' => 'Perempuan',
        ]);

        $response = $this->actingAs($this->peksos)
            ->delete("/activities/{$activity->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('activities', ['id' => $activity->id]);
    }

    public function test_user_can_update_status_sedang_proses_to_selesai(): void
    {
        $activity = Activity::create([
            'user_id' => $this->peksos->id,
            'nama' => 'Peksos Selesai',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Sedang Proses',
            'kategori' => 'ODGJ',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $response = $this->actingAs($this->peksos)
            ->patch("/activities/{$activity->id}/status", ['status' => 'Selesai']);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_user_cannot_revert_status_selesai_to_sedang_proses(): void
    {
        $activity = Activity::create([
            'user_id' => $this->peksos->id,
            'nama' => 'Peksos No Revert',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Selesai',
            'kategori' => 'Anak',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $response = $this->actingAs($this->peksos)
            ->patch("/activities/{$activity->id}/status", ['status' => 'Sedang Proses']);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_user_cannot_delete_other_users_activity(): void
    {
        $activity = Activity::create([
            'user_id' => $this->admin->id,
            'nama' => 'Admin Owned',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Selesai',
            'kategori' => 'Lansia',
            'jenis_kelamin' => 'Perempuan',
        ]);

        $response = $this->actingAs($this->peksos)
            ->delete("/activities/{$activity->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('activities', ['id' => $activity->id]);
    }

    // =========================================================================
    // EXPORT
    // =========================================================================

    public function test_export_route_requires_auth(): void
    {
        // Note: Full export test (Excel::store) hangs in PHPUnit in-memory SQLite env.
        // The export feature should be verified manually via browser:
        //   Login as admin → go to /activities → click Export
        //   Verify "Kegiatan.xlsx" downloads and opens in Excel.

        // We verify the route exists and requires auth
        $this->get('/activities/export')->assertRedirect('/login');
    }

    // =========================================================================
    // VIEW: UI PERMISSION CHECKS (Admin sees delete, user does not)
    // =========================================================================

    public function test_admin_sees_delete_button_on_index(): void
    {
        Activity::create([
            'user_id' => $this->peksos->id,
            'nama' => 'View Test',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Sedang Proses',
            'kategori' => 'ODGJ',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $this->actingAs($this->admin)
            ->get('/activities')
            ->assertStatus(200)
            ->assertSee('Hapus');
    }

    public function test_user_does_not_see_delete_button_on_index(): void
    {
        Activity::create([
            'user_id' => $this->peksos->id,
            'nama' => 'View Test User',
            'tanggal' => '2026-02-28 10:00:00',
            'kegiatan' => 'Test',
            'status' => 'Sedang Proses',
            'kategori' => 'ODGJ',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $this->actingAs($this->peksos)
            ->get('/activities')
            ->assertStatus(200)
            ->assertDontSee('Hapus');
    }
}