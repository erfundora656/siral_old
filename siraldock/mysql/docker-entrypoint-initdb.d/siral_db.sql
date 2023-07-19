-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2021 at 05:20 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siral_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `dirigido`
--

CREATE TABLE `dirigido` (
  `iddirigido` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `detalles` text,
  `sede_idsede` int(11) NOT NULL,
  `turno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plato`
--

CREATE TABLE `plato` (
  `idplato` int(11) NOT NULL,
  `nombre` text,
  `precio` float NOT NULL DEFAULT '0',
  `cantidad` float NOT NULL DEFAULT '1',
  `tipocantidad_idtipocantidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `sede_idsede` int(11) NOT NULL,
  `turno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prereservacion`
--

CREATE TABLE `prereservacion` (
  `idprereservacion` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `trabajador_idtrabajador` int(11) NOT NULL,
  `sede_idsede` int(11) NOT NULL,
  `turno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sede`
--

CREATE TABLE `sede` (
  `idsede` int(11) NOT NULL,
  `nombre` text,
  `activa` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sede`
--

INSERT INTO `sede` (`idsede`, `nombre`, `activa`) VALUES
(3, 'Central (UCLV)', 1),
(4, 'Central (Camilitos)', 1),
(5, 'Felix Varela', 1),
(6, 'Central', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `trabajador_idtrabajador` int(11) NOT NULL,
  `plato_idplato` int(11) NOT NULL,
  `chequeo` int(1) NOT NULL,
  `fecha` date NOT NULL,
  `sede_idsede` int(11) NOT NULL,
  `turno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tipocantidad`
--

CREATE TABLE `tipocantidad` (
  `idtipocantidad` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `simbolo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipocantidad`
--

INSERT INTO `tipocantidad` (`idtipocantidad`, `nombre`, `simbolo`) VALUES
(5, 'Gramos', 'gr'),
(6, 'Unidades', 'U'),
(7, 'Litros', 'L'),
(8, 'Onzas', 'oz');

-- --------------------------------------------------------

--
-- Table structure for table `trabajador`
--

CREATE TABLE `trabajador` (
  `idtrabajador` int(11) NOT NULL,
  `ci` varchar(11) NOT NULL,
  `nombres` text,
  `apellidos` text,
  `codigo` varchar(15) NOT NULL,
  `usuario` varchar(45) DEFAULT NULL,
  `email` varchar(90) NOT NULL,
  `becado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombres` text,
  `apellidos` text,
  `rol` tinyint(4) DEFAULT '0',
  `usuario` varchar(45) NOT NULL,
  `sede_idsede` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombres`, `apellidos`, `rol`, `usuario`, `sede_idsede`) VALUES
(15, 'Administrador', 'Principal', 3, 'erfundora', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dirigido`
--
ALTER TABLE `dirigido`
  ADD PRIMARY KEY (`iddirigido`);

--
-- Indexes for table `plato`
--
ALTER TABLE `plato`
  ADD PRIMARY KEY (`idplato`),
  ADD KEY `fk_plato_tipocantidad` (`tipocantidad_idtipocantidad`);

--
-- Indexes for table `prereservacion`
--
ALTER TABLE `prereservacion`
  ADD PRIMARY KEY (`idprereservacion`,`trabajador_idtrabajador`),
  ADD KEY `fk_prereservacion_trabajador1` (`trabajador_idtrabajador`);

--
-- Indexes for table `sede`
--
ALTER TABLE `sede`
  ADD PRIMARY KEY (`idsede`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`trabajador_idtrabajador`,`plato_idplato`),
  ADD KEY `fk_trabajador_has_plato_plato1` (`plato_idplato`);

--
-- Indexes for table `tipocantidad`
--
ALTER TABLE `tipocantidad`
  ADD PRIMARY KEY (`idtipocantidad`);

--
-- Indexes for table `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`idtrabajador`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dirigido`
--
ALTER TABLE `dirigido`
  MODIFY `iddirigido` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `plato`
--
ALTER TABLE `plato`
  MODIFY `idplato` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prereservacion`
--
ALTER TABLE `prereservacion`
  MODIFY `idprereservacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `sede`
--
ALTER TABLE `sede`
  MODIFY `idsede` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tipocantidad`
--
ALTER TABLE `tipocantidad`
  MODIFY `idtipocantidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `idtrabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `plato`
--
ALTER TABLE `plato`
  ADD CONSTRAINT `fk_plato_tipocantidad` FOREIGN KEY (`tipocantidad_idtipocantidad`) REFERENCES `tipocantidad` (`idtipocantidad`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_trabajador_has_plato_plato1` FOREIGN KEY (`plato_idplato`) REFERENCES `plato` (`idplato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajador_has_plato_trabajador1` FOREIGN KEY (`trabajador_idtrabajador`) REFERENCES `trabajador` (`idtrabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
