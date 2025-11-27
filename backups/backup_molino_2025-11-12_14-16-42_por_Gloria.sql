-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: molino
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administrador`
--

DROP TABLE IF EXISTS `administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrador` (
  `idAdmin` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idAdmin`),
  UNIQUE KEY `correo_UNIQUE` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrador`
--

LOCK TABLES `administrador` WRITE;
/*!40000 ALTER TABLE `administrador` DISABLE KEYS */;
INSERT INTO `administrador` VALUES (1,'Gloria','Torres Martínez','moyitatorres9@gmail.com','$2y$10$xtMfcm46qVJDxcXkf5aUmu2pLqe0oyvuLV.khwZWTo4kGN8uads1e','7778360154');
/*!40000 ALTER TABLE `administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avisos`
--

DROP TABLE IF EXISTS `avisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avisos` (
  `id_aviso` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` date NOT NULL,
  `autor_id_admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_aviso`),
  KEY `fk_avisos_admin` (`autor_id_admin`),
  CONSTRAINT `fk_avisos_admin` FOREIGN KEY (`autor_id_admin`) REFERENCES `administrador` (`idAdmin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avisos`
--

LOCK TABLES `avisos` WRITE;
/*!40000 ALTER TABLE `avisos` DISABLE KEYS */;
INSERT INTO `avisos` VALUES (3,'DIA FESTIVO','el dia 17 no hay clases','2025-11-04',1),(4,'GH','GH','2025-11-04',1);
/*!40000 ALTER TABLE `avisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `genero` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idCliente`),
  UNIQUE KEY `correo_UNIQUE` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (2,'Miles','Morales','Masculino','miles@gmail.com','$2y$10$f96wf/yBdkd7g4xx6DWmi.MyH4OUQL5pRkWqAY5PyagHQurdPPli.','7777','2025-10-26 09:54:59'),(5,'Moy2',' Escobar','Femenino','wew@gmail.com','$2y$10$dfvETLuZMTjjM5Ii0.DUI.QxmAMS12uwC5RfuGgsFWgNSde4P6I2G','77775','2025-10-28 23:39:23'),(7,'weredfd','Morales','Otro','DFDF@GMAIL.COM','$2y$10$Dihg21rHLnItBXqDWfCaNOgJDzPuHZaQ4COmqdnNLGbbI8pibDtUK','7777','2025-10-29 01:24:00'),(9,'Juan','Tijuana','Femenino','ddd@gmail.com','$2y$10$U8ftaHjqaBlEADurOxR5Q.mjN.FxxHub9isYBva7/87deCvgz21DC','7778000002','2025-10-30 23:58:56'),(10,'Carlos','Moral','Masculino','persona2@gmail.com','$2y$10$JQrFjztiPeqJxNZTovYvVu5US6EokZ8e1AvidCAGys1WiabltvDbG','7777','2025-10-31 11:21:15'),(14,'juam','Torres Martínez','Masculino','persona6@gmail.com','$2y$10$yca/tRCq9TjOaCx69ryzUu2j3.HgNN09Ys.LtENp854EOnMH9HCeq','7778000009','2025-11-07 12:23:10'),(17,'David','Castañeda','Masculino','moyo@gmail.com','$2y$10$4mUE/HmbX3Hdirlh8V99zuEyG3mDuHnyzrCqdAHfgEoDZpj/7DHxS','7771234567','2025-11-12 11:27:43'),(18,'Pepe','Juarez Rodriguez','Masculino','cmcm@gmail.com','$2y$10$eXUdioZ5N2AZLqUJmZCVb.35oTPFMJ2NTBD4ExPMkdWf/cvRL1p7q','7771230971','2025-11-12 14:10:53');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto`
--

DROP TABLE IF EXISTS `contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacto` (
  `id_contacto` int(11) NOT NULL DEFAULT 1,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `correo_contacto` varchar(100) NOT NULL,
  `url_facebook` varchar(255) DEFAULT NULL,
  `actualizado_por_admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_contacto`),
  KEY `fk_contacto_admin` (`actualizado_por_admin`),
  CONSTRAINT `fk_contacto_admin` FOREIGN KEY (`actualizado_por_admin`) REFERENCES `administrador` (`idAdmin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto`
--

LOCK TABLES `contacto` WRITE;
/*!40000 ALTER TABLE `contacto` DISABLE KEYS */;
INSERT INTO `contacto` VALUES (1,'77783601787','Interior Mercado La Asunción de Tejalpa, Local 105, Calle Real de Yautepec, Tejalpa, Mor. CP. 62570','moyitatorres9@gmail.com','https://web.facebook.com/profile.php?id=100052222674585',1);
/*!40000 ALTER TABLE `contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id_faq` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta` varchar(255) NOT NULL,
  `respuesta` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `creado_por_admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_faq`),
  KEY `fk_faq_admin` (`creado_por_admin`),
  CONSTRAINT `fk_faq_admin` FOREIGN KEY (`creado_por_admin`) REFERENCES `administrador` (`idAdmin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,'¿En qué horario laboran?','7:00 am  a 3:00 pm \r\n',1,1),(2,'¿Qué cantidad mínima se puede moler?','A partir de 4kg en adelante\r\n',1,1),(3,'¿Hacen entregas a domicilio?','Sí, pero sólo en algunas zonas y ciertos horarios\r\n',1,1),(4,'¿Puedo traer mi propio maíz para moler?','Sí previamente nixtamalizado, al igual que cualquier tipo de grano o chile previamente tostado o remojado\r\n',1,1),(5,'¿Cuánto tiempo dura la masa?','De 2-3 días en el refrigerador, congelada hasta 3 semanas\r\n',1,1),(6,'¿Tienen estacionamiento disponible?','Sí, el mercado cuenta con estacionamiento\r\n',1,1),(7,'¿Qué pasa si no puedo recoger mi pedido a tiempo?','Se resguarda en el refrigerador unos días en caso de que el cliente no acuda, la calidad del producto se compromete y por lo tanto no nos hacemos responsables',1,1),(10,'Que días no se trabaja?','En dias festivos como dia de muertos o navidad',1,1);
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios_molida`
--

DROP TABLE IF EXISTS `horarios_molida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horarios_molida` (
  `id_horario` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `tipo_molida` varchar(100) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `id_admin_creador` int(11) DEFAULT NULL,
  `id_trabajador_creador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `fk_horarios_producto` (`id_producto`),
  KEY `fk_horarios_admin` (`id_admin_creador`),
  KEY `fk_horarios_trabajador` (`id_trabajador_creador`),
  CONSTRAINT `fk_horarios_admin` FOREIGN KEY (`id_admin_creador`) REFERENCES `administrador` (`idAdmin`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_horarios_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_horarios_trabajador` FOREIGN KEY (`id_trabajador_creador`) REFERENCES `trabajador` (`idTrabajador`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios_molida`
--

LOCK TABLES `horarios_molida` WRITE;
/*!40000 ALTER TABLE `horarios_molida` DISABLE KEYS */;
INSERT INTO `horarios_molida` VALUES (1,'2025-11-04','08:00:00','12:00:00','Molina de masa',7,'Se muele masa',1,NULL),(2,'2025-11-09','08:50:00','09:30:00','Molida de hojas',8,'Se muelen hojas porque no se x',1,NULL),(4,'2025-11-05','02:20:00','03:20:00','Molina de masa',8,'nose ',1,NULL);
/*!40000 ALTER TABLE `horarios_molida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pregunta_cliente`
--

DROP TABLE IF EXISTS `pregunta_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pregunta_cliente` (
  `id_pregunta` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `pregunta_texto` text NOT NULL,
  `respuesta_texto` text DEFAULT NULL,
  `fecha_pregunta` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_respuesta` datetime DEFAULT NULL,
  `id_trabajador_respuesta` int(11) DEFAULT NULL,
  `id_admin_respuesta` int(11) DEFAULT NULL,
  `estado` enum('pendiente','respondida') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id_pregunta`),
  KEY `fk_pregunta_cliente` (`id_cliente`),
  KEY `fk_pregunta_trabajador_resp` (`id_trabajador_respuesta`),
  KEY `fk_pregunta_admin_resp` (`id_admin_respuesta`),
  CONSTRAINT `fk_pregunta_admin_resp` FOREIGN KEY (`id_admin_respuesta`) REFERENCES `administrador` (`idAdmin`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_pregunta_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`idCliente`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_pregunta_trabajador_resp` FOREIGN KEY (`id_trabajador_respuesta`) REFERENCES `trabajador` (`idTrabajador`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pregunta_cliente`
--

LOCK TABLES `pregunta_cliente` WRITE;
/*!40000 ALTER TABLE `pregunta_cliente` DISABLE KEYS */;
INSERT INTO `pregunta_cliente` VALUES (1,2,'Cuanto cuesta moler 5Kg de masa?','200 pesos jsjs\r\n','2025-11-05 22:41:58','2025-11-06 01:07:44',NULL,1,'respondida'),(6,2,'que se hace o como?','no se ','2025-11-06 00:55:08','2025-11-06 01:30:17',6,NULL,'respondida'),(9,2,'Que dias no habra servicio?\r\n',NULL,'2025-11-12 13:07:20',NULL,NULL,NULL,'pendiente'),(10,2,'Que dias se molera masa para tortilla?',NULL,'2025-11-12 13:07:50',NULL,NULL,NULL,'pendiente'),(11,18,'CUanto es 50topitos + 50topitos','¡50 topitos más 50 topitos son 100 topitos! 💯Es una simple suma:$$50 + 50 = 100$$','2025-11-12 14:11:44','2025-11-12 14:13:13',NULL,1,'respondida');
/*!40000 ALTER TABLE `pregunta_cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (7,'Masa para tortilla','Masa para llevar y hacer tortillas','public/media/products/6913802d4f0c6-masa.jpeg','activo','2025-10-29 21:46:12'),(8,'Hoja de tamal','Hojas de tamales para hacer tamales','public/media/products/69138019ed4c0-hoja de tamal.jpg','activo','2025-10-29 21:46:26'),(18,'Tostadas','Se venden tostadas frias\r\n','public/media/products/69138040a10fb-tostatas.png','activo','2025-11-11 11:36:39'),(19,'Granos de maíz palomero','Granos para que hagas palomitas de maíz','public/media/products/6913800f5fc54-granos.jpg','activo','2025-11-11 11:39:40'),(20,'Panes','Se vende pan fresco','public/media/products/691380378734c-panes.png','activo','2025-11-11 11:45:32'),(21,'Ajos','Se venden ajos para comer','public/media/products/69137ff86396e-ajos.png','activo','2025-11-11 11:46:04'),(22,'Masa negra para tortillas','Masa negra para hacer tortillas de color negro','public/media/products/69138023460cd-masa_negra.png','activo','2025-11-11 11:46:47');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios_ofrecidos`
--

DROP TABLE IF EXISTS `servicios_ofrecidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicios_ofrecidos` (
  `id_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('Entrega','Recepción','Envío','Otro') NOT NULL,
  `nombre_servicio` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_fin` time DEFAULT NULL,
  `dias_disponibles` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios_ofrecidos`
--

LOCK TABLES `servicios_ofrecidos` WRITE;
/*!40000 ALTER TABLE `servicios_ofrecidos` DISABLE KEYS */;
INSERT INTO `servicios_ofrecidos` VALUES (1,'Recepción','Se recibe su maíz para moler','Recibimos su masa para ser molida por nosotros','09:20:00','14:00:00','Lunes,Miércoles,Viernes','2025-11-02 20:20:26'),(2,'Envío','Envío a domicilio','Usted nos dice y nosotros hacemos envíos locales cercanos','09:20:00','13:00:00','Lunes, Martes y Jueves','2025-11-02 20:21:50'),(3,'Entrega','Se entrega masa para tortillas','Puede dejar su maíz para ser molido y se le entrega','09:10:00','10:30:00','Lunes,Martes,Miércoles,Sábado','2025-11-02 20:25:42'),(4,'Envío','masa','entrega de masa a domicilio','13:22:00','17:22:00','Lunes, Martes y Jueves','2025-11-03 10:24:01'),(5,'Recepción','ejemplo','nose','07:04:00','15:03:00','Miércoles,Jueves,Sábado','2025-11-03 23:08:19');
/*!40000 ALTER TABLE `servicios_ofrecidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trabajador`
--

DROP TABLE IF EXISTS `trabajador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trabajador` (
  `idTrabajador` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `genero` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `puesto` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idTrabajador`),
  UNIQUE KEY `correo_UNIQUE` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajador`
--

LOCK TABLES `trabajador` WRITE;
/*!40000 ALTER TABLE `trabajador` DISABLE KEYS */;
INSERT INTO `trabajador` VALUES (5,'Eduardo',' Mauro','Masculino','eduardo@molinololis.com','$2y$10$smiDmq1a3O.4DEIzhCcjF.TBoAM9GPx7g0.WbOa0Tm1RtgAOptkRC','7778000001','Encargado'),(6,'María',' Dolores Martínez','Femenino','maria@molinololis.com','$2y$10$smiDmq1a3O.4DEIzhCcjF.TBoAM9GPx7g0.WbOa0Tm1RtgAOptkRC','7778000002','Ayudante general'),(7,'Gregorio',' Escobar','Masculino','gregorio@molinololis.com','$2y$10$smiDmq1a3O.4DEIzhCcjF.TBoAM9GPx7g0.WbOa0Tm1RtgAOptkRC','7778000003','Ayudante general'),(8,'Danitza',' Dolores Martínez','Femenino','danitza@molinololis.com','$2y$10$smiDmq1a3O.4DEIzhCcjF.TBoAM9GPx7g0.WbOa0Tm1RtgAOptkRC','7778000004','Ayudante general'),(9,'Josue',' Torres Martínez','Masculino','josue@molinololis.com','$2y$10$smiDmq1a3O.4DEIzhCcjF.TBoAM9GPx7g0.WbOa0Tm1RtgAOptkRC','7778000001','Ayudante general');
/*!40000 ALTER TABLE `trabajador` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-12 14:16:42
