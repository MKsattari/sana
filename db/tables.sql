-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2015 at 08:12 PM
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
-- Table structure for table `sana_person`
--

CREATE TABLE IF NOT EXISTS `sana_person` (
  `personID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
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
  `convoy` varchar(255) DEFAULT NULL COMMENT 'نام کاروان',
  `convoyManager` varchar(100) DEFAULT NULL COMMENT 'نام مدیر کاروان',
  `followersName` varchar(255) DEFAULT NULL COMMENT 'نام همراهان',
  `status` varchar(100) DEFAULT NULL COMMENT 'وضعیت (گمشده یا پیدا شده)',
  `isolatedLocation` varchar(255) DEFAULT NULL COMMENT 'محل جدا شدن',
  `ageRange` int(11) DEFAULT NULL COMMENT 'بازه سنی',
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
  `birthDate` int(11) DEFAULT NULL COMMENT 'بازه سنی',
  `phone` char(100) DEFAULT NULL COMMENT 'تلفن ثابت',
  `mobilePhone` char(15) DEFAULT NULL COMMENT 'همراه',
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
-- Table structure for table `sana_state`
--

CREATE TABLE IF NOT EXISTS `sana_state` (
  `stateID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `stateName` varchar(255) NOT NULL COMMENT 'وضعیت',
  PRIMARY KEY (`stateID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='اطلاعات وضعیت' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sana_state`
--

CREATE TABLE IF NOT EXISTS `sana_country` (
  `countryID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `countryName` varchar(255) NOT NULL COMMENT 'وضعیت',
  PRIMARY KEY (`countryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='اطلاعات وضعیت' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;