# Task Manager App

Task Manager App adalah aplikasi manajemen tugas sederhana yang memungkinkan pengguna untuk membuat, mengubah status, dan mencari tugas. Aplikasi ini dibangun dengan PHP dan MySQL.

## Panduan Instalasi

Berikut adalah langkah-langkah untuk menyiapkan server dan menjalankan aplikasi Task Manager di lingkungan lokal Anda.

### Persyaratan

- PHP 7.x atau versi lebih baru
- MySQL Server
- Web Server (misalnya, Apache)

### Langkah-langkah Instalasi

1. **Persiapkan Database:**

   - Buat database baru di MySQL dengan nama `task_manager`.
   - Buat tabel `tasks` dengan struktur sebagai berikut:
     - `id` (int, auto increment, primary key)
     - `title` (varchar(255))
     - `description` (text)
     - `status` (enum: "Pending", "In Progress", "Completed")
     - `created_at` (timestamp, default: current timestamp)
     - `updated_at` (timestamp, default: current timestamp on update)

2. **Konfigurasi Aplikasi:**

   - Buka file `config.php` dalam direktori aplikasi.
   - Sesuaikan pengaturan database (hostname, username, password, nama database) sesuai dengan konfigurasi MySQL Anda.

3. **Menjalankan Aplikasi:**

   - Pastikan web server (misalnya, Apache) telah aktif.
   - Salin semua file proyek ke direktori web server Anda (misalnya, `htdocs` untuk Apache).
   - Buka browser dan akses aplikasi dengan URL `http://localhost/taskmanager/` (sesuaikan dengan direktori tempat Anda menempatkan aplikasi).

## Penggunaan Aplikasi

- Akses aplikasi melalui URL `http://localhost/project/`.
- Anda dapat melihat daftar tugas, menambah tugas baru, mengubah status tugas, dan melakukan pencarian berdasarkan judul atau status.
- Gunakan formulir di halaman utama untuk mengelola tugas.

## Kontribusi

Jika Anda ingin berkontribusi pada pengembangan aplikasi ini, silakan kirimkan permintaan pull (pull request) atau laporkan masalah (issue) di repositori GitHub kami: [link-repo](https://github.com/rizkyrizkyjs/taskmanager).

## Lisensi

Aplikasi ini dilisensikan di bawah Lisensi MIT - lihat file [LICENSE](LICENSE) untuk detailnya.

---

Terima kasih telah menggunakan Task Manager App! Jika Anda memiliki pertanyaan atau masalah, jangan ragu untuk menghubungi kami di [rizkyjounioselabu@gmail.com](mailto:rizkyjounioselabu@gmail.com).