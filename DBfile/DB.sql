-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 25 Mar 2021, 10:52
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
-- Baza danych: `rpg`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `attacks`
--

CREATE TABLE `attacks` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `stamina_cost` float(12,2) NOT NULL,
  `mana_cost` float(12,2) NOT NULL,
  `Combat_text` varchar(500) NOT NULL,
  `Combat_power` smallint(6) NOT NULL,
  `Purchase_cost` int(11) NOT NULL,
  `Type` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `attacks`
--

INSERT INTO `attacks` (`id`, `name`, `stamina_cost`, `mana_cost`, `Combat_text`, `Combat_power`, `Purchase_cost`, `Type`) VALUES
(1, 'Punch', 5.00, 0.00, ' hiting a opponent!', 100, 0, 'melee'),
(2, 'ice rain', 0.00, 30.00, ' icicles fall on your opponent', 150, 50, 'magic');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `chat`
--

INSERT INTO `chat` (`id`, `username`, `msg`) VALUES
(38, 'admin', 'tbydhffffffffff'),
(39, 'admin', 'eluwina kurłogłązy'),
(40, 'admin', 'nudy w hoiiiii some1 want trade leg items?'),
(41, 'admin', '76t5f7ygu8ihj879ygh78'),
(42, 'Wenzzi', 'test');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `chests`
--

CREATE TABLE `chests` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `cost` int(11) NOT NULL,
  `items` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `pieces` int(11) NOT NULL,
  `required_level` int(11) NOT NULL,
  `purchase_cost` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `intelligence` int(11) NOT NULL,
  `endurance` int(11) NOT NULL,
  `luck` int(11) NOT NULL,
  `damage` int(11) NOT NULL,
  `item_type` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `rare` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `items`
--

INSERT INTO `items` (`id`, `name`, `pieces`, `required_level`, `purchase_cost`, `strength`, `intelligence`, `endurance`, `luck`, `damage`, `item_type`, `type`, `rare`) VALUES
(1, 'Short sword', 0, 1, 10, 4, 0, 0, 0, 0, 'equipment', 'weapon', 'common'),
(3, 'wolf skin armor', 0, 3, 10, 0, 0, 5, 0, 0, 'equipment', 'armor', 'common'),
(4, 'Dragon scales ring', 0, 30, 300, 25, 10, 5, 0, 0, 'equipment', 'ring', 'rare'),
(5, 'Medusa talisman', 0, 65, 1000, 20, 50, 5, 0, 0, 'equipment', 'talisman', 'Legendary'),
(6, 'Dagger', 0, 3, 25, 10, 0, 0, 0, 15, 'equipment', 'weapon', 'common'),
(7, 'Staff of destruction', 0, 35, 300, 0, 35, 5, 0, 150, 'equipment', 'weapon', 'rare'),
(8, 'Long sword', 0, 5, 35, 15, 0, 5, 0, 25, 'equipment', 'weapon', 'common'),
(9, 'Medium iron armor', 0, 15, 300, 5, 0, 10, 0, 0, 'equipment', 'armor', 'common'),
(10, 'rusty ring', 0, 2, 15, 2, 1, 2, 3, 0, 'equipment', 'ring', 'common'),
(11, 'Plush pink rabbit', 0, 20, 300, 5, 2, 5, 10, 0, 'equipment', 'talisman', 'rare'),
(12, 'Elder dragon eye ring', 0, 45, 450, 15, 30, 20, 15, 0, 'equipment', 'ring', 'Epic'),
(13, 'Small piece of silver', 0, 1, 5, 1, 1, 1, 1, 1, 'gold', 'none', 'common');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `monsters`
--

CREATE TABLE `monsters` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `level` smallint(6) NOT NULL,
  `max_health` float(12,2) NOT NULL,
  `min_dmg` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `intelligence` int(11) NOT NULL,
  `endurance` int(11) NOT NULL,
  `attacks` text NOT NULL,
  `drops` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `monsters`
--

