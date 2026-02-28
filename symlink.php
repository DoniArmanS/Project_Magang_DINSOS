<?php
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo 'Link /storage sudah ada. Menghapus dulu...<br>';
    if (is_link($link)) {
        unlink($link);
    } else {
        rmdir($link); // jika folder kosong
    }
}

if (symlink($target, $link)) {
    echo '✅ Symlink berhasil dibuat! Gambar seharusnya muncul sekarang.';
} else {
    echo '❌ Gagal membuat symlink.';
}