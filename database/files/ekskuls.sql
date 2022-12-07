-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- Dump data of "ekskuls" ----------------------------------
BEGIN;

INSERT INTO `ekskuls`(`id`,`kode_ekskul`,`label`,`created_at`,`updated_at`) VALUES 
( '1', 'prm', 'Pramuka', '2021-06-13 21:04:33', '2021-06-13 21:04:33' ),
( '2', 'drm', 'Drumban', '2021-06-13 21:04:48', '2021-06-13 21:04:48' ),
( '3', 'hq', 'Hifdhil Quran', '2021-06-13 21:05:20', '2021-06-13 21:05:20' ),
( '4', 'ps', 'Pencak Silat', '2021-06-13 21:05:39', '2021-06-13 21:05:39' );
COMMIT;
-- ---------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


