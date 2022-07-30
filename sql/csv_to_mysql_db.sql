-
-- Database: `csv_to_mysql_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `continent_code` varchar(100) NOT NULL,
  `currency_code` varchar(100) NOT NULL,
  `iso2_code` varchar(100) NOT NULL,
  `iso3_code` varchar(100) NOT NULL,
  `iso_numeric_code` varchar(100) NOT NULL,
  `fips_code` varchar(100) NOT NULL,
  `calling_code` varchar(100) NOT NULL,
  `common_name` varchar(225) NOT NULL,
  `official_name` varchar(255) NOT NULL,
  `endonym` varchar(255) NOT NULL,
  `demonym` varchar(225) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `iso_code` varchar(255) NOT NULL,
  `iso_numeric_code` varchar(100) NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `official_name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
