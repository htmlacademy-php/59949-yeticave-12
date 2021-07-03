CREATE DATABASE yeticave
    DEFAULT CHARACTER  SET utf8
    DEFAULT COLLATE utf8_general_ci;

CREATE SCHEMA auction;

CREATE TABLE auction.users (
    PRIMARY KEY (id),
    id           INT           AUTO_INCREMENT,
    email        VARCHAR(128)  NOT NULL          UNIQUE,
    name         VARCHAR(255)  NOT NULL,
    password     VARCHAR(255)  NOT NULL,
    contact      VARCHAR(255)  NOT NULL,
    token        VARCHAR(255)  DEFAULT NULL,

    created_at   DATETIME      NOT NULL          DEFAULT NOW(),
    updated_at   DATETIME,
    deleted_at   DATETIME
);

CREATE UNIQUE INDEX user_email ON auction.users(email);

CREATE TABLE auction.lots (
    PRIMARY KEY (id),
    id              INT           AUTO_INCREMENT,
    expiry_dt       DATETIME      NOT NULL,
    title           VARCHAR(255)  NOT NULL,
    description     TEXT          NOT NULL,
    img_path        VARCHAR(255)  NOT NULL,
    initial_price   INT           NOT NULL,
    bet_step        INT           NOT NULL,
    category_id     INT                            REFERENCES categories(id),
    author          INT           NOT NULL         REFERENCES users(id),
    winner          INT                            REFERENCES users(id),

    created_at   DATETIME      NOT NULL          DEFAULT NOW(),
    updated_at   DATETIME,
    deleted_at   DATETIME
);

CREATE INDEX lot_id ON auction.lots(id, category_id);
CREATE INDEX lot_title ON auction.lots(title);

CREATE TABLE auction.categories (
    PRIMARY KEY (id),
    id     INT                  AUTO_INCREMENT,
    title  VARCHAR(255),
    code   VARCHAR(255),

    created_at   DATETIME       NOT NULL          DEFAULT NOW(),
    updated_at   DATETIME,
    deleted_at   DATETIME
);

CREATE INDEX category_id ON auction.categories(id);

CREATE TABLE auction.bets (
    PRIMARY KEY (id),
    id           INT       AUTO_INCREMENT,
    amount       INT       NOT NULL ,
    user_id      INT       REFERENCES users(id),
    lot_id       INT       REFERENCES lots(id),

    created_at   DATETIME      NOT NULL          DEFAULT NOW(),
    updated_at   DATETIME,
    deleted_at   DATETIME
);

CREATE INDEX user_bets ON auction.bets(user_id, lot_id);

USE yeticave
