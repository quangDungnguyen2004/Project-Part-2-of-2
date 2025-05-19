-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 19, 2025 at 05:26 PM
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
-- Database: `job_listing_db`
--
-- --------------------------------------------------------

--
-- Table structure for table `jobrequirement`
--

CREATE TABLE `jobrequirement` (
  `ReqID` int(11) NOT NULL,
  `JobRefNumber` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobrequirement`
--

INSERT INTO `jobrequirement` (`ReqID`, `JobRefNumber`) VALUES
(1, 'FT105'),
(2, 'FT105'),
(3, 'FT105'),
(4, 'FT105'),
(5, 'FT105'),
(6, 'FT102'),
(7, 'FT102'),
(8, 'FT102'),
(9, 'FT102'),
(10, 'FT102');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `JobRefNumber` varchar(11) NOT NULL,
  `JobTitle` varchar(255) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `JobDesc` text NOT NULL,
  `Salary` varchar(255) NOT NULL,
  `Hours` text NOT NULL,
  `Reports` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`JobRefNumber`, `JobTitle`, `Location`, `JobDesc`, `Salary`, `Hours`, `Reports`) VALUES
('FT102', 'Data Engineer', 'Sydney, NSW', 'We\'re looking for a Data Engineer to design, build, and maintain scalable data pipelines and infrastructure for our AI systems.', '$95,000 - $115,000', 'Full-time, 38 - 40 hours/week', 'Head of Data Engineering'),
('FT105', 'AI/ML Engineer (Foundational Models)', 'Melbourne CBD / Hybrid', 'Join our core AI research team to develop scalable, general-purpose models that power the next generation of intelligent systems.', '$110,000 - $140,000', 'Full-time, 38 - 40 hours/week', 'Director of Applied AI');

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `ReqID` int(11) NOT NULL,
  `Req` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`ReqID`, `Req`) VALUES
(1, '3+ years experience in ML model development, preferably with Transformers or Deep RL'),
(2, 'Strong background in Python, PyTorch, and large-scale data processing'),
(3, 'Familiarity with distributed training, vector databases, or LLM fine-tuning'),
(4, 'Published research in ML conferences (e.g., NeurIPS, ICML)'),
(5, 'Experience deploying ML models in production'),
(6, 'Minimum 2-4 years experience in data engineering or backend development'),
(7, 'Degree in Computer Science, Engineering, or a related field'),
(8, 'Experience with tools like Apache Spark, Kafka, Airflow, and SQL/NoSQL databases'),
(9, 'Experience with cloud platforms (AWS, GCP, or Azure)'),
(10, 'Knowledge of real-time data processing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobrequirement`
--
ALTER TABLE `jobrequirement`
  ADD PRIMARY KEY (`ReqID`,`JobRefNumber`),
  ADD KEY `JobRefNumber` (`JobRefNumber`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`JobRefNumber`);

--
-- Indexes for table `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`ReqID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `ReqID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobrequirement`
--
ALTER TABLE `jobrequirement`
  ADD CONSTRAINT `jobRequirement_ibfk_1` FOREIGN KEY (`ReqID`) REFERENCES `requirements` (`ReqID`) ON DELETE CASCADE,
  ADD CONSTRAINT `jobRequirement_ibfk_2` FOREIGN KEY (`JobRefNumber`) REFERENCES `jobs` (`JobRefNumber`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
