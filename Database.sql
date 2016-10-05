-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2016 at 07:18 AM
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
(34, 'School', 0);

-- --------------------------------------------------------

--
-- Table structure for table `charts`
--

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

CREATE TABLE `feedback` (
  `fb_id` int(9) NOT NULL,
  `wzh_id` int(9) NOT NULL,
  `initiatief` varchar(500) DEFAULT NULL,
  `help_asked` int(1) NOT NULL,
  `help_werkplek` varchar(500) DEFAULT NULL,
  `vervolgstap_zelf` varchar(500) DEFAULT NULL,
  `ondersteuning_werkplek` varchar(500) DEFAULT NULL,
  `ondersteuning_opleiding` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `moeilijkheden`
--

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
(5, 'Alleen', 'Alleen', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stageplaatsen`
--

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
(68, 'Hogeschool Utrecht', 'Amersfoort', '', '', 'Esther', 'esther.vanderstappen@gmail.com', '0612723062', '');

-- --------------------------------------------------------

--
-- Table structure for table `statussen`
--

CREATE TABLE `statussen` (
  `st_id` int(11) NOT NULL,
  `st_value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `statussen`
--

INSERT INTO `statussen` (`st_id`, `st_value`) VALUES
(1, 'Afgerond'),
(2, 'Nog mee bezig'),
(3, 'On hold');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

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
(2, 1661989, 'Max Arkadiusz', 'Cassee', 0, '$2y$10$FAX/4FNLqigfnaQSthPeSemItXrndFQc8t5SzR3XN7uC5OQLpApbW', 'M', '1992-04-20', 'max.cassee@student.hu.nl', '06-53480285', '0000-00-00 00:00:00', ''),
(11, 1, 'Esther', 'van der Stappen', 1, '$2y$10$MzevFrUvgAv2sBDOqz7UueELpbWbUINJtYFGK4qAWrmMfj3QI5RzG', 'F', '1999-01-01', 'esther.vanderstappen@hu.nl', '0612723062', '2016-06-09 06:02:04', 'Utrecht'),
(12, 1, 'Ilya', 'Zitter', 1, '$2y$10$6/IRdGgRDdBSYr0IZkvAJufRRIuiYvK90/7AlPE/GlmVS/0DpcTiC', 'F', '1965-06-10', 'ilya.zitter@hu.nl', '0612345678', '2016-06-10 10:16:36', 'Utrecht'),
(30, 4567890, 'Esther', 'van der Stappen', 0, '$2y$10$z26ryrlPgd6YxDS3Lfj9FOUoW38O0eqnS.ZavHTtQ5BuAMMxySCRi', 'F', '1998-05-31', 'esther@hu.nl', '0612723062', '2016-06-30 12:04:10', 'Utrecht');

-- --------------------------------------------------------

--
-- Table structure for table `student_stages`
--

CREATE TABLE `student_stages` (
  `stud_stid` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `stageplaats_id` int(11) NOT NULL,
  `startdatum` datetime NOT NULL,
  `einddatum` date NOT NULL,
  `aantaluren` int(9) NOT NULL,
  `opdrachtomschrijving` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_stages`
--

INSERT INTO `student_stages` (`stud_stid`, `student_id`, `stageplaats_id`, `startdatum`, `einddatum`, `aantaluren`, `opdrachtomschrijving`) VALUES
(41, 30, 68, '2016-08-22 00:00:00', '2016-11-23', 300, 'Toelichting op de stage'),
(49, 2, 65, '2016-08-22 00:00:00', '2016-09-23', 500, 'Dit is de stage opdracht.'),
(50, 2, 66, '2016-08-22 00:00:00', '2016-08-25', 500, 'Dit is ook een stage opdracht.'),
(51, 30, 67, '2016-08-23 00:00:00', '2016-12-24', 1000, 'Tessting testing testing test');

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

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
(5, 2, 'active_internship', '50'),
(6, 30, 'active_internship', '41');

-- --------------------------------------------------------

--
-- Table structure for table `werkzaamheden`
--

CREATE TABLE `werkzaamheden` (
  `wzh_id` int(11) NOT NULL,
  `student_stage_id` int(11) NOT NULL,
  `wzh_aantaluren` float NOT NULL,
  `wzh_omschrijving` varchar(80) NOT NULL,
  `wzh_datum` date NOT NULL,
  `lerenmet` varchar(50) NOT NULL,
  `lerenmetdetail` varchar(500) NOT NULL,
  `moeilijkheid_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `prev_wzh_id` int(9) DEFAULT NULL,
  `display` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `werkzaamheden`
--

INSERT INTO `werkzaamheden` (`wzh_id`, `student_stage_id`, `wzh_aantaluren`, `wzh_omschrijving`, `wzh_datum`, `lerenmet`, `lerenmetdetail`, `moeilijkheid_id`, `status_id`, `categorie_id`, `prev_wzh_id`, `display`) VALUES
(184, 50, 0.25, 'Test, Niet afgerond', '2016-08-23', '', '1', 1, 2, 1, NULL, 0),
(185, 50, 0.25, 'Test, nu wel afgerond', '2016-08-23', '', '1', 1, 1, 1, 184, 0);

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
  MODIFY `cg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
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
  MODIFY `fb_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `moeilijkheden`
--
ALTER TABLE `moeilijkheden`
  MODIFY `mh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `samenwerkingsverbanden`
--
ALTER TABLE `samenwerkingsverbanden`
  MODIFY `swv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `stageplaatsen`
--
ALTER TABLE `stageplaatsen`
  MODIFY `stp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `statussen`
--
ALTER TABLE `statussen`
  MODIFY `st_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
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
  MODIFY `wzh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;
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
