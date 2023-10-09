CREATE TABLE product_groups (
    `id`  BIGINT PRIMARY KEY  DEFAULT UUID_SHORT(),
    `title` VARCHAR(64) NOT NULL,
    `description` TEXT NULL,
    `avatar` VARCHAR(256) NULL
) ENGINE = InnoDB, DEFAULT CHARSET = UTF8

CREATE TABLE product_actions (
    `id`  BIGINT PRIMARY KEY  DEFAULT UUID_SHORT(),
    `title` VARCHAR(64) NOT NULL,
    `description` TEXT NULL,
    `discount` FLOAT NOT NULL
) ENGINE = InnoDB, DEFAULT CHARSET = UTF8

CREATE TABLE products (
    `id`  BIGINT PRIMARY KEY  DEFAULT UUID_SHORT(),
    `id_group` BIGINT NOT NULL,
    `title` VARCHAR(64) NOT NULL,
    `description` TEXT NULL,
    `avatar` VARCHAR(256) NULL,
    `price`  FLOAT NOT NULL,
    `id_action` BIGINT NULL
) ENGINE = InnoDB, DEFAULT CHARSET = UTF8

INSERT INTO product_groups (`title`) VALUES ('Вироби зі скла');
INSERT INTO product_groups (`title`) VALUES ('Вироби з дерева');
INSERT INTO product_groups (`title`) VALUES ('Вироби з каменю');
INSERT INTO product_groups (`title`) VALUES ('Офісні товари');

INSERT INTO products (`id_group`, `title`, `avatar`, `price`)
VALUES ( 
    (SELECT id FROM product_groups WHERE `title`='Вироби зі скла'),
    'Новорічна куля', 'glass1.png', 300
);
INSERT INTO products (`id_group`, `title`, `avatar`, `price`)
VALUES ( 
    (SELECT id FROM product_groups WHERE `title`='Вироби зі скла'),
    'Скляний бик', 'glass2.jpg', 800
);
INSERT INTO products (`id_group`, `title`, `avatar`, `price`)
VALUES ( 
    (SELECT id FROM product_groups WHERE `title`='Вироби зі скла'),
    'Куля з гелікоптером', 'glass3.jpg', 1200
);


INSERT INTO products (`id_group`, `title`, `avatar`, `price`)
VALUES ( 
    (SELECT id FROM product_groups WHERE `title`='Вироби з дерева'),
    'Кошик', 'wood1.jpg', 700
);
INSERT INTO products (`id_group`, `title`, `avatar`, `price`)
VALUES ( 
    (SELECT id FROM product_groups WHERE `title`='Вироби з дерева'),
    'Булава', 'wood2.jpg', 1500
);
INSERT INTO products (`id_group`, `title`, `avatar`, `price`)
VALUES ( 
    (SELECT id FROM product_groups WHERE `title`='Вироби з дерева'),
    'Келих', 'wood3.jpg', 1000
);

Д.З. Реалізувати фільтр товарів за групою:
У контролері перевірити чи є ГЕТ-параметр 'grp',
якщо він є та його значення не 'all', то додати це
значення в умову для SQL-запиту 
* позначати вибрану групу стильовим класом 'active'