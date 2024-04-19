-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 20, 2024 at 12:51 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hireme`
--
CREATE DATABASE IF NOT EXISTS `hireme` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `hireme`;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `CompanyID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `CompanyName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `CompanyDescription` text COLLATE utf8mb4_general_ci NOT NULL,
  `LegalDocuments` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'redundant, will remove later',
  `CompanyAddress` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `VerificationStatus` enum('Verified','Pending','Rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`CompanyID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companyapplication`
--

DROP TABLE IF EXISTS `companyapplication`;
CREATE TABLE IF NOT EXISTS `companyapplication` (
  `CompanyApplicationID` int NOT NULL AUTO_INCREMENT,
  `CompanyID` int NOT NULL,
  `DocumentName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `DocumentFilePath` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `VerificationStatus` enum('Pending','Verified','Rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `ReasonForRejection` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`CompanyApplicationID`),
  KEY `CompanyID` (`CompanyID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favoritejobs`
--

DROP TABLE IF EXISTS `favoritejobs`;
CREATE TABLE IF NOT EXISTS `favoritejobs` (
  `FavoriteID` int NOT NULL AUTO_INCREMENT,
  `JobSeekerID` int NOT NULL,
  `JobID` int NOT NULL,
  PRIMARY KEY (`FavoriteID`),
  KEY `JobSeekerID` (`JobSeekerID`),
  KEY `JobID` (`JobID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

DROP TABLE IF EXISTS `interviews`;
CREATE TABLE IF NOT EXISTS `interviews` (
  `InterviewID` int NOT NULL AUTO_INCREMENT,
  `JobID` int NOT NULL,
  `JobSeekerApplicationID` int NOT NULL,
  `InterviewDate` datetime NOT NULL,
  `DateMade` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('Pending','No show','Done','') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`InterviewID`),
  KEY `JobID` (`JobID`),
  KEY `JobSeekerApplicationID` (`JobSeekerApplicationID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `JobID` int NOT NULL AUTO_INCREMENT,
  `CompanyID` int NOT NULL,
  `JobTitle` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `JobDescription` text COLLATE utf8mb4_general_ci NOT NULL,
  `JobType` enum('Full-Time','Part-Time','Contract','Intern') COLLATE utf8mb4_general_ci NOT NULL,
  `SalaryMin` decimal(10,2) NOT NULL,
  `SalaryMax` decimal(10,2) NOT NULL,
  `WorkHours` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `JobLocation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `JobLocationType` enum('WFH','On Site') COLLATE utf8mb4_general_ci NOT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `VerificationStatus` enum('Verified','Pending','Rejected') COLLATE utf8mb4_general_ci NOT NULL,
  `JobIndustry` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `OtherIndustry` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`JobID`),
  KEY `CompanyID` (`CompanyID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobseekerapplication`
--

DROP TABLE IF EXISTS `jobseekerapplication`;
CREATE TABLE IF NOT EXISTS `jobseekerapplication` (
  `JobSeekerApplicationID` int NOT NULL AUTO_INCREMENT,
  `JobID` int DEFAULT NULL,
  `UserID` int DEFAULT NULL,
  `ResumeFilePath` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ApplicationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('Pending','Rejected','Verified') COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`JobSeekerApplicationID`),
  KEY `JobID` (`JobID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobseekers`
--

DROP TABLE IF EXISTS `jobseekers`;
CREATE TABLE IF NOT EXISTS `jobseekers` (
  `JobSeekerID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `FirstName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `LastName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `BirthDate` date NOT NULL,
  `Address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ContactNumber` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`JobSeekerID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Role` enum('Admin','Company','Job Seeker','User') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'User',
  `Token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `unique_email` (`Email`),
  UNIQUE KEY `unique_username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `companyapplication`
--
ALTER TABLE `companyapplication`
  ADD CONSTRAINT `companyapplication_ibfk_1` FOREIGN KEY (`CompanyID`) REFERENCES `companies` (`CompanyID`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`CompanyID`) REFERENCES `companies` (`CompanyID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
