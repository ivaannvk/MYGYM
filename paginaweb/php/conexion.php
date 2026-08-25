<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$basedatos = "migym";

// Conectar al servidor MySQL
$conexion = new mysqli($servidor, $usuario, $contrasena);

if ($conexion->connect_error) {
    die("Error de conexión con el servidor: " . $conexion->connect_error);
}

// Crear base de datos si no existe
$conexion->query("CREATE DATABASE IF NOT EXISTS `$basedatos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conexion->select_db($basedatos);
$conexion->set_charset("utf8mb4");

// 1. Tabla de Alimentos
$conexion->query("
CREATE TABLE IF NOT EXISTS alimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    calorias DECIMAL(6,2) NOT NULL DEFAULT 0,
    proteinas DECIMAL(5,2) NOT NULL DEFAULT 0,
    carbohidratos DECIMAL(5,2) NOT NULL DEFAULT 0,
    grasas DECIMAL(5,2) NOT NULL DEFAULT 0,
    categoria VARCHAR(60) NOT NULL DEFAULT 'General',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$checkCol = $conexion->query("SHOW COLUMNS FROM alimentos LIKE 'categoria'");
if ($checkCol && $checkCol->num_rows == 0) {
    $conexion->query("ALTER TABLE alimentos ADD COLUMN categoria VARCHAR(60) NOT NULL DEFAULT 'General' AFTER grasas");
}

// 2. Tabla de Rutinas (Cabecera)
$conexion->query("
CREATE TABLE IF NOT EXISTS rutinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_nombre VARCHAR(120) NOT NULL,
    cliente_telefono VARCHAR(30) DEFAULT '',
    cliente_email VARCHAR(120) DEFAULT '',
    titulo VARCHAR(150) NOT NULL,
    notas TEXT DEFAULT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// 3. Tabla de Ejercicios por Rutina
$conexion->query("
CREATE TABLE IF NOT EXISTS rutina_ejercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rutina_id INT NOT NULL,
    ejercicio VARCHAR(150) NOT NULL,
    series INT NOT NULL DEFAULT 3,
    repeticiones VARCHAR(60) NOT NULL DEFAULT '10',
    peso VARCHAR(60) DEFAULT '',
    descanso VARCHAR(60) DEFAULT '',
    notas VARCHAR(255) DEFAULT '',
    FOREIGN KEY (rutina_id) REFERENCES rutinas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// 4. Tabla de Series Individuales por Ejercicio
$conexion->query("
CREATE TABLE IF NOT EXISTS rutina_series (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ejercicio_id INT NOT NULL,
    rutina_id INT NOT NULL,
    num_serie INT NOT NULL DEFAULT 1,
    peso VARCHAR(60) NOT NULL DEFAULT '0',
    repeticiones VARCHAR(60) NOT NULL DEFAULT '10',
    notas VARCHAR(100) DEFAULT '',
    FOREIGN KEY (ejercicio_id) REFERENCES rutina_ejercicios(id) ON DELETE CASCADE,
    FOREIGN KEY (rutina_id) REFERENCES rutinas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// 5. Tabla de Dietas (Cabecera)
$conexion->query("
CREATE TABLE IF NOT EXISTS dietas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_nombre VARCHAR(120) NOT NULL,
    cliente_telefono VARCHAR(30) DEFAULT '',
    cliente_email VARCHAR(120) DEFAULT '',
    titulo VARCHAR(150) NOT NULL,
    notas TEXT DEFAULT NULL,
    total_calorias DECIMAL(7,2) DEFAULT 0,
    total_proteinas DECIMAL(6,2) DEFAULT 0,
    total_carbos DECIMAL(6,2) DEFAULT 0,
    total_grasas DECIMAL(6,2) DEFAULT 0,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// 6. Tabla de Días / Opciones de Menú por Dieta
$conexion->query("
CREATE TABLE IF NOT EXISTS dieta_dias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dieta_id INT NOT NULL,
    nombre_dia VARCHAR(120) NOT NULL DEFAULT 'Día 1',
    orden INT NOT NULL DEFAULT 1,
    FOREIGN KEY (dieta_id) REFERENCES dietas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// 7. Tabla de Comidas / Alimentos por Dieta y Día
$conexion->query("
CREATE TABLE IF NOT EXISTS dieta_comidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dieta_id INT NOT NULL,
    dia_id INT DEFAULT NULL,
    comida_tipo VARCHAR(60) NOT NULL DEFAULT 'Desayuno',
    alimento_nombre VARCHAR(120) NOT NULL,
    gramos DECIMAL(6,2) NOT NULL DEFAULT 100,
    calorias DECIMAL(6,2) NOT NULL DEFAULT 0,
    proteinas DECIMAL(5,2) NOT NULL DEFAULT 0,
    carbohidratos DECIMAL(5,2) NOT NULL DEFAULT 0,
    grasas DECIMAL(5,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (dieta_id) REFERENCES dietas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$checkDiaId = $conexion->query("SHOW COLUMNS FROM dieta_comidas LIKE 'dia_id'");
if ($checkDiaId && $checkDiaId->num_rows == 0) {
    $conexion->query("ALTER TABLE dieta_comidas ADD COLUMN dia_id INT DEFAULT NULL AFTER dieta_id");
}

// Sembrar alimentos iniciales si la tabla está vacía
$resAlimentos = $conexion->query("SELECT COUNT(*) AS total FROM alimentos");
if ($resAlimentos) {
    $row = $resAlimentos->fetch_assoc();
    if ($row['total'] == 0) {
        $conexion->query("
        INSERT INTO alimentos (nombre, calorias, proteinas, carbohidratos, grasas, categoria) VALUES
        ('Pechuga de pollo', 165.00, 31.00, 0.00, 3.60, 'Carnes'),
        ('Arroz blanco', 130.00, 2.70, 28.00, 0.30, 'Cereales'),
        ('Avena', 389.00, 16.90, 66.30, 6.90, 'Cereales'),
        ('Huevo entero', 155.00, 13.00, 1.10, 11.00, 'Huevos'),
        ('Clara de huevo', 52.00, 11.00, 0.70, 0.20, 'Huevos'),
        ('Atún al natural', 116.00, 26.00, 0.00, 1.00, 'Pescados'),
        ('Salmón', 208.00, 20.00, 0.00, 13.00, 'Pescados'),
        ('Ternera magra', 250.00, 26.00, 0.00, 15.00, 'Carnes'),
        ('Plátano', 89.00, 1.10, 23.00, 0.30, 'Frutas'),
        ('Manzana', 52.00, 0.30, 14.00, 0.20, 'Frutas'),
        ('Patata cocida', 87.00, 1.90, 20.00, 0.10, 'Tubérculos'),
        ('Aceite de oliva virgen extra', 884.00, 0.00, 0.00, 100.00, 'Grasas saludables'),
        ('Nueces', 654.00, 15.20, 13.70, 65.20, 'Frutos secos'),
        ('Queso fresco batido 0%', 46.00, 8.50, 3.50, 0.10, 'Lácteos'),
        ('Proteína Whey en polvo', 390.00, 80.00, 6.00, 5.00, 'Suplementación');
        ");
    }
}
?>