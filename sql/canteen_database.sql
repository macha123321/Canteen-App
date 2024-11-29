-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 02:46 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canteen_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `menuitems`
--

CREATE TABLE `menuitems` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `Stock` int(11) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menuitems`
--

INSERT INTO `menuitems` (`item_id`, `item_name`, `Stock`, `price`, `available`) VALUES
(3, 'Pizza', 20, 12.99, 1),
(4, 'Burger', 50, 8.49, 1),
(5, 'Sushi', 15, 14.99, 1),
(6, 'Pasta', 30, 10.99, 1),
(7, 'Tacos', 40, 5.99, 1),
(8, 'Salad', 25, 7.49, 1),
(9, 'Sandwich', 60, 6.29, 1),
(10, 'Ice Cream', 80, 4.49, 1),
(11, 'Steak', 10, 19.99, 1),
(12, 'Soup', 35, 6.79, 1),
(13, 'Fried Chicken', 50, 9.99, 1),
(14, 'Spring Rolls', 40, 5.49, 1),
(15, 'Grilled Cheese', 45, 4.99, 1),
(16, 'Fish Tacos', 30, 7.99, 1),
(17, 'Burrito', 60, 8.99, 1),
(18, 'Caesar Salad', 25, 8.49, 1),
(19, 'Hot Dog', 70, 3.99, 1),
(20, 'Ramen', 20, 11.49, 1),
(21, 'Chili', 15, 7.99, 1),
(22, 'Vegetable Stir-fry', 30, 9.49, 1),
(23, 'Chicken Wings', 55, 10.99, 1),
(24, 'Fettuccine Alfredo', 25, 13.49, 1),
(25, 'Chicken Caesar Wrap', 40, 7.29, 1),
(26, 'Donuts', 90, 2.99, 1),
(27, 'Bagel with Cream Cheese', 50, 3.49, 1),
(28, 'Quiche', 20, 9.99, 1),
(29, 'Lobster Roll', 10, 22.99, 1),
(31, 'Meatball Sub', 45, 7.99, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Staff','Admin') DEFAULT 'Staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(9, 'nasir', '$2y$10$HJePrv7YmUfwsNznBW1wv.Jh7TQvqFiQXdZQvLRmClNjNI.b2rjNa', 'Staff'),
(10, 'admin', '$2y$10$cA3EUQF8HELVnZRfmv5kyeU1irND1Kd0JH2i4cnmK8.xO/LJ4fF5.', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menuitems`
--
ALTER TABLE `menuitems`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_item_id` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menuitems`
--
ALTER TABLE `menuitems`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_item_id` FOREIGN KEY (`item_id`) REFERENCES `menuitems` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
