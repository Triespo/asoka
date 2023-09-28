#####################################################################################

# Create Permissions

GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '';
FLUSH PRIVILEGES;

#####################################################################################

# Create Database

DROP DATABASE IF EXISTS asoka;
CREATE DATABASE asoka DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE asoka;

#####################################################################################

-- -----------------------------------------------------
-- Table `Usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Usuario` ;

CREATE TABLE IF NOT EXISTS `Usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(120) NOT NULL ,
  `password` VARCHAR(256) NOT NULL ,
  `nombre` VARCHAR(45) NULL ,
  `apellidos` VARCHAR(90) NULL ,
  `direccion` VARCHAR(120) NULL ,
  `telefono` VARCHAR(9) NULL ,
  `dni` VARCHAR(9) NULL ,
  `tipo` TINYINT(4) UNSIGNED NOT NULL DEFAULT 3,
  `estado` TINYINT(4) UNSIGNED NOT NULL DEFAULT 1,
  `f_nacimiento` DATE NULL ,
  `registration_key` varchar(255) DEFAULT NULL,
  `borrado` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 ,
  `f_creado` DATETIME NULL ,
  `f_modificado` DATETIME NULL ,
  `f_borrado` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB;

INSERT INTO `Usuario` (`id`,`email`,`password`,`nombre`,`apellidos`,`direccion`,`telefono`,`tipo`)
VALUES
  (1,'grg10@alu.ua.es','sha256:1000:RD/Pnht7iCqo+xXdOJBN50qhrOs00N/6:Klats4/WLTgFy1jpMaOY1Bi6RnMVZ7B4','Gustavo','Real','C/ Falsa 123','666112233',1),
  (2,'jdmb1@alu.ua.es','sha256:1000:PTox99Go4cNvk4IzzFFjFYhKpqTLFMQZ:Jf9iy6/7AL6tdQXA8qg5fulroU9brpnd','Juan Daniel','M','Sesame Street','555123456',1),
  (3,'voluntario@asoka.dev','sha256:1000:fTuRqaiiXRiJTPdjQBom8AHWsahT+wm+:vxVMUkwZkXFJAS0nhcF0lVJm1BBRK+lf','Darth','Vader','Death Star II','666666666',2),
  (4,'adoptante@asoka.dev','sha256:1000:rgWogxhw32QZw4+6/wQD3SOrI6HI6ek0:E07kPMQ286B5koDxDv83KcxzOjuMVFxs','Mace','Windu','Consejo Jedi','111222333',3);
-- -----------------------------------------------------
-- Table `Mensaje`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Mensaje` ;

CREATE  TABLE IF NOT EXISTS `Mensaje` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL ,
  `id_destinatario` INT NOT NULL ,
  `titulo` VARCHAR(100) NOT NULL ,
  `contenido` TEXT NOT NULL ,
  `estado` TINYINT(4) NOT NULL DEFAULT 0,
  `borrado` TINYINT(1) NOT NULL DEFAULT 0,
  `f_envio` DATETIME NULL ,
  `f_recepcion` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `mensaje_emisor_idx` (`id_usuario` ASC) ,
  INDEX `mensaje_receptor_idx` (`id_destinatario` ASC) ,
  CONSTRAINT `mensaje_emisor`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `Usuario` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `mensaje_receptor`
    FOREIGN KEY (`id_destinatario` )
    REFERENCES `Usuario` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Adoptante`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Adoptante` ;

CREATE  TABLE IF NOT EXISTS `Adoptante` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE KEY `id_usuario_UNIQUE` (`id_usuario`),
  INDEX `usuario_adoptante_idx` (`id_usuario` ASC) ,
  CONSTRAINT `usuario_adoptante`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `Usuario` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

INSERT INTO `Adoptante` (`id`,`id_usuario`)
VALUES
  (1, 4);

-- -----------------------------------------------------
-- Table `Admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Admin` ;

CREATE  TABLE IF NOT EXISTS `Admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL ,
  `superadmin` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) ,
  UNIQUE KEY `id_usuario_UNIQUE` (`id_usuario`),
  INDEX `admin_usuario_idx` (`id_usuario` ASC) ,
  CONSTRAINT `admin_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `Usuario` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

INSERT INTO `Admin` (`id`,`id_usuario`,`superadmin`)
VALUES
  (1, 1, 1),
  (2, 2, 0);

-- -----------------------------------------------------
-- Table `Voluntario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Voluntario` ;

