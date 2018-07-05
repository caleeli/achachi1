-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-12-2011 a las 21:22:44
-- Versión del servidor: 5.1.37
-- Versión de PHP: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `test`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auhis`
--

CREATE TABLE IF NOT EXISTS `auhis` (
  `auhiscorr` int(11) DEFAULT NULL,
  `auhisfech` date DEFAULT NULL,
  `auhisnpre` int(11) DEFAULT NULL,
  `auhisuser` char(3) DEFAULT NULL,
  `auhismarc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aumau`
--

CREATE TABLE IF NOT EXISTS `aumau` (
  `aumaucorr` int(11) DEFAULT NULL,
  `aumautipo` char(1) DEFAULT NULL,
  `aumaudesc` char(100) DEFAULT NULL,
  `aumauayda` char(200) DEFAULT NULL,
  `aumautegn` char(3) DEFAULT NULL,
  `aumaumrcd` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `aumau`
--

INSERT INTO `aumau` (`aumaucorr`, `aumautipo`, `aumaudesc`, `aumauayda`, `aumautegn`, `aumaumrcd`) VALUES
(1, 'N', 'Cumple con las politicas actuales', 'descrip.de la pregunta', 'BCO', 1),
(2, 'D', 'Cuenta  con inforcred', 'informe de INF', 'BCO', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gbage`
--

