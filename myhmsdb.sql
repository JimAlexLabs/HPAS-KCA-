-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 16, 2025 at 02:34 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myhmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintb`
--

CREATE TABLE `admintb` (
  `username` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admintb`
--

INSERT INTO `admintb` (`username`, `password`) VALUES
('admin', 'admin000');

-- --------------------------------------------------------

--
-- Table structure for table `appointmenttb`
--

CREATE TABLE `appointmenttb` (
  `pid` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `doctor` varchar(30) NOT NULL,
  `docFees` int(5) NOT NULL,
  `appdate` date NOT NULL,
  `apptime` time NOT NULL,
  `userStatus` int(5) NOT NULL,
  `doctorStatus` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointmenttb`
--

INSERT INTO `appointmenttb` (`pid`, `ID`, `fname`, `lname`, `gender`, `email`, `contact`, `doctor`, `docFees`, `appdate`, `apptime`, `userStatus`, `doctorStatus`) VALUES
(4, 1, 'Mike', 'Rickter', 'Male', 'mikerickter@gmail.com', '0711225678', 'Jimal', 1500, '2025-01-14', '10:00:00', 1, 0),
(4, 2, 'Mike', 'Rickter', 'Male', 'mikerickter2@gmail.com', '0723848946', 'Kairo', 2500, '2025-02-28', '10:00:00', 0, 1),
(4, 3, 'Mike', 'Rickter', 'Male', 'mikerickter3@gmail.com', '0723848946', 'Annette', 1200, '2025-02-19', '03:00:00', 0, 1),
(11, 4, 'Shantel', 'Wairimu', 'Female', 'shantelwairimu@gmail.com', '0739768946', 'Diana', 1800, '2025-02-15', '20:00:00', 1, 1),
(4, 5, 'Mike', 'Rickter', 'Male', 'mikerickter4@gmail.com', '0723848946', 'Lydia', 2000, '2025-02-28', '12:00:00', 1, 1),
(4, 6, 'Mike', 'Rickter', 'Male', 'mikerickter5@gmail.com', '0723848946', 'Kay', 3000, '2025-02-26', '15:00:00', 0, 1),
(2, 8, 'Alicia', 'Mwangi', 'Female', 'aliciamwangi@gmail.com', '0718976897', 'Jimal', 1500, '2025-03-21', '10:00:00', 1, 1),
(5, 9, 'Dan', 'Kemutai', 'Male', 'dankemutai@gmail.com', '0729070897', 'Kay', 3000, '2025-03-19', '20:00:00', 1, 0),
(4, 10, 'Mike', 'Rickter', 'Male', 'mikerickter6@gmail.com', '0723848946', 'Kay', 3000, '2025-03-25', '14:00:00', 1, 0),
(4, 11, 'Mike', 'Rickter', 'Male', 'mikerickter7@gmail.com', '0723848946', 'Diana', 1800, '2025-03-27', '15:00:00', 1, 1),
(9, 12, 'Dan', 'Kemutai', 'Male', 'dankemutai2@gmail.com', '0728683619', 'Koffi', 3500, '2025-03-26', '12:00:00', 1, 1),
(9, 13, 'Dan', 'Kemutai', 'Male', 'dankemutai3@gmail.com', '0728683619', 'Tiana', 1500, '2025-03-26', '14:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `name` varchar(30) NOT NULL,
  `email` text NOT NULL,
  `contact` varchar(10) NOT NULL,
  `message` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`name`, `email`, `contact`, `message`) VALUES
('Mathew', 'mathew@gmail.com', '0711223344', 'Hello, I would like to inquire about your services. Can you provide more details?'),
('Victoria', 'victoria@gmail.com', '0722334455', 'Great job on the website! It is very user-friendly and informative.'),
('Anita', 'anita@gmail.com', '0733445566', 'Hi, how can I get in touch with your support team? I have a few questions.'),
('Ashley', 'ashley@gmail.com', '0744556677', 'Your website is amazing! I really enjoy using it. Keep up the good work!'),
('Manuel', 'manuel@gmail.com', '0755667788', 'Hello, I would like to schedule a meeting. When are you available?'),
('Katherine', 'katherine@gmail.com', '0766778899', 'I was very impressed with your service. Thank you for the excellent support!'),
('Arman', 'arman@gmail.com', '0777889900', 'Your service is fantastic! I appreciate the quick response and assistance.'),
('Asiayi', 'asiayi@gmail.com', '0788990011', 'Thank you for your help! Your service is top-notch.'),
('Janet', 'janet@gmail.com', '0799001122', 'I love your service! It has made my life so much easier. Thank you!');

-- --------------------------------------------------------

--
-- Table structure for table `doctb`
--

CREATE TABLE `doctb` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `spec` varchar(50) NOT NULL,
  `docFees` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctb`
--

INSERT INTO `doctb` (`username`, `password`, `email`, `spec`, `docFees`) VALUES
('Jimal', 'jimal123', 'jimal@gmail.com', 'General', 1500),
('Kairo', 'kairo123', 'kairo@gmail.com', 'Cardiologist', 2500),
('Annette', 'annette123', 'annette@gmail.com', 'General', 1200),
('Diana', 'diana123', 'diana@gmail.com', 'Pediatrician', 1800),
('Lydia', 'lydia123', 'lydia@gmail.com', 'Pediatrician', 2000),
('Kay', 'kay123', 'kay@gmail.com', 'Cardiologist', 3000),
('Koffi', 'koffi123', 'koffi@gmail.com', 'Neurologist', 3500),
('Tiana', 'tiana123', 'tiana@gmail.com', 'Pediatrician', 1500);

-- --------------------------------------------------------

--
-- Table structure for table `patreg`
--

CREATE TABLE `patreg` (
  `pid` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `password` varchar(30) NOT NULL,
  `cpassword` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patreg`
--

INSERT INTO `patreg` (`pid`, `fname`, `lname`, `gender`, `email`, `contact`, `password`, `cpassword`) VALUES
(1, 'Agent', 'Keen', 'Male', 'agentkeen@gmail.com', '0711987654', 'agent123', 'agent123'),
(2, 'Angel', 'Rose', 'Female', 'angelrose@gmail.com', '0722897689', 'angel123', 'angel123'),
(3, 'Shamla', 'Khan', 'Male', 'shamlakhan@gmail.com', '0733898463', 'shamla123', 'shamla123'),
(4, 'Kim', 'Justin', 'Male', 'kimjustin@gmail.com', '0744849464', 'kim123', 'kim123'),
(5, 'Gautam', 'Adani', 'Male', 'gautamadani@gmail.com', '0755897653', 'gautam123', 'gautam123'),
(6, 'John', 'Duran', 'Male', 'johnduran@gmail.com', '0766986865', 'john123', 'john123'),
(7, 'Nancy', 'Deborah', 'Female', 'nancydeborah@gmail.com', '0777972454', 'nancy123', 'nancy123'),
(8, 'Kenny', 'Sebastian', 'Male', 'kennysebastian@gmail.com', '0788879868', 'kenny123', 'kenny123'),
(9, 'William', 'Ruto', 'Male', 'williamruto@gmail.com', '0799619153', 'william123', 'william123'),
(10, 'Peter', 'Mwajuma', 'Male', 'petermwajuma@gmail.com', '0700362815', 'peter123', 'peter123'),
(11, 'Sandra', 'Kajoohn', 'Female', 'sandrakajoohn@gmail.com', '0711768946', 'sandra123', 'sandra123');

-- --------------------------------------------------------

--
-- Table structure for table `prestb`
--

CREATE TABLE `prestb` (
  `doctor` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `appdate` date NOT NULL,
  `apptime` time NOT NULL,
  `disease` varchar(250) NOT NULL,
  `allergy` varchar(250) NOT NULL,
  `prescription` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prestb`
--

INSERT INTO `prestb` (`doctor`, `pid`, `ID`, `fname`, `lname`, `appdate`, `apptime`, `disease`, `allergy`, `prescription`) VALUES
('Diana', 4, 11, 'Kim', 'Justin', '2025-03-27', '15:00:00', 'Cough', 'None', 'Take 5ml of cough syrup (Benadryl) every 8 hours for 5 days. Stay hydrated.'),
('Jimal', 2, 8, 'Angel', 'Rose', '2025-03-21', '10:00:00', 'Severe Fever', 'None', 'Take Paracetamol 500mg every 6 hours. Rest and drink plenty of fluids.'),
('Koffi', 9, 12, 'William', 'Ruto', '2025-03-26', '12:00:00', 'High Fever', 'None', 'Take Ibuprofen 400mg every 8 hours. Monitor temperature and rest.'),
('Tiana', 9, 13, 'William', 'Ruto', '2025-03-26', '14:00:00', 'Cough and Cold', 'Skin dryness', 'Take Loratadine 10mg daily. Drink warm water and consume fruits rich in vitamin C.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointmenttb`
--
ALTER TABLE `appointmenttb`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `patreg`
--
ALTER TABLE `patreg`
  ADD PRIMARY KEY (`pid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointmenttb`
--
ALTER TABLE `appointmenttb`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `patreg`
--
ALTER TABLE `patreg`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;