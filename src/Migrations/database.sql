-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema usermanagemnet
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `usermanagemnet` ;

-- -----------------------------------------------------
-- Schema usermanagemnet
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `usermanagemnet` DEFAULT CHARACTER SET latin1 ;
USE `usermanagemnet` ;

-- -----------------------------------------------------
-- Table `usermanagemnet`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usermanagemnet`.`roles` ;

CREATE TABLE IF NOT EXISTS `usermanagemnet`.`roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `usermanagemnet`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usermanagemnet`.`users` ;

CREATE TABLE IF NOT EXISTS `usermanagemnet`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `salary` FLOAT NULL DEFAULT NULL,
  `timeZone` VARCHAR(255) NULL DEFAULT NULL,
  `roleId` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `users_role_id`
  FOREIGN KEY (`roleId`)
  REFERENCES `usermanagemnet`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = latin1;

CREATE INDEX `users_role_id_idx` ON `usermanagemnet`.`users` (`roleId` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
