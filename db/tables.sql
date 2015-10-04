-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2015 at 11:14 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sana`
--

-- --------------------------------------------------------

--
-- Table structure for table `sana_location`
--

CREATE TABLE IF NOT EXISTS `sana_location` (
  `locationID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `locationClass` varchar(255) NOT NULL COMMENT 'کلاس موقعیت آدرس',
  `locationName` varchar(255) NOT NULL COMMENT 'نام موقعیت',
  `parentID` varchar(255) NOT NULL COMMENT 'شناسه پدر',
  `hierarchyIDPath` varchar(255) NOT NULL COMMENT 'درخت شناسه های سلسله مراتب',
  `hierarchyPath` varchar(255) NOT NULL COMMENT 'درخت سلسله مراتب',
  PRIMARY KEY (`locationID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='اطلاعات سلسله مراتب آدرس' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_object`
--

CREATE TABLE IF NOT EXISTS `sana_object` (
  `objectID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `objectName` varchar(255) NOT NULL COMMENT 'عنوان شی',
  `ownerID` int(11) NOT NULL COMMENT 'شناسه مالک',
  `ownerName` varchar(255) NOT NULL COMMENT 'نام مالک',
  `lastName` varchar(255) NOT NULL COMMENT 'نام خانوادگی مالک',
  `mobilePhone` char(15) DEFAULT NULL COMMENT 'شماره موبایل مالک',
  `color` varchar(200) NOT NULL COMMENT 'رنگ',
  `status` varchar(100) DEFAULT NULL COMMENT 'وضعیت (گمشده یا پیدا شده)',
  `content` varchar(255) DEFAULT NULL COMMENT 'محتوا',
  `financialValue` varchar(255) DEFAULT NULL COMMENT 'ارزش مالی',
  `registrationUser` int(11) DEFAULT NULL COMMENT 'کاربر ثبت کننده',
  `registrationDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت ثبت',
  `registrationStation` int(11) DEFAULT NULL COMMENT 'ایستگاه مراجعه',
  `isolatedDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت جدا شدن',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='اطلاعات اشیا' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_person`
--

CREATE TABLE IF NOT EXISTS `sana_person` (
  `personID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `personName` varchar(100) NOT NULL COMMENT 'نام',
  `lastName` varchar(200) NOT NULL COMMENT 'نام خانوادگی',
  `nationalID` char(10) DEFAULT NULL COMMENT 'کد ملی',
  `nationalNumber` char(10) DEFAULT NULL COMMENT 'شماره شناسنامه',
  `fatherName` varchar(100) NOT NULL COMMENT 'نام پدر',
  `gender` varchar(50) NOT NULL COMMENT 'جنسیت',
  `country` varchar(100) NOT NULL COMMENT 'کشور',
  `province` varchar(100) NOT NULL COMMENT 'استان',
  `county` varchar(100) DEFAULT NULL COMMENT 'شهرستان',
  `district` varchar(100) DEFAULT NULL COMMENT 'بخش',
  `city_ruralDistrict` varchar(100) DEFAULT NULL COMMENT 'شهر/دهستان',
  `region_village` varchar(100) DEFAULT NULL COMMENT 'منطقه/روستا',
  `address` varchar(255) DEFAULT NULL COMMENT 'آدرس',
  `convoy` varchar(255) DEFAULT NULL COMMENT 'نام کاروان',
  `convoyManager` varchar(100) DEFAULT NULL COMMENT 'نام مدیر کاروان',
  `followersName` varchar(255) DEFAULT NULL COMMENT 'نام همراهان',
  `status` varchar(100) DEFAULT NULL COMMENT 'وضعیت (گمشده یا پیدا شده)',
  `isolatedLocation` varchar(255) DEFAULT NULL COMMENT 'محل جدا شدن',
  `birthDate` int(11) DEFAULT NULL COMMENT 'سال تولد',
  `ageRange` char(10) DEFAULT NULL COMMENT 'بازه سنی',
  `dress1` varchar(255) DEFAULT NULL COMMENT 'رنگ لباس',
  `dress2` varchar(255) DEFAULT NULL COMMENT 'رنگ شلوار/دامن',
  `signTags` varchar(255) DEFAULT NULL COMMENT 'علامت مشخصه',
  `phone` char(100) DEFAULT NULL COMMENT 'تلفن ثابت',
  `mobilePhone` char(15) DEFAULT NULL COMMENT 'همراه',
  `email` char(150) DEFAULT NULL COMMENT 'ایمیل',
  `temporaryResidence` varchar(255) DEFAULT NULL COMMENT 'محل اسکان موقت',
  `visitsCount` int(11) DEFAULT NULL COMMENT 'تعداد مراجعات',
  `picture` char(255) DEFAULT NULL COMMENT 'پیوست نمودن عکس',
  `registrationUser` int(11) DEFAULT NULL COMMENT 'کاربر ثبت کننده',
  `registrationDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت ثبت',
  `registrationStation` int(11) DEFAULT NULL COMMENT 'ایستگاه مراجعه',
  `isolatedDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت جدا شدن',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='اطلاعات اشخاص' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_project`
--

CREATE TABLE IF NOT EXISTS `sana_project` (
  `projectID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `projectName` varchar(255) NOT NULL COMMENT 'نام پروژه',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`projectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='اطلاعات پروژه' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_state`
--

CREATE TABLE IF NOT EXISTS `sana_state` (
  `stateID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `stateClass` varchar(255) NOT NULL COMMENT 'کلاس وضعیت',
  `stateName` varchar(255) NOT NULL COMMENT 'وضعیت',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`stateID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='اطلاعات وضعیت' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sana_state`
--

INSERT INTO `sana_state` (`stateID`, `stateClass`, `stateName`, `description`) VALUES
(1, 'location', 'country', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sana_user`
--

CREATE TABLE IF NOT EXISTS `sana_user` (
  `userID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `username` varchar(255) NOT NULL COMMENT 'نام کاربری',
  `personName` varchar(100) NOT NULL COMMENT 'نام',
  `lastName` varchar(200) NOT NULL COMMENT 'نام خانوادگی',
  `nationalID` char(10) DEFAULT NULL COMMENT 'کد ملی',
  `nationalNumber` char(10) DEFAULT NULL COMMENT 'شماره شناسنامه',
  `fatherName` varchar(100) NOT NULL COMMENT 'نام پدر',
  `gender` bit(1) NOT NULL COMMENT 'جنسیت',
  `country` varchar(100) NOT NULL COMMENT 'کشور',
  `province` varchar(100) NOT NULL COMMENT 'استان',
  `county` varchar(100) DEFAULT NULL COMMENT 'شهرستان',
  `district` varchar(100) DEFAULT NULL COMMENT 'بخش',
  `city_ruralDistrict` varchar(100) DEFAULT NULL COMMENT 'شهر/دهستان',
  `region_village` varchar(100) DEFAULT NULL COMMENT 'منطقه/روستا',
  `address` varchar(255) DEFAULT NULL COMMENT 'آدرس',
  `birthDate` int(11) DEFAULT NULL COMMENT 'سال تولد',
  `ageRange` char(10) DEFAULT NULL COMMENT 'بازه سنی',
  `phone` char(100) DEFAULT NULL COMMENT 'تلفن ثابت',
  `mobilePhone` char(15) DEFAULT NULL COMMENT 'همراه',
  `userPassword` varchar(255) DEFAULT NULL COMMENT 'رمز کاربر',
  `email` char(150) DEFAULT NULL COMMENT 'ایمیل',
  `picture` char(255) DEFAULT NULL COMMENT 'عکس',
  `registrationUser` int(11) DEFAULT NULL COMMENT 'کاربر ثبت کننده',
  `registrationDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت ثبت',
  `registrationStation` int(11) DEFAULT NULL COMMENT 'ایستگاه مراجعه',
  `isolatedDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت جدا شدن',
  `acl` varchar(100) DEFAULT NULL COMMENT 'سطح دسترسی',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='اطلاعات کاربران' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userlevelpermissions`
--

CREATE TABLE IF NOT EXISTS `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`userlevelid`,`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userlevelpermissions`
--

INSERT INTO `userlevelpermissions` (`userlevelid`, `tablename`, `permission`) VALUES
(-2, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_location', 0),
(-2, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_object', 0),
(-2, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_person', 0),
(-2, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_project', 0),
(-2, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_state', 0),
(-2, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_user', 0),
(0, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_location', 0),
(0, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_object', 0),
(0, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_person', 0),
(0, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_project', 0),
(0, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_state', 0),
(0, '{07091A10-D58A-4784-942B-0E21010F5DFC}sana_user', 0);

-- --------------------------------------------------------

--
-- Table structure for table `userlevels`
--

CREATE TABLE IF NOT EXISTS `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) NOT NULL,
  PRIMARY KEY (`userlevelid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userlevels`
--

INSERT INTO `userlevels` (`userlevelid`, `userlevelname`) VALUES
(-2, 'Anonymous'),
(-1, 'Administrator'),
(0, 'Default');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
