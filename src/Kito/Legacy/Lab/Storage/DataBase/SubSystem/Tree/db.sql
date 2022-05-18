-- phpMyAdmin SQL Dump
-- version 3.3.2
-- http://www.phpmyadmin.net
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `blk_BLK`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ATTRIBUTE`
--

CREATE TABLE IF NOT EXISTS `BLK_ATTRIBUTE` (
  `ATTRIBUTE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ATTRIBUTE_NAME_ID` int(11) NOT NULL,
  `ATTRIBUTE_VALUE_ID` int(11) NOT NULL,
  PRIMARY KEY (`ATTRIBUTE_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ATTRIBUTE_NAME`
--

CREATE TABLE IF NOT EXISTS `BLK_ATTRIBUTE_NAME` (
  `ATTRIBUTE_NAME_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ATTRIBUTE_NAME_VALUE` varchar(383) DEFAULT NULL,
  PRIMARY KEY (`ATTRIBUTE_NAME_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ATTRIBUTE_VALUE`
--

CREATE TABLE IF NOT EXISTS `BLK_ATTRIBUTE_VALUE` (
  `ATTRIBUTE_VALUE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ATTRIBUTE_VALUE_VALUE` text,
  PRIMARY KEY (`ATTRIBUTE_VALUE_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ZONE`
--

CREATE TABLE IF NOT EXISTS `BLK_ZONE` (
  `ZONE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ZONE_PARENT_ID` int(11) DEFAULT NULL,
  `ZONE_NAME_ID` int(11) NOT NULL,
  PRIMARY KEY (`ZONE_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ZONE_ATTRIBUTE`
--

CREATE TABLE IF NOT EXISTS `BLK_ZONE_ATTRIBUTE` (
  `ZONE_ATTRIBUTE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ZONE_ATTRIBUTE_ZONE_ID` int(11) NOT NULL,
  `ZONE_ATTRIBUTE_ATTRIBUTE_ID` int(11) NOT NULL,
  PRIMARY KEY (`ZONE_ATTRIBUTE_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_NAME`
--

CREATE TABLE IF NOT EXISTS `BLK_NAME` (
  `NAME_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME_VALUE` varchar(383) DEFAULT NULL,
  PRIMARY KEY (`NAME_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_NODE_LINK`
--

CREATE TABLE IF NOT EXISTS `BLK_NODE_LINK` (
  `NODE_LINK_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NODE_LINK_NAME_ID_A` int(11) NOT NULL,
  `NODE_LINK_NAME_ID_B` int(11) NOT NULL,
  PRIMARY KEY (`NODE_LINK_ID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BLK_ZONE_LINK`
--

CREATE TABLE IF NOT EXISTS `BLK_ZONE_LINK` (
  `ZONE_LINK_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ZONE_LINK_ZONE_ID_A` int(11) NOT NULL,
  `ZONE_LINK_ZONE_ID_B` int(11) NOT NULL,
  PRIMARY KEY (`ZONE_LINK_ID`)
) ENGINE=MyISAM;