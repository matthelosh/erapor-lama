-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- Dump data of "periodes" ---------------------------------
BEGIN;

INSERT INTO `periodes`(`id`,`kode_periode`,`tapel`,`semester`,`deskripsi`,`created_at`,`updated_at`,`active`) VALUES 
( '1', '20211', '2020/2021', '1', 'Periode 2020/2021 Semester Ganjil', '2021-04-06 10:59:13', '2021-06-13 11:34:12', '0' ),
( '2', '20212', '2020/2021', '2', 'Periode 2020/2021 Semester Genap', '2021-04-06 11:52:44', '2021-06-13 11:34:12', '1' ),
( '3', '21221', '2021/2022', '1', 'Periode 2021/2022 Semester Ganjil', '2021-04-06 11:55:34', '2021-04-09 11:01:37', '0' ),
( '4', '21222', '2021/2022', '2', 'Periode 2021/2022 Semester Genap', '2021-04-06 11:59:15', '2021-04-06 11:59:15', '0' ),
( '5', '22231', '2022/2023', '1', 'Periode 2022/2023 Semester Ganjil', '2021-04-09 10:29:09', '2021-04-09 10:29:09', '0' ),
( '6', '22232', '2022/2023', '2', 'Periode 2022/2023 Semester Genap', '2021-04-09 10:29:28', '2021-04-09 10:29:28', '0' );
COMMIT;
-- ---------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


