-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-04-2015 a las 00:34:17
-- Versión del servidor: 5.6.15-log
-- Versión de PHP: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `topurf`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_champion`
--

CREATE TABLE IF NOT EXISTS `tbl_champion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `championName` varchar(32) DEFAULT NULL,
  `img` varchar(48) DEFAULT NULL,
  `pickRate` int(11) DEFAULT '0',
  `banRate` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_match`
--

CREATE TABLE IF NOT EXISTS `tbl_match` (
  `id` int(11) NOT NULL DEFAULT '0',
  `timeExtract` int(11) DEFAULT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'Not processed',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_playerstats`
--

CREATE TABLE IF NOT EXISTS `tbl_playerstats` (
  `id_match` int(11) NOT NULL DEFAULT '0',
  `id_summoner` int(11) NOT NULL DEFAULT '0',
  `id_champion` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `minionsKilled` int(11) DEFAULT NULL,
  `goldEarned` int(11) DEFAULT NULL,
  `totalDamageTaken` int(11) DEFAULT NULL,
  `totalDamageDealtToChampions` int(11) DEFAULT NULL,
  `totalTimeCrowdControlDealt` int(11) DEFAULT NULL,
  `totalHeal` int(11) DEFAULT NULL,
  `wardPlaced` int(11) DEFAULT NULL,
  `wardKilled` int(11) DEFAULT NULL,
  `kills` int(11) DEFAULT NULL,
  `deaths` int(11) DEFAULT NULL,
  `assists` int(11) DEFAULT NULL,
  `win` tinyint(4) DEFAULT NULL,
  `timePlayed` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_match`,`id_summoner`),
  KEY `id_summoner` (`id_summoner`),
  KEY `id_champion` (`id_champion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_summoner`
--

CREATE TABLE IF NOT EXISTS `tbl_summoner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `summonerName` varchar(32) DEFAULT NULL,
  `region` varchar(8) DEFAULT NULL,
  `id_match_score` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `id_match_kills` int(11) DEFAULT NULL,
  `kills` int(11) DEFAULT NULL,
  `id_match_deaths` int(11) DEFAULT NULL,
  `deaths` int(11) DEFAULT NULL,
  `id_match_assists` int(11) DEFAULT NULL,
  `assists` int(11) DEFAULT NULL,
  `id_match_dmg` int(11) DEFAULT NULL,
  `totalDamageDealtToChampions` int(11) DEFAULT NULL,
  `id_match_taken` int(11) DEFAULT NULL,
  `totalDamageTaken` int(11) DEFAULT NULL,
  `verificationCode` varchar(32) NOT NULL,
  `verificationStatus` varchar(32) NOT NULL DEFAULT 'Not verified',
  PRIMARY KEY (`id`),
  KEY `id_match_score` (`id_match_score`),
  KEY `id_match_kills` (`id_match_kills`),
  KEY `id_match_deaths` (`id_match_deaths`),
  KEY `id_match_assists` (`id_match_assists`),
  KEY `id_match_dmg` (`id_match_dmg`),
  KEY `id_match_taken` (`id_match_taken`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=118569 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_summoner` int(11) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_summoner` (`id_summoner`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1107322459284981 ;

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
