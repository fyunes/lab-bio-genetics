CREATE DATABASE  IF NOT EXISTS `laboratorio_medico` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `laboratorio_medico`;

-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: laboratorio_medico
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `obras_sociales`
--

DROP TABLE IF EXISTS `obras_sociales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obras_sociales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obras_sociales`
--

LOCK TABLES `obras_sociales` WRITE;
/*!40000 ALTER TABLE `obras_sociales` DISABLE KEYS */;
INSERT INTO `obras_sociales` VALUES (1,'OSDE'),(2,'Swiss Medical'),(3,'Medife'),(4,'Galeno'),(5,'Omint');
/*!40000 ALTER TABLE `obras_sociales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente` (
  `ID_Paciente` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Sexo` varchar(10) DEFAULT NULL,
  `FechaNac` date DEFAULT NULL,
  `NumDoc` varchar(20) DEFAULT NULL,
  `NumCel` varchar(20) DEFAULT NULL,
  `Correo` varchar(100) DEFAULT NULL,
  `Usuario` varchar(50) DEFAULT NULL,
  `Contrasena` varchar(255) DEFAULT NULL,
  `ObraSocial` varchar(100) DEFAULT NULL,
  `Num_afiliadoOS` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Paciente`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente`
--

LOCK TABLES `paciente` WRITE;
/*!40000 ALTER TABLE `paciente` DISABLE KEYS */;
INSERT INTO `paciente` VALUES (1,'Franco','Bacchiddu',NULL,NULL,'43235466','347664994','franbacchiddu@gmail.com','Franco',NULL,'1',NULL);
/*!40000 ALTER TABLE `paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `practicas`
--

DROP TABLE IF EXISTS `practicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `practicas` (
  `ID_Practica` int(11) NOT NULL AUTO_INCREMENT,
  `TipoPractica` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `PreparacionPaciente` text DEFAULT NULL,
  PRIMARY KEY (`ID_Practica`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `practicas`
--

LOCK TABLES `practicas` WRITE;
/*!40000 ALTER TABLE `practicas` DISABLE KEYS */;
INSERT INTO `practicas` VALUES (1,'Citogenética','Análisis de cromosomas para detectar anomalías numéricas y estructurales. Incluye cariotipo y bandeo cromosómico.','No requiere ayuno. Se necesita muestra de sangre periférica en tubo con heparina sódica. Evitar exposición a rayos X una semana antes del estudio.'),(2,'Diagnóstico prenatal','Conjunto de técnicas para evaluar la salud del feto durante el embarazo. Incluye análisis de líquido amniótico y muestras de vellosidades coriónicas.','Consultar con el médico tratante según el tipo específico de prueba. Puede requerir ayuno de 8 horas. Traer estudios previos y ecografías.'),(3,'Hibridación In Situ (FISH)','Técnica que permite detectar secuencias específicas de ADN en cromosomas usando sondas fluorescentes. Útil para diagnóstico de alteraciones genéticas.','No requiere preparación especial. Se necesita muestra de sangre periférica o tejido según indicación médica.'),(4,'Biología Molecular','Análisis de ADN/ARN para detectar mutaciones genéticas, variantes y marcadores moleculares asociados a enfermedades.','Ayuno de 8 horas. No consumir alcohol 24 horas antes. Muestra de sangre en tubo EDTA o según especificación del estudio.'),(5,'Citometría de flujo','Análisis celular que permite identificar y cuantificar diferentes poblaciones celulares. Útil en diagnóstico de leucemias y linfomas.','Ayuno de 6 horas. Muestra de sangre periférica en tubo con EDTA. No realizar ejercicio intenso 24 horas antes.'),(6,'Análisis Bioquímicos','Estudios de laboratorio que evalúan diferentes componentes químicos en sangre, orina y otros fluidos corporales. Incluye perfil lipídico, glucemia, función renal y hepática.','Ayuno de 8-12 horas. No consumir alcohol 24 horas antes. Evitar ejercicio intenso el día anterior. Traer muestra de orina de 24 horas si fue solicitada.');
/*!40000 ALTER TABLE `practicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resultados_paciente`
--

DROP TABLE IF EXISTS `resultados_paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resultados_paciente` (
  `ID_Practica` int(11) NOT NULL,
  `ID_Paciente` int(11) NOT NULL,
  `ResultadoPractica` text DEFAULT NULL,
  PRIMARY KEY (`ID_Practica`,`ID_Paciente`),
  KEY `ID_Paciente` (`ID_Paciente`),
  CONSTRAINT `resultados_paciente_ibfk_1` FOREIGN KEY (`ID_Practica`) REFERENCES `practicas` (`ID_Practica`),
  CONSTRAINT `resultados_paciente_ibfk_2` FOREIGN KEY (`ID_Paciente`) REFERENCES `paciente` (`ID_Paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resultados_paciente`
--

LOCK TABLES `resultados_paciente` WRITE;
/*!40000 ALTER TABLE `resultados_paciente` DISABLE KEYS */;
/*!40000 ALTER TABLE `resultados_paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turno`
--

DROP TABLE IF EXISTS `turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `turno` (
  `ID_Turno` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha_turno` date DEFAULT NULL,
  `Estado` varchar(50) DEFAULT NULL,
  `ID_Paciente` int(11) DEFAULT NULL,
  `ID_Practica` int(11) DEFAULT NULL,
  `OrdenMedica` text DEFAULT NULL,
  PRIMARY KEY (`ID_Turno`),
  KEY `ID_Paciente` (`ID_Paciente`),
  KEY `ID_Practica` (`ID_Practica`),
  CONSTRAINT `turno_ibfk_1` FOREIGN KEY (`ID_Paciente`) REFERENCES `paciente` (`ID_Paciente`),
  CONSTRAINT `turno_ibfk_2` FOREIGN KEY (`ID_Practica`) REFERENCES `practicas` (`ID_Practica`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turno`
--

LOCK TABLES `turno` WRITE;
/*!40000 ALTER TABLE `turno` DISABLE KEYS */;
INSERT INTO `turno` VALUES (1,'2024-11-28','Pendiente',1,6,'uploads/67422b1b396bc_1732389659.JPG'),(2,'2024-11-27','Pendiente',1,4,'uploads/67422b5240ef4_1732389714.JPG');
/*!40000 ALTER TABLE `turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `obra_social_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `obra_social_id` (`obra_social_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`obra_social_id`) REFERENCES `obras_sociales` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin','lab','$2y$10$7DL2jze3GAcBBWv4pTpyteVxRc3EfbBRJ7j2jLt9pIuanmyFy2dGW','Masculino','123456','1990-08-23','1234567','admin@admin.com',NULL),(2,'cliente','lab','$2y$10$YplCr6NAEtuSvHwYOQFeM.pNJe7Ka5G6RcQRLnW4.OHxVB.Eft2Hq','Femenino','1234568','2024-12-04','12345689','cliente@gmail.com',4);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-23 16:46:28
