SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema usermanagemnet
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `usermanagemnet` ;
CREATE SCHEMA IF NOT EXISTS `usermanagemnet` DEFAULT CHARACTER SET latin1 ;
USE `usermanagemnet` ;

-- -----------------------------------------------------
-- Table `usermanagemnet`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usermanagemnet`.`roles` ;

CREATE TABLE IF NOT EXISTS `usermanagemnet`.`roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `usermanagemnet`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usermanagemnet`.`users` ;

CREATE TABLE IF NOT EXISTS `usermanagemnet`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `salary` FLOAT NULL DEFAULT NULL,
  `time_zone` VARCHAR(255) NULL DEFAULT NULL,
  `country` VARCHAR(45) NULL DEFAULT NULL,
  `role_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `users_role_id`
    FOREIGN KEY (`role_id`)
    REFERENCES `usermanagemnet`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1735
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `users_role_id_idx` ON `usermanagemnet`.`users` (`role_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `usermanagemnet`.`roles`
-- -----------------------------------------------------
START TRANSACTION;
USE `usermanagemnet`;
INSERT INTO `usermanagemnet`.`roles` (`id`, `name`) VALUES (1, 'Developer');
INSERT INTO `usermanagemnet`.`roles` (`id`, `name`) VALUES (2, 'Tester');
INSERT INTO `usermanagemnet`.`roles` (`id`, `name`) VALUES (3, 'Product Owner');
INSERT INTO `usermanagemnet`.`roles` (`id`, `name`) VALUES (4, 'Scrum Master');

COMMIT;

