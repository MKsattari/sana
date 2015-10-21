-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2015 at 08:58 PM
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
-- Table structure for table `sana_location_level1`
--

CREATE TABLE IF NOT EXISTS `sana_location_level1` (
  `locationLevel1ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `locationName` varchar(255) NOT NULL COMMENT 'نام موقعیت',
  PRIMARY KEY (`locationLevel1ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='سطح یک از اطلاعات سلسله مراتب آدرس' AUTO_INCREMENT=11 ;

--
-- Dumping data for table `sana_location_level1`
--

INSERT INTO `sana_location_level1` (`locationLevel1ID`, `locationName`) VALUES
(9, 'ایران'),
(10, 'عراق');

-- --------------------------------------------------------

--
-- Table structure for table `sana_location_level2`
--

CREATE TABLE IF NOT EXISTS `sana_location_level2` (
  `locationLevel2ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `locationName` varchar(255) NOT NULL COMMENT 'نام موقعیت',
  `locationLevel1ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel1Name` varchar(255) NOT NULL COMMENT 'نام موقعیت سطح بالاتر',
  PRIMARY KEY (`locationLevel2ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='سطح دو اطلاعات سلسله مراتب آدرس' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `sana_location_level2`
--

INSERT INTO `sana_location_level2` (`locationLevel2ID`, `locationName`, `locationLevel1ID`, `locationLevel1Name`) VALUES
(9, 'آذربایجان شرقی', 9, 'ایران'),
(10, 'آذربایجان غربی', 9, 'ایران'),
(11, 'اردبیل', 9, 'ایران');

-- --------------------------------------------------------

--
-- Table structure for table `sana_location_level3`
--

CREATE TABLE IF NOT EXISTS `sana_location_level3` (
  `locationLevel3ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `locationName` varchar(255) NOT NULL COMMENT 'نام موقعیت',
  `locationLevel1ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel2ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel2Name` varchar(255) NOT NULL COMMENT 'نام موقعیت سطح بالاتر',
  PRIMARY KEY (`locationLevel3ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='سطح سه اطلاعات سلسله مراتب آدرس' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `sana_location_level3`
--

INSERT INTO `sana_location_level3` (`locationLevel3ID`, `locationName`, `locationLevel1ID`, `locationLevel2ID`, `locationLevel2Name`) VALUES
(9, 'آذر شهر', 9, 9, 'آذربایجان شرقی');

-- --------------------------------------------------------

--
-- Table structure for table `sana_location_level4`
--

CREATE TABLE IF NOT EXISTS `sana_location_level4` (
  `locationLevel4ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `locationName` varchar(255) NOT NULL COMMENT 'نام موقعیت',
  `locationLevel1ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel2ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel3ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel3Name` varchar(255) NOT NULL COMMENT 'نام موقعیت سطح بالاتر',
  PRIMARY KEY (`locationLevel4ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='سطح چهار اطلاعات سلسله مراتب آدرس' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_location_level5`
--

CREATE TABLE IF NOT EXISTS `sana_location_level5` (
  `locationLevel5ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `locationName` varchar(255) NOT NULL COMMENT 'نام موقعیت',
  `locationLevel1ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel2ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel3ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel4ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel4Name` varchar(255) NOT NULL COMMENT 'نام موقعیت سطح بالاتر',
  PRIMARY KEY (`locationLevel5ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='سطح پنج اطلاعات سلسله مراتب آدرس' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_location_level6`
--

CREATE TABLE IF NOT EXISTS `sana_location_level6` (
  `locationLevel6ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `locationName` varchar(255) NOT NULL COMMENT 'نام موقعیت',
  `locationLevel1ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel2ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel3ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel4ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel5ID` int(11) NOT NULL COMMENT 'موقعیت سطح بالاتر',
  `locationLevel5Name` varchar(255) NOT NULL COMMENT 'نام موقعیت سطح بالاتر',
  PRIMARY KEY (`locationLevel6ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='سطح شش اطلاعات سلسله مراتب آدرس' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_message`
--

CREATE TABLE IF NOT EXISTS `sana_message` (
  `messageID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `personID` bigint(20) NOT NULL COMMENT 'گیرنده',
  `userID` bigint(20) NOT NULL COMMENT 'فرستنده',
  `messageType` varchar(100) NOT NULL COMMENT 'قرار یا پیام',
  `messageText` text NOT NULL COMMENT 'متن پیام',
  `stationID` int(11) DEFAULT NULL COMMENT 'ایستگاه مراجعه',
  `messageDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت مراجعه',
  `registrationUser` int(11) DEFAULT NULL COMMENT 'کاربر ثبت کننده',
  `registrationDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت ثبت',
  `registrationStation` int(11) DEFAULT NULL COMMENT 'ایستگاه مراجعه',
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='پیام و قرار' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sana_message`
--

INSERT INTO `sana_message` (`messageID`, `personID`, `userID`, `messageType`, `messageText`, `stationID`, `messageDateTime`, `registrationUser`, `registrationDateTime`, `registrationStation`) VALUES
(1, 4, 1, 'عادی', 'تست', 1, NULL, NULL, NULL, NULL);

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
  `passportNumber` char(15) DEFAULT NULL COMMENT 'شماره گذرنامه',
  `fatherName` varchar(100) DEFAULT NULL COMMENT 'نام پدر',
  `gender` varchar(50) NOT NULL COMMENT 'جنسیت',
  `locationLevel1` varchar(255) NOT NULL COMMENT 'کشور',
  `locationLevel2` varchar(255) NOT NULL COMMENT 'استان',
  `locationLevel3` varchar(255) DEFAULT NULL COMMENT 'شهرستان',
  `locationLevel4` varchar(255) DEFAULT NULL COMMENT 'بخش',
  `locationLevel5` varchar(255) DEFAULT NULL COMMENT 'شهر/دهستان',
  `locationLevel6` varchar(255) DEFAULT NULL COMMENT 'منطقه/روستا',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='اطلاعات اشخاص' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `sana_person`
--

INSERT INTO `sana_person` (`personID`, `personName`, `lastName`, `nationalID`, `nationalNumber`, `passportNumber`, `fatherName`, `gender`, `locationLevel1`, `locationLevel2`, `locationLevel3`, `locationLevel4`, `locationLevel5`, `locationLevel6`, `address`, `convoy`, `convoyManager`, `followersName`, `status`, `isolatedLocation`, `birthDate`, `ageRange`, `dress1`, `dress2`, `signTags`, `phone`, `mobilePhone`, `email`, `temporaryResidence`, `visitsCount`, `picture`, `registrationUser`, `registrationDateTime`, `registrationStation`, `isolatedDateTime`, `description`) VALUES
(1, 'محمد کاظم', 'ستاری', '0075697841', NULL, NULL, 'مظاهر', 'مذکر', 'ایران', 'آذربایجان شرقی', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sana40.jpg', -1, '2015-10-05 00:00:00', NULL, NULL, NULL),
(2, 'محمد مهدی', 'ولی خانی', NULL, NULL, NULL, NULL, 'مذکر', 'ایران', 'آذربایجان شرقی', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, -1, '2015-10-05 00:00:00', NULL, NULL, NULL),
(3, 'مرتضی', 'رحیمی', NULL, NULL, NULL, NULL, 'مذکر', 'ایران', 'آذربایجان شرقی', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, -1, '2015-10-05 00:00:00', NULL, NULL, NULL),
(4, 'عباس رضا', 'رحیمی بشر', NULL, NULL, NULL, NULL, 'مذکر', 'ایران', 'آذربایجان شرقی', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, -1, '2015-10-05 00:00:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sana_project`
--

CREATE TABLE IF NOT EXISTS `sana_project` (
  `projectID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `projectName` varchar(255) NOT NULL COMMENT 'نام پروژه',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`projectID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='اطلاعات پروژه' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sana_project`
--

INSERT INTO `sana_project` (`projectID`, `projectName`, `description`) VALUES
(1, 'پیادو روی اربعین 1394', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sana_sms`
--

CREATE TABLE IF NOT EXISTS `sana_sms` (
  `smsID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `userID` int(11) NOT NULL COMMENT 'کاربر',
  `mobilePhone` char(15) NOT NULL COMMENT 'شماره موبایل',
  `message` varchar(255) NOT NULL COMMENT 'متن پیامک',
  `result` varchar(100) DEFAULT NULL COMMENT 'نتیجه',
  `description` varchar(255) DEFAULT NULL COMMENT 'توضیحات',
  PRIMARY KEY (`smsID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_state`
--

CREATE TABLE IF NOT EXISTS `sana_state` (
  `stateID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `stateClass` varchar(255) NOT NULL COMMENT 'کلاس وضعیت',
  `stateName` varchar(255) NOT NULL COMMENT 'وضعیت',
  `stateLanguage` char(100) DEFAULT NULL COMMENT 'زبان وضعیتهایی را مشخص می کند که در چند زبانی واژه های متفاوت دارند',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`stateID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='اطلاعات وضعیت' AUTO_INCREMENT=19 ;

--
-- Dumping data for table `sana_state`
--

INSERT INTO `sana_state` (`stateID`, `stateClass`, `stateName`, `stateLanguage`, `description`) VALUES
(1, 'age', '0-6', NULL, NULL),
(2, 'age', '7-12', NULL, NULL),
(3, 'age', '13-20', NULL, NULL),
(4, 'age', '21-35', NULL, NULL),
(5, 'age', '36-40', 'en', 'شهر/دهستان'),
(6, 'age', '51-...', 'en', NULL),
(7, 'gender', 'male', 'en', NULL),
(8, 'gender', 'female', 'en', NULL),
(9, 'gender', 'مذکر', 'fa', NULL),
(10, 'gender', 'مونث', 'fa', NULL),
(11, 'person', 'گمشده', 'fa', NULL),
(12, 'person', 'عادی', 'fa', NULL),
(13, 'location', 'کشور', 'fa', NULL),
(14, 'location', 'استان', 'fa', NULL),
(15, 'location', 'شهرستان', 'fa', NULL),
(16, 'location', 'بخش', 'fa', NULL),
(17, 'location', 'شهر/دهستان', 'fa', NULL),
(18, 'location', 'منطقه/روستا', 'fa', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sana_station`
--

CREATE TABLE IF NOT EXISTS `sana_station` (
  `stationID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `stationName` varchar(255) NOT NULL COMMENT 'نام موقعیت',
  `address` varchar(255) DEFAULT NULL COMMENT 'آدرس',
  `GPS1` char(10) NOT NULL COMMENT 'طول',
  `GPS2` char(10) NOT NULL COMMENT 'عرض',
  `GPS3` char(10) NOT NULL COMMENT 'ارتفاع',
  `stationType` varchar(255) NOT NULL COMMENT 'نوع ایستگاه-گمشده، قرار، هردو',
  `projectID` int(11) DEFAULT NULL COMMENT 'پروژه',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`stationID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ایستگاهها' AUTO_INCREMENT=11 ;

--
-- Dumping data for table `sana_station`
--

INSERT INTO `sana_station` (`stationID`, `stationName`, `address`, `GPS1`, `GPS2`, `GPS3`, `stationType`, `projectID`, `description`) VALUES
(9, 'ایستگاه مرکزی', NULL, '0', '0', '0', '', 1, NULL),
(10, 'test2', NULL, '0', '0', '0', '', 1, NULL);

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
  `stationID` int(11) DEFAULT NULL COMMENT 'ایستگاه مراجعه',
  `isolatedDateTime` datetime DEFAULT NULL COMMENT 'تاریخ و ساعت جدا شدن',
  `acl` int(11) DEFAULT NULL COMMENT 'سطح دسترسی',
  `description` text COMMENT 'توضیحات',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='اطلاعات کاربران' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sana_user`
--

INSERT INTO `sana_user` (`userID`, `username`, `personName`, `lastName`, `nationalID`, `nationalNumber`, `fatherName`, `gender`, `country`, `province`, `county`, `district`, `city_ruralDistrict`, `region_village`, `address`, `birthDate`, `ageRange`, `phone`, `mobilePhone`, `userPassword`, `email`, `picture`, `registrationUser`, `registrationDateTime`, `stationID`, `isolatedDateTime`, `acl`, `description`) VALUES
(1, '09124121047', 'محمد کاظم', 'ستاری امناب', NULL, NULL, 'مظاهر', b'0', 'ایران', 'تهران', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'roz0502081!*R', NULL, 'sana40(1).jpg', NULL, NULL, NULL, NULL, NULL, NULL),
(2, '09127705071', 'ع', 'م', NULL, '0912770507', '', b'0', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '09127705071', 'Sana09127705071!', NULL, NULL, -1, '2015-10-21 00:00:00', NULL, NULL, -1, 'Sana09127705071!');

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