INSERT INTO `monsters` (`id`, `name`, `level`, `max_health`, `min_dmg`, `strength`, `intelligence`, `endurance`, `attacks`, `drops`) VALUES
(23, 'Dog', 2, 50.00, 20, 3, 5, 2, '{\"1\":{\"combat_text\":\"bites you\",\"power\":80,\"type\":\"melee\"},\"2\":{\"combat_text\":\" tears you apart with his claws\",\"power\":70,\"type\":\"melee\"}}', ''),
(24, 'vampire', 60, 900.00, 50, 30, 50, 2, '{\"1\":{\"combat_text\":\"attacks you with bats\",\"power\":80,\"type\":\"magic\"},\"2\":{\"combat_text\":\" uses an illusion on you through telekinesis\",\"power\":150,\"type\":\"magic\"}}', ''),
(27, 'Red demon', 55, 4500.00, 30, 50, 20, 40, '{\"1\":{\"combat_text\":\"drains your soul\",\"power\":150,\"type\":\"magic\"},\"2\":{\"combat_text\":\"hits you\",\"power\":170,\"type\":\"melee\"}}', ''),
(28, 'Gray demon', 75, 6500.00, 50, 40, 70, 99, '{\"1\":{\"combat_text\":\"strikes you with lightning\",\"power\":180,\"type\":\"magic\"},\"2\":{\"combat_text\":\"hit you\",\"power\":150,\"type\":\"melee\"}}', ''),
(36, 'test', 70, 30.00, 1, 1, 1, 1, '{\"1\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"},\"2\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"}}', '{\"1\":{\"id\":3,\"amount\":1,\"chances\":30},\"2\":{\"id\":6,\"amount\":1,\"chances\":30}}'),
(37, 'monster65', 65, 1.00, 1, 1, 1, 1, '{\"1\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"},\"2\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"}}', '{\"1\":{\"id\":13,\"amount\":3,\"chances\":100},\"2\":{\"id\":6,\"amount\":1,\"chances\":50}}'),
(38, 'monster95', 95, 1.00, 1, 1, 1, 1, '{\"1\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"},\"2\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"}}', '{\"1\":{\"id\":6,\"amount\":1,\"chances\":100},\"2\":{\"id\":1,\"amount\":1,\"chances\":1}}'),
(39, 'test', 80, 1.00, 1, 1, 1, 1, '{\"1\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"},\"2\":{\"combat_text\":\"6\",\"power\":1,\"type\":\"melee\"}}', '{\"1\":{\"id\":6,\"amount\":3,\"chances\":80},\"2\":{\"id\":12,\"amount\":1,\"chances\":20}}'),
(40, 'test1', 80, 1.00, 1, 1, 11, 1, '{\"1\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"},\"2\":{\"combat_text\":\"1\",\"power\":1,\"type\":\"melee\"}}', '{\"1\":{\"id\":13,\"amount\":3,\"max_amount\":10,\"chances\":100},\"2\":{\"id\":6,\"amount\":1,\"max_amount\":1,\"chances\":50}}');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `admin_permission` int(11) NOT NULL,
  `last_online` bigint(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` smallint(6) NOT NULL,
  `stats_points` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `health` float(12,2) NOT NULL,
  `max_health` float(12,2) NOT NULL,
  `mana` float(12,2) NOT NULL,
  `stamina` float(12,2) NOT NULL,
  `monster_que` int(11) NOT NULL,
  `silver_coin` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `dexterity` int(11) NOT NULL,
  `intelligence` int(11) NOT NULL,
  `endurance` int(11) NOT NULL,
  `luck` int(11) NOT NULL,
  `crit_chance` float(12,1) NOT NULL,
  `min_dmg` int(11) NOT NULL,
  `attacks` text NOT NULL,
  `attacks_equipped` text NOT NULL,
  `max_equipped_attacks` int(11) NOT NULL,
  `equipped_attacks` int(11) NOT NULL,
  `items` varchar(500) NOT NULL,
  `friends` varchar(500) NOT NULL,
  `weapon` varchar(50) NOT NULL,
  `armor` varchar(50) NOT NULL,
  `talisman` varchar(50) NOT NULL,
  `ring` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `admin_permission`, `last_online`, `username`, `password`, `level`, `stats_points`, `exp`, `health`, `max_health`, `mana`, `stamina`, `monster_que`, `silver_coin`, `strength`, `dexterity`, `intelligence`, `endurance`, `luck`, `crit_chance`, `min_dmg`, `attacks`, `attacks_equipped`, `max_equipped_attacks`, `equipped_attacks`, `items`, `friends`, `weapon`, `armor`, `talisman`, `ring`) VALUES
(19, 0, 1615710981, 'admin', '21232f297a57a5a743894a0e4a801fc3', 80, 2, 1545, 930.00, 930.00, 81.50, 54.00, 39, 102710, 9, 1, 64, 53, 35, 17.5, -40, '[]', '{\"1\":[],\"2\":[]}', 4, 2, '[]', 'Array', '', '', '11', '12'),
(24, 0, 1614246769, 'Wenzzi', '6c810159081d36a24893ce9764919819', 40, 0, 43, 57.00, 140.00, 50.00, 0.00, 27, 8, 2, 1, 1, 4, 0, 0.0, 20, '[]', '{\"1\":[]}', 4, 1, '{\"1\":[],\"6\":[],\"3\":[]}', '', '', '', '', ''),
(28, 0, 0, 'wenzzi2', '6c810159081d36a24893ce9764919819', 80, 10, 380, 148.00, 650.00, 0.00, 0.00, 0, 19179, 68, 1, 114, 55, 0, 0.0, 160, '{\"3\":[],\"8\":[],\"9\":[],\"10\":[],\"11\":[]}', '{\"3\":[]}', 4, 1, '{\"1\":[]}', '', '7', '3', '5', '4'),
(29, 0, 0, 'testnew', '927456201d8ad89188f60fa902ceafb7', 3, 0, 26, 0.00, 100.00, 0.00, 0.00, 0, 7, 3, 1, 1, 1, 0, 0.0, 20, '[]', '{\"3\":[]}', 4, 1, '[]', '', '', '', '', '');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `attacks`
--
ALTER TABLE `attacks`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `chests`
--
ALTER TABLE `chests`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `monsters`
--
ALTER TABLE `monsters`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `attacks`
--
ALTER TABLE `attacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT dla tabeli `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT dla tabeli `chests`
--
ALTER TABLE `chests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `monsters`
--
ALTER TABLE `monsters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
