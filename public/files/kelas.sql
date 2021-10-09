-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- Dump data of "kelas" ------------------------------------
BEGIN;

INSERT INTO `kelas`(`id`,`kode_kelas`,`label`,`tingkat`,`created_at`,`updated_at`) VALUES 
( '1', '1', 'Satu', '1', '2021-04-12 13:58:44', '2021-04-12 13:58:44' ),
( '2', '2', 'Dua', '2', '2021-04-12 13:58:45', '2021-04-12 13:58:45' ),
( '3', '3', 'Tiga', '3', '2021-04-12 13:58:45', '2021-04-12 13:58:45' ),
( '4', '4', 'Empat', '4', '2021-04-12 13:58:45', '2021-04-12 13:58:45' ),
( '5', '5', 'Lima', '5', '2021-04-12 13:58:45', '2021-04-12 13:58:45' ),
( '6', '6', 'Enam', '6', '2021-04-12 13:58:45', '2021-04-12 13:58:45' );
COMMIT;
-- ---------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


