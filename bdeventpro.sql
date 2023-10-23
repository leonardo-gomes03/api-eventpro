-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.22-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para bdeventpro
CREATE DATABASE IF NOT EXISTS `bdeventpro` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `bdeventpro`;

-- Copiando estrutura para tabela bdeventpro.tbcidade
CREATE TABLE IF NOT EXISTS `tbcidade` (
  `idcidade` int(11) NOT NULL AUTO_INCREMENT,
  `nomecidade` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idcidade`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela bdeventpro.tbcidade: ~37 rows (aproximadamente)
/*!40000 ALTER TABLE `tbcidade` DISABLE KEYS */;
INSERT INTO `tbcidade` (`idcidade`, `nomecidade`) VALUES
	(1, 'Adolfo'),
	(2, 'Bady Bassit'),
	(3, 'Bálsamo'),
	(4, 'Cedral'),
	(5, 'Guapiaçu'),
	(6, 'Ibirá'),
	(7, 'Icém'),
	(8, 'Ipiguá'),
	(9, 'Irapuã'),
	(10, 'Jaci'),
	(11, 'José Bonifácio'),
	(12, 'Macaubal'),
	(13, 'Mendonça'),
	(14, 'Mirassol'),
	(15, 'Mirassolândia'),
	(16, 'Monte Aprazível'),
	(17, 'Neves Paulista'),
	(18, 'Nipoã'),
	(19, 'Nova Aliança'),
	(20, 'Nova Granada'),
	(21, 'Olímpia'),
	(22, 'Onda Verde'),
	(23, 'Orindiúva'),
	(24, 'Palestina'),
	(25, 'Paulo de Faria'),
	(26, 'Planalto'),
	(27, 'Poloni'),
	(28, 'Potirendaba'),
	(29, 'Sales'),
	(30, 'São José do Rio Preto'),
	(31, 'Severínia'),
	(32, 'Tanabi'),
	(33, 'Ubarana'),
	(34, 'Uchoa'),
	(35, 'União Paulista'),
	(36, 'Urupês'),
	(37, 'Zacarias');
/*!40000 ALTER TABLE `tbcidade` ENABLE KEYS */;

-- Copiando estrutura para tabela bdeventpro.tbfreelancerservico
CREATE TABLE IF NOT EXISTS `tbfreelancerservico` (
  `codservico` int(11) NOT NULL,
  `codfreelancer` int(11) NOT NULL,
  KEY `codservico` (`codservico`),
  KEY `codfreelancer` (`codfreelancer`),
  CONSTRAINT `tbfreelancerservico_ibfk_1` FOREIGN KEY (`codservico`) REFERENCES `tbservico` (`idservico`),
  CONSTRAINT `tbfreelancerservico_ibfk_2` FOREIGN KEY (`codfreelancer`) REFERENCES `tbusuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela bdeventpro.tbfreelancerservico: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `tbfreelancerservico` DISABLE KEYS */;
INSERT INTO `tbfreelancerservico` (`codservico`, `codfreelancer`) VALUES
	(17, 2),
	(18, 2);
/*!40000 ALTER TABLE `tbfreelancerservico` ENABLE KEYS */;

-- Copiando estrutura para tabela bdeventpro.tbprojeto
CREATE TABLE IF NOT EXISTS `tbprojeto` (
  `idprojeto` int(11) NOT NULL AUTO_INCREMENT,
  `codcliente` int(11) NOT NULL,
  `codtipo` int(11) NOT NULL,
  `codcidade` int(11) DEFAULT NULL,
  `tituloprojeto` varchar(100) NOT NULL,
  `horainicioprojeto` time NOT NULL,
  `horafimprojeto` time NOT NULL,
  `dataprojeto` date NOT NULL,
  `qtdpessoas` int(11) NOT NULL,
  `descricaoprojeto` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`idprojeto`),
  KEY `codcliente` (`codcliente`),
  KEY `codtipo` (`codtipo`),
  KEY `codcidade` (`codcidade`),
  CONSTRAINT `tbprojeto_ibfk_1` FOREIGN KEY (`codcliente`) REFERENCES `tbusuario` (`idusuario`),
  CONSTRAINT `tbprojeto_ibfk_2` FOREIGN KEY (`codtipo`) REFERENCES `tbtipo` (`idtipo`),
  CONSTRAINT `tbprojeto_ibfk_3` FOREIGN KEY (`codcidade`) REFERENCES `tbcidade` (`idcidade`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela bdeventpro.tbprojeto: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `tbprojeto` DISABLE KEYS */;
INSERT INTO `tbprojeto` (`idprojeto`, `codcliente`, `codtipo`, `codcidade`, `tituloprojeto`, `horainicioprojeto`, `horafimprojeto`, `dataprojeto`, `qtdpessoas`, `descricaoprojeto`) VALUES
	(1, 1, 1, 25, 'Casamento ao luar', '18:00:00', '04:00:00', '2031-12-06', 400, '');
/*!40000 ALTER TABLE `tbprojeto` ENABLE KEYS */;

-- Copiando estrutura para tabela bdeventpro.tbprojetoservico
CREATE TABLE IF NOT EXISTS `tbprojetoservico` (
  `codservico` int(11) NOT NULL,
  `codprojeto` int(11) NOT NULL,
  KEY `codservico` (`codservico`),
  KEY `codprojeto` (`codprojeto`),
  CONSTRAINT `tbprojetoservico_ibfk_1` FOREIGN KEY (`codservico`) REFERENCES `tbservico` (`idservico`),
  CONSTRAINT `tbprojetoservico_ibfk_2` FOREIGN KEY (`codprojeto`) REFERENCES `tbprojeto` (`idprojeto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela bdeventpro.tbprojetoservico: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `tbprojetoservico` DISABLE KEYS */;
INSERT INTO `tbprojetoservico` (`codservico`, `codprojeto`) VALUES
	(10, 1),
	(18, 1),
	(19, 1);
/*!40000 ALTER TABLE `tbprojetoservico` ENABLE KEYS */;

-- Copiando estrutura para tabela bdeventpro.tbproposta
CREATE TABLE IF NOT EXISTS `tbproposta` (
  `idproposta` int(11) NOT NULL AUTO_INCREMENT,
  `codprojeto` int(11) NOT NULL,
  `codfreelancer` int(11) NOT NULL,
  `codservico` int(11) NOT NULL,
  `statusproposta` varchar(100) NOT NULL,
  `valorproposta` decimal(10,2) NOT NULL,
  `descricaoproposta` varchar(1000) DEFAULT NULL,
  `notaavaliacao` decimal(2,2) DEFAULT NULL,
  `comentarioavaliacao` varchar(2000) DEFAULT NULL,
  `fotoavaliacao` blob DEFAULT NULL,
  PRIMARY KEY (`idproposta`),
  KEY `codprojeto` (`codprojeto`),
  KEY `codfreelancer` (`codfreelancer`),
  KEY `codservico` (`codservico`),
  CONSTRAINT `tbproposta_ibfk_1` FOREIGN KEY (`codprojeto`) REFERENCES `tbprojeto` (`idprojeto`),
  CONSTRAINT `tbproposta_ibfk_2` FOREIGN KEY (`codfreelancer`) REFERENCES `tbusuario` (`idusuario`),
  CONSTRAINT `tbproposta_ibfk_3` FOREIGN KEY (`codservico`) REFERENCES `tbfreelancerservico` (`codservico`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela bdeventpro.tbproposta: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `tbproposta` DISABLE KEYS */;
INSERT INTO `tbproposta` (`idproposta`, `codprojeto`, `codfreelancer`, `codservico`, `statusproposta`, `valorproposta`, `descricaoproposta`, `notaavaliacao`, `comentarioavaliacao`, `fotoavaliacao`) VALUES
	(1, 1, 1, 18, 'Em análise', 600.00, 'Trabalho das 18h até as 00h', 0.00, '', _binary '');
/*!40000 ALTER TABLE `tbproposta` ENABLE KEYS */;

-- Copiando estrutura para tabela bdeventpro.tbservico
CREATE TABLE IF NOT EXISTS `tbservico` (
  `idservico` int(11) NOT NULL AUTO_INCREMENT,
  `nomeservico` varchar(100) NOT NULL,
  PRIMARY KEY (`idservico`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela bdeventpro.tbservico: ~21 rows (aproximadamente)
/*!40000 ALTER TABLE `tbservico` DISABLE KEYS */;
INSERT INTO `tbservico` (`idservico`, `nomeservico`) VALUES
	(1, 'Animador de festas'),
	(2, 'Acessoria de eventos'),
	(3, 'Bandas e/ou Cantores(as)'),
	(4, 'Bartenders'),
	(5, 'Brindes e lembrancinhas'),
	(6, 'Buffet'),
	(7, 'Carros e limousines'),
	(8, 'Chocolateiro'),
	(9, 'Churrasqueiro'),
	(10, 'Confeiteiro'),
	(11, 'Decorador'),
	(12, 'DJ'),
	(13, 'Equipamento para festas'),
	(14, 'Florista'),
	(15, 'Food Truck'),
	(16, 'Fotografia'),
	(17, 'Garçons e copeiras'),
	(18, 'Recepcionistas e Cerimonialistas'),
	(19, 'Segurança'),
	(20, 'Sommelier'),
	(21, 'Ônibus balada');
/*!40000 ALTER TABLE `tbservico` ENABLE KEYS */;

-- Copiando estrutura para tabela bdeventpro.tbtipo
CREATE TABLE IF NOT EXISTS `tbtipo` (
  `idtipo` int(11) NOT NULL AUTO_INCREMENT,
  `nometipo` varchar(100) NOT NULL,
  PRIMARY KEY (`idtipo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela bdeventpro.tbtipo: ~10 rows (aproximadamente)
/*!40000 ALTER TABLE `tbtipo` DISABLE KEYS */;
INSERT INTO `tbtipo` (`idtipo`, `nometipo`) VALUES
	(1, 'Casamento'),
	(2, 'Churrasco'),
	(3, 'Festa de Aniversário'),
	(4, 'Evento Corporativo'),
	(5, 'Exposição'),
	(6, 'Batizado'),
	(7, 'Show'),
	(8, 'Comemoração comum'),
	(9, 'Evento Religioso'),
	(10, 'Outro');
/*!40000 ALTER TABLE `tbtipo` ENABLE KEYS */;

-- Copiando estrutura para tabela bdeventpro.tbusuario
CREATE TABLE IF NOT EXISTS `tbusuario` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nomeusuario` varchar(100) NOT NULL,
  `username` varchar(25) NOT NULL,
  `datanascusuario` date NOT NULL,
  `telefoneusuario` char(14) NOT NULL,
  `generousuario` char(1) NOT NULL,
  `emailusuario` varchar(100) NOT NULL,
  `cpfusuario` char(14) NOT NULL,
  `senhausuario` varchar(20) NOT NULL,
  `statususuario` tinyint(1) NOT NULL,
  `freelancerusuario` tinyint(1) NOT NULL,
  `fotoperfilusuario` blob DEFAULT NULL,
  `experienciausuario` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `emailusuario` (`emailusuario`),
  UNIQUE KEY `cpfusuario` (`cpfusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela bdeventpro.tbusuario: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `tbusuario` DISABLE KEYS */;
INSERT INTO `tbusuario` (`idusuario`, `nomeusuario`, `username`, `datanascusuario`, `telefoneusuario`, `generousuario`, `emailusuario`, `cpfusuario`, `senhausuario`, `statususuario`, `freelancerusuario`, `fotoperfilusuario`, `experienciausuario`) VALUES
	(1, 'Lays Eduarda de Oliveira Ribeiro', 'LaysEduarda4', '2006-03-14', '(17)98122-0544', 'F', 'layseduardaoliveira@gmail.com', '505.043.778-41', 'brunolenir', 1, 0, _binary '', ''),
	(2, 'Lenir Aparecida de Oliveira', 'Leniro36', '1985-10-09', '(17)99161-6406', 'F', 'leniro36@gmail.com', '327.198.7778-5', '503042', 1, 1, _binary '', 'Há 20 anos na área');
/*!40000 ALTER TABLE `tbusuario` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
