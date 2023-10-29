-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.28-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para bdeventpro
DROP DATABASE IF EXISTS `bdeventpro`;
CREATE DATABASE IF NOT EXISTS `bdeventpro` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `bdeventpro`;

-- Copiando estrutura para tabela bdeventpro.tbavaliacao
CREATE TABLE IF NOT EXISTS `tbavaliacao` (
  `idavaliacao` int(11) NOT NULL AUTO_INCREMENT,
  `codprojeto` int(11) DEFAULT NULL,
  `codcliente` int(11) DEFAULT NULL,
  `codfreelancer` int(11) DEFAULT NULL,
  `notaavaliacao` decimal(3,1) NOT NULL,
  `comentarioavaliacao` varchar(2000) DEFAULT NULL,
  `fotoavaliacao` blob DEFAULT NULL,
  PRIMARY KEY (`idavaliacao`),
  KEY `codprojeto` (`codprojeto`),
  KEY `codcliente` (`codcliente`),
  KEY `codfreelancer` (`codfreelancer`),
  CONSTRAINT `tbavaliacao_ibfk_1` FOREIGN KEY (`codprojeto`) REFERENCES `tbprojeto` (`idprojeto`),
  CONSTRAINT `tbavaliacao_ibfk_2` FOREIGN KEY (`codcliente`) REFERENCES `tbusuario` (`idusuario`),
  CONSTRAINT `tbavaliacao_ibfk_3` FOREIGN KEY (`codfreelancer`) REFERENCES `tbusuario` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbavaliacao: ~0 rows (aproximadamente)
INSERT INTO `tbavaliacao` (`idavaliacao`, `codprojeto`, `codcliente`, `codfreelancer`, `notaavaliacao`, `comentarioavaliacao`, `fotoavaliacao`) VALUES
	(1, 1, 1, 2, 1.5, 'Super grossa!', _binary '');

-- Copiando estrutura para tabela bdeventpro.tbcidade
CREATE TABLE IF NOT EXISTS `tbcidade` (
  `idcidade` int(11) NOT NULL AUTO_INCREMENT,
  `nomecidade` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idcidade`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbcidade: ~37 rows (aproximadamente)
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

-- Copiando estrutura para tabela bdeventpro.tbdenuncia
CREATE TABLE IF NOT EXISTS `tbdenuncia` (
  `iddenuncia` int(11) NOT NULL AUTO_INCREMENT,
  `codautordenuncia` int(11) DEFAULT NULL,
  `coddenunciado` int(11) DEFAULT NULL,
  `comentariodenuncia` varchar(5000) NOT NULL,
  PRIMARY KEY (`iddenuncia`),
  KEY `codautordenuncia` (`codautordenuncia`),
  KEY `coddenunciado` (`coddenunciado`),
  CONSTRAINT `tbdenuncia_ibfk_1` FOREIGN KEY (`codautordenuncia`) REFERENCES `tbusuario` (`idusuario`),
  CONSTRAINT `tbdenuncia_ibfk_2` FOREIGN KEY (`coddenunciado`) REFERENCES `tbusuario` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbdenuncia: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela bdeventpro.tbfreelancerservico
CREATE TABLE IF NOT EXISTS `tbfreelancerservico` (
  `codservico` int(11) NOT NULL,
  `codfreelancer` int(11) NOT NULL,
  KEY `codservico` (`codservico`),
  KEY `codfreelancer` (`codfreelancer`),
  CONSTRAINT `tbfreelancerservico_ibfk_1` FOREIGN KEY (`codservico`) REFERENCES `tbservico` (`idservico`),
  CONSTRAINT `tbfreelancerservico_ibfk_2` FOREIGN KEY (`codfreelancer`) REFERENCES `tbusuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbfreelancerservico: ~2 rows (aproximadamente)
INSERT INTO `tbfreelancerservico` (`codservico`, `codfreelancer`) VALUES
	(14, 2),
	(16, 2);

-- Copiando estrutura para tabela bdeventpro.tbprojeto
CREATE TABLE IF NOT EXISTS `tbprojeto` (
  `idprojeto` int(11) NOT NULL AUTO_INCREMENT,
  `codcliente` int(11) NOT NULL,
  `codtipo` int(11) NOT NULL,
  `codcidade` int(11) DEFAULT NULL,
  `tituloprojeto` varchar(100) NOT NULL,
  `datahorainicio` datetime NOT NULL,
  `datahorafim` datetime NOT NULL,
  `datahorapublicacao` datetime NOT NULL,
  `qtdpessoas` int(11) NOT NULL,
  `descricaoprojeto` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`idprojeto`),
  KEY `codcliente` (`codcliente`),
  KEY `codtipo` (`codtipo`),
  KEY `codcidade` (`codcidade`),
  CONSTRAINT `tbprojeto_ibfk_1` FOREIGN KEY (`codcliente`) REFERENCES `tbusuario` (`idusuario`),
  CONSTRAINT `tbprojeto_ibfk_2` FOREIGN KEY (`codtipo`) REFERENCES `tbtipo` (`idtipo`),
  CONSTRAINT `tbprojeto_ibfk_3` FOREIGN KEY (`codcidade`) REFERENCES `tbcidade` (`idcidade`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbprojeto: ~0 rows (aproximadamente)
INSERT INTO `tbprojeto` (`idprojeto`, `codcliente`, `codtipo`, `codcidade`, `tituloprojeto`, `datahorainicio`, `datahorafim`, `datahorapublicacao`, `qtdpessoas`, `descricaoprojeto`) VALUES
	(1, 1, 1, 30, 'Casamento dos Sonhos!', '2023-10-16 18:00:00', '2023-10-17 01:00:00', '2023-10-28 14:23:29', 350, 'Fotografo talentoso');

-- Copiando estrutura para tabela bdeventpro.tbprojetoservico
CREATE TABLE IF NOT EXISTS `tbprojetoservico` (
  `codservico` int(11) NOT NULL,
  `codprojeto` int(11) NOT NULL,
  KEY `codservico` (`codservico`),
  KEY `codprojeto` (`codprojeto`),
  CONSTRAINT `tbprojetoservico_ibfk_1` FOREIGN KEY (`codservico`) REFERENCES `tbservico` (`idservico`),
  CONSTRAINT `tbprojetoservico_ibfk_2` FOREIGN KEY (`codprojeto`) REFERENCES `tbprojeto` (`idprojeto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbprojetoservico: ~0 rows (aproximadamente)
INSERT INTO `tbprojetoservico` (`codservico`, `codprojeto`) VALUES
	(16, 1);

-- Copiando estrutura para tabela bdeventpro.tbproposta
CREATE TABLE IF NOT EXISTS `tbproposta` (
  `idproposta` int(11) NOT NULL AUTO_INCREMENT,
  `codprojeto` int(11) NOT NULL,
  `codfreelancer` int(11) NOT NULL,
  `codservico` int(11) NOT NULL,
  `statusproposta` varchar(100) NOT NULL,
  `valorproposta` decimal(10,2) NOT NULL,
  `descricaoproposta` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`idproposta`),
  KEY `codprojeto` (`codprojeto`),
  KEY `codfreelancer` (`codfreelancer`),
  KEY `codservico` (`codservico`),
  CONSTRAINT `tbproposta_ibfk_1` FOREIGN KEY (`codprojeto`) REFERENCES `tbprojeto` (`idprojeto`),
  CONSTRAINT `tbproposta_ibfk_2` FOREIGN KEY (`codfreelancer`) REFERENCES `tbusuario` (`idusuario`),
  CONSTRAINT `tbproposta_ibfk_3` FOREIGN KEY (`codservico`) REFERENCES `tbfreelancerservico` (`codservico`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbproposta: ~0 rows (aproximadamente)
INSERT INTO `tbproposta` (`idproposta`, `codprojeto`, `codfreelancer`, `codservico`, `statusproposta`, `valorproposta`, `descricaoproposta`) VALUES
	(3, 1, 2, 16, 'Finalizada', 200.00, 'Faço o serviço perfeitamente');

-- Copiando estrutura para tabela bdeventpro.tbservico
CREATE TABLE IF NOT EXISTS `tbservico` (
  `idservico` int(11) NOT NULL AUTO_INCREMENT,
  `nomeservico` varchar(100) NOT NULL,
  PRIMARY KEY (`idservico`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbservico: ~21 rows (aproximadamente)
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

-- Copiando estrutura para tabela bdeventpro.tbtipo
CREATE TABLE IF NOT EXISTS `tbtipo` (
  `idtipo` int(11) NOT NULL AUTO_INCREMENT,
  `nometipo` varchar(100) NOT NULL,
  PRIMARY KEY (`idtipo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbtipo: ~10 rows (aproximadamente)
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdeventpro.tbusuario: ~2 rows (aproximadamente)
INSERT INTO `tbusuario` (`idusuario`, `nomeusuario`, `username`, `datanascusuario`, `telefoneusuario`, `generousuario`, `emailusuario`, `cpfusuario`, `senhausuario`, `statususuario`, `freelancerusuario`, `fotoperfilusuario`, `experienciausuario`) VALUES
	(1, 'Leonardo Bruno de Oliveira Ribeiro', 'Leoox', '2021-10-16', '(17)48599-4569', 'M', 'leozinho@gmail.com', '589.569.854.14', 'Leoxx', 1, 0, _binary '', ''),
	(2, 'Lays Eduarda de Oliveira Ribeiro', 'LaysEduarda4', '2006-03-14', '(17)98122-0544', 'F', 'layseduardaoliveira@gmail.com', '505.043.778.41', '45534', 1, 1, _binary '', 'Formada em fotografia');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
