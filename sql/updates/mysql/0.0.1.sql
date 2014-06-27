-- Obs: install.mysql.utf8.sql 18-11-2013 11:56:31Z chdemko $
-- Gestor de congresos y revistas cientificas

-- eliminamos versiones anteriores

DROP TABLE IF EXISTS `#__valoracionIssue`;
DROP TABLE IF EXISTS `#__valoracionEdicion`;
DROP TABLE IF EXISTS `#__entidadCategoria`;
DROP TABLE IF EXISTS `#__topicoSimilar`;
DROP TABLE IF EXISTS `#__issueTopico`;
DROP TABLE IF EXISTS `#__revistaTopico`;
DROP TABLE IF EXISTS `#__edicionTopico`;
DROP TABLE IF EXISTS `#__congresoTopico`;
DROP TABLE IF EXISTS `#__categoria`;
DROP TABLE IF EXISTS `#__entidadIndiceCalidad`;
DROP TABLE IF EXISTS `#__issue`;
DROP TABLE IF EXISTS `#__revista`;
DROP TABLE IF EXISTS `#__topico`;
DROP TABLE IF EXISTS `#__edicion`;
DROP TABLE IF EXISTS `#__congreso`;
DROP TABLE IF EXISTS `#__editorial`;


-- creamos tablas

