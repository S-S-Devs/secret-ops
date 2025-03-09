-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS secret_ops;
USE secret_ops;

-- Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,
    country VARCHAR(50) NOT NULL,
    role ENUM('user', 'admin') NOT NULL
);

-- Crear la tabla de mapas
CREATE TABLE IF NOT EXISTS mapas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen LONGBLOB NOT NULL
);

-- Crear la tabla de armas
CREATE TABLE IF NOT EXISTS armas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen LONGBLOB NOT NULL
);