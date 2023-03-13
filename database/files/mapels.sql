-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- Dump data of "mapels" -----------------------------------
BEGIN;

INSERT INTO `mapels`(`id`,`kode_mapel`,`nama_mapel`,`label`,`deskripsi`,`created_at`,`updated_at`,`kategori`) VALUES 
( '1', 'pabp', 'Pendidikan Agama dan Budi Pekerti', 'Agama', 'Mapel Agama', '2021-04-10 13:44:49', '2021-04-10 13:44:49', 'wajib' ),
( '2', 'ppkn', 'Pendidikan Pancasila dan Kewarganegaraan', 'PPKN', 'Mapel PPKN', '2021-04-10 13:45:17', '2021-04-10 13:45:17', 'wajib' ),
( '3', 'bid', 'Bahasa Indonesia', 'B. Indonesia', 'Mapel Bahasa Indonesia', '2021-04-10 13:47:05', '2021-04-10 13:47:05', 'wajib' ),
( '4', 'mtk', 'Matematika', 'MTK', 'Mapel Matematika', '2021-04-10 13:47:22', '2021-04-10 13:47:22', 'wajib' ),
( '5', 'ipa', 'Ilmu Pengetahuan Alam', 'IPA', 'Mapel IPA', '2021-04-10 13:47:53', '2021-04-10 13:47:53', 'wajib' ),
( '6', 'ips', 'Ilmu Pengetahuan Sosial', 'IPS', 'Mapel IPS', '2021-04-10 13:48:10', '2021-04-10 13:48:10', 'wajib' ),
( '7', 'sbdp', 'Seni Budaya dan Prakarya', 'SBDP', 'Mapel Seni dan Prakarya', '2021-04-10 13:48:52', '2021-04-10 13:48:52', 'wajib' ),
( '8', 'pjok', 'Pendidikan Jasmani dan Olahraga', 'PJOK', 'Mapel Olah Raga', '2021-04-10 13:49:33', '2021-04-10 13:49:33', 'wajib' ),
( '9', 'bd', 'Bahasa Daerah', 'B. Daerah', 'Mapel Bahasa Daerah', '2021-04-10 13:50:01', '2021-04-10 13:50:01', 'mulok' ),
( '10', 'ben', 'Bahasa Inggris', 'B. Inggris', 'Mapel Bahasa Inggris', '2021-04-10 13:50:36', '2021-04-10 13:50:36', 'mulok' );
COMMIT;
-- ---------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


