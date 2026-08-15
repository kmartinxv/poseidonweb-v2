-- Poseidon Vet — MySQL schema
-- Run this once in phpMyAdmin (or `mysql -u user -p dbname < schema.sql`).
-- Compatible with MySQL 5.7+ / MariaDB 10.x (HostGator shared hosting default).

SET NAMES utf8mb4;

-- ── Staff (admin/editor accounts for the backend) ─────────────────
CREATE TABLE IF NOT EXISTS staff (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(120)        NOT NULL,
  email         VARCHAR(160)        NOT NULL UNIQUE,
  password_hash VARCHAR(255)        NOT NULL,
  role          ENUM('admin','editor') NOT NULL DEFAULT 'editor',
  created_at    TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Shop categories ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS categories (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(80)  NOT NULL,
  slug       VARCHAR(80)  NOT NULL UNIQUE,
  sort_order INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Products ───────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS products (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(160)   NOT NULL,
  slug        VARCHAR(160)   NOT NULL UNIQUE,
  category_id INT UNSIGNED   NULL,
  price       DECIMAL(10,2)  NOT NULL,
  old_price   DECIMAL(10,2)  NULL,
  description TEXT           NULL,
  image       VARCHAR(255)   NULL,
  rating      DECIMAL(2,1)   NOT NULL DEFAULT 5.0,
  reviews     INT UNSIGNED   NOT NULL DEFAULT 0,
  badge       VARCHAR(20)    NULL,
  in_stock    TINYINT(1)     NOT NULL DEFAULT 1,
  created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Blog posts ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS blog_posts (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug          VARCHAR(180)  NOT NULL UNIQUE,
  title         VARCHAR(220)  NOT NULL,
  excerpt       TEXT          NULL,
  category      VARCHAR(60)   NULL,
  author        VARCHAR(100)  NULL,
  author_avatar VARCHAR(255)  NULL,
  image         VARCHAR(255)  NULL,
  body          LONGTEXT      NULL,
  tags          VARCHAR(255)  NULL,
  read_time     VARCHAR(20)   NULL,
  published     TINYINT(1)    NOT NULL DEFAULT 1,
  created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Promotional banners (shop on-sale strip + site-wide slots) ────
CREATE TABLE IF NOT EXISTS banners (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(160)  NOT NULL,
  subtitle   VARCHAR(255)  NULL,
  image      VARCHAR(255)  NULL,
  link       VARCHAR(255)  NULL,
  placement  ENUM('shop','home','services') NOT NULL DEFAULT 'shop',
  active     TINYINT(1)    NOT NULL DEFAULT 1,
  sort_order INT           NOT NULL DEFAULT 0,
  created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
