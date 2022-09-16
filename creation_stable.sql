CREATE TABLE `dwwm_vervins`.`utilisateurs` ( 
`id` INT NOT NULL AUTO_INCREMENT , 
`utilisateur` VARCHAR(50) NOT NULL , 
`motdepasse` VARCHAR(255) NOT NULL , 
`date_heure_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
