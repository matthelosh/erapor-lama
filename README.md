# EraporSD  

## Fitur
### Admin:
1. Manajemen Guru
2. Manajemen Siswa
3. Manajemen User
4. manajemen Rombel
5. Data Sekolah
6. Pengaturan Rapor
7. Pengaturan Periode
### Wali Kelas
1. Manajemen Siswa
2. Entri Nilai (Harian, PTS, PAS)
3. Cetak Rapor (PTS, PAS)
4. Cetak Kartu Siswa / NISN
### Guru Mapel
1. Entri Nilai 

### Siswa / Orang Tua
1. Update Profil Siswa
2. Melihat Nilai dan Rapor Siswa

## Instalasi:
<p>Pastikan sudah terinstal Git, Web Server, PHP ^7.4, MySQl/MariaDB, Composer, Node, NPM</p>

### Clone repo ini menggunakan git
1. Masuk ke terminal(unix) / Command Line (Windows) dan pindah ke root direktori web server ex. c:\xampp\htdocs (Windows), /var/www/ (Linux)
2. Ketikkan perintah:  
	`git clone https://github.com/matthelosh/noname.git nama*aplikasi`
3. Install dependency laravel:
	`composer install`
4. Copy file .env.example -> .env
5. Buat key aplikasi:
	`php artisan key:generate`
6. Ubah konfigurasi pada file .env sesuai dengan database
7. (Khusus linux) ubah hak akses pada folder storage
	`sudo chmod -R 777 storage`
8. Migrasi tabel ke database
	`php artisan migrate`
9. Isikan beberapa data master
	`php artisan db:seed --class=DatabaseSeeder`
10. Buka Browser dan masukkan alamat lokal sesuai konfigurasi.
11. Selesai