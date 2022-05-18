-- phpMyAdmin SQL Dump
-- version 3.3.2
-- http://www.phpmyadmin.net
--
-- Tiempo de generación: 26-11-2010 a las 20:57:26
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.4-2ubuntu5.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `imo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ATTR`
--

CREATE TABLE IF NOT EXISTS `BLK_ATTR` (
  `ATTR_ID` int(4) NOT NULL auto_increment,
  `ATTR_NAME` varchar(50) NOT NULL,
  `ATTR_VALUE` varchar(255) default NULL,
  `ATTR_MODULE` varchar(10) NOT NULL,
  `ATTR_FUNCTION` varchar(10) NOT NULL,
  `ATTR_SYSTEM` enum('N','Y') NOT NULL,
  PRIMARY KEY  (`ATTR_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_CACHE`
--

CREATE TABLE IF NOT EXISTS `BLK_CACHE` (
  `CACHE_ID` int(4) NOT NULL auto_increment,
  `CACHE_NAME` varchar(50) NOT NULL,
  `CACHE_VALUE` varchar(255) NOT NULL,
  PRIMARY KEY  (`CACHE_ID`)
) ENGINE=MEMORY;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_TEXT`
--

CREATE TABLE IF NOT EXISTS `BLK_TEXT` (
  `TEXT_ID` int(4) NOT NULL auto_increment,
  `TEXT_NAME` varchar(50) NOT NULL,
  `TEXT_TEXT` text,
  `TEXT_PATH` varchar(255) default NULL,
  PRIMARY KEY  (`TEXT_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ZONE`
--

CREATE TABLE IF NOT EXISTS `BLK_ZONE` (
  `ZONE_ID` int(4) NOT NULL auto_increment,
  `ZONE_PARENT_ID` int(4) NOT NULL,
  `ZONE_NAME` varchar(50) default NULL,
  `ZONE_SYSTEM` enum('N','Y') NOT NULL,
  PRIMARY KEY  (`ZONE_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ZONE_ATTR`
--

CREATE TABLE IF NOT EXISTS `BLK_ZONE_ATTR` (
  `ZONE_ATTR_ID` int(4) NOT NULL auto_increment,
  `ZONE_ATTR_ID_ATTR` int(4) NOT NULL,
  `ZONE_ATTR_ID_ZONE` int(4) NOT NULL,
  `ZONE_ATTR_VALUE` varchar(255) NOT NULL,
  PRIMARY KEY  (`ZONE_ATTR_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ZONE_LINKS`
--

CREATE TABLE IF NOT EXISTS `BLK_ZONE_LINKS` (
  `ZONE_LINK_ID` int(11) NOT NULL auto_increment,
  `ZONE_LINK_ZONE_ID_A` int(11) NOT NULL,
  `ZONE_LINK_ZONE_ID_B` int(11) NOT NULL,
  `ZONE_LINK_REL` int(3) NOT NULL,
  PRIMARY KEY  (`ZONE_LINK_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ZONE_LIST`
--

CREATE TABLE IF NOT EXISTS `BLK_ZONE_LIST` (
  `BLK_ZONE_LIST_ID` int(11) NOT NULL auto_increment,
  `BLK_ZONE_LIST_ZONE_ID` int(11) NOT NULL,
  `BLK_ZONE_LIST_ATTR_ID` int(11) NOT NULL,
  `BLK_ZONE_LIST_VALUE` varchar(255) NOT NULL,
  PRIMARY KEY  (`BLK_ZONE_LIST_ID`)
) ENGINE=MyISAM;
