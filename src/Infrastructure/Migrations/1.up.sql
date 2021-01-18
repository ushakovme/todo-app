CREATE TABLE `tasks`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `content`   text                      DEFAULT NULL,
    `completed` tinyint(1)       NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
