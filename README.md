
<div align="center">

# 🌟 SIM-PPKS (Project Magang DINSOS)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)

**Platform Manajemen Data Terpadu Dinas Sosial dan Pemberdayaan Masyarakat Kota Batam**

</div>

---

## 📖 Tentang Project

**SIM-PPKS** adalah solusi digital yang dirancang khusus untuk menggantikan proses pendataan manual dan pengelolaan spreadsheet yang terpisah-pisah di Dinas Sosial. Aplikasi ini mempermudah pencatatan, pelaporan, dan monitoring aktivitas harian serta data PPKS secara efisien dan *real-time*.

## ✨ Fitur Unggulan

🚀 **Pencatatan Aktivitas Harian**
Input kegiatan harian dengan detail lengkap, status, dan bukti foto.

📊 **Ekspor Laporan Otomatis**
Unduh laporan aktivitas dalam format Excel dengan filter harian, mingguan, atau bulanan.

📱 **Berbagi ke WhatsApp**
Bagikan detail aktivitas langsung ke WhatsApp dengan *card* gambar yang digenerate otomatis (menggunakan html2canvas) dan template teks yang rapi.

🎨 **Antarmuka Modern**
Desain responsif dan user-friendly berbasis Tailwind CSS yang nyaman digunakan di Desktop maupun Mobile.

## 🛠️ Teknologi yang Digunakan

Aplikasi ini dibangun dengan teknologi web modern untuk menjamin performa dan kemudahan pengembangan:

| Kategori | Teknologi |
|----------|-----------|
| **Framework** | [Laravel 10](https://laravel.com) |
| **Styling** | [Tailwind CSS](https://tailwindcss.com) |
| **Database** | MySQL |
| **Scripting** | JavaScript (Vanilla + Plugins) |
| **Library** | `html2canvas` (Image Gen), `maatwebsite/excel` (Export) |

## ⚙️ Instalasi

Ikuti langkah berikut untuk menjalankan project ini di komputer lokal Anda:

1.  **Clone Repository**
    ```bash
    git clone https://github.com/DoniArmanS/Project_Magang_DINSOS.git
    cd Project_Magang_DINSOS
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    Salin file `.env.example` menjadi `.env` dan konfigurasi database Anda.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Migrasi Database**
    ```bash
    php artisan migrate
    ```

5.  **Link Storage**
    ```bash
    php artisan storage:link
    ```

6.  **Jalankan Aplikasi**
    ```bash
    npm run dev
    # Di terminal lain:
    php artisan serve
    ```

Aplikasi siap diakses di `http://127.0.0.1:8000` 🚀

---

<div align="center">

### Dibuat dengan ❤️ oleh **DoniArmanS**

&copy; 2026 Project Magang DINSOS

</div>
