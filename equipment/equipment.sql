-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2011 at 07:47 PM
-- Server version: 5.1.47
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_phpcheckout`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessorytype`
--

CREATE TABLE IF NOT EXISTS `accessorytype` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=121 ;

--
-- Dumping data for table `accessorytype`
--

INSERT INTO `accessorytype` (`ID`, `Name`) VALUES
(1, 'Battery'),
(2, 'Charger'),
(3, 'Lav Mic'),
(4, 'DV Cable'),
(5, 'AV Cable'),
(6, 'Headphones'),
(7, '.5x Wide-Angle adapter'),
(8, '2x Telephoto adaper'),
(9, 'Neutral Density Filter'),
(10, 'Charger AC cord'),
(11, 'AC Battery insert'),
(12, 'Docking Station'),
(30, 'Bubble level'),
(13, 'Camera light'),
(14, 'Charger DC cord'),
(15, 'USB Cable'),
(16, '4 AA Rechargeable Batteries'),
(17, 'AA Battery Charger'),
(18, '2gb Compact Flash Card'),
(19, '80mm Lens'),
(20, '18-55mm stock lens'),
(21, 'Lens Cap'),
(22, 'Tripod'),
(23, '8gb SD card'),
(24, 'Light Stand'),
(25, 'Omni Light'),
(26, 'Rubber case'),
(27, 'AC Cord'),
(28, 'Umbrella reflector'),
(29, 'Mounting Plate'),
(31, 'Extension Cord'),
(46, 'USB Extension'),
(32, 'AC Adapter'),
(33, 'XLR Cord'),
(34, 'Power/PC Sync Cord'),
(35, 'Bag of washers'),
(36, 'Box of screws'),
(37, 'Mounting Plate'),
(38, 'Wiring Harness'),
(39, 'Battery Pack'),
(47, 'manual winder'),
(45, 'Bag'),
(40, 'Component Video Cable'),
(41, 'Rain Cover'),
(42, 'Dark Cloth'),
(43, 'remote cord'),
(44, '4x5 Film Holder'),
(48, '18-70mm lens');

-- --------------------------------------------------------

--
-- Table structure for table `checkedout`
--

CREATE TABLE IF NOT EXISTS `checkedout` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KitID` int(11) NOT NULL DEFAULT '0',
  `StudentID` varchar(24) NOT NULL DEFAULT '0',
  `DateOut` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ExpectedDateIn` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateIn` varchar(50) NOT NULL DEFAULT '',
  `Strike` int(10) DEFAULT NULL,
  `FinePaid` varchar(255) DEFAULT NULL,
  `BannedDate` datetime DEFAULT NULL,
  `Accessories` varchar(255) NOT NULL DEFAULT '',
  `Notes` varchar(255) DEFAULT NULL,
  `Problem` int(1) NOT NULL DEFAULT '0',
  `CheckoutUser` varchar(255) DEFAULT '0',
  `CheckinUser` varchar(255) NOT NULL DEFAULT '',
  `ReserveDate` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `checkedout`
--


-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`ID`, `Name`) VALUES
(1, 'Digital'),
(2, 'Photo');

-- --------------------------------------------------------

--
-- Table structure for table `kit`
--

CREATE TABLE IF NOT EXISTS `kit` (
  `ID` int(16) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  `Image` varchar(255) DEFAULT NULL,
  `Repair` int(11) DEFAULT NULL,
  `Genre` varchar(100) DEFAULT NULL,
  `CheckHours` int(11) DEFAULT NULL,
  `SerialNumber` varchar(100) DEFAULT NULL,
  `ModelNumber` varchar(100) DEFAULT NULL,
  `ImageThumb` varchar(100) DEFAULT NULL,
  `ContractRequired` int(1) DEFAULT '0',
  `Notes` varchar(255) DEFAULT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `kit`
--

INSERT INTO `kit` (`ID`, `Name`, `Image`, `Repair`, `Genre`, `CheckHours`, `SerialNumber`, `ModelNumber`, `ImageThumb`, `ContractRequired`, `Notes`) VALUES
(12, 'Nikon D40', 'large-still-nikon-d40.jpg', NULL, 'Still Camera', NULL, NULL, NULL, 'thumb-still-nikon-d40.jpg', 0, NULL),
(8, 'Frotto-Tripod', 'large-default.jpg', NULL, 'Tripod', NULL, NULL, NULL, 'thumb-default.jpg', 0, NULL),
(5, 'Canon GL1 ', 'large-video-canon-gl.jpg', NULL, 'Video Camera', NULL, NULL, NULL, 'thumb-video-canon-gl.jpg', 0, NULL),
(6, 'Canon ZR850-1', 'large-video-canon-zr850.jpg', NULL, 'Video Camera', NULL, NULL, NULL, 'thumb-video-canon-zr850.jpg', 0, NULL),
(23, 'Wacom-1', 'large-equip-wacom.jpg', NULL, 'Graphic Tablet', NULL, NULL, NULL, 'thumb-equip-wacom.jpg', 0, NULL),
(17, 'Samson C01U 1', 'large-audio-samson.jpg', NULL, 'Equipment', NULL, NULL, NULL, 'thumb-audio-samson.jpg', 0, NULL),
(11, 'Nikon Coolpix1', 'large-still-nikon-coolpix.jpg', NULL, 'Still Camera', NULL, NULL, NULL, 'thumb-still-nikon-coolpix.jpg', 0, NULL),
(13, 'Nikon D80', 'large-still-nikon-d70.jpg', NULL, 'Still Camera', NULL, NULL, NULL, 'thumb-still-nikon-d70.jpg', 0, NULL),
(16, 'Nikon-D70-1', 'large-still-nikon-d70.jpg', NULL, 'Still Camera', NULL, NULL, NULL, 'thumb-still-nikon-d70.jpg', 0, NULL),
(10, 'Mamiya 645E-1', 'large-still-mamiya-645e.jpg', NULL, 'Still Camera', NULL, NULL, NULL, 'thumb-still-mamiya-645e.jpg', 0, NULL),
(7, 'CanonHD 1', 'large-video-canon-hd.jpg', NULL, 'Video Camera', NULL, NULL, NULL, 'thumb-video-canon-hd.jpg', 0, NULL),
(1, 'Aiptek DigHD 1', 'large-video-aiptek.jpg', NULL, 'Video Camera', NULL, NULL, NULL, 'thumb-video-aiptek.jpg', 0, NULL),
(2, 'Audio-Tech Mic 1', 'large-audio-mic.jpg', NULL, 'Equipment', NULL, NULL, NULL, 'thumb-audio-mic.jpg', 0, NULL),
(19, 'Stabilizer-1', 'large-equipment-stabilizer.jpg', NULL, 'Tripod', NULL, NULL, NULL, 'thumb-equipment-stabilizer.jpg', 0, NULL),
(18, 'Sony Cybershot 1', NULL, NULL, 'Still Camera', NULL, NULL, NULL, NULL, 0, NULL),
(14, 'Nikon D90', 'large-still-nikon-d70.jpg', NULL, 'Still Camera', NULL, NULL, NULL, 'thumb-still-nikon-d70.jpg', 0, NULL),
(15, 'Nikon SB-900', 'large-lights-nikon-sb900.jpg', NULL, 'Equipment', NULL, NULL, NULL, 'thumb-lights-nikon-sb900.jpg', 0, NULL),
(9, 'Light Meter 1', 'large-lights-meter.jpg', NULL, 'Equipment', NULL, NULL, NULL, 'thumb-lights-meter.jpg', 0, NULL),
(22, 'View Camera 1', 'large-still-toyo.jpg', NULL, 'Still Camera', NULL, NULL, NULL, 'thumb-still-toyo.jpg', 0, NULL),
(20, 'Travel Lights', NULL, NULL, 'Equipment', NULL, NULL, NULL, NULL, 0, NULL),
(3, 'Bogen Tripod 1', NULL, NULL, 'Equipment', NULL, NULL, NULL, NULL, 0, NULL),
(4, 'Calumet Tripod', 'large-default.jpg', 0, 'Equipment', NULL, '', '', 'thumb-default.jpg', 0, ''),
(21, 'Vado HD 1', 'large-video-vado.jpg', NULL, 'Video Camera', NULL, NULL, NULL, 'thumb-video-vado.jpg', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kit_accessorytype`
--

