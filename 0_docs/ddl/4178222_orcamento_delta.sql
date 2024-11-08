USE `4178222_orcamento`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: 4178222_orcamento
-- ------------------------------------------------------
-- Server version	5.1.72-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_servico`
--

DROP TABLE IF EXISTS `tb_servico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_servico` (
  `id_servico` int(11) NOT NULL AUTO_INCREMENT,
  `servico` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_servico`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_servico`
--

LOCK TABLES `tb_servico` WRITE;
/*!40000 ALTER TABLE `tb_servico` DISABLE KEYS */;
INSERT INTO `tb_servico` VALUES (1,'CIRURGIA'),(2,'EXAME');
/*!40000 ALTER TABLE `tb_servico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_procedimento`
--

DROP TABLE IF EXISTS `tb_procedimento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_procedimento` (
  `id_procedimento` int(11) NOT NULL AUTO_INCREMENT,
  `procedimento` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_procedimento`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_procedimento`
--

LOCK TABLES `tb_procedimento` WRITE;
/*!40000 ALTER TABLE `tb_procedimento` DISABLE KEYS */;
INSERT INTO `tb_procedimento` VALUES (1,'AGULHAMENTO'),(2,'ALL SCAN â€“ BINO'),(3,'ANEL'),(4,'ANGIOGRAFIA â€“ AVANTI â€“ OCTA'),(5,'ANGIOGRAFIA â€“ TRITON â€“ OCTA'),(6,'ANGIOGRAFIA COM CONTRASTE'),(7,'AUTO REFRAÃ‡ÃƒO â€“ BINO'),(8,'AVALIAÃ‡ÃƒO VIAS LACRIMAIS'),(9,'BAT â€“ BINO'),(10,'BIOMETRIA â€“ IMERSÃƒO â€“ BINO'),(11,'BIOMICROSCOPIA ULTR. (UBM)'),(12,'BLEFARO'),(13,'CAMPO VISUAL â€“ (COMPASS)'),(14,'CHECK-UP'),(15,'CHECK-UP + BIO'),(16,'CIRURGIA FISTULIZANTE'),(17,'CRIO'),(18,'CROSSLINKING'),(19,'DACRIOCISTORRINOSTOMIA'),(20,'DESCOMPRESSÃƒO DA ORBITA'),(21,'DIOPSYS (PONT. EVOCADO)'),(22,'ECOGRAFIA'),(23,'ECTROPIO / ENTROPIO'),(24,'ENUCLEAÃ‡ÃƒO OU EVISCERAÃ‡ÃƒO SEM / COM IMPLANTE'),(25,'ENXERTO DE ESCLERA'),(26,'ESTEREOFOTO DE PAPILA'),(27,'ESTRABISMO'),(28,'FACO + LIO'),(29,'FOTO A LASER- INTEGRE- 1Âº aplicaÃ§Ã£o'),(30,'FOTO A LASER- INTEGRE- 2Âº aplicaÃ§Ã£o'),(31,'FOTOCOAGULAÃ‡ÃƒO A LASER â€“ argonio'),(32,'GALILEI G4 â€“ BINO'),(33,'GALILEI G4 + ALL SCAN OU LENSTAR'),(34,'GALILEI G6 â€“ BINO'),(35,'GONIOSCOPIA'),(36,'IMPLANTE SECUNDARIO DE LIO'),(37,'IMPLANTE VALVULAR'),(38,'INFILTRAÃ‡ÃƒO CONJUTIVAL'),(39,'INJEÃ‡ÃƒO'),(40,'IRIDECTOMIA CIRURGICA'),(41,'LAGOFTALMO'),(42,'LASIK'),(43,'LENSTAR â€“ BINO'),(44,'LENSX + LIO'),(45,'LUZ PULSADA'),(46,'MANUTENÃ‡ÃƒO ANUAL LUZ PULSADA'),(47,'MAPEAMENTO DE RETINA'),(48,'MICROSCOPIA ESPECULAR- BINO'),(49,'OCT DE CÃ“RNEA â€“ BINO'),(50,'OCT DE GLAUCOMA â€“ GDX â€“ nervo optico'),(51,'OCT DE RETINA/ MACULA'),(52,'OPDSCAN ABERROMETRO - â€“ BINO'),(53,'PAQUIMETRIA â€“ BINO'),(54,'PARACENTESE DE CAMERA ANTERIOR'),(55,'PCT- LUZ PULSADA/TEAR LAB/SCHIRMER/PINÃ‡A DE EXPRESSÃƒO'),(56,'PINÃ‡A DE EXPRESSÃƒO â€“ MEIBOMIUS'),(57,'PLUG DE SILICONE'),(58,'PONTECIAL MACULAR(SPH) - â€“ BINO'),(59,'PRK'),(60,'PTERIGIO'),(61,'PTOSE'),(62,'RASPAGEM DA CORNEA'),(63,'RECOBRIMENTO CONJUNTIVAL'),(64,'RECONSTITUIÃ‡ÃƒO DE FORNIX CONJUNTIVAL'),(65,'RECONSTITUIÃ‡ÃƒO DE GLOBO OCULAR'),(66,'RECONSTITUIÃ‡ÃƒO DE VIAS LACRIMAIS'),(67,'RECONSTRUÃ‡ÃƒO DE CAMARA ANTERIOR'),(68,'RECONSTRUÃ‡ÃƒO DE CAVIDADE ORBITÃRIA'),(69,'RECONSTRUÃ‡ÃƒO DE PALPEBRA'),(70,'RECONSTRUÃ‡ÃƒO TOTAL DE PALPEBRA'),(71,'REPOSICIONAMENTO DE LIO'),(72,'RESSECÃ‡ÃƒO LAMELAR'),(73,'RETINOGRAFIA- EIDON'),(74,'RETINOGRAFIA- EIDON- alta -AUTOFLORESCENCIA'),(75,'RETIRADA DE CORPO ESTRANHO'),(76,'RETIRADA DE LIO'),(77,'RETIRADA DE OLEO'),(78,'RETIRADA DE PONTOS'),(79,'RETIRADA DE TUBOS'),(80,'RETRACAO PALPEBRAL'),(81,'SIMBLEFARO'),(82,'SLT-TRABECULOPLASTIA BINO'),(83,'SLT-TRABECULOPLASTIA MONO'),(84,'SONDAGEM DE VIAS LACRIMAIS'),(85,'SUTURA DE CONJUNTIVA'),(86,'SUTURA DE CORNEA'),(87,'SUTURA DE ESCLERA'),(88,'SUTURA DE PALPEBRA'),(89,'SUTURA OU RECONSTITUIÃ‡ÃƒO DOS CANALICULOS'),(90,'TARSORRAFIA'),(91,'TEAR LAB-TESTE DE OSMOLARIDADE'),(92,'TESTE DE SCHIRMER'),(93,'TESTE DE SOBRECARGA HIDRICA â€“ TSH'),(94,'TONOMETRIA DE GOLDMANN'),(95,'TOPOGRAFIA â€“ BINO'),(96,'TOPOLYZER â€“ BINO'),(97,'TOPOPLASTIA'),(98,'TRABECULECTOMIA'),(99,'TRANSPLANTE CONJUNTIVAL'),(100,'TRANSPLANTE DE CORNEA '),(101,'TRANSPLANTE DE ESCLERA'),(102,'TRIQUIASE COM DIATERMO COAGULAÃ‡ÃƒO'),(103,'TUMOR DE CONJUNTIVA / SHAVE'),(104,'TUMOR DE ORBITA - EXERESE'),(105,'TUMOR DE PALPEBRA / SHAVE'),(106,'VERION'),(107,'VISITA HOSPITALAR'),(108,'VITRECTOMIA ANTERIOR'),(109,'VPP'),(110,'VPP + FACO'),(111,'XANTELASMA'),(112,'YAG â€“ bino'),(113,'YAG â€“ mono'),(114,'ESTRABISMO + FORNICE'),(115,'CALAZIO');
/*!40000 ALTER TABLE `tb_procedimento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_tipo_pagamento`
--

DROP TABLE IF EXISTS `tb_tipo_pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_tipo_pagamento` (
  `id_tipo_pagamento` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_pagamento` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_pagamento`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_tipo_pagamento`