CREATE TABLE `#__editorial` (
    `nombre` varchar(128) NOT NULL,
    `siglas` varchar(128),
    `link` varchar(512),  
    PRIMARY KEY  (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__congreso` (
    `nombre` varchar(128) NOT NULL,
    `acronimo` varchar(128),
    `linkCongreso` varchar(512),
    `periodicidad` int,
    `nomEditorial` varchar(128),
    PRIMARY KEY  (`nombre`),
    FOREIGN KEY (`nomEditorial`) REFERENCES `#__editorial`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__edicion` (
    `orden` int NOT NULL,
    `nomCongreso` varchar(128) NOT NULL,
    `lugar` varchar(128),
    `linkEdicion` varchar(512),
    `limRecepcion` date,
    `fechaDefinitiva` date,
    `fechaInicio` date,
    `fechaFin` date,  
    PRIMARY KEY (`orden`, `nomCongreso`),
    FOREIGN KEY (`nomCongreso`) REFERENCES `#__congreso`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__topico` (
    `nombre` varchar(128) NOT NULL,
    `descripcion` text,
    PRIMARY KEY (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__revista` (
    `issn` varchar(128) NOT NULL,
    `nombre` varchar(128),
    `acronimo` varchar(128),
    `linkRevista` varchar(512),
    `periodicidad` int,
    `nomEditorial` varchar(128),
    PRIMARY KEY  (`issn`),
    FOREIGN KEY (`nomEditorial`) REFERENCES `#__editorial`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__issue` (
    `volumen` varchar(64) NOT NULL,
    `numero` int NOT NULL,
    `issnRevista` varchar(128) NOT NULL,
    `limRecepcion` date,
    `fechaDefinitiva` date,
    `fechaPublicacion` date,
    `temaPrincipal` varchar(128),
    PRIMARY KEY (`volumen`, `numero`, `issnRevista`),
    FOREIGN KEY (`issnRevista`) REFERENCES `#__revista`(`issn`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`temaPrincipal`) REFERENCES `#__topico`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


-- Indice de calidad

CREATE TABLE `#__entidadIndiceCalidad` (
    `nombre` varchar(128) NOT NULL,
    `siglas` varchar(128),
    `link` varchar(512),
    `criterios` text,
    `tipoCategoria` varchar(128),
    PRIMARY KEY (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__categoria` (
    `categoria` varchar(128) NOT NULL,
    `valoracion` int NOT NULL,
    PRIMARY KEY (`categoria`, `valoracion`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


-- Asociaciones

CREATE TABLE `#__congresoTopico` (
    `nomCongreso` varchar(128) NOT NULL,
    `nomTopico` varchar(128) NOT NULL,
    PRIMARY KEY (`nomCongreso`, `nomTopico`),
    FOREIGN KEY (`nomCongreso`) REFERENCES `#__congreso`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`nomTopico`) REFERENCES `#__topico`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE    
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__edicionTopico` (
    `ordenEdicion` int NOT NULL,
    `nomCongreso` varchar(128) NOT NULL,
    `nomTopico` varchar(128) NOT NULL,
    PRIMARY KEY (`ordenEdicion`, `nomCongreso`, `nomTopico`),
    FOREIGN KEY (`ordenEdicion`, `nomCongreso`) REFERENCES `#__edicion`(`orden`, `nomCongreso`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`nomTopico`) REFERENCES `#__topico`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE    
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__revistaTopico` (
    `issnRevista` varchar(128) NOT NULL,
    `nomTopico` varchar(128) NOT NULL,
    PRIMARY KEY (`issnRevista`, `nomTopico`),
    FOREIGN KEY (`issnRevista`) REFERENCES `#__revista`(`issn`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`nomTopico`) REFERENCES `#__topico`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE    
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__issueTopico` (
    `volumenIssue` varchar(64) NOT NULL,
    `numeroIssue` int NOT NULL,
    `issnRevista` varchar(128) NOT NULL,
    `nomTopico` varchar(128) NOT NULL,
    PRIMARY KEY (`volumenIssue`, `numeroIssue`, `issnRevista`, `nomTopico`),
    FOREIGN KEY (`volumenIssue`, `numeroIssue`, `issnRevista`) REFERENCES `#__issue`(`volumen`, `numero`, `issnRevista`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`nomTopico`) REFERENCES `#__topico`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE    
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__topicoSimilar` (
    `nomTopico` varchar(128) NOT NULL,
    `nomTopicoSimilar` varchar(128) NOT NULL,
    PRIMARY KEY (`nomTopico`, `nomTopicoSimilar`),
    FOREIGN KEY (`nomTopico`) REFERENCES `#__topico`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`nomTopicoSimilar`) REFERENCES `#__topico`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE    
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__entidadCategoria` (
    `nomEntidad` varchar(128) NOT NULL,
    `categoria` varchar(128) NOT NULL,
    `valoracionCategoria` int NOT NULL,
    PRIMARY KEY (`nomEntidad`, `categoria`, `valoracionCategoria`),
    FOREIGN KEY (`nomEntidad`) REFERENCES `#__entidadIndiceCalidad`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`categoria`, `valoracionCategoria`) REFERENCES `#__categoria`(`categoria`, `valoracion`)
        ON DELETE CASCADE
        ON UPDATE CASCADE    
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__valoracionEdicion` (
    `nomEntidad` varchar(128) NOT NULL,
    `ordenEdicion` int NOT NULL,
    `nomCongreso` varchar(128) NOT NULL,
    `indice` varchar(128),
    PRIMARY KEY (`nomEntidad`, `ordenEdicion`, `nomCongreso`),
    FOREIGN KEY (`nomEntidad`) REFERENCES `#__entidadIndiceCalidad`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`ordenEdicion`, `nomCongreso`) REFERENCES `#__edicion`(`orden`, `nomCongreso`)
        ON DELETE CASCADE
        ON UPDATE CASCADE    
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__valoracionIssue` (
    `nomEntidad` varchar(128) NOT NULL,
    `volumenIssue` varchar(64) NOT NULL,
    `numeroIssue` int NOT NULL,
    `issnRevista` varchar(128) NOT NULL,
    `indice` varchar(128),
    PRIMARY KEY (`nomEntidad`, `volumenIssue`, `numeroIssue`, `issnRevista`),
    FOREIGN KEY (`nomEntidad`) REFERENCES `#__entidadIndiceCalidad`(`nombre`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`volumenIssue`, `numeroIssue`, `issnRevista`) REFERENCES `#__issue`(`volumen`, `numero`, `issnRevista`)
        ON DELETE CASCADE
        ON UPDATE CASCADE    
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

