-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- Dump data of "temas" ------------------------------------
BEGIN;

INSERT INTO `temas`(`id`,`kode_tema`,`teks`,`created_at`,`updated_at`,`kelas_id`) VALUES 
( '1', '11', 'Diriku', NULL, NULL, '1' ),
( '2', '12', 'Kegemaranku', NULL, NULL, '1' ),
( '3', '13', 'Kegiatanku', NULL, NULL, '1' ),
( '4', '14', 'Keluargaku', NULL, NULL, '1' ),
( '5', '15', 'Pengalamanku', NULL, NULL, '1' ),
( '6', '16', 'Lingkungan bersih, sehat dan asri', NULL, NULL, '1' ),
( '7', '17', 'Benda, Hewan dan Tanaman Sekitarku', NULL, NULL, '1' ),
( '8', '18', 'Peristiwa Alam', NULL, NULL, '1' ),
( '9', '21', 'Hidup Rukun', NULL, NULL, '2' ),
( '10', '22', 'Bermain di Lingkunganku', NULL, NULL, '2' ),
( '11', '23', 'Tugasku Sehari-hari', NULL, NULL, '2' ),
( '12', '24', 'Hidup Bersih dan Sehat', NULL, NULL, '2' ),
( '13', '25', 'Pengalamanku', NULL, NULL, '2' ),
( '14', '26', 'Merawat Hewan dan Tumbuhan', NULL, NULL, '2' ),
( '15', '27', 'Kebersamaan', NULL, NULL, '2' ),
( '16', '28', 'Keselamatan di Rumah dan Perjalanan', NULL, NULL, '2' ),
( '17', '31', 'Pertumbuhan dan Perkembangan Makhluk Hidup', NULL, NULL, '3' ),
( '18', '32', 'Menyayangi Tumbuhan dan Hewan', NULL, NULL, '3' ),
( '19', '33', 'Benda di Sekitarku', NULL, NULL, '3' ),
( '20', '34', 'Kewajiban dan Hakku', NULL, NULL, '3' ),
( '21', '35', 'Cuaca', NULL, NULL, '3' ),
( '22', '36', 'Energi dan Perubahannya', NULL, NULL, '3' ),
( '23', '37', 'Perkembangan Teknologi', NULL, NULL, '3' ),
( '24', '38', 'Praja Muda Karana', NULL, NULL, '3' ),
( '25', '41', 'Indahnya Kebersamaan', NULL, NULL, '4' ),
( '26', '42', 'Selalu Berhemat Energi', NULL, NULL, '4' ),
( '27', '43', 'Peduli terhadap Makhluk Hidup', NULL, NULL, '4' ),
( '28', '44', 'Berbagai Pekerjaan', NULL, NULL, '4' ),
( '29', '45', 'Pahlawanku', NULL, NULL, '4' ),
( '30', '46', 'Cita-citaku', NULL, NULL, '4' ),
( '31', '47', 'Indahnya Keragaman Negeriku', NULL, NULL, '4' ),
( '32', '48', 'Daerah Tempat Tinggalku', NULL, NULL, '4' ),
( '33', '49', 'Kayanya Negeriku', NULL, NULL, '4' ),
( '34', '51', 'Organ Gerak Hewan dan Manusia', NULL, NULL, '5' ),
( '35', '52', 'Udara Bersih bagi Kesehatan', NULL, NULL, '5' ),
( '36', '53', 'Makanan Sehat', NULL, NULL, '5' ),
( '37', '54', 'Sehat itu Penting', NULL, NULL, '5' ),
( '38', '55', 'Ekosistem', NULL, NULL, '5' ),
( '39', '56', 'Panas dan Perpindahannya', NULL, NULL, '5' ),
( '40', '57', 'Peristiwa dalam Kehidupan', NULL, NULL, '5' ),
( '41', '58', 'Lingkungan Sahabat Kita', NULL, NULL, '5' ),
( '42', '59', 'Benda-benda di Sekitar Kita', NULL, NULL, '5' ),
( '43', '61', 'Selamatkan makhluk hidup', NULL, NULL, '6' ),
( '44', '62', 'Persatuan dalam Perbedaan', NULL, NULL, '6' ),
( '45', '63', 'Tokoh dan Penemuan', NULL, NULL, '6' ),
( '46', '64', 'Globalisasi', NULL, NULL, '6' ),
( '47', '65', 'Wirausaha', NULL, NULL, '6' ),
( '48', '66', 'Menuju Masyarakat Sehat', NULL, NULL, '6' ),
( '49', '67', 'Kepemimpinan', NULL, NULL, '6' ),
( '50', '68', 'Bumiku', NULL, NULL, '6' ),
( '51', '69', 'Menjelajah Luar Angkasa', NULL, NULL, '6' );
COMMIT;
-- ---------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


