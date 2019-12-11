-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 11 Gru 2019, 02:34
-- Wersja serwera: 10.4.8-MariaDB
-- Wersja PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `quizapp`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `accounts`
--

CREATE TABLE `accounts` (
  `username` varchar(32) NOT NULL,
  `password_hash` char(60) NOT NULL,
  `admin` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `accounts`
--

INSERT INTO `accounts` (`username`, `password_hash`, `admin`) VALUES
('admin', '$2y$10$tvAGxQySzosklFKKwo2GUOpiguVMGi/cbUsyDhu4E71tpJrXPbnT.', b'1'),
('techtad', '$2y$10$hlNmTrv6uB1BdWJqkcEb0OVrMiZ990r3H5yLKN9do90a37E.VZNVC', b'0');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `questions`
--

CREATE TABLE `questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `quiz_id` int(10) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `answer_a` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `answer_b` varchar(256) NOT NULL,
  `answer_c` varchar(256) NOT NULL,
  `answer_d` varchar(256) NOT NULL,
  `right_answer` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question`, `answer_a`, `answer_b`, `answer_c`, `answer_d`, `right_answer`) VALUES
(26, 15, '1+1=?', '0', '1', '2', '3', 'C'),
(27, 15, '2+2=?', '-99999', '69', '+nieskończoność', '4', 'D'),
(28, 15, '9999+1111=?', '11110', '100000', '10101', '20002', 'A'),
(29, 16, 'Bitwa pod Grunwaldem', '1140', '1410', '966', '1939', 'B'),
(30, 16, 'Odsiecz Wiedeńska', '1507', '1638', '1683', '2016', 'C'),
(31, 15, '8+9=?', '456', '789', '4', '17', 'D'),
(32, 16, '3', 'w', 'e', 'q', 'w', 'A'),
(33, 16, '4', 'w', 'e', 'q', 'a', 'A'),
(34, 16, '5', 'qw', 'qw', 'a', 'qw', 'B'),
(35, 16, '6', 'as', 'qwz', 'qwa', 'asa', 'A'),
(37, 16, '8', 'weq', 'eawsae', 'waeae', 'weae', 'C'),
(38, 16, '9', 'waewa', 'waewa', 'asdwea', 'ew', 'D'),
(39, 16, '10', 'wewae', 'waeaw', 'waewad', 'waewa', 'B'),
(40, 16, '11', 'waewa', 'waewae', 'weawe', 'waewe', 'A'),
(42, 16, '12', 's', 'd', 's', 'w', 'B'),
(43, 16, '13', 'q', 'ew', 'wa', 'a', 'D'),
(44, 16, '14', 'weq', 'aawe', 'wqqa', 'asd', 'C'),
(45, 16, 'Pytanie?', 'w', 'e', 'a', 'q', 'C');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `quizzes`
--

INSERT INTO `quizzes` (`id`, `name`, `description`) VALUES
(15, 'Dodawanie', 'Podstawowa matematyka'),
(16, 'Daty bitew', 'Sprawdza wiedzę historyczną');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `scores`
--

CREATE TABLE `scores` (
  `take_id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `user` varchar(32) NOT NULL,
  `answer` char(1) NOT NULL DEFAULT 'A',
  `correct` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `scores`
--

INSERT INTO `scores` (`take_id`, `question_id`, `user`, `answer`, `correct`) VALUES
(1, 37, 'techtad', 'A', b'0'),
(1, 42, 'techtad', 'X', b'0'),
(1, 44, 'techtad', 'A', b'0'),
(1, 33, 'techtad', 'A', b'1'),
(1, 35, 'techtad', 'A', b'1'),
(1, 30, 'techtad', 'C', b'1'),
(1, 40, 'techtad', 'A', b'1'),
(1, 43, 'techtad', 'A', b'0'),
(1, 34, 'techtad', 'A', b'0'),
(1, 39, 'techtad', 'A', b'0'),
(2, 34, 'techtad', 'D', b'0'),
(2, 29, 'techtad', 'B', b'1'),
(2, 44, 'techtad', 'B', b'0'),
(2, 43, 'techtad', 'A', b'0'),
(2, 39, 'techtad', 'B', b'1'),
(2, 37, 'techtad', 'B', b'0'),
(2, 32, 'techtad', 'A', b'1'),
(2, 38, 'techtad', 'A', b'0'),
(2, 40, 'techtad', 'A', b'1'),
(2, 42, 'techtad', 'A', b'0'),
(3, 34, 'techtad', 'C', b'0'),
(3, 29, 'techtad', 'B', b'1'),
(3, 44, 'techtad', 'A', b'0'),
(3, 43, 'techtad', 'A', b'0'),
(3, 39, 'techtad', 'A', b'0'),
(3, 37, 'techtad', 'A', b'0'),
(3, 32, 'techtad', 'B', b'0'),
(3, 38, 'techtad', 'A', b'0'),
(3, 40, 'techtad', 'A', b'1'),
(3, 42, 'techtad', 'D', b'0'),
(4, 32, 'admin', 'C', b'0'),
(4, 42, 'admin', 'B', b'1'),
(4, 38, 'admin', 'B', b'0'),
(4, 37, 'admin', 'A', b'0'),
(4, 35, 'admin', 'A', b'1'),
(4, 29, 'admin', 'B', b'1'),
(4, 39, 'admin', 'A', b'0'),
(4, 43, 'admin', 'A', b'0'),
(4, 44, 'admin', 'D', b'0'),
(4, 34, 'admin', 'C', b'0'),
(5, 32, 'techtad', 'A', b'1'),
(5, 45, 'techtad', 'C', b'1'),
(5, 39, 'techtad', 'D', b'0'),
(5, 43, 'techtad', 'A', b'0'),
(5, 30, 'techtad', 'B', b'0'),
(5, 40, 'techtad', 'B', b'0'),
(5, 35, 'techtad', 'D', b'0'),
(5, 44, 'techtad', 'D', b'0'),
(5, 34, 'techtad', 'C', b'0'),
(5, 42, 'techtad', 'A', b'0');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `takes`
--

CREATE TABLE `takes` (
  `id` int(10) UNSIGNED NOT NULL,
  `quiz_id` int(10) UNSIGNED NOT NULL,
  `user` varchar(32) NOT NULL,
  `correct` tinyint(3) UNSIGNED NOT NULL,
  `wrong` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `takes`
--

INSERT INTO `takes` (`id`, `quiz_id`, `user`, `correct`, `wrong`) VALUES
(1, 16, 'techtad', 4, 6),
(2, 16, 'techtad', 4, 6),
(3, 16, 'techtad', 2, 8),
(4, 16, 'admin', 3, 7),
(5, 16, 'techtad', 2, 8);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`username`);

--
-- Indeksy dla tabeli `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeksy dla tabeli `takes`
--
ALTER TABLE `takes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT dla tabeli `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT dla tabeli `takes`
--
ALTER TABLE `takes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