CREATE TABLE IF NOT EXISTS `gbage` (
  `gbagecage` int(11) NOT NULL,
  `gbagenomb` varchar(90) NOT NULL,
  `gbagetdid` smallint(6) DEFAULT NULL,
  `gbagendid` varchar(20) DEFAULT NULL,
  `gbagenruc` varchar(20) DEFAULT NULL,
  `gbagetper` smallint(6) DEFAULT NULL,
  `gbagefnac` date DEFAULT NULL,
  `gbagesexo` smallint(6) DEFAULT NULL,
  `gbageeciv` smallint(6) DEFAULT NULL,
  `gbagenaci` varchar(30) DEFAULT NULL,
  `gbageprof` smallint(6) DEFAULT NULL,
  `gbagedir1` varchar(30) DEFAULT NULL,
  `gbagedir2` varchar(30) DEFAULT NULL,
  `gbageddo1` varchar(30) DEFAULT NULL,
  `gbageddo2` varchar(30) DEFAULT NULL,
  `gbagetlfd` varchar(20) DEFAULT NULL,
  `gbagetlfo` varchar(20) DEFAULT NULL,
  `gbagencas` varchar(10) DEFAULT NULL,
  `gbagenfax` varchar(10) DEFAULT NULL,
  `gbagetlex` varchar(10) DEFAULT NULL,
  `gbageciiu` decimal(6,0) DEFAULT NULL,
  `gbageact1` varchar(30) DEFAULT NULL,
  `gbageact2` varchar(30) DEFAULT NULL,
  `gbagecalf` varchar(2) DEFAULT NULL,
  `gbagefreg` date DEFAULT NULL,
  `gbageplaz` smallint(6) DEFAULT NULL,
  `gbageagen` smallint(6) DEFAULT NULL,
  `gbageuser` varchar(20) DEFAULT NULL,
  `gbagehora` varchar(8) DEFAULT NULL,
  `gbagefpro` date DEFAULT NULL,
  `gbageclas` varchar(1) DEFAULT NULL,
  `gbagestat` smallint(6) DEFAULT NULL,
  `gbagefsta` date DEFAULT NULL,
  `gbagestaa` smallint(6) DEFAULT NULL,
  `gbagefsaa` date DEFAULT NULL,
  `gbageumrc` varchar(20) DEFAULT NULL,
  `gbageumod` varchar(20) DEFAULT NULL,
  `gbagefmod` date DEFAULT NULL,
  `gbagefmrc` date DEFAULT NULL,
  `gbagemrcb` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prdeu`
--

CREATE TABLE IF NOT EXISTS `prdeu` (
  `prdeucage` int(11) NOT NULL AUTO_INCREMENT,
  `prdeunpre` int(11) NOT NULL,
  PRIMARY KEY (`prdeucage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prmpr`
--

CREATE TABLE IF NOT EXISTS `prmpr` (
  `prmprnpre` int(11) DEFAULT NULL,
  `prmprcage` int(11) DEFAULT NULL,
  `prmprfreg` date NOT NULL,
  `prmprnctr` char(12) DEFAULT NULL,
  `prmprlncr` int(11) DEFAULT NULL,
  `prmprreso` char(12) DEFAULT NULL,
  `prmprtcre` smallint(6) NOT NULL,
  `prmprorgr` smallint(6) NOT NULL,
  `prmprautp` int(11) DEFAULT NULL,
  `prmprrseg` int(11) DEFAULT NULL,
  `prmprconv` int(11) DEFAULT NULL,
  `prmprrubr` smallint(6) NOT NULL,
  `prmprciiu` decimal(6,0) DEFAULT NULL,
  `prmprdest` smallint(6) NOT NULL,
  `prmprddes` char(30) DEFAULT NULL,
  `prmprcmon` smallint(6) NOT NULL,
  `prmprmapt` decimal(14,2) NOT NULL,
  `prmprplzo` int(11) DEFAULT NULL,
  `prmpruplz` smallint(6) NOT NULL,
  `prmprtsex` smallint(6) DEFAULT NULL,
  `prmprfpag` smallint(6) NOT NULL,
  `prmprppgk` int(11) DEFAULT NULL,
  `prmprppgi` int(11) DEFAULT NULL,
  `prmprgrac` int(11) DEFAULT NULL,
  `prmpruppg` smallint(6) NOT NULL,
  `prmprdiap` smallint(6) DEFAULT NULL,
  `prmprfprp` date DEFAULT NULL,
  `prmpriupg` decimal(14,2) NOT NULL,
  `prmprsald` decimal(14,2) NOT NULL,
  `prmprkven` decimal(14,2) NOT NULL,
  `prmprmdes` decimal(14,2) NOT NULL,
  `prmprmseg` decimal(14,2) NOT NULL,
  `prmprstat` smallint(6) NOT NULL,
  `prmprfsta` date NOT NULL,
  `prmprfpvc` date DEFAULT NULL,
  `prmprfvac` date DEFAULT NULL,
  `prmprfvor` date DEFAULT NULL,
  `prmprstan` smallint(6) NOT NULL,
  `prmprfsan` date NOT NULL,
  `prmprfdes` date DEFAULT NULL,
  `prmprfulp` date DEFAULT NULL,
  `prmprfcal` smallint(6) DEFAULT NULL,
  `prmprcalf` char(1) NOT NULL,
  `prmprviad` smallint(6) NOT NULL,
  `prmprctad` char(13) DEFAULT NULL,
  `prmprviac` smallint(6) NOT NULL,
  `prmprctac` char(13) DEFAULT NULL,
  `prmprdeba` char(1) NOT NULL,
  `prmprcrpg` smallint(6) NOT NULL,
  `prmprfrpg` date DEFAULT NULL,
  `prmprpdvg` decimal(14,2) NOT NULL,
  `prmprpsus` decimal(14,2) NOT NULL,
  `prmprfdev` date NOT NULL,
  `prmprmcpd` char(1) NOT NULL,
  `prmprreve` char(1) NOT NULL,
  `prmprusrr` char(3) DEFAULT NULL,
  `prmprmrcb` smallint(6) NOT NULL,
  `prmprplaz` smallint(6) NOT NULL,
  `prmpragen` smallint(6) NOT NULL,
  `prmpruser` char(3) NOT NULL,
  `prmprhora` char(8) NOT NULL,
  `prmprfpro` date NOT NULL,
  `prmprcclf` smallint(6) DEFAULT NULL,
  `prmprcpac` smallint(6) DEFAULT NULL,
  `prmprnlex` int(11) DEFAULT NULL,
  `prmprnmod` smallint(6) DEFAULT NULL,
  `prmprfinc` date DEFAULT NULL,
  `prmprnatu` smallint(6) DEFAULT NULL,
  `prmprnprp` int(11) DEFAULT NULL,
  `prmprctpp` smallint(6) DEFAULT NULL,
  `prmprcbnq` int(11) DEFAULT NULL,
  `prmprnrpg` int(11) DEFAULT NULL,
  `prmprpais` int(11) DEFAULT NULL,
  `prmprdpto` int(11) DEFAULT NULL,
  `prmprcprv` int(11) DEFAULT NULL,
  `prmprciud` int(11) DEFAULT NULL,
  `prmprzona` int(11) DEFAULT NULL,
  `prmprcmun` int(11) DEFAULT NULL,
  `prmprambg` smallint(6) DEFAULT NULL,
  `prmprcgta` int(11) DEFAULT NULL,
  `prmprugps` smallint(6) DEFAULT NULL,
  `prmprlong` decimal(20,10) DEFAULT NULL,
  `prmprlati` decimal(20,10) DEFAULT NULL,
  `prmprnfam` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_login` varchar(128) NOT NULL,
  `usr_password` text NOT NULL,
  `usr_email` text NOT NULL,
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usr_id`, `usr_login`, `usr_password`, `usr_email`) VALUES
(1, 'test', 'd41d8cd98f00b204e9800998ecf8427e', 'calidavidx21@hotmail.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
