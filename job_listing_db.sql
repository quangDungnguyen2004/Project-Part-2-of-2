-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 25, 2025 at 07:26 AM
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
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `EOInumber` int(11) NOT NULL,
  `JobRefNumber` varchar(20) NOT NULL,
  `FirstName` varchar(20) NOT NULL CHECK (`FirstName` regexp '^[A-Za-z]{1,20}$'),
  `LastName` varchar(20) NOT NULL CHECK (`LastName` regexp '^[A-Za-z]{1,20}$'),
  `StreetAddress` varchar(40) NOT NULL,
  `Suburb` varchar(40) NOT NULL,
  `State` enum('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  `Postcode` char(4) NOT NULL CHECK (`Postcode` regexp '^[0-9]{4}$'),
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(15) NOT NULL CHECK (`Phone` regexp '^[0-9 ]{8,12}$'),
  `OtherSkills` text DEFAULT NULL,
  `Status` enum('New','Current','Final') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `people_skills`
--

CREATE TABLE `people_skills` (
  `EOInumber` int(11) NOT NULL,
  `SkillID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `ReqID` int(11) NOT NULL,
  `Req` text NOT NULL,
  `JobRefNumber` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`ReqID`, `Req`, `JobRefNumber`) VALUES
(1, '3+ years experience in ML model development, preferably with Transformers or Deep RL', 'FT105'),
(2, 'Strong background in Python, PyTorch, and large-scale data processing', 'FT105'),
(3, 'Familiarity with distributed training, vector databases, or LLM fine-tuning', 'FT105'),
(4, 'Published research in ML conferences (e.g., NeurIPS, ICML)', 'FT105'),
(5, 'Experience deploying ML models in production', 'FT105'),
(6, 'Minimum 2-4 years experience in data engineering or backend development', 'FT102'),
(7, 'Degree in Computer Science, Engineering, or a related field', 'FT102'),
(8, 'Experience with tools like Apache Spark, Kafka, Airflow, and SQL/NoSQL databases', 'FT102'),
(9, 'Experience with cloud platforms (AWS, GCP, or Azure)', 'FT102'),
(10, 'Knowledge of real-time data processing', 'FT102');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `SkillID` int(11) NOT NULL,
  `SkillName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`SkillID`, `SkillName`) VALUES
(5, 'C++'),
(4, 'Java'),
(3, 'Julia'),
(1, 'Python'),
(2, 'R');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', 'admin1', '2025-05-25 05:25:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`JobRefNumber`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`EOInumber`),
  ADD KEY `people_ibfk_1` (`JobRefNumber`);

--
-- Indexes for table `people_skills`
--
ALTER TABLE `people_skills`
  ADD PRIMARY KEY (`EOInumber`,`SkillID`),
  ADD KEY `SkillID` (`SkillID`);

--
-- Indexes for table `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`ReqID`),
  ADD KEY `JobRefNumber` (`JobRefNumber`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`SkillID`),
  ADD UNIQUE KEY `SkillName` (`SkillName`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `ReqID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `SkillID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `people`
--
ALTER TABLE `people`
  ADD CONSTRAINT `people_ibfk_1` FOREIGN KEY (`JobRefNumber`) REFERENCES `jobs` (`JobRefNumber`) ON DELETE CASCADE;

--
-- Constraints for table `people_skills`
--
ALTER TABLE `people_skills`
  ADD CONSTRAINT `people_skills_ibfk_1` FOREIGN KEY (`EOInumber`) REFERENCES `people` (`EOInumber`) ON DELETE CASCADE,
  ADD CONSTRAINT `people_skills_ibfk_2` FOREIGN KEY (`SkillID`) REFERENCES `skills` (`SkillID`) ON DELETE CASCADE;

--
-- Constraints for table `requirements`
--
ALTER TABLE `requirements`
  ADD CONSTRAINT `requirements_ibfk_1` FOREIGN KEY (`JobRefNumber`) REFERENCES `jobs` (`JobRefNumber`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