--

LOCK TABLES `tb_tipo_pagamento` WRITE;
/*!40000 ALTER TABLE `tb_tipo_pagamento` DISABLE KEYS */;
INSERT INTO `tb_tipo_pagamento` VALUES (1,'BOLETO'),(2,'CHEQUE'),(3,'CRÃ‰DITO'),(4,'DÃ‰BITO'),(5,'DINHEIRO'),(6,'TRANSFERÃŠNCIA');
/*!40000 ALTER TABLE `tb_tipo_pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_opme`
--

DROP TABLE IF EXISTS `tb_opme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_opme` (
  `id_opme` int(11) NOT NULL AUTO_INCREMENT,
  `opme` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_opme`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_opme`
--

LOCK TABLES `tb_opme` WRITE;
/*!40000 ALTER TABLE `tb_opme` DISABLE KEYS */;
INSERT INTO `tb_opme` VALUES (149,'AHAMED'),(150,'CLAREON'),(151,'COLA'),(152,'CRAWFORD'),(153,'EYLIA'),(154,'IQ'),(155,'IQ TÃ“RICA'),(156,'LUCENTIS'),(157,'MA60AC'),(158,'MA60MA'),(159,'MIGS'),(160,'MONOKA'),(161,'NACIONAL'),(162,'OLOGEM'),(163,'ORZUDEX'),(164,'PANOPTIX'),(165,'PANOPTIX TÃ“RICA'),(166,'RAYONE'),(167,'RAYONE ASF'),(168,'RAYONE TRI'),(169,'RESTOR'),(170,'SA60AT'),(171,'SENSAR'),(172,'SMFONY'),(173,'SMFONY TÃ“RICA'),(174,'SUZANA'),(175,'TCN'),(176,'TECNISS'),(177,'TECNISS MT'),(178,'TECNISS MULTI'),(179,'TECNISS TÃ“RICA'),(180,'TRÃ‰PANO'),(181,'TRÃ‰PANO A VÃCUO'),(182,'RESTOR TÃ“RICA'),(183,'AVASTIN'),(184,'VISCOAT'),(185,'RADIOFREQUÃŠNCIA'),(186,'00 - CRIAR OPME');
/*!40000 ALTER TABLE `tb_opme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_lateralidade`
--

DROP TABLE IF EXISTS `tb_lateralidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_lateralidade` (
  `id_lateralidade` int(11) NOT NULL AUTO_INCREMENT,
  `lateralidade` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_lateralidade`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_lateralidade`
--

LOCK TABLES `tb_lateralidade` WRITE;
/*!40000 ALTER TABLE `tb_lateralidade` DISABLE KEYS */;
INSERT INTO `tb_lateralidade` VALUES (11,'AO'),(12,'INFERIOR'),(13,'OD'),(14,'OE'),(15,'SUPERIOR');
/*!40000 ALTER TABLE `tb_lateralidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_status`
--

DROP TABLE IF EXISTS `tb_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_status` (
  `id_status` int(11) NOT NULL AUTO_INCREMENT,
  `de_status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_status`
--

LOCK TABLES `tb_status` WRITE;
/*!40000 ALTER TABLE `tb_status` DISABLE KEYS */;
INSERT INTO `tb_status` VALUES (1,'FECHADO'),(2,'NÃƒO FECHADO'),(3,'PENDENTE'),(4,'Convenio S/D'),(5,'Convenio C/D');
/*!40000 ALTER TABLE `tb_status` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-07 20:30:37
