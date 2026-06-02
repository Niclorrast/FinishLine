-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: finishline_db
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Autenticacion`
--

DROP TABLE IF EXISTS `Autenticacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Autenticacion` (
  `id_autenticacion` int NOT NULL AUTO_INCREMENT,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id_autenticacion`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Autenticacion`
--

LOCK TABLES `Autenticacion` WRITE;
/*!40000 ALTER TABLE `Autenticacion` DISABLE KEYS */;
INSERT INTO `Autenticacion` VALUES (1,'$2y$10$5DFyRDE.6696z6vlum1e7O6g91tOullTcrqxQGTZ1va4Jtvn9qe12'),(2,'$2y$10$9z.se8UMs58HjmU0vPEUwu74enFXfqIc2KafcXRZ0rvXvitTO546u'),(3,'$2y$10$xA6uFNljJmafeK5HHnySZO4fw6sgs1VS.FUtI3jHttZ6FChN12ZHm'),(4,'$2y$10$5DFyRDE.6696z6vlum1e7O6g91tOullTcrqxQGTZ1va4Jtvn9qe12'),(5,'$2y$10$S.qmRmxJQg8N2DU.v0TVoe..Hg/v1NaeHXVQ3A96pJhdkr7zN1Qtu'),(7,'$2y$10$Vdiglsek4pr0yMP8tUE9eOx.Ks8.1A3RiEwvnFWsDuXUcXJ4kZpha'),(8,'$2y$10$FUEfRRhke5kw4528QPUQE.XQbnGt5YkSHdh7YgbMFbZBSX7LH07xa');
/*!40000 ALTER TABLE `Autenticacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Nota_X_Ticket`
--

DROP TABLE IF EXISTS `Nota_X_Ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Nota_X_Ticket` (
  `id_nota` int NOT NULL AUTO_INCREMENT,
  `id_ticket` int NOT NULL,
  `id_usuario` int NOT NULL,
  `nota` text NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_nota`),
  KEY `id_ticket` (`id_ticket`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `Nota_X_Ticket_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `Ticket` (`id_ticket`) ON DELETE CASCADE,
  CONSTRAINT `Nota_X_Ticket_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Nota_X_Ticket`
--

LOCK TABLES `Nota_X_Ticket` WRITE;
/*!40000 ALTER TABLE `Nota_X_Ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `Nota_X_Ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Respuesta_x_solicitud`
--

DROP TABLE IF EXISTS `Respuesta_x_solicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Respuesta_x_solicitud` (
  `id_respuesta` int NOT NULL AUTO_INCREMENT,
  `id_solicitud` int NOT NULL,
  `respuesta` text NOT NULL,
  `fecha_respuesta` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_respuesta`),
  UNIQUE KEY `uq_solicitud` (`id_solicitud`),
  CONSTRAINT `fk_rxs_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `Solicitudes` (`id_solicitud`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Respuesta_x_solicitud`
--

LOCK TABLES `Respuesta_x_solicitud` WRITE;
/*!40000 ALTER TABLE `Respuesta_x_solicitud` DISABLE KEYS */;
/*!40000 ALTER TABLE `Respuesta_x_solicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Respuesta_x_ticket`
--

DROP TABLE IF EXISTS `Respuesta_x_ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Respuesta_x_ticket` (
  `id_respuesta` int NOT NULL AUTO_INCREMENT,
  `id_ticket` int NOT NULL,
  `respuesta` text NOT NULL,
  `fecha_respuesta` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_respuesta`),
  KEY `fk_rxt_ticket` (`id_ticket`),
  CONSTRAINT `fk_rxt_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `Ticket` (`id_ticket`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Respuesta_x_ticket`
--

LOCK TABLES `Respuesta_x_ticket` WRITE;
/*!40000 ALTER TABLE `Respuesta_x_ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `Respuesta_x_ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Servicios`
--

DROP TABLE IF EXISTS `Servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Servicios` (
  `id_servicio` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` text,
  `disponible` tinyint(1) DEFAULT '1',
  `categoria_vehiculo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Servicios`
--

LOCK TABLES `Servicios` WRITE;
/*!40000 ALTER TABLE `Servicios` DISABLE KEYS */;
INSERT INTO `Servicios` VALUES (4,'Lijado Superficial + Pintado Entero','Lijado completo de carrocería, imprimación y pintado bicapa con barniz UV.',1,'Todos'),(5,'Chapa + Pintura','Reparación de golpes y deformaciones en carrocería con pintado de acabado.',1,'Todos'),(6,'Pulido Profesional','Eliminación de micro-arañazos y recuperación del brillo original de la pintura.',1,'Todos'),(7,'Micro-Reparación + Pintado Selectivo','Reparación localizada por piezas con igualación exacta del color original.',1,'Todos'),(8,'Restauración de Llantas','Reparación de arañazos y oxidación en llantas con pintura y barniz protector.',1,'Todos');
/*!40000 ALTER TABLE `Servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Solicitudes`
--

DROP TABLE IF EXISTS `Solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Solicitudes` (
  `id_solicitud` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','en_proceso','respondida') DEFAULT 'pendiente',
  PRIMARY KEY (`id_solicitud`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Solicitudes`
--

LOCK TABLES `Solicitudes` WRITE;
/*!40000 ALTER TABLE `Solicitudes` DISABLE KEYS */;
/*!40000 ALTER TABLE `Solicitudes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ticket`
--

DROP TABLE IF EXISTS `Ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Ticket` (
  `id_ticket` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_servicio` int DEFAULT NULL,
  `descripcion` text,
  `matricula` varchar(20) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `presupuesto` decimal(10,2) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'pendiente',
  `fecha_cita` datetime DEFAULT NULL,
  `modelo_auto` varchar(20) DEFAULT NULL,
  `id_empleado` int DEFAULT NULL,
  `descripcion_trabajo` text,
  PRIMARY KEY (`id_ticket`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_servicio` (`id_servicio`),
  KEY `Ticket_ibfk_3` (`id_empleado`),
  CONSTRAINT `fk_ticket_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `Usuario` (`id_usuario`),
  CONSTRAINT `Ticket_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `Usuario` (`id_usuario`),
  CONSTRAINT `Ticket_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `Servicios` (`id_servicio`),
  CONSTRAINT `Ticket_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `Usuario` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ticket`
--

LOCK TABLES `Ticket` WRITE;
/*!40000 ALTER TABLE `Ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `Ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuario`
--

DROP TABLE IF EXISTS `Usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_autenticacion` int DEFAULT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellido` varchar(100) NOT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Correo` varchar(150) NOT NULL,
  `Rol` varchar(50) DEFAULT 'cliente',
  PRIMARY KEY (`id_usuario`),
  KEY `id_autenticacion` (`id_autenticacion`),
  CONSTRAINT `Usuario_ibfk_1` FOREIGN KEY (`id_autenticacion`) REFERENCES `Autenticacion` (`id_autenticacion`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuario`
--

LOCK TABLES `Usuario` WRITE;
/*!40000 ALTER TABLE `Usuario` DISABLE KEYS */;
INSERT INTO `Usuario` VALUES (1,1,'Henry','Cantoral','12345678','henry@test.com','cliente'),(2,2,'Pope','cito','12358','pope@e.com','empleado'),(3,3,'proyecto','daw','12','pro@e.daw','cliente'),(4,4,'Admin','Principal','652955253','finishlineheesni@gmail.com','admin'),(5,5,'Henry','Cantoral','652955253','henrycf97@gmail.com','empleado'),(7,7,'Jean','Cantoral','6142','1@f.com','empleado'),(8,8,'Pepe','Pepe1','675757383','pepe@pe.com','empleado');
/*!40000 ALTER TABLE `Usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento_x_tickets`
--

DROP TABLE IF EXISTS `documento_x_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento_x_tickets` (
  `id_documento` int NOT NULL,
  `id_ticket` int NOT NULL,
  PRIMARY KEY (`id_documento`,`id_ticket`),
  KEY `id_ticket` (`id_ticket`),
  CONSTRAINT `documento_x_tickets_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `documentos` (`id_documento`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `documento_x_tickets_ibfk_2` FOREIGN KEY (`id_ticket`) REFERENCES `Ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento_x_tickets`
--

LOCK TABLES `documento_x_tickets` WRITE;
/*!40000 ALTER TABLE `documento_x_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento_x_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentos` (
  `id_documento` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `ruta` varchar(255) DEFAULT NULL,
  `fecha_subida` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_documento`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos`
--

LOCK TABLES `documentos` WRITE;
/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset`
--

DROP TABLE IF EXISTS `password_reset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira_en` datetime NOT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `password_reset_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset`
--

LOCK TABLES `password_reset` WRITE;
/*!40000 ALTER TABLE `password_reset` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'finishline_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-22 14:08:21