CREATE TABLE IF NOT EXISTS `kit_accessorytype` (
  `ID` int(16) NOT NULL AUTO_INCREMENT,
  `KitID` int(16) NOT NULL DEFAULT '0',
  `AccessorytypeID` int(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `kit_accessorytype`
--

INSERT INTO `kit_accessorytype` (`ID`, `KitID`, `AccessorytypeID`) VALUES
(1, 1, 15),
(2, 1, 5),
(3, 2, 45),
(4, 4, 0),
(5, 4, 45);

-- --------------------------------------------------------

--
-- Table structure for table `kit_class`
--

CREATE TABLE IF NOT EXISTS `kit_class` (
  `ID` int(16) NOT NULL AUTO_INCREMENT,
  `KitID` int(16) NOT NULL DEFAULT '0',
  `ClassID` int(16) NOT NULL DEFAULT '0',
  `CheckHours` int(16) NOT NULL DEFAULT '0',
  `OverNightAllowed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `kit_class`
--

INSERT INTO `kit_class` (`ID`, `KitID`, `ClassID`, `CheckHours`, `OverNightAllowed`) VALUES
(1, 1, 1, 48, 2),
(2, 2, 1, 48, 2),
(3, 4, 2, 48, 2);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` varchar(255) NOT NULL DEFAULT '',
  `FirstName` varchar(255) NOT NULL DEFAULT '',
  `LastName` varchar(255) NOT NULL DEFAULT '',
  `Email` varchar(255) DEFAULT NULL,
  `Phone` varchar(255) DEFAULT NULL,
  `ContractSigned` int(1) DEFAULT NULL,
  `uid` varchar(10) DEFAULT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`ID`, `StudentID`, `FirstName`, `LastName`, `Email`, `Phone`, `ContractSigned`, `uid`) VALUES
(1, '29016001234567', 'First', 'Last', 'email@email.com', '5551234567', 1, 'flast');

-- --------------------------------------------------------

--
-- Table structure for table `student_class`
--

CREATE TABLE IF NOT EXISTS `student_class` (
  `ID` int(16) NOT NULL AUTO_INCREMENT,
  `StudentID` varchar(18) NOT NULL DEFAULT '0',
  `ClassID` int(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `student_class`
--

INSERT INTO `student_class` (`ID`, `StudentID`, `ClassID`) VALUES
(1, '29016001234567', 1),
(2, '29016001234567', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NOT NULL DEFAULT '',
  `Password` varchar(255) NOT NULL DEFAULT '',
  `Type` varchar(8) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Username`, `Password`, `Type`) VALUES
(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'Admin'),
(2, 'labtech', '5f4dcc3b5aa765d61d8327deb882cf99', 'LabMon');