CREATE  TABLE IF NOT EXISTS `Voluntario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE KEY `id_usuario_UNIQUE` (`id_usuario`),
  INDEX `voluntario_usuario_idx` (`id_usuario` ASC) ,
  CONSTRAINT `voluntario_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `Usuario` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

INSERT INTO `Voluntario` (`id`,`id_usuario`)
VALUES
  (1, 3);

-- -----------------------------------------------------
-- Table `Turno`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Turno` ;

CREATE  TABLE IF NOT EXISTS `Turno` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dia` INT NOT NULL ,
  `hora` TIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TurnoVoluntario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TurnoVoluntario` ;

CREATE  TABLE IF NOT EXISTS `TurnoVoluntario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_voluntario` INT NOT NULL ,
  `id_turno` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `turnoVoluntario_voluntario_idx` (`id_voluntario` ASC) ,
  INDEX `turnoVoluntario_turno_idx` (`id_turno` ASC) ,
  CONSTRAINT `turnoVoluntario_voluntario`
    FOREIGN KEY (`id_voluntario` )
    REFERENCES `Voluntario` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `turnoVoluntario_turno`
    FOREIGN KEY (`id_turno` )
    REFERENCES `Turno` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Tarea`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Tarea` ;

CREATE  TABLE IF NOT EXISTS `Tarea` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL ,
  `tipo` TINYINT(4) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TareaVoluntario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TareaVoluntario` ;

CREATE  TABLE IF NOT EXISTS `TareaVoluntario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_voluntario` INT NOT NULL ,
  `id_tarea` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `tareaVoluntario_voluntario_idx` (`id_voluntario` ASC) ,
  INDEX `tareaVoluntario_tarea_idx` (`id_tarea` ASC) ,
  CONSTRAINT `tareaVoluntario_voluntario`
    FOREIGN KEY (`id_voluntario` )
    REFERENCES `Voluntario` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `tareaVoluntario_tarea`
    FOREIGN KEY (`id_tarea` )
    REFERENCES `Tarea` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Animal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Animal` ;

CREATE TABLE IF NOT EXISTS `Animal` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(20) NOT NULL ,
  `imagen` VARCHAR(500) NULL ,
  `f_nacimiento` DATE NULL ,
  `descripcion` VARCHAR(1000) NOT NULL ,
  `sexo` TINYINT(1) NOT NULL DEFAULT 0,
  `tamanyo` VARCHAR(20) NULL ,
  `peso` FLOAT NULL ,
  `raza` VARCHAR(45) NULL ,
  `estado` TINYINT(4) NOT NULL DEFAULT 0 ,
  `id_adoptante` INT NULL ,
  `borrado` TINYINT(1) NULL ,
  `f_creacion` DATETIME NULL ,
  `f_modificacion` DATETIME NULL ,
  `f_borrado` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `animal_adoptante_idx` (`id_adoptante` ASC) ,
  CONSTRAINT `animal_adoptante`
    FOREIGN KEY (`id_adoptante` )
    REFERENCES `Adoptante` (`id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;

INSERT INTO `Animal` (`id`,`nombre`,`descripcion`,`sexo`,`raza`,`estado`,`id_adoptante`) 
VALUES 
(1,'R2-D2','Smart Droid',0,'Droide',1,1),
(2,'Federico','Perro con pelo color canela.',0,NULL,0,NULL);

-- -----------------------------------------------------
-- Table `Viaje`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Viaje` ;

CREATE  TABLE IF NOT EXISTS `Viaje` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_animal` INT NOT NULL ,
  `destino` VARCHAR(100) NULL ,
  `estado` TINYINT(4) NULL ,
  `f_salida` DATETIME NULL ,
  `f_llegada` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `viaje_animal_idx` (`id_animal` ASC) ,
  CONSTRAINT `viaje_animal`
    FOREIGN KEY (`id_animal` )
    REFERENCES `Animal` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `AnimalVoluntario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AnimalVoluntario` ;

CREATE  TABLE IF NOT EXISTS `AnimalVoluntario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_animal` INT NOT NULL ,
  `id_voluntario` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `animalVoluntario_animal_idx` (`id_animal` ASC) ,
  INDEX `animalVoluntario_voluntario_idx` (`id_voluntario` ASC) ,
  CONSTRAINT `animalVoluntario_animal`
    FOREIGN KEY (`id_animal` )
    REFERENCES `Animal` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `animalVoluntario_voluntario`
    FOREIGN KEY (`id_voluntario` )
    REFERENCES `Voluntario` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;
