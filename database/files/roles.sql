-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- Dump data of "roles" ------------------------------------
BEGIN;

INSERT INTO `roles`(`id`,`kode_role`,`label`,`created_at`,`updated_at`) VALUES 
( '1', 'admin', 'Admin', '2021-04-22 17:12:02', '2021-04-22 17:12:02' ),
( '2', 'wali', 'Wali Kelas', '2021-04-22 17:12:02', '2021-04-22 17:12:02' ),
( '3', 'gpai', 'Guru Agama', '2021-04-22 17:12:02', '2021-04-22 17:12:02' ),
( '4', 'gor', 'Guru Olahraga', '2021-04-22 17:12:02', '2021-04-22 17:12:02' ),
( '5', 'gben', 'Guru Bahasa Inggris', '2021-04-22 17:12:02', '2021-04-22 17:12:02' ),
( '6', 'ks', 'Kepala Sekolah', '2021-04-22 17:12:02', '2021-04-22 17:12:02' ),
( '7', 'siswa', 'Siswa', NULL, NULL );
COMMIT;
-- ---------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


