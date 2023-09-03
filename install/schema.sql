DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(30) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `password` CHAR(255) NOT NULL,
    `profile` VARCHAR(10) NOT NULL DEFAULT '',
    `avatar` VARCHAR(255) DEFAULT ''
) ENGINE = InnoDB;

-- Criar utilizador admin com password: abcd1234
INSERT INTO `users` VALUES(1, 'admin', 'admin@exemplo.pt','$2y$10$oLiCqMcPoYbyazsWkhsyde/WKWQwjXujG4z2o20FqDCDoyvbW.qbO','admin','');


-- Criar tabela messages
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `remetente` INT(11) NOT NULL,
  `destinatario` INT(11) NOT NULL,
  `assunto` VARCHAR(50) NULL,
  `corpo` TEXT NULL,
  `data_hora` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_messages_users1_idx` (`remetente` ASC),
  INDEX `fk_messages_users2_idx` (`destinatario` ASC),
  CONSTRAINT `fk_messages_users1`
    FOREIGN KEY (`remetente`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_messages_users2`
    FOREIGN KEY (`destinatario`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;