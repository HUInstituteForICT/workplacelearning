-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2016 at 04:39 PM
-- Server version: 5.7.12-log
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thema7`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorieen`
--

DROP TABLE IF EXISTS `categorieen`;
CREATE TABLE `categorieen` (
  `cg_id` int(11) NOT NULL,
  `cg_value` varchar(50) NOT NULL,
  `ss_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categorieen`
--

INSERT INTO `categorieen` (`cg_id`, `cg_value`, `ss_id`) VALUES
(1, 'Onderzoek doen', 0),
(2, 'Programmeren', 0),
(3, 'Testen', 0),
(4, 'Documenteren', 0),
(5, 'Overleg', 0),
(34, 'School', 0),
(35, 'Nieuwe Categorie', 49),
(36, 'Nieuwe Categorie 3', 49),
(37, 'Nieuwe Categorie 2', 49);

-- --------------------------------------------------------

--
-- Table structure for table `charts`
--

DROP TABLE IF EXISTS `charts`;
CREATE TABLE `charts` (
  `id` int(11) NOT NULL,
  `student_id` int(9) NOT NULL,
  `title` varchar(60) DEFAULT NULL,
  `serializedData` blob,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deadlines`
--

DROP TABLE IF EXISTS `deadlines`;
CREATE TABLE `deadlines` (
  `dl_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `dl_value` varchar(50) NOT NULL,
  `dl_tijd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `fb_id` int(9) NOT NULL,
  `wzh_id` int(9) NOT NULL,
  `notfinished` varchar(100) NOT NULL,
  `initiatief` varchar(500) DEFAULT '',
  `progress_satisfied` int(1) NOT NULL DEFAULT '1',
  `help_asked` int(1) NOT NULL,
  `help_werkplek` varchar(500) DEFAULT NULL,
  `vervolgstap_zelf` varchar(500) DEFAULT NULL,
  `ondersteuning_werkplek` varchar(500) DEFAULT NULL,
  `ondersteuning_opleiding` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`fb_id`, `wzh_id`, `notfinished`, `initiatief`, `progress_satisfied`, `help_asked`, `help_werkplek`, `vervolgstap_zelf`, `ondersteuning_werkplek`, `ondersteuning_opleiding`) VALUES
(5, 191, 'Lange Test', 'initiatief genomen', 2, 1, 'Test', 'sneller werken', 'Geen', 'Geen'),
(6, 194, '', '', 1, 0, NULL, NULL, NULL, NULL),
(7, 195, '', '', 1, 0, NULL, NULL, NULL, NULL),
(8, 196, '', '', 1, 0, NULL, NULL, NULL, NULL),
(9, 200, '', '', 1, 0, NULL, NULL, NULL, NULL),
(10, 201, '', '', 1, 0, NULL, NULL, NULL, NULL),
(11, 206, '', '', 1, 0, NULL, NULL, NULL, NULL),
(12, 208, '', '', 1, 0, NULL, NULL, NULL, NULL),
(13, 202, 'Weinig/Geen Ervaring', '', 2, 0, NULL, 'Ik wil een collega om hulp vragen.', 'Zie vervolgstap', NULL),
(14, 203, '', '', 1, 2, NULL, 'Gekregen tips onderzoeken.', 'Collega heeft mij een aantal websites gegeven waar ik naar kan kijken.', NULL),
(15, 212, '', '', 1, 0, NULL, NULL, NULL, NULL),
(16, 213, '', '', 1, 0, NULL, NULL, NULL, NULL),
(17, 214, '', '', 1, 0, NULL, NULL, NULL, NULL),
(18, 215, '', '', 1, 0, NULL, NULL, NULL, NULL),
(19, 217, '', '', 1, 0, NULL, NULL, NULL, NULL),
(20, 218, '', '', 1, 0, NULL, NULL, NULL, NULL),
(21, 227, '', '', 1, 0, NULL, NULL, NULL, NULL),
(22, 228, '', '', 1, 0, NULL, NULL, NULL, NULL),
(23, 231, '', '', 1, 0, NULL, NULL, NULL, NULL),
(24, 233, '', '', 1, 0, NULL, NULL, NULL, NULL),
(25, 235, 'Geen/Weinig Ervaring', 'Ik heb zelf op internet gezocht naar bronnen en een handleiding.', 2, 0, 'Geen', 'Ik wil de installatie nu voorzien van een eigen home pagina.', 'Geen', 'Geen'),
(26, 236, 'Geen/Weinig Ervaring', 'Verschillende urls ingevoerd in de routering, gezocht op internet.', 2, 0, 'Geen', 'Naar een collega toe gaan om hulp te vragen', 'Een collega die meekijkt.', 'Geen'),
(27, 237, 'Geen/Weinig Ervaring', 'Ik ben naar een collega toegegaan.', 2, 2, 'een collega heeft me laten zien hoe de routering werkt.', 'Verdergaan met kijken naar Blade templates', 'Geen', 'Geen'),
(28, 239, 'Geen/Weinig Ervaring', 'Opletten', 1, 1, 'Hulp van Piet', 'Nog meer opletten', 'Hulp van Klaas', 'Geen'),
(29, 242, 'Tijdgebrek', 'Stap 1', 2, 1, 'Vergadering', 'Test 1', 'Test 2', 'Test 3');

-- --------------------------------------------------------

--
-- Table structure for table `moeilijkheden`
--

DROP TABLE IF EXISTS `moeilijkheden`;
CREATE TABLE `moeilijkheden` (
  `mh_id` int(11) NOT NULL,
  `mh_value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `moeilijkheden`
--

INSERT INTO `moeilijkheden` (`mh_id`, `mh_value`) VALUES
(1, 'Makkelijk'),
(2, 'Gemiddeld'),
(3, 'Moeilijk');

-- --------------------------------------------------------

--
-- Table structure for table `samenwerkingsverbanden`
--

DROP TABLE IF EXISTS `samenwerkingsverbanden`;
CREATE TABLE `samenwerkingsverbanden` (
  `swv_id` int(11) NOT NULL,
  `swv_value` varchar(40) NOT NULL,
  `swv_omschrijving` varchar(200) NOT NULL DEFAULT ' ',
  `ss_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `samenwerkingsverbanden`
--

INSERT INTO `samenwerkingsverbanden` (`swv_id`, `swv_value`, `swv_omschrijving`, `ss_id`) VALUES
(1, 'Stagebegeleider', 'De begeleider die vanuit het bedrijf is toegewezen om jou te begeleiden.', 0),
(2, 'Collega', 'Een andere programmeur van je stagebedrijf.', 0),
(3, 'Specialist', 'Een specialist op vakgebied. (geen collega)', 0),
(4, 'Medestagiair/Student', 'Een mede student die bij hetzelfde bedrijf zit', 0),
(19, 'Testpersoon', ' ', 50),
(20, 'Ander', ' ', 41);

-- --------------------------------------------------------

--
-- Table structure for table `stageplaatsen`
--

DROP TABLE IF EXISTS `stageplaatsen`;
CREATE TABLE `stageplaatsen` (
  `stp_id` int(11) NOT NULL,
  `bedrijfsnaam` varchar(100) NOT NULL,
  `plaats` varchar(100) NOT NULL,
  `postcode` varchar(6) NOT NULL,
  `huisnummer` varchar(8) NOT NULL,
  `contactpersoon` varchar(100) NOT NULL,
  `contactemail` varchar(255) NOT NULL,
  `telefoon` varchar(20) NOT NULL,
  `aantalwerknemers` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stageplaatsen`
--

INSERT INTO `stageplaatsen` (`stp_id`, `bedrijfsnaam`, `plaats`, `postcode`, `huisnummer`, `contactpersoon`, `contactemail`, `telefoon`, `aantalwerknemers`) VALUES
(1, 'Hogeschool Utrecht', 'Utrecht', '3552AS', '1', 'E. van der Stappen', 'e.vanderstappen@hu.nl', '06-12345678', '255'),
(2, 'HU', 'Utrecht', '3415GG', '1A', 'Henk van de Ridder', 'Henk.vanderidder@hu.nl', '06-12345687', '250'),
(3, 'HU', 'Utrecht', '3415GG', '1A', 'Henk van de Ridder', 'Henk.vanderidder@hu.nl', '06-12345687', '250'),
(4, 'Praxis', 'Ijsselstein', '3415BG', '1A', 'Pieter Diedelson', 'p.diedie@praxa.com', '06-32178965', '37'),
(5, 'Praxis', 'Ijsselstein', '3415BG', '1A', 'Pieter Diedelson', 'p.diedie@praxa.com', '06-32178965', '37'),
(6, 'Praxis', 'Ijsselstein', '3415BG', '1A', 'Pieter Diedelson', 'p.diedie@praxa.com', '06-32178965', '37'),
(7, 'Appie', 'Ijsselstein', '3041BB', '44A', 'Albert Heijn', 'A.heijn@appie.nl', '06-12345687', '43'),
(8, 'Appie', 'Ijsselstein', '3041BB', '44A', 'Albert Heijn', 'A.heijn@appie.nl', '06-12345687', '43'),
(9, 'Appie', 'Ijsselstein', '3041BB', '44A', 'Albert Heijn', 'A.heijn@appie.nl', '06-12345687', '43'),
(11, '', '', '', '', '', '', '', ''),
(12, '', '', '', '', '', '', '', ''),
(13, '', '', '', '', '', '', '', ''),
(14, '', '', '', '', '', '', '', ''),
(15, '', '', '', '', '', '', '', ''),
(16, 'Hogeschool Utrecht', 'Utrecht', '3411AB', '1', 'Jan Langerak', 'jan.langerak@student.hu.nl', '0634679012', '172'),
(17, 'HU Utrecht', 'Utrecht', '3415GG', '18', 'Esther', 'esther.vanderstappen@hu.nl', '0123456789', '5'),
(18, 'Jumbo', 'Nieuwegein', '2233GH', '54', 'Jim Umbo', 'J.umbo@jumbo.nl', '06-78945612', '32'),
(19, 'Hogeschool Utrecht', 'Utrecht', '3500AD', '1', 'E. van der Stappen', 'e.vanderstappen@hu.nl', '06-12345678', '4000'),
(20, 'Hogeschool Utrecht', 'Utrecht', '', '', 'E. van der Stappen', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(21, 'Hogeschool Utrecht', 'Utrecht', '', '', 'E. van der Stappen', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(22, 'Test', 'Test', '', '', 'Test', 'test@test.com', '06-12345678', ''),
(23, 'HU Utrecht', 'Utrecht', '', '', 'Esther', 'esther.vanderstappen@hu.nl', '0123456789', ''),
(24, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(25, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(26, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(27, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(28, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(29, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(30, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(31, 'Hogeschool Utrecht', 'Utrecht', '', '', 'E. van der Stappen', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(32, 'Hogeschool Utrecht', 'Utrecht', '', '', 'E. van der Stappen', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(33, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonA', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(34, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonA', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(35, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonA', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(36, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonA', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(37, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonAB', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(38, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonAB', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(39, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonAB', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(40, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonABC', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(41, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonABCD', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(42, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonABC', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(43, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(44, 'Hogeschool Utrecht', 'Utrecht', '', '', 'PersoonABC', 'e.vanderstappen@hu.nl', '06-12345678', ''),
(45, 'Nieuw', 'Locatie', '', '', 'Eenpersoon', 'eenpersoon@naam.domein', '06-12345678', ''),
(46, 'Nieuw', 'Locatie', '', '', 'Eenpersoon', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(47, 'Mijnbedrijf', 'Mijnlocatie', '', '', 'Contact Persoon', 'contact@persoon.nl', '06-12345678', ''),
(48, 'test', 'test', '', '', 'testtest', 'test@test.com', '06-12345678', ''),
(49, 'Nieuw', 'Locatie', '', '', 'Eenpersoon', 'eenpersoon@naam.domein', '06-12345678', ''),
(50, 'HU Utrecht', 'Utrecht', '', '', 'Esther', 'esther.vanderstappen@hu.nl', '0123456789', ''),
(51, 'Ingewikkeld B.V.', 'Amersfoort', '', '', 'Mark de Baas', 'm.debaas@ingewikkeld.nl', '06-12345678', ''),
(52, 'Hogeschool Utrecht', 'Amersfoort', '', '', 'Esther', 'esther.vanderstappen@gmail.com', '0612723062', ''),
(53, 'Nieuw', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(54, 'Nieuw', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(55, 'Nieuw', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(56, 'Nieuw', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(57, 'Nieuw', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(58, 'Nieuw', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(59, 'Nieuw', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(60, 'Nieuw', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(61, 'Nieuw2', 'Locatie', '', '', 'Eenpersoon', 'test@test.com', '06-12345678', ''),
(62, 'Nieuw B.V.', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(63, 'Nieuw B.V.', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(64, 'Nieuw2', 'Locatie', '', '', 'Eenpersoon', 'test@test.com', '06-12345678', ''),
(65, 'Nieuw B.V.', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(66, 'Nieuw2', 'Locatie', '', '', 'Eenpersoon', 'test@test.com', '06-12345678', ''),
(67, 'Hogeschool Utrecht', 'Amersfoort', '', '', 'Esther', 'esther.vanderstappen@gmail.com', '0612723062', ''),
(68, 'Hogeschool Utrecht', 'Amersfoort', '', '', 'Esther', 'esther.vanderstappen@gmail.com', '0612723062', ''),
(69, 'Bedrijf B.V.', 'Locatie', '', '', 'Eenpersoon', 'test@test.com', '06-12345678', ''),
(70, 'Bedrijf B.V.', 'Locatie', '', '', 'Eenpersoon', 'test@test.com', '06-12345678', ''),
(71, 'Bedrijf B.V.', 'Locatie', '', '', 'Eenpersoon', 'test@test.com', '06-12345678', ''),
(72, 'Nieuw B.V.', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(73, 'Bedrijf B.V.', 'Locatie', '', '', 'Eenpersoon', 'test@test.com', '06-12345678', ''),
(74, 'Bedrijf B.V.', 'Locatie', '', '', 'Eenpersoon', 'test@test.com', '06-12345678', ''),
(75, 'Nieuw B.V.', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', ''),
(76, 'Nieuw B.V.', 'Bedrijf', '', '', 'Persoon', 'test@test.com', '06-12345678', '');

-- --------------------------------------------------------

--
-- Table structure for table `statussen`
--

DROP TABLE IF EXISTS `statussen`;
CREATE TABLE `statussen` (
  `st_id` int(11) NOT NULL,
  `st_value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `statussen`
--

INSERT INTO `statussen` (`st_id`, `st_value`) VALUES
(1, 'Afgerond'),
(2, 'Mee Bezig'),
(3, 'On hold');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `stud_id` int(11) NOT NULL,
  `studentnummer` int(12) NOT NULL,
  `voornaam` varchar(50) NOT NULL,
  `achternaam` varchar(50) NOT NULL,
  `userlevel` int(1) NOT NULL DEFAULT '0',
  `pw_hash` varchar(128) NOT NULL,
  `geslacht` varchar(1) NOT NULL,
  `geboortedatum` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefoon` varchar(45) NOT NULL,
  `datumregistratie` datetime DEFAULT NULL,
  `answer` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stud_id`, `studentnummer`, `voornaam`, `achternaam`, `userlevel`, `pw_hash`, `geslacht`, `geboortedatum`, `email`, `telefoon`, `datumregistratie`, `answer`) VALUES
(2, 1661989, 'Max Arkadiusz', 'Cassee', 1, '$2y$10$e3uTvHMT9jMmBlMUQvZdheA2s2DbQoptlRrGH2ffqCTVyvJece9dq', 'M', '1992-04-20', 'max.cassee@student.hu.nl', '06-53480285', '0000-00-00 00:00:00', 'Testplaats'),
(11, 1, 'Esther', 'van der Stappen', 1, '$2y$10$eB.NzfeHvhzL.Hk5sbTQjeZbAto5RkyJ98G5qEnbsjXY/HwuzeJ1W', 'F', '1999-01-01', 'esther.vanderstappen@hu.nl', '0612723062', '2016-06-09 06:02:04', 'Utrecht'),
(12, 1, 'Ilya', 'Zitter', 1, '$2y$10$6/IRdGgRDdBSYr0IZkvAJufRRIuiYvK90/7AlPE/GlmVS/0DpcTiC', 'F', '1965-06-10', 'ilya.zitter@hu.nl', '0612345678', '2016-06-10 10:16:36', 'Utrecht'),
(30, 4567890, 'Esther', 'van der Stappen', 0, '$2y$10$dE8Mxqf0zUgzvlZciQjHdebRPciyDajYduRnvGZLWHQKGRAlfznyG', 'F', '1998-05-31', 'esther@hu.nl', '0612723062', '2016-06-30 12:04:10', 'Utrecht');

-- --------------------------------------------------------

--
-- Table structure for table `student_stages`
--

DROP TABLE IF EXISTS `student_stages`;
CREATE TABLE `student_stages` (
  `stud_stid` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `stageplaats_id` int(11) NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date NOT NULL,
  `aantaluren` int(9) NOT NULL,
  `opdrachtomschrijving` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_stages`
--

INSERT INTO `student_stages` (`stud_stid`, `student_id`, `stageplaats_id`, `startdatum`, `einddatum`, `aantaluren`, `opdrachtomschrijving`) VALUES
(41, 30, 68, '2016-08-22', '2016-11-23', 300, 'Toelichting op de stage'),
(49, 2, 76, '2016-05-01', '2016-12-23', 500, 'Dit is de stage opdracht.'),
(50, 2, 74, '2016-08-22', '2016-10-25', 500, 'Dit is ook een stage opdracht.'),
(51, 30, 67, '2016-08-23', '2016-12-24', 1000, 'Tessting testing testing test');

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

DROP TABLE IF EXISTS `tips`;
CREATE TABLE `tips` (
  `tip_id` int(11) NOT NULL,
  `tip_value` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tips`
--

INSERT INTO `tips` (`tip_id`, `tip_value`) VALUES
(3, 'Wees nieuwsgierig en als je iets niet begrijpt blijf doorvragen.'),
(4, 'Zorg dat je bureau schoon blijft, ruim zoveel mogelijk op wat je niet nodig hebt.'),
(5, 'Vraag je mede student om hulp als je ergens vastloopt.'),
(6, 'Als je je werk af hebt, vraag of je andere taken kunt uitvoeren of ergens mee kan helpen.'),
(7, 'Probeer geen social media te gebruiken, tenzij het bij je werkzaamheden hoort.'),
(8, 'Rotklusjes heb je atlijd, maar als je alleen rotklusjes hebt heb je een probleem.'),
(9, 'Je hoeft niet overal ja op te zeggen, maar aanpassen aan het bedrijf is beter.'),
(10, 'Leer je collega''s kennen, lunch met ze is een keer.'),
(11, 'Je kan altijd je begeleider om hulp vragen.'),
(12, 'Houdt een beetje contact met het bedrijf na je stage.'),
(13, 'Vraag een duidelijke taakomschrijving van wat je moet doen.'),
(14, 'Vraag feedback van je collegas over jezelf en je werk.');

-- --------------------------------------------------------

--
-- Table structure for table `usersettings`
--

DROP TABLE IF EXISTS `usersettings`;
CREATE TABLE `usersettings` (
  `setting_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usersettings`
--

INSERT INTO `usersettings` (`setting_id`, `student_id`, `setting_name`, `setting_value`) VALUES
(5, 2, 'active_internship', '49'),
(6, 30, 'active_internship', '41');

-- --------------------------------------------------------

--
-- Table structure for table `werkzaamheden`
--

DROP TABLE IF EXISTS `werkzaamheden`;
CREATE TABLE `werkzaamheden` (
  `wzh_id` int(11) NOT NULL,
  `student_stage_id` int(11) NOT NULL,
  `wzh_aantaluren` float NOT NULL,
  `wzh_omschrijving` varchar(80) NOT NULL,
  `wzh_datum` date NOT NULL,
  `lerenmet` varchar(50) NOT NULL,
  `lerenmetdetail` varchar(1000) NOT NULL,
  `moeilijkheid_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `prev_wzh_id` int(9) DEFAULT NULL,
  `display` int(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `werkzaamheden`
--

INSERT INTO `werkzaamheden` (`wzh_id`, `student_stage_id`, `wzh_aantaluren`, `wzh_omschrijving`, `wzh_datum`, `lerenmet`, `lerenmetdetail`, `moeilijkheid_id`, `status_id`, `categorie_id`, `prev_wzh_id`, `display`, `created_at`) VALUES
(186, 50, 0.25, 'Test2', '2016-08-26', 'persoon', '2', 2, 2, 2, NULL, 1, '2016-08-26 00:00:00'),
(187, 50, 0.25, 'Test3', '2016-08-26', 'persoon', '19', 2, 3, 2, NULL, 0, '2016-08-26 00:00:00'),
(188, 50, 0.25, 'Test4', '2016-08-26', 'internet', 'http://www.google.nl', 2, 2, 1, NULL, 1, '2016-08-26 00:00:00'),
(189, 50, 0.25, 'Test5', '2016-08-26', 'boek', 'De Naam van het artikel!', 2, 2, 2, NULL, 1, '2016-08-26 00:00:00'),
(190, 50, 0.25, 'Test6', '2016-08-26', 'Anders', 'Dit is een test.', 1, 1, 1, NULL, 0, '2016-08-26 00:00:00'),
(191, 41, 2, 'Hallo', '2016-08-26', 'alleen', '', 3, 2, 2, NULL, 0, '2016-08-26 00:00:00'),
(192, 41, 3, 'test', '2016-08-26', 'alleen', '', 2, 2, 3, NULL, 0, '2016-08-26 00:00:00'),
(193, 41, 0.25, 'Test vervolgactiviteiten', '2016-08-26', 'persoon', '1', 2, 2, 2, 192, 0, '2016-08-26 00:00:00'),
(194, 41, 4, 'Afronden vervolg', '2016-08-26', 'alleen', '', 3, 1, 2, 193, 0, '2016-08-26 00:00:00'),
(195, 49, 0.25, 'Test2', '2016-08-26', 'persoon', '1', 3, 2, 1, NULL, 0, '2016-08-26 00:00:00'),
(196, 41, 3, 'mbnkjn', '2016-08-26', 'alleen', '', 3, 1, 3, NULL, 0, '2016-08-26 00:00:00'),
(197, 49, 0.25, 'Dit is een tamelijk lange omschrijving.', '2016-08-28', 'persoon', '1', 2, 1, 1, NULL, 0, '2016-08-28 00:00:00'),
(198, 49, 0.25, 'Deze omschrijving is heel erg lang, en is bedoeld om het dashboard te testen.', '2016-08-28', 'persoon', '1', 2, 1, 1, NULL, 0, '2016-08-28 00:00:00'),
(199, 49, 0.25, 'Deze omschrijving is heel erg lang, en is bedoeld om het dashboard te testen. Nu', '2016-08-28', 'persoon', '1', 1, 1, 1, NULL, 0, '2016-08-28 00:00:00'),
(200, 49, 2, 'Dit is mijn lastige activiteit.', '2016-08-28', 'alleen', '', 1, 2, 2, NULL, 1, '2016-08-28 00:00:00'),
(201, 49, 0.25, 'Dit is een test.', '2016-08-28', 'alleen', '', 3, 2, 2, NULL, 0, '2016-08-28 00:00:00'),
(202, 49, 2, 'Eerste Keten-Activiteit', '2016-08-28', 'alleen', '', 2, 2, 2, NULL, 0, '2016-08-28 00:00:00'),
(203, 49, 0.5, 'Tweede-Keten activiteit', '2016-08-28', 'persoon', '2', 2, 2, 3, 202, 0, '2016-08-28 00:00:00'),
(204, 49, 1, 'Derde-Keten activiteit', '2016-08-28', 'internet', 'http://www.google.nl/', 1, 2, 4, 203, 0, '2016-08-28 00:00:00'),
(205, 49, 2, 'Vierde-Keten activiteit', '2016-08-28', 'alleen', '', 2, 1, 5, 204, 0, '2016-08-28 00:00:00'),
(206, 41, 0.25, 'ertew', '2016-08-29', 'persoon', '1', 3, 1, 1, NULL, 0, '2016-08-29 00:00:00'),
(207, 41, 2, 'cwrwe', '2016-08-29', 'alleen', '', 1, 1, 1, NULL, 0, '2016-08-29 00:00:00'),
(208, 41, 2, 'xqeqEqex', '2016-08-29', 'alleen', '', 3, 3, 3, NULL, 0, '2016-08-29 00:00:00'),
(209, 49, 0.25, '2e keten, eerste activiteit', '2016-09-04', 'persoon', '1', 2, 2, 1, NULL, 0, '2016-09-04 00:00:00'),
(210, 49, 0.5, '2e keten, tweede activiteit', '2016-09-04', 'persoon', '1', 2, 2, 2, 209, 0, '2016-09-04 00:00:00'),
(211, 49, 2, '2e keten, derde activeiteit', '2016-09-04', 'alleen', '', 2, 1, 2, 210, 0, '2016-09-04 00:00:00'),
(212, 41, 2, 'fff', '2016-09-05', 'alleen', '', 3, 2, 3, NULL, 1, '2016-09-05 00:00:00'),
(213, 41, 2, 'gg', '2016-09-05', 'alleen', '', 3, 2, 3, NULL, 1, '2016-09-05 00:00:00'),
(214, 49, 2, 'Moeilijk, niet afgerond en keten.', '2016-09-07', 'persoon', '1', 2, 2, 2, 201, 1, '2016-09-07 00:00:00'),
(215, 49, 2, 'test3', '2016-09-07', 'alleen', '', 2, 2, 2, 195, 0, '2016-09-07 00:00:00'),
(216, 49, 0.5, 'Tweede Keten in september, eerste activiteit', '2016-09-08', 'alleen', '', 2, 2, 2, NULL, 0, '2016-09-08 00:00:00'),
(217, 49, 2, 'Tweede keten in september, tweede activiteit', '2016-09-08', 'alleen', '', 2, 2, 2, 216, 0, '2016-09-08 00:00:00'),
(218, 49, 3, 'Tweede keten in september, derde activiteit', '2016-09-08', 'alleen', '', 3, 2, 3, 217, 0, '2016-09-08 00:00:00'),
(219, 49, 2, 'Tweede keten in september, vierde activiteit', '2016-09-08', 'alleen', '', 1, 1, 4, 218, 0, '2016-09-08 00:00:00'),
(220, 49, 0.75, 'Lastige activiteit', '2016-09-09', 'alleen', '', 3, 1, 2, NULL, 0, '2016-09-09 00:00:00'),
(222, 49, 2, 'Categorietest2', '2016-09-09', 'alleen', '', 2, 1, 35, NULL, 0, '2016-09-09 00:00:00'),
(223, 49, 2, 'Categorietest3', '2016-09-09', 'alleen', '', 3, 1, 1, NULL, 0, '2016-09-09 00:00:00'),
(225, 49, 2, 'Categorietest4', '2016-09-09', 'alleen', '', 2, 1, 37, NULL, 0, '2016-09-09 00:00:00'),
(226, 41, 2, 'Testinvoer 1', '2016-09-09', 'alleen', '', 2, 2, 3, NULL, 0, '2016-09-09 00:00:00'),
(227, 41, 1, 'Testinvoer 2', '2016-09-09', 'persoon', '1', 3, 2, 3, 226, 0, '2016-09-09 00:00:00'),
(228, 41, 4, 'Testinvoer 3', '2016-09-09', 'boek', 'Testing with TMap', 2, 2, 4, 227, 0, '2016-09-09 00:00:00'),
(229, 41, 6, 'Testinvoer 4', '2016-09-09', 'alleen', '', 1, 1, 2, 228, 0, '2016-09-09 00:00:00'),
(230, 41, 1, 'Testinvoer A', '2016-09-09', 'persoon', '2', 2, 2, 5, NULL, 0, '2016-09-09 00:00:00'),
(231, 41, 2, 'Testinvoer B', '2016-09-09', 'alleen', '', 3, 2, 4, 230, 0, '2016-09-09 00:00:00'),
(232, 41, 3, 'Testing feedback', '2016-09-09', 'alleen', '', 3, 3, 2, 231, 0, '2016-09-09 00:00:00'),
(233, 49, 2, 'test4', '2016-09-10', 'alleen', '', 3, 2, 2, 215, 1, '2016-09-10 00:00:00'),
(234, 49, 1, 'Onderzoek gedaan naar gebruik van Laravel.', '2016-09-10', 'alleen', '', 3, 2, 1, NULL, 0, '2016-09-10 00:00:00'),
(235, 49, 0.5, 'Eerste Laravel Installatie opgezet.', '2016-09-10', 'alleen', '', 2, 2, 2, 234, 0, '2016-09-10 00:00:00'),
(236, 49, 2, 'Ik heb de Laravel installatie ingesteld, maar deze herkent URLs niet.', '2016-09-10', 'alleen', '', 3, 2, 3, 235, 0, '2016-09-10 00:00:00'),
(237, 49, 0.5, 'URLs ingesteld in de Laravel installatie', '2016-09-10', 'persoon', '2', 2, 2, 2, 236, 0, '2016-09-10 00:00:00'),
(238, 49, 0.75, 'Gezocht op google naar blade templates', '2016-09-10', 'internet', 'https://laravel.com/docs/5.3/blade', 1, 2, 1, 237, 1, '2016-09-10 00:00:00'),
(239, 41, 3, 'Allo allo', '2016-09-11', 'persoon', '4', 2, 2, 5, 191, 0, '2016-09-11 00:00:00'),
(240, 41, 4, 'Doei', '2016-09-11', 'alleen', '', 3, 1, 34, 239, 0, '2016-09-11 00:00:00'),
(241, 41, 1, 'Test 1', '2016-09-12', 'alleen', '', 1, 2, 3, NULL, 0, '2016-09-12 00:00:00'),
(242, 41, 3, 'Test 2', '2016-09-12', 'persoon', '1', 2, 2, 5, 241, 0, '2016-09-12 00:00:00'),
(243, 41, 0.5, 'Test 3', '2016-09-12', 'alleen', '', 3, 1, 3, 242, 0, '2016-09-12 00:00:00'),
(244, 41, 1, 'overleg', '2016-09-12', 'persoon', '20', 2, 2, 5, NULL, 1, '2016-09-12 00:00:00'),
(245, 41, 0.25, 'Notulen maken', '2016-09-12', 'alleen', '', 1, 1, 4, NULL, 0, '2016-09-12 00:00:00'),
(246, 49, 0.25, 'testtesttest', '2016-09-24', 'alleen', '', 2, 2, 1, NULL, 1, '2016-09-24 14:34:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorieen`
--
ALTER TABLE `categorieen`
  ADD PRIMARY KEY (`cg_id`);

--
-- Indexes for table `charts`
--
ALTER TABLE `charts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deadlines`
--
ALTER TABLE `deadlines`
  ADD PRIMARY KEY (`dl_id`),
  ADD KEY `student_id_fk` (`student_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fb_id`),
  ADD UNIQUE KEY `wzh_unique` (`wzh_id`);

--
-- Indexes for table `moeilijkheden`
--
ALTER TABLE `moeilijkheden`
  ADD PRIMARY KEY (`mh_id`);

--
-- Indexes for table `samenwerkingsverbanden`
--
ALTER TABLE `samenwerkingsverbanden`
  ADD PRIMARY KEY (`swv_id`);

--
-- Indexes for table `stageplaatsen`
--
ALTER TABLE `stageplaatsen`
  ADD PRIMARY KEY (`stp_id`);

--
-- Indexes for table `statussen`
--
ALTER TABLE `statussen`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `student_stages`
--
ALTER TABLE `student_stages`
  ADD PRIMARY KEY (`stud_stid`),
  ADD KEY `fk_student_id` (`student_id`),
  ADD KEY `fk_stp_stageplaats_id` (`stageplaats_id`);

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`tip_id`);

--
-- Indexes for table `usersettings`
--
ALTER TABLE `usersettings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `werkzaamheden`
--
ALTER TABLE `werkzaamheden`
  ADD PRIMARY KEY (`wzh_id`),
  ADD KEY `fk_student_stage_id` (`student_stage_id`),
  ADD KEY `fk_samenwerkingsverband_id` (`lerenmetdetail`),
  ADD KEY `fk_moeilijkheid_id` (`moeilijkheid_id`),
  ADD KEY `fk_status_id` (`status_id`),
  ADD KEY `fk_categorie_id` (`categorie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorieen`
--
ALTER TABLE `categorieen`
  MODIFY `cg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `charts`
--
ALTER TABLE `charts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deadlines`
--
ALTER TABLE `deadlines`
  MODIFY `dl_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `fb_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `moeilijkheden`
--
ALTER TABLE `moeilijkheden`
  MODIFY `mh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `samenwerkingsverbanden`
--
ALTER TABLE `samenwerkingsverbanden`
  MODIFY `swv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `stageplaatsen`
--
ALTER TABLE `stageplaatsen`
  MODIFY `stp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `statussen`
--
ALTER TABLE `statussen`
  MODIFY `st_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `student_stages`
--
ALTER TABLE `student_stages`
  MODIFY `stud_stid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `tip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `usersettings`
--
ALTER TABLE `usersettings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `werkzaamheden`
--
ALTER TABLE `werkzaamheden`
  MODIFY `wzh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `deadlines`
--
ALTER TABLE `deadlines`
  ADD CONSTRAINT `student_id_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_stages`
--
ALTER TABLE `student_stages`
  ADD CONSTRAINT `fk_stp_stageplaats_id` FOREIGN KEY (`stageplaats_id`) REFERENCES `stageplaatsen` (`stp_id`),
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`stud_id`);

--
-- Constraints for table `werkzaamheden`
--
ALTER TABLE `werkzaamheden`
  ADD CONSTRAINT `fk_categorie_id` FOREIGN KEY (`categorie_id`) REFERENCES `categorieen` (`cg_id`),
  ADD CONSTRAINT `fk_moeilijkheid_id` FOREIGN KEY (`moeilijkheid_id`) REFERENCES `moeilijkheden` (`mh_id`),
  ADD CONSTRAINT `fk_status_id` FOREIGN KEY (`status_id`) REFERENCES `statussen` (`st_id`),
  ADD CONSTRAINT `fk_student_stage_id` FOREIGN KEY (`student_stage_id`) REFERENCES `student_stages` (`stud_stid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
