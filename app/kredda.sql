-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 20, 2020 at 07:17 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billspadi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(134) NOT NULL,
  `password` varchar(333) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'timchosen@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `airtime_providers`
--

CREATE TABLE `airtime_providers` (
  `id` int(11) NOT NULL,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `codename` varchar(100) NOT NULL,
  `discount` float NOT NULL,
  `logo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `airtime_providers`
--

INSERT INTO `airtime_providers` (`id`, `code`, `name`, `codename`, `discount`, `logo`) VALUES
(5, '2', '9Mobile', 'etisalat', 1, ''),
(6, '3', 'GLO', 'glo', 1.9, ''),
(7, '1', 'Airtel', 'airtel', 1.5, ''),
(8, '15', 'MTN', 'mtn', 1.4, '');

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(200) NOT NULL,
  `tag_line` varchar(250) DEFAULT NULL,
  `about` text NOT NULL,
  `privacy` text NOT NULL,
  `terms` text NOT NULL,
  `api_provider` varchar(200) NOT NULL,
  `userid` varchar(200) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `pubkey` varchar(200) NOT NULL,
  `seckey` varchar(200) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `country` varchar(4) NOT NULL,
  `cable_service_charge` float DEFAULT NULL,
  `electricity_service_charge` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `site_name`, `tag_line`, `about`, `privacy`, `terms`, `api_provider`, `userid`, `pass`, `pubkey`, `seckey`, `currency`, `country`, `cable_service_charge`, `electricity_service_charge`) VALUES
(1, 'Bills Padi', 'save on everyday bills', '<p>saving on everyday bills sd</p>\r\n\r\n<p>because of me they wull know you. okay</p>\r\n\r\n<p>pay us now for relaxing</p>\r\n\r\n<p>everyday</p>\r\n', '<p>we are private and we have policy</p>\r\n', '<p>obey terms and conditions and you will be alright</p>\r\n', 'https://mobileairtimeng.com/httpapi/', '09014719431', '8da104d13e05aed1e5805', 'FLWPUBK_TEST-17c0407ac1561aeb6d27adf9880a16ea-X', 'FLWSECK_TEST-82e738843e190becdcc3acc56fd6417f-X', 'NGN', 'NG', 100, 50);

-- --------------------------------------------------------

--
-- Table structure for table `cable_providers`
--

CREATE TABLE `cable_providers` (
  `id` int(11) NOT NULL,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `codename` varchar(100) NOT NULL,
  `discount` float NOT NULL,
  `logo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cable_providers`
--

INSERT INTO `cable_providers` (`id`, `code`, `name`, `codename`, `discount`, `logo`) VALUES
(3, '1', 'DSTV', 'dstv', 0.5, ''),
(4, '1', 'GoTV', 'gotv', 0.5, ''),
(6, '4', 'Start Times', 'startime', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `credits`
--

CREATE TABLE `credits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `remark` varchar(125) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_providers`
--

CREATE TABLE `data_providers` (
  `id` int(11) NOT NULL,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `codename` varchar(100) NOT NULL,
  `discount` float NOT NULL,
  `logo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_providers`
--

INSERT INTO `data_providers` (`id`, `code`, `name`, `codename`, `discount`, `logo`) VALUES
(5, '2', '9Mobile', 'etisalat', 1, ''),
(6, '3', 'GLO', 'glo', 1.9, ''),
(7, '1', 'Airtel', 'airtel', 1.5, ''),
(8, '15', 'MTN', 'mtn', 1.4, '');

-- --------------------------------------------------------

--
-- Table structure for table `debits`
--

CREATE TABLE `debits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `remark` varchar(125) NOT NULL,
  `time_spent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reset_codes`
--

CREATE TABLE `reset_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `user` varchar(256) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saved_contacts`
--

CREATE TABLE `saved_contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `detail` varchar(50) NOT NULL,
  `service` varchar(50) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `support_categories`
--

CREATE TABLE `support_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(199) NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `support_categories`
--

INSERT INTO `support_categories` (`id`, `name`, `description`) VALUES
(1, 'Payment issues', ''),
(2, 'Failed Transaction', '');

-- --------------------------------------------------------

--
-- Table structure for table `support_msgs`
--

CREATE TABLE `support_msgs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `status` enum('pending','answered','closed','') NOT NULL DEFAULT 'pending',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `support_replies`
--

CREATE TABLE `support_replies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `support_msg_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `status` enum('pending','answered','closed','') NOT NULL DEFAULT 'pending',
  `read_status` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `ref` varchar(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `beneficiary` varchar(200) NOT NULL,
  `type` varchar(50) NOT NULL,
  `vendor` varchar(240) NOT NULL,
  `sub_type` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `description` varchar(200) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(250) NOT NULL,
  `f_name` varchar(256) CHARACTER SET utf8 NOT NULL,
  `l_name` varchar(200) NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(56) CHARACTER SET utf8 NOT NULL,
  `transaction_pin` varchar(200) DEFAULT NULL,
  `balance` float NOT NULL DEFAULT '0',
  `password` varchar(256) NOT NULL,
  `gender` varchar(7) DEFAULT NULL,
  `location` varchar(300) DEFAULT NULL,
  `lang` varchar(20) DEFAULT 'eng',
  `fb` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `twit` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `tme` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `yob` int(11) DEFAULT NULL,
  `dob` int(11) DEFAULT NULL,
  `mob` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  `is_blocked` varchar(7) NOT NULL DEFAULT 'false',
  `referer` varchar(256) NOT NULL,
  `verify` varchar(56) NOT NULL,
  `membership` varchar(11) NOT NULL DEFAULT 'Basic',
  `ref_code` varchar(8) NOT NULL,
  `ver_ref` int(11) NOT NULL DEFAULT '4'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airtime_providers`
--
ALTER TABLE `airtime_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cable_providers`
--
ALTER TABLE `cable_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credits`
--
ALTER TABLE `credits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_providers`
--
ALTER TABLE `data_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `debits`
--
ALTER TABLE `debits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reset_codes`
--
ALTER TABLE `reset_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_contacts`
--
ALTER TABLE `saved_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_categories`
--
ALTER TABLE `support_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_msgs`
--
ALTER TABLE `support_msgs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_replies`
--
ALTER TABLE `support_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_email` (`id`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `airtime_providers`
--
ALTER TABLE `airtime_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cable_providers`
--
ALTER TABLE `cable_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `credits`
--
ALTER TABLE `credits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_providers`
--
ALTER TABLE `data_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `debits`
--
ALTER TABLE `debits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reset_codes`
--
ALTER TABLE `reset_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_contacts`
--
ALTER TABLE `saved_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_categories`
--
ALTER TABLE `support_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_msgs`
--
ALTER TABLE `support_msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_replies`
--
ALTER TABLE `support_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
