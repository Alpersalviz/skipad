-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 10 Şub 2017, 10:04:20
-- Sunucu sürümü: 5.6.17
-- PHP Sürümü: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Veritabanı: `link_guide`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ad_type` enum('interstitial','header_banner','popup') NOT NULL,
  `impression` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_ip` varchar(20) NOT NULL,
  `ppc` float NOT NULL,
  `first_price` float NOT NULL,
  `current_price` float NOT NULL,
  `publish` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Tablo döküm verisi `ads`
--

INSERT INTO `ads` (`ID`, `title`, `url`, `ad_type`, `impression`, `user_id`, `created_date`, `created_ip`, `ppc`, `first_price`, `current_price`, `publish`) VALUES
(1, 'Bet365', 'https://www.bet365.com/en/', 'interstitial', 0, 3, '2017-01-30 00:00:00', '127.0.0.1', 0.3, 10, 10, 1),
(5, 'Masdsa', 'http://static.adzerk.net/Advertisers/0932cfed3e374852a27a09c2ed27061c.png', 'header_banner', 5, 4, '2017-02-02 08:40:35', '127.0.0.1', 0.1, 20, 19.5, 1),
(6, 'Sağlığınız İçin Bize Ulaşın', 'https://www.bet365.com/en/', 'interstitial', 0, 4, '2017-02-04 12:53:38', '127.0.0.1', 0.1, 2, 2, 1),
(7, 'asdsdfasdfdsagd', 'https://www.bet365.com/en/', 'interstitial', 5, 4, '2017-02-04 12:56:24', '127.0.0.1', 0.23, 1, 0.11, 1),
(8, 'Popup', 'https://www.youtube.com/watch?v=pHnCT1_neqw', 'popup', 1, 4, '2017-02-07 20:30:31', '127.0.0.1', 0, 100, 100, 0),
(9, 'Azaer', 'http://masmedikal.com/uploads/d18db19f05c21f366905cca845ca1eb6.jpg', 'popup', 1, 4, '2017-02-07 20:37:41', '127.0.0.1', 20, 50, 30, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ad_ip`
--

CREATE TABLE IF NOT EXISTS `ad_ip` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) NOT NULL,
  `ip` int(11) NOT NULL,
  `date` date NOT NULL,
  `url_id` int(11) NOT NULL,
  `ad_type` enum('interstitial','header_banner','popup') NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `id_ip` (`ip`,`url_id`,`ad_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Tablo döküm verisi `ad_ip`
--

INSERT INTO `ad_ip` (`ID`, `ad_id`, `ip`, `date`, `url_id`, `ad_type`) VALUES
(7, 5, 1270, '2017-02-04', 7, 'header_banner'),
(8, 7, 1270, '2017-02-04', 7, 'interstitial'),
(9, 5, 1270, '2017-02-04', 9, 'header_banner'),
(10, 7, 1270, '2017-02-04', 9, 'interstitial'),
(11, 8, 1270, '2017-02-07', 7, 'popup'),
(12, 5, 1270, '2017-02-07', 6, 'header_banner'),
(13, 7, 1270, '2017-02-07', 6, 'interstitial'),
(14, 9, 1270, '2017-02-07', 6, 'popup');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `payment_type` enum('paypal','bank') DEFAULT NULL,
  `status` enum('wait','done') NOT NULL,
  `balance` float NOT NULL,
  `payment_info` varchar(255) NOT NULL,
  `type` enum('notification','send') NOT NULL,
  `payment_date` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Tablo döküm verisi `payments`
--

INSERT INTO `payments` (`ID`, `user_id`, `payment_type`, `status`, `balance`, `payment_info`, `type`, `payment_date`) VALUES
(1, 4, 'bank', 'done', 100, 'Ziraat bankası 123456', 'send', '2016-11-30 00:00:00'),
(3, 4, NULL, 'done', 50, 'Garanti Bankası', 'notification', '2016-12-30 00:00:00'),
(4, 4, NULL, 'done', 25, 'Finans Bank', 'notification', '2017-01-30 00:00:00'),
(5, 4, NULL, 'wait', 25, 'Garanti Bankası', 'notification', '2017-02-08 00:00:00'),
(6, 4, 'paypal', 'wait', 100, 'Ziraat bankası 123456', 'send', '2017-03-30 00:00:00'),
(7, 4, 'paypal', 'done', 50.3, 'Ziraat bankası 123456', 'send', '2017-02-04 17:41:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ppc_country`
--

CREATE TABLE IF NOT EXISTS `ppc_country` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ppc` float NOT NULL,
  `ppc_publisher` float NOT NULL,
  `country_code` varchar(4) NOT NULL,
  `is_3g` bit(1) NOT NULL,
  `ad_type` enum('header_banner','interstitial','popup') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Tablo döküm verisi `ppc_country`
--

INSERT INTO `ppc_country` (`ID`, `ppc`, `ppc_publisher`, `country_code`, `is_3g`, `ad_type`) VALUES
(1, 0.31, 0.38, 'TR', b'1', 'interstitial'),
(2, 0.31, 0.38, 'TR', b'1', 'header_banner');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `payment_info` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `header_ppc` float NOT NULL,
  `interstitial_ppc` float NOT NULL,
  `payment_banks` varchar(255) NOT NULL,
  `minimum_payment` int(11) NOT NULL,
  `header_ppc_publisher` float NOT NULL,
  `interstitial_ppc_publisher` float NOT NULL,
  `popup_ppc` float NOT NULL,
  `popup_ppc_publisher` float NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Tablo döküm verisi `settings`
--

INSERT INTO `settings` (`ID`, `payment_info`, `title`, `header_ppc`, `interstitial_ppc`, `payment_banks`, `minimum_payment`, `header_ppc_publisher`, `interstitial_ppc_publisher`, `popup_ppc`, `popup_ppc_publisher`) VALUES
(1, '<p>İş Bankası TR:100001000000</p>\n<p>İş Bankası TR:100001000000</p>', 'Reklam', 0.1, 0.1, 'İş Bankası,Garanti Bankası,Finans Bank', 20, 0.05, 0.05, 20, 25);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urls`
--

CREATE TABLE IF NOT EXISTS `urls` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(20) NOT NULL,
  `redirect_url` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `impression` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `visitor` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Tablo döküm verisi `urls`
--

INSERT INTO `urls` (`ID`, `url`, `redirect_url`, `price`, `impression`, `user_id`, `created_date`, `visitor`) VALUES
(5, 'M2NPiO', 'http://www.formvalidator.net/#security-validators', 0, 0, 3, '2017-01-30 15:27:10', 0),
(6, 'eCxFrT', 'http://www.masmedikal.com/beta/', 0.25, 3, 4, '2017-02-02 08:39:41', 1),
(7, 'pTaio4', 'http://www.masmedikal.com/', 0.25, 5, 4, '2017-02-02 10:06:26', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','user') NOT NULL,
  `name` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `account_type` enum('personel','corporate') NOT NULL,
  `payment_type` enum('paypal','bank') NOT NULL,
  `payment_info` varchar(255) NOT NULL,
  `balance` float NOT NULL,
  `created_ip` varchar(20) NOT NULL,
  `surname` varchar(250) NOT NULL,
  `publish` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Tablo döküm verisi `user`
--

INSERT INTO `user` (`ID`, `email`, `password`, `user_type`, `name`, `address1`, `address2`, `city`, `country`, `zip_code`, `phone_number`, `account_type`, `payment_type`, `payment_info`, `balance`, `created_ip`, `surname`, `publish`) VALUES
(1, 'alper', '12345', 'admin', '', '', '', '', '', '', '', 'personel', 'paypal', '', 0, '0', '', 1),
(2, 'cagri', '123456', 'admin', '', '', '', '', '', '', '', 'personel', 'paypal', '', 0, '0', '', 0),
(4, 'alpersalviz@gmail.com', '123', 'user', 'Alper', '7052 sok. No:37 D:2', '7052 sok. No:37 D:2', 'İzmir', 'Türkiye', '35510', '5062798442', '', 'paypal', 'Ziraat bankasi Fiana', 25.2, '127.0.0.1', 'Şalvız', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
