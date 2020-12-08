-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 08 Gru 2020, 15:36
-- Wersja serwera: 10.4.11-MariaDB
-- Wersja PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `u986763087_pld`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `chat`
--

CREATE TABLE `chat` (
  `ID` int(11) NOT NULL,
  `The_ID` int(11) NOT NULL COMMENT 'ID ZADANIA',
  `SentFrom` int(11) NOT NULL COMMENT 'Kto wysłał',
  `Message` text COLLATE utf8_polish_ci NOT NULL COMMENT 'Wiadomość',
  `Date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Data nadania wiadomości'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `chat`
--

INSERT INTO `chat` (`ID`, `The_ID`, `SentFrom`, `Message`, `Date`) VALUES
(2, 7, 7, 'Bezpieczna eksploatacja wózków jezdniowych to priorytet', '2019-09-26 06:51:15'),
(4, 16, 8, 'Jeszcze mniejsza wiadomość dla człowieka, jeszcze większy krok dla projektu!', '2019-09-26 08:07:23'),
(9, 7, 6, 'A tutaj link http://www.riverlakestudios.pl', '2019-09-26 09:22:18'),
(11, 7, 6, 'Link do raportu 87: https://docs.google.com/spreadsheets/d/1YOwKe0JtuFN_e7kdTeeeRaUZeGJS7-MR9bRWtceQ4Y8/edit#gid=323709649', '2019-09-26 09:27:15'),
(12, 12, 6, 'Dodajmy sobie wiadomość do wszystkich proszę bardzo :)', '2019-09-26 16:02:57'),
(13, 15, 6, 'No obojętnie', '2019-09-28 06:11:51'),
(14, 7, 6, 'Tutaj fajna stronka www.onet.pl', '2019-10-02 10:49:13'),
(15, 15, 6, 'Dłuższa wiadomość, aby zbadać pojemność jednego okna chatu, kontrola błędów, która jest tak potrzebna w dużym projekcie', '2019-10-18 11:00:10'),
(16, 15, 6, 'Hej, jak wam idzie realizacja?', '2019-10-24 09:20:21'),
(17, 21, 6, 'o2.pl\nPo prostu', '2019-11-07 18:54:54'),
(18, 15, 6, 'Podaje linka do zasobów: drive.google.com', '2019-11-12 20:24:46'),
(21, 25, 6, 'Hej, co tam u Ciebie?!', '2019-11-25 14:21:37'),
(22, 25, 6, 'Hej, co tam u Ciebie?!', '2019-11-25 14:21:54'),
(23, 29, 6, 'www.decathlon.pl', '2019-11-25 14:24:00'),
(26, 53, 16, 'Super !!', '2019-12-15 09:54:52'),
(43, 62, 7, 'pRoSzEm CiEm', '2020-01-15 12:22:33');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `done`
--

CREATE TABLE `done` (
  `ID` int(11) NOT NULL,
  `The_ID` int(11) NOT NULL,
  `Topic` text COLLATE utf8_polish_ci NOT NULL,
  `Info` text COLLATE utf8_polish_ci NOT NULL,
  `Type` text COLLATE utf8_polish_ci NOT NULL,
  `WhoAdd` int(11) NOT NULL,
  `ForWho` int(11) NOT NULL,
  `Length` int(11) NOT NULL DEFAULT 2,
  `Visited` timestamp NOT NULL DEFAULT current_timestamp(),
  `Visited_Admin` timestamp NOT NULL DEFAULT current_timestamp(),
  `End` timestamp NOT NULL DEFAULT current_timestamp(),
  `Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `done`
--

INSERT INTO `done` (`ID`, `The_ID`, `Topic`, `Info`, `Type`, `WhoAdd`, `ForWho`, `Length`, `Visited`, `Visited_Admin`, `End`, `Date`) VALUES
(189, 62, 'Powidz że to działo', 'Proszem', '', 7, 6, 1, '2020-02-09 19:04:14', '2020-02-09 19:04:14', '2020-01-21 23:00:00', '2020-02-09 19:04:14'),
(205, 86, 'Nowe zadanie D', 'Cyk zadanko 3 osoby!', 'def', 24, 24, 2, '2020-02-12 07:49:23', '2020-02-12 07:49:23', '2020-02-20 23:00:00', '2020-02-12 07:49:23'),
(206, 70, 'Udostępnić grafiki na MARZEC', 'Pamiętajcie, że macie czas tylko do 22!', 'sta', 24, 8, 1, '2020-02-26 06:02:53', '2020-02-26 06:02:53', '2020-02-21 23:00:00', '2020-02-26 06:02:53'),
(211, 70, 'Udostępnić grafiki na MARZEC', 'Pamiętajcie, że macie czas tylko do 22!', 'sta', 24, 6, 1, '2020-07-22 10:39:30', '2020-07-22 10:39:30', '2020-02-21 23:00:00', '2020-07-22 10:39:30'),
(219, 91, 'Inwentaryzacja wózków pickingowych 07.2020', 'Ocena techniczna wózków pickingowych i uzupełnienie pliku: https://docs.google.com/spreadsheets/d/1WWSRG0pZ0V_mOJxKrbfzdI5qdPXT_UmCiS2bxXKA40E/edit#gid=0', 'def', 0, 6, 2, '2020-07-22 12:39:07', '2020-07-22 12:39:07', '2020-07-24 22:00:00', '2020-07-22 12:39:07'),
(220, 85, 'Kadrowe 4', 'FFFFF', 'sta', 24, 6, 2, '2020-11-11 19:40:19', '2020-11-11 19:40:19', '2020-02-15 23:00:00', '2020-11-11 19:40:19'),
(221, 78, 'Zadanie 21', 'XDER', 'def', 6, 6, 3, '2020-11-11 19:40:24', '2020-11-11 19:40:24', '2020-03-02 23:00:00', '2020-11-11 19:40:24'),
(222, 77, 'Zadanie 20', 'Zadanie 2Zadanie 2Zadanie 2Zadanie 2', 'def', 6, 6, 2, '2020-11-11 19:40:26', '2020-11-11 19:40:26', '2020-02-29 23:00:00', '2020-11-11 19:40:26'),
(223, 93, 'Zadanie nadane osobie', 'Proszę wykonać ASAP.\r\nOstateczny termin 13.11.2020!', 'def', 24, 6, 3, '2020-12-08 14:30:53', '2020-12-08 14:30:53', '2020-11-12 23:00:00', '2020-12-08 14:30:53');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `hr_tasks`
--

CREATE TABLE `hr_tasks` (
  `ID` int(11) NOT NULL COMMENT 'ID',
  `WhoAdd` int(11) NOT NULL COMMENT 'ID kto dodał zadanie',
  `AddDate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Data dodania',
  `Deadline` date NOT NULL COMMENT 'Data deadline''u',
  `Info` text COLLATE utf8_polish_ci NOT NULL COMMENT 'Informacje co zrobić',
  `InfoAdd` text COLLATE utf8_polish_ci NOT NULL COMMENT 'Miejsce na dodatkowe informacje',
  `Completed` text COLLATE utf8_polish_ci NOT NULL COMMENT 'Czy zrobione true/false',
  `WhoCompleted` int(11) NOT NULL COMMENT 'ID kto wykonał zadanie',
  `CompletedDate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Data wykonania'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `hr_tasks`
--

INSERT INTO `hr_tasks` (`ID`, `WhoAdd`, `AddDate`, `Deadline`, `Info`, `InfoAdd`, `Completed`, `WhoCompleted`, `CompletedDate`) VALUES
(1, 24, '2020-08-13 09:49:06', '2020-08-20', 'Ogarnąć listy płacowe', '', 'true', 6, '2020-08-21 12:10:42'),
(2, 6, '2020-08-18 10:56:39', '2030-08-17', 'Beczka jak rzeczka', '', 'false', 0, '2020-08-18 10:56:39'),
(3, 6, '2020-08-21 11:37:50', '2020-08-22', 'Zadanie z konsoli', '', 'true', 6, '2020-08-21 12:10:52'),
(4, 6, '2020-08-21 11:38:58', '2020-08-23', 'Zadanie dodane na szybko', '', 'false', 0, '2020-08-21 11:38:58'),
(6, 6, '2020-08-21 11:40:37', '2020-08-17', 'Zadanie dodane na szybko 3', '', 'true', 6, '2020-08-21 12:13:32'),
(7, 6, '2020-08-21 12:11:14', '2020-08-25', 'A tutaj trochę więcej informacji i na inny termin', 'A tutaj notatka jakoś wcześniej', 'false', 6, '2020-09-22 12:33:01'),
(8, 6, '2020-08-22 08:12:40', '2030-08-18', 'A tutaj pustą notatkę robimy cyk\nPamiętajcie o czymś tam\nNie wiem\nI Shift też działa :D', '', 'false', 0, '2020-08-22 08:12:40'),
(9, 6, '2020-08-22 08:12:40', '2030-08-19', '', '', 'false', 0, '2020-08-22 08:12:40'),
(10, 6, '2020-08-22 08:12:40', '2030-08-20', 'Ostatnia próba cyk\nI pyk', '', 'false', 0, '2020-08-22 08:12:40'),
(11, 6, '2020-08-22 08:12:40', '2030-08-21', '', '', 'false', 0, '2020-08-22 08:12:40'),
(12, 6, '2020-08-22 08:12:40', '2030-08-22', 'A tutaj notatka dnia', '', 'false', 0, '2020-08-22 08:12:40'),
(13, 6, '2020-08-22 08:12:40', '2030-08-23', '', '', 'false', 0, '2020-08-22 08:12:40'),
(14, 6, '2020-08-22 08:12:40', '2030-08-24', '', '', 'false', 0, '2020-08-22 08:12:40'),
(15, 6, '2020-08-22 08:12:40', '2030-08-25', '', '', 'false', 0, '2020-08-22 08:12:40'),
(16, 6, '2020-08-22 08:12:40', '2030-08-26', '', '', 'false', 0, '2020-08-22 08:12:40'),
(17, 6, '2020-08-22 08:12:40', '2030-08-27', '', '', 'false', 0, '2020-08-22 08:12:40'),
(18, 6, '2020-08-22 08:12:40', '2030-08-28', '', '', 'false', 0, '2020-08-22 08:12:40'),
(19, 6, '2020-08-22 08:12:40', '2030-08-29', '', '', 'false', 0, '2020-08-22 08:12:40'),
(20, 6, '2020-08-22 08:12:40', '2030-08-30', '', '', 'false', 0, '2020-08-22 08:12:40'),
(21, 6, '2020-08-22 08:12:40', '2030-08-31', '', '', 'false', 0, '2020-08-22 08:12:40'),
(22, 6, '2020-08-22 08:12:40', '2030-09-01', '', '', 'false', 0, '2020-08-22 08:12:40'),
(23, 6, '2020-08-22 08:12:40', '2030-09-02', '', '', 'false', 0, '2020-08-22 08:12:40'),
(24, 6, '2020-08-22 08:12:40', '2030-09-03', '', '', 'false', 0, '2020-08-22 08:12:40'),
(25, 6, '2020-08-22 08:12:40', '2030-09-04', '', '', 'false', 0, '2020-08-22 08:12:40'),
(26, 6, '2020-08-22 08:12:40', '2030-09-05', '', '', 'false', 0, '2020-08-22 08:12:40'),
(27, 6, '2020-08-22 08:12:40', '2030-09-06', '', '', 'false', 0, '2020-08-22 08:12:40'),
(28, 6, '2020-08-22 08:12:40', '2030-09-07', '', '', 'false', 0, '2020-08-22 08:12:40'),
(29, 6, '2020-08-22 08:12:40', '2030-09-08', '', '', 'false', 0, '2020-08-22 08:12:40'),
(30, 6, '2020-08-22 08:12:40', '2030-09-09', '', '', 'false', 0, '2020-08-22 08:12:40'),
(31, 6, '2020-08-22 08:12:40', '2030-09-10', '', '', 'false', 0, '2020-08-22 08:12:40'),
(32, 6, '2020-08-22 08:12:40', '2030-09-11', '', '', 'false', 0, '2020-08-22 08:12:40'),
(33, 6, '2020-08-22 08:12:40', '2030-09-12', '', '', 'false', 0, '2020-08-22 08:12:40'),
(34, 6, '2020-08-22 08:12:40', '2030-09-13', '', '', 'false', 0, '2020-08-22 08:12:40'),
(35, 6, '2020-08-22 08:12:40', '2030-09-14', '', '', 'false', 0, '2020-08-22 08:12:40'),
(36, 6, '2020-08-22 08:12:40', '2030-09-15', '', '', 'false', 0, '2020-08-22 08:12:40'),
(37, 6, '2020-08-22 08:12:40', '2030-09-16', '', '', 'false', 0, '2020-08-22 08:12:40'),
(38, 6, '2020-08-22 08:12:40', '2030-09-17', '', '', 'false', 0, '2020-08-22 08:12:40'),
(39, 6, '2020-08-22 08:12:40', '2030-09-18', '', '', 'false', 0, '2020-08-22 08:12:40'),
(40, 6, '2020-08-22 08:12:40', '2030-09-19', '', '', 'false', 0, '2020-08-22 08:12:40'),
(41, 6, '2020-08-22 08:12:40', '2030-09-20', '', '', 'false', 0, '2020-08-22 08:12:40'),
(42, 6, '2020-08-22 08:12:40', '2030-09-21', '', 'Notatka poprzednia zmieniona', 'false', 0, '2020-08-22 08:12:40'),
(43, 6, '2020-08-22 08:12:40', '2030-09-22', 'A to notatka rano, z nowym contentem', 'Notatka popo, zmieniona ponownie', 'false', 6, '2020-08-22 08:12:40'),
(44, 6, '2020-08-22 08:12:40', '2030-09-23', '', 'Notatka na jutro zią', 'false', 6, '2020-08-22 08:12:40'),
(45, 6, '2020-08-22 08:12:40', '2030-09-24', '', '', 'false', 0, '2020-08-22 08:12:40'),
(46, 6, '2020-08-22 08:12:40', '2030-09-25', '', '', 'false', 0, '2020-08-22 08:12:40'),
(47, 6, '2020-08-22 08:12:40', '2030-09-26', '', '', 'false', 0, '2020-08-22 08:12:40'),
(48, 6, '2020-08-22 08:12:40', '2030-09-27', '', '', 'false', 0, '2020-08-22 08:12:40'),
(49, 6, '2020-08-22 08:12:40', '2030-09-28', '', '', 'false', 0, '2020-08-22 08:12:40'),
(50, 6, '2020-08-22 08:12:40', '2030-09-29', '', '', 'false', 0, '2020-08-22 08:12:40'),
(51, 6, '2020-08-22 08:12:40', '2030-09-30', '', '', 'false', 0, '2020-08-22 08:12:40'),
(52, 6, '2020-08-22 08:12:40', '2030-10-01', '', '', 'false', 0, '2020-08-22 08:12:40'),
(53, 6, '2020-08-22 08:12:40', '2030-10-02', '', '', 'false', 0, '2020-08-22 08:12:40'),
(54, 6, '2020-08-22 08:12:40', '2030-10-03', '', '', 'false', 0, '2020-08-22 08:12:40'),
(55, 6, '2020-08-22 08:12:40', '2030-10-04', '', '', 'false', 0, '2020-08-22 08:12:40'),
(56, 6, '2020-08-22 08:12:40', '2030-10-05', '', '', 'false', 0, '2020-08-22 08:12:40'),
(57, 6, '2020-08-22 08:12:40', '2030-10-06', '', '', 'false', 0, '2020-08-22 08:12:40'),
(58, 6, '2020-08-22 08:12:40', '2030-10-07', '', '', 'false', 0, '2020-08-22 08:12:40'),
(59, 6, '2020-08-22 08:12:40', '2030-10-08', '', '', 'false', 0, '2020-08-22 08:12:40'),
(60, 6, '2020-08-22 08:12:40', '2030-10-09', '', '', 'false', 0, '2020-08-22 08:12:40'),
(61, 6, '2020-08-22 08:12:40', '2030-10-10', '', '', 'false', 0, '2020-08-22 08:12:40'),
(62, 6, '2020-08-22 08:12:40', '2030-10-11', '', '', 'false', 0, '2020-08-22 08:12:40'),
(63, 6, '2020-08-22 08:12:40', '2030-10-12', '', '', 'false', 0, '2020-08-22 08:12:40'),
(64, 6, '2020-08-22 08:12:40', '2030-10-13', '', '', 'false', 0, '2020-08-22 08:12:40'),
(65, 6, '2020-08-22 08:12:40', '2030-10-14', '', '', 'false', 0, '2020-08-22 08:12:40'),
(66, 6, '2020-08-22 08:12:40', '2030-10-15', '', '', 'false', 0, '2020-08-22 08:12:40'),
(67, 6, '2020-08-22 08:12:40', '2030-10-16', '', '', 'false', 0, '2020-08-22 08:12:40'),
(68, 6, '2020-08-22 08:12:40', '2030-10-17', '', '', 'false', 0, '2020-08-22 08:12:40'),
(69, 6, '2020-08-22 08:12:40', '2030-10-18', '', '', 'false', 0, '2020-08-22 08:12:40'),
(70, 6, '2020-08-22 08:12:40', '2030-10-19', '', '', 'false', 0, '2020-08-22 08:12:40'),
(71, 6, '2020-08-22 08:12:40', '2030-10-20', '', '', 'false', 0, '2020-08-22 08:12:40'),
(72, 6, '2020-08-22 08:12:40', '2030-10-21', '', '', 'false', 0, '2020-08-22 08:12:40'),
(73, 6, '2020-08-22 08:12:40', '2030-10-22', '', '', 'false', 0, '2020-08-22 08:12:40'),
(74, 6, '2020-08-22 08:12:40', '2030-10-23', '', '', 'false', 0, '2020-08-22 08:12:40'),
(75, 6, '2020-08-22 08:12:40', '2030-10-24', '', '', 'false', 0, '2020-08-22 08:12:40'),
(76, 6, '2020-08-22 08:12:40', '2030-10-25', '', '', 'false', 0, '2020-08-22 08:12:40'),
(77, 6, '2020-08-22 08:12:40', '2030-10-26', '', '', 'false', 0, '2020-08-22 08:12:40'),
(78, 6, '2020-08-22 08:12:40', '2030-10-27', '', '', 'false', 0, '2020-08-22 08:12:40'),
(79, 6, '2020-08-22 08:12:40', '2030-10-28', '', '', 'false', 0, '2020-08-22 08:12:40'),
(80, 6, '2020-08-22 08:12:40', '2030-10-29', '', '', 'false', 0, '2020-08-22 08:12:40'),
(81, 6, '2020-08-22 08:12:40', '2030-10-30', '', '', 'false', 0, '2020-08-22 08:12:40'),
(82, 6, '2020-08-22 08:12:40', '2030-10-31', '', '', 'false', 0, '2020-08-22 08:12:40'),
(83, 6, '2020-08-22 08:12:40', '2030-11-01', '', '', 'false', 0, '2020-08-22 08:12:40'),
(84, 6, '2020-08-22 08:12:40', '2030-11-02', '', '', 'false', 0, '2020-08-22 08:12:40'),
(85, 6, '2020-08-22 08:12:40', '2030-11-03', '', '', 'false', 0, '2020-08-22 08:12:40'),
(86, 6, '2020-08-22 08:12:40', '2030-11-04', '', '', 'false', 0, '2020-08-22 08:12:40'),
(87, 6, '2020-08-22 08:12:40', '2030-11-05', '', '', 'false', 0, '2020-08-22 08:12:40'),
(88, 6, '2020-08-22 08:12:40', '2030-11-06', '', '', 'false', 0, '2020-08-22 08:12:40'),
(89, 6, '2020-08-22 08:12:40', '2030-11-07', '', '', 'false', 0, '2020-08-22 08:12:40'),
(90, 6, '2020-08-22 08:12:40', '2030-11-08', '', '', 'false', 0, '2020-08-22 08:12:40'),
(91, 6, '2020-08-22 08:12:40', '2030-11-09', '-> zadanie wykonane\n-> rzeczy jeszcze do ogarnięcia', '-> sprawdzić listy płac\n-> spróbujcie jutro coś tam coś tam', 'false', 6, '2020-08-22 08:12:40'),
(92, 6, '2020-08-22 08:12:40', '2030-11-10', '', '', 'false', 0, '2020-08-22 08:12:40'),
(93, 6, '2020-08-22 08:12:40', '2030-11-11', '', '', 'false', 0, '2020-08-22 08:12:40'),
(94, 6, '2020-08-22 08:12:40', '2030-11-12', '', '', 'false', 0, '2020-08-22 08:12:40'),
(95, 6, '2020-08-22 08:12:40', '2030-11-13', '', '', 'false', 0, '2020-08-22 08:12:40'),
(96, 6, '2020-08-22 08:12:40', '2030-11-14', '', '', 'false', 0, '2020-08-22 08:12:40'),
(97, 6, '2020-08-22 08:12:40', '2030-11-15', '', '', 'false', 0, '2020-08-22 08:12:40'),
(98, 6, '2020-08-22 08:12:40', '2030-11-16', '', '', 'false', 0, '2020-08-22 08:12:40'),
(99, 6, '2020-08-22 08:12:40', '2030-11-17', '', '', 'false', 0, '2020-08-22 08:12:40'),
(100, 6, '2020-08-22 08:12:40', '2030-11-18', '', '', 'false', 0, '2020-08-22 08:12:40'),
(101, 6, '2020-08-22 08:12:40', '2030-11-19', '', '', 'false', 0, '2020-08-22 08:12:40'),
(102, 6, '2020-08-22 08:12:40', '2030-11-20', '', '', 'false', 0, '2020-08-22 08:12:40'),
(103, 6, '2020-08-22 08:12:40', '2030-11-21', '', '', 'false', 0, '2020-08-22 08:12:40'),
(104, 6, '2020-08-22 08:12:40', '2030-11-22', '', '', 'false', 0, '2020-08-22 08:12:40'),
(105, 6, '2020-08-22 08:12:40', '2030-11-23', '', '', 'false', 0, '2020-08-22 08:12:40'),
(106, 6, '2020-08-22 08:12:40', '2030-11-24', '', '', 'false', 0, '2020-08-22 08:12:40'),
(107, 6, '2020-08-22 08:12:40', '2030-11-25', '', '', 'false', 0, '2020-08-22 08:12:40'),
(108, 6, '2020-08-22 08:12:40', '2030-11-26', '', '', 'false', 0, '2020-08-22 08:12:40'),
(109, 6, '2020-08-22 08:12:40', '2030-11-27', '', '', 'false', 0, '2020-08-22 08:12:40'),
(110, 6, '2020-08-22 08:12:40', '2030-11-28', '', '', 'false', 0, '2020-08-22 08:12:40'),
(111, 6, '2020-08-22 08:12:40', '2030-11-29', '', '', 'false', 0, '2020-08-22 08:12:40'),
(112, 6, '2020-08-22 08:12:40', '2030-11-30', '', '', 'false', 0, '2020-08-22 08:12:40'),
(113, 6, '2020-08-22 08:12:40', '2030-12-01', '', '', 'false', 0, '2020-08-22 08:12:40'),
(114, 6, '2020-08-22 08:12:40', '2030-12-02', '', '', 'false', 0, '2020-08-22 08:12:40'),
(115, 6, '2020-08-22 08:12:40', '2030-12-03', '', '', 'false', 0, '2020-08-22 08:12:40'),
(116, 6, '2020-08-22 08:12:40', '2030-12-04', '', '', 'false', 0, '2020-08-22 08:12:40'),
(117, 6, '2020-08-22 08:12:40', '2030-12-05', '', '', 'false', 0, '2020-08-22 08:12:40'),
(118, 6, '2020-08-22 08:12:40', '2030-12-06', '', '', 'false', 0, '2020-08-22 08:12:40'),
(119, 6, '2020-08-22 08:12:40', '2030-12-07', '', '', 'false', 0, '2020-08-22 08:12:40'),
(120, 6, '2020-08-22 08:12:40', '2030-12-08', '', '', 'false', 0, '2020-08-22 08:12:40'),
(121, 6, '2020-08-22 08:12:40', '2030-12-09', '', '', 'false', 0, '2020-08-22 08:12:40'),
(122, 6, '2020-08-22 08:12:40', '2030-12-10', '', '', 'false', 0, '2020-08-22 08:12:40'),
(123, 6, '2020-08-22 08:12:40', '2030-12-11', '', '', 'false', 0, '2020-08-22 08:12:40'),
(124, 6, '2020-08-22 08:12:40', '2030-12-12', '', '', 'false', 0, '2020-08-22 08:12:40'),
(125, 6, '2020-08-22 08:12:40', '2030-12-13', '', '', 'false', 0, '2020-08-22 08:12:40'),
(126, 6, '2020-08-22 08:12:40', '2030-12-14', '', '', 'false', 0, '2020-08-22 08:12:40'),
(127, 6, '2020-08-22 08:12:40', '2030-12-15', '', '', 'false', 0, '2020-08-22 08:12:40'),
(128, 6, '2020-08-22 08:12:40', '2030-12-16', '', '', 'false', 0, '2020-08-22 08:12:40'),
(129, 6, '2020-08-22 08:12:40', '2030-12-17', '', '', 'false', 0, '2020-08-22 08:12:40'),
(130, 6, '2020-08-22 08:12:40', '2030-12-18', '', '', 'false', 0, '2020-08-22 08:12:40'),
(131, 6, '2020-08-22 08:12:40', '2030-12-19', '', '', 'false', 0, '2020-08-22 08:12:40'),
(132, 6, '2020-08-22 08:12:40', '2030-12-20', '', '', 'false', 0, '2020-08-22 08:12:40'),
(133, 6, '2020-08-22 08:12:40', '2030-12-21', '', '', 'false', 0, '2020-08-22 08:12:40'),
(134, 6, '2020-08-22 08:12:40', '2030-12-22', '', '', 'false', 0, '2020-08-22 08:12:40'),
(135, 6, '2020-08-22 08:12:40', '2030-12-23', '', '', 'false', 0, '2020-08-22 08:12:40'),
(136, 6, '2020-08-22 08:12:40', '2030-12-24', '', '', 'false', 0, '2020-08-22 08:12:40'),
(137, 6, '2020-08-22 08:12:40', '2030-12-25', '', '', 'false', 0, '2020-08-22 08:12:40'),
(138, 6, '2020-08-22 08:12:40', '2030-12-26', '', '', 'false', 0, '2020-08-22 08:12:40'),
(139, 6, '2020-08-22 08:12:40', '2030-12-27', '', '', 'false', 0, '2020-08-22 08:12:40'),
(140, 6, '2020-08-22 08:12:40', '2030-12-28', '', '', 'false', 0, '2020-08-22 08:12:40'),
(141, 6, '2020-08-22 08:12:40', '2030-12-29', '', '', 'false', 0, '2020-08-22 08:12:40'),
(142, 6, '2020-08-22 08:12:40', '2030-12-30', '', '', 'false', 0, '2020-08-22 08:12:40'),
(143, 6, '2020-08-22 08:12:40', '2030-12-31', '', '', 'false', 0, '2020-08-22 08:12:40'),
(144, 6, '2020-08-22 08:12:40', '0000-00-00', '', '', 'false', 0, '2020-08-22 08:12:40'),
(145, 6, '2020-08-22 08:57:59', '2020-08-22', 'Zadanie prio na dziś!', '', 'false', 0, '2020-08-22 08:57:59'),
(146, 6, '2020-08-22 08:58:14', '2020-08-22', 'A tutaj jeszcze jedno!', '', 'false', 0, '2020-08-22 08:58:14'),
(147, 6, '2020-09-22 09:46:24', '2020-09-23', 'Zadanie przyszłe 3', 'Przykładowa notatka do przykładowego zadania\nCo tam, jak tam', 'false', 6, '2020-09-23 12:50:29'),
(148, 6, '2020-09-22 12:42:21', '2020-09-24', 'Przykładowe zadanie dla testu', 'Pliki pliki', 'false', 0, '2020-09-22 12:42:21'),
(150, 6, '2020-11-11 19:45:53', '2020-11-09', 'Uzupełnić wnioski', '', 'false', 0, '2020-11-11 19:45:53');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `job`
--

CREATE TABLE `job` (
  `ID` int(11) NOT NULL,
  `The_ID` int(11) NOT NULL COMMENT 'ID ZADANIA',
  `Topic` text COLLATE utf8_polish_ci NOT NULL COMMENT 'Temat',
  `Info` text COLLATE utf8_polish_ci NOT NULL COMMENT 'Informacja o zadaniu',
  `Type` text COLLATE utf8_polish_ci NOT NULL,
  `WhoAdd` int(11) NOT NULL COMMENT 'ID osoby dodającej',
  `ForWho` int(11) NOT NULL COMMENT 'ID osoby, która ma wykonać zadanie',
  `Length` int(11) NOT NULL DEFAULT 2,
  `Start` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Data dodania zadania',
  `Visited` timestamp NOT NULL DEFAULT current_timestamp(),
  `Visited_Admin` timestamp NOT NULL DEFAULT current_timestamp(),
  `End` date NOT NULL COMMENT 'Data deadline''u'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `job`
--

INSERT INTO `job` (`ID`, `The_ID`, `Topic`, `Info`, `Type`, `WhoAdd`, `ForWho`, `Length`, `Start`, `Visited`, `Visited_Admin`, `End`) VALUES
(282, 62, 'Powidz że to działo', 'Proszem', 'def', 7, 7, 1, '2020-01-15 12:22:06', '2020-01-15 12:22:40', '2020-01-15 12:22:40', '2020-01-22'),
(313, 69, 'Ustalić grafik', 'Jak najszybciej', 'sta', 6, 7, 1, '2020-02-09 20:03:12', '2020-02-09 20:03:12', '2020-02-09 20:04:23', '2020-02-20'),
(314, 69, 'Ustalić grafik', 'Jak najszybciej', '', 6, 6, 1, '2020-02-09 20:05:24', '2020-02-09 20:05:24', '2020-02-09 20:05:24', '2020-02-20'),
(316, 70, 'Udostępnić grafiki na MARZEC', 'Pamiętajcie, że macie czas tylko do 22!', 'sta', 24, 19, 1, '2020-02-09 20:10:48', '2020-02-09 20:10:48', '2020-02-10 13:14:55', '2020-02-22'),
(339, 86, 'Nowe zadanie D', 'Cyk zadanko 3 osoby!', 'def', 24, 22, 2, '2020-02-12 07:47:14', '2020-02-12 07:47:14', '2020-02-12 07:49:17', '2020-02-21'),
(340, 86, 'Nowe zadanie D', 'Cyk zadanko 3 osoby!', 'def', 24, 19, 2, '2020-02-12 07:47:16', '2020-02-12 07:47:16', '2020-02-12 07:49:17', '2020-02-21'),
(344, 91, 'Inwentaryzacja wózków pickingowych 07.2020', 'Ocena techniczna wózków pickingowych i uzupełnienie pliku: https://docs.google.com/spreadsheets/d/1WWSRG0pZ0V_mOJxKrbfzdI5qdPXT_UmCiS2bxXKA40E/edit#gid=0', 'def', 0, 8, 2, '2020-07-22 12:32:37', '2020-07-22 12:32:37', '2020-07-22 12:32:37', '2020-07-25'),
(345, 91, 'Inwentaryzacja wózków pickingowych 07.2020', 'Ocena techniczna wózków pickingowych i uzupełnienie pliku: https://docs.google.com/spreadsheets/d/1WWSRG0pZ0V_mOJxKrbfzdI5qdPXT_UmCiS2bxXKA40E/edit#gid=0', 'def', 0, 10, 2, '2020-07-22 12:32:37', '2020-07-22 12:32:37', '2020-07-22 12:32:37', '2020-07-25'),
(346, 91, 'Inwentaryzacja wózków pickingowych 07.2020', 'Ocena techniczna wózków pickingowych i uzupełnienie pliku: https://docs.google.com/spreadsheets/d/1WWSRG0pZ0V_mOJxKrbfzdI5qdPXT_UmCiS2bxXKA40E/edit#gid=0', 'def', 0, 11, 2, '2020-07-22 12:32:37', '2020-07-22 12:32:37', '2020-07-22 12:32:37', '2020-07-25'),
(350, 92, 'Zadanie nadane na długi okres', 'Przykładowe zadanie', 'def', 6, 6, 2, '2020-12-08 14:30:41', '2020-12-08 14:30:56', '2020-12-08 14:30:56', '2020-12-23');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `job_index`
--

CREATE TABLE `job_index` (
  `ID` int(11) NOT NULL,
  `Jednostka` text COLLATE utf8_polish_ci NOT NULL,
  `The_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `job_index`
--

INSERT INTO `job_index` (`ID`, `Jednostka`, `The_ID`) VALUES
(1, 'CAR Gliwice', 93);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `job_red`
--

CREATE TABLE `job_red` (
  `ID` int(11) NOT NULL,
  `The_ID` int(11) NOT NULL,
  `ForWho` int(11) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `job_red`
--

INSERT INTO `job_red` (`ID`, `The_ID`, `ForWho`, `Date`) VALUES
(16, 32, 8, '2019-11-30 10:55:15'),
(17, 32, 9, '2019-11-30 10:55:15'),
(18, 32, 10, '2019-11-30 10:55:15'),
(19, 32, 11, '2019-11-30 10:55:15'),
(20, 32, 15, '2019-11-30 10:55:15'),
(23, 36, 8, '2019-11-30 10:55:16'),
(24, 36, 9, '2019-11-30 10:55:16'),
(25, 36, 10, '2019-11-30 10:55:16'),
(26, 36, 11, '2019-11-30 10:55:16'),
(27, 36, 15, '2019-11-30 10:55:16'),
(30, 37, 8, '2019-11-30 10:55:17'),
(31, 37, 9, '2019-11-30 10:55:17'),
(32, 37, 10, '2019-11-30 10:55:17'),
(33, 37, 11, '2019-11-30 10:55:17'),
(34, 37, 15, '2019-11-30 10:55:17'),
(37, 38, 8, '2019-11-30 10:55:18'),
(38, 38, 9, '2019-11-30 10:55:18'),
(39, 38, 10, '2019-11-30 10:55:18'),
(40, 38, 11, '2019-11-30 10:55:18'),
(41, 38, 15, '2019-11-30 10:55:18'),
(42, 38, 16, '2019-11-30 10:55:19'),
(47, 39, 8, '2019-11-30 10:55:19'),
(48, 39, 9, '2019-11-30 10:55:20'),
(49, 39, 10, '2019-11-30 10:55:20'),
(50, 39, 11, '2019-11-30 10:55:20'),
(51, 39, 15, '2019-11-30 10:55:20'),
(52, 39, 16, '2019-11-30 10:55:20'),
(53, 40, 7, '2019-11-30 10:55:20'),
(54, 40, 16, '2019-11-30 10:55:20'),
(63, 43, 18, '2019-12-02 09:46:38'),
(78, 62, 7, '2020-07-22 11:39:31'),
(80, 69, 7, '2020-07-22 11:39:31'),
(81, 69, 6, '2020-07-22 11:39:31'),
(82, 70, 19, '2020-07-22 11:39:31'),
(84, 77, 6, '2020-07-22 11:39:31'),
(85, 78, 6, '2020-07-22 11:39:31'),
(87, 85, 6, '2020-07-22 11:39:31'),
(88, 86, 22, '2020-07-22 11:39:31'),
(89, 86, 19, '2020-07-22 11:39:31');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `job_ri`
--

CREATE TABLE `job_ri` (
  `ID` int(11) NOT NULL,
  `ForWho` int(11) NOT NULL,
  `Identificator` text COLLATE utf8_polish_ci NOT NULL,
  `Info` text COLLATE utf8_polish_ci NOT NULL,
  `Completed` text COLLATE utf8_polish_ci NOT NULL,
  `Month` text COLLATE utf8_polish_ci NOT NULL,
  `EditTimestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `job_ri`
--

INSERT INTO `job_ri` (`ID`, `ForWho`, `Identificator`, `Info`, `Completed`, `Month`, `EditTimestamp`) VALUES
(10, 6, '1584723187612', 'Sprawdzić czy działa', 'false', '2003', '2020-03-20 16:53:12'),
(11, 6, '1584723193391', 'I nie zlicza innych miechów', 'true', '2004', '2020-03-20 16:53:19'),
(12, 6, '1584723201043', 'Dokładnie!', 'false', '2003', '2020-03-20 16:53:24'),
(13, 6, '1584723327470', 'Poprawić produktywność', 'false', '2003', '2020-03-20 16:55:37'),
(14, 8, '1584726514159', 'Jedno zadanko cyk', 'false', '2003', '2020-03-20 17:48:38'),
(15, 8, '1584726519449', 'Drugie zadanko cyk', 'true', '2003', '2020-03-20 17:48:44'),
(16, 8, '1584726525787', 'Trzecie zadanko cyk', 'true', '2004', '2020-03-20 17:48:50');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project`
--

CREATE TABLE `project` (
  `ID` int(11) NOT NULL,
  `Name` text COLLATE utf8_polish_ci NOT NULL,
  `Description` text COLLATE utf8_polish_ci NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Admin` text COLLATE utf8_polish_ci NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `project`
--

INSERT INTO `project` (`ID`, `Name`, `Description`, `User_ID`, `Admin`, `Date`) VALUES
(1, 'Pierwszy projekt', 'Wracamy do poprzedniego opisu: Ogólnym celem naszego projektu jest urozmaicenie PlanDeci i rozwinięcie jej na wiele sposobów. Dajcie proszę znać jak wam się podoba nowa aktualizacja!', 8, 'true', '2020-04-28 13:01:08'),
(2, 'Drugi projekt', '', 8, 'true', '2020-04-09 10:22:10'),
(3, 'Trzeci projekt', '', 8, 'true', '2020-04-09 10:22:35'),
(4, 'Czwarty projekt', '', 8, 'true', '2020-04-09 10:29:22'),
(6, 'Pierwszy projekt', 'Wracamy do poprzedniego opisu: Ogólnym celem naszego projektu jest urozmaicenie PlanDeci i rozwinięcie jej na wiele sposobów. Dajcie proszę znać jak wam się podoba nowa aktualizacja!', 6, 'false', '2020-04-23 13:40:01'),
(7, 'Pierwszy projekt', 'Wracamy do poprzedniego opisu: Ogólnym celem naszego projektu jest urozmaicenie PlanDeci i rozwinięcie jej na wiele sposobów. Dajcie proszę znać jak wam się podoba nowa aktualizacja!', 10, 'false', '2020-04-24 17:00:51');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project_jobs`
--

CREATE TABLE `project_jobs` (
  `ID` int(11) NOT NULL,
  `Project_Name` text COLLATE utf8_polish_ci NOT NULL,
  `Info` text COLLATE utf8_polish_ci NOT NULL,
  `ForWho` int(11) NOT NULL,
  `Completed` text COLLATE utf8_polish_ci NOT NULL,
  `Start` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `End` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `project_jobs`
--

INSERT INTO `project_jobs` (`ID`, `Project_Name`, `Info`, `ForWho`, `Completed`, `Start`, `End`) VALUES
(1, 'Pierwszy projekt', 'Pierwsze zadanie do projektu', 6, 'false', '2020-04-22 15:31:23', '2020-04-30'),
(3, 'Pierwszy projekt', 'Trzecie zadanie do projektu', 8, 'true', '2020-04-22 15:32:11', '2020-05-01'),
(4, 'Pierwszy projekt', 'Teścik czy zadanie działa', 6, 'false', '2020-04-23 14:22:03', '2020-04-30'),
(6, 'Pierwszy projekt', 'Ćwiczenia gitary', 10, 'false', '2020-04-24 18:26:06', '2020-05-03'),
(7, 'Pierwszy projekt', 'Dajcie mi A', 8, 'false', '2020-04-28 11:40:41', '2020-05-03'),
(8, 'Pierwszy projekt', 'Dajcie mi N', 6, 'false', '2020-04-28 11:41:05', '2020-05-29'),
(9, 'Pierwszy projekt', 'Dużo zadań trzeba zrobić', 10, 'false', '2020-04-28 11:42:32', '2020-05-21'),
(10, 'Pierwszy projekt', 'Cała lista', 8, 'false', '2020-04-28 11:42:52', '2020-04-30'),
(11, 'Pierwszy projekt', 'Ni mom pojęcia', 8, 'false', '2020-04-28 11:43:11', '2020-05-04'),
(12, 'Pierwszy projekt', 'Szła dzieweczka', 6, 'false', '2020-04-28 11:43:34', '2020-05-31'),
(13, 'Pierwszy projekt', 'Do laseczka', 6, 'false', '2020-04-28 11:43:49', '2020-06-01'),
(14, 'Pierwszy projekt', 'Tutaj matury', 6, 'false', '2020-04-28 11:44:09', '2020-06-08'),
(15, 'Pierwszy projekt', 'A tutaj moje urodziny', 6, 'false', '2020-04-28 11:44:31', '2020-06-11'),
(16, 'Pierwszy projekt', 'A tutaj skończyłem szkołe', 6, 'false', '2020-04-28 11:45:03', '2020-04-24'),
(17, 'Pierwszy projekt', 'I jeszcze jeden', 6, 'false', '2020-04-28 11:45:29', '2020-04-29');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project_resources`
--

CREATE TABLE `project_resources` (
  `ID` int(11) NOT NULL,
  `Project_Name` text COLLATE utf8_polish_ci NOT NULL,
  `Is_Picture` text COLLATE utf8_polish_ci NOT NULL,
  `Info` text COLLATE utf8_polish_ci NOT NULL,
  `Connect_With` int(11) NOT NULL,
  `WhoAdd` int(11) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `project_resources`
--

INSERT INTO `project_resources` (`ID`, `Project_Name`, `Is_Picture`, `Info`, `Connect_With`, `WhoAdd`, `Date`) VALUES
(1, 'Pierwszy projekt', 'false', 'Siemanko, krótki pościk, żeby sprawdzić jak wam idzie!', 0, 6, '2020-04-22 15:51:22');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `save_server`
--

CREATE TABLE `save_server` (
  `ID` int(11) NOT NULL,
  `Date` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Tabela trzyma zapis o przebiegu save_server';

--
-- Zrzut danych tabeli `save_server`
--

INSERT INTO `save_server` (`ID`, `Date`) VALUES
(43, '22.07.2020');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `susers`
--

CREATE TABLE `susers` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Jednostka` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `susers`
--

INSERT INTO `susers` (`ID`, `User_ID`, `Jednostka`) VALUES
(1, 6, 'CAR Gliwice'),
(2, 16, 'CAR Gliwice');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `task`
--

CREATE TABLE `task` (
  `ID` int(11) NOT NULL,
  `WhoAdd` int(11) NOT NULL,
  `Info` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Login` text COLLATE utf8_polish_ci NOT NULL,
  `Password` text COLLATE utf8_polish_ci NOT NULL,
  `Imie` text COLLATE utf8_polish_ci NOT NULL,
  `Nazwisko` text COLLATE utf8_polish_ci NOT NULL,
  `Dzial` text COLLATE utf8_polish_ci NOT NULL,
  `Rola` text COLLATE utf8_polish_ci NOT NULL,
  `RI` text COLLATE utf8_polish_ci NOT NULL,
  `Email` text COLLATE utf8_polish_ci NOT NULL,
  `Jednostka` text COLLATE utf8_polish_ci NOT NULL,
  `Activity` timestamp NOT NULL DEFAULT current_timestamp(),
  `Spoznien` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Użytkownicy PYR';

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`ID`, `Login`, `Password`, `Imie`, `Nazwisko`, `Dzial`, `Rola`, `RI`, `Email`, `Jednostka`, `Activity`, `Spoznien`) VALUES
(6, 'aheese', 'aheese', 'Aleksander', 'Heese', 'wskl', 'prac', '8', 'aleksander.heese@decathlon.com', 'CAR Gliwice', '2020-12-08 14:33:40', 14),
(7, 'akowalski', 'akowalski', 'Adam', 'Kowalski', 'btwn', 'szko', '8', 'test1rvrlk@o2.pl', 'CAR Gliwice', '2020-01-15 12:22:34', 13),
(8, 'bnowak', 'bnowak', 'Bartosz', 'Nowak', 'quec', 'kier', '', 'test2rvrlk@o2.pl', 'CAR Gliwice', '2020-04-28 13:01:08', 12),
(9, 'sjarzebina', 'sjarzebina', 'Stefan', 'Jarzębina', 'kale', 'staz', '', 'tenisista2000@o2.pl', 'CAR Gliwice', '2019-11-23 08:32:39', 9),
(10, 'okucharczyk', 'okucharczyk', 'Olga', 'Kucharczyk', 'domy', 'prac', '', 'borys6355@onet.pl', 'CAR Gliwice', '2019-11-23 08:32:39', 8),
(11, 'kpuszczyk', 'kpuszczyk', 'Karolina', 'Puszczyk', 'ines', 'prac', '', 'randommail@wp.pl', 'CAR Gliwice', '2020-02-04 20:23:17', 8),
(15, 'zjanicki', 'zjanick', 'Zbigniew', 'Janicki', 'sube', 'szko', '', 'zjanicki@o2.pl', 'CAR Gliwice', '2020-01-15 12:01:53', 7),
(19, 'padamska', 'padamska', 'Patrycja', 'Adamska', 'ecom', 'kier', '', 'padamska@gmail.com', 'CAR Gliwice', '2020-01-21 10:53:49', 2),
(20, 'ibrzozowski', 'ibrzozowski', 'Igor', 'Brzozowski', 'geol', 'staz', '8', 'ibrzozowski@gmail.com', 'CAR Gliwice', '2020-01-21 10:55:12', 0),
(21, 'nlewandowski', 'nlewandowski', 'Natan', 'Lewandowski', 'ramp', 'kier', '8', 'rand@gmail.com', 'CAR Gliwice', '2020-01-21 12:19:23', 1),
(22, 'mwlodarczyk', 'mwlodarczyk', 'Maria', 'Włodarczyk', 'admi', 'admi', '', 'rand@gmail.com', 'CAR Gliwice', '2020-01-21 10:58:03', 1),
(24, 'hkania', 'hkania', 'Hubert', 'Kania', 'kadr', 'kadr', '', 'hkania@gmail.com', 'CAR Gliwice', '2020-11-11 19:48:59', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `done`
--
ALTER TABLE `done`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `hr_tasks`
--
ALTER TABLE `hr_tasks`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `job_index`
--
ALTER TABLE `job_index`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `job_red`
--
ALTER TABLE `job_red`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `job_ri`
--
ALTER TABLE `job_ri`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `project_jobs`
--
ALTER TABLE `project_jobs`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `project_resources`
--
ALTER TABLE `project_resources`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `save_server`
--
ALTER TABLE `save_server`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `susers`
--
ALTER TABLE `susers`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `chat`
--
ALTER TABLE `chat`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT dla tabeli `done`
--
ALTER TABLE `done`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT dla tabeli `hr_tasks`
--
ALTER TABLE `hr_tasks`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT dla tabeli `job`
--
ALTER TABLE `job`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=352;

--
-- AUTO_INCREMENT dla tabeli `job_index`
--
ALTER TABLE `job_index`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `job_red`
--
ALTER TABLE `job_red`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT dla tabeli `job_ri`
--
ALTER TABLE `job_ri`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT dla tabeli `project`
--
ALTER TABLE `project`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT dla tabeli `project_jobs`
--
ALTER TABLE `project_jobs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT dla tabeli `project_resources`
--
ALTER TABLE `project_resources`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `save_server`
--
ALTER TABLE `save_server`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT dla tabeli `susers`
--
ALTER TABLE `susers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `task`
--
ALTER TABLE `task`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
