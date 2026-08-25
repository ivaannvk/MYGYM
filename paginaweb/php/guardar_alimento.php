<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
    $nombre = trim($_POST["nombre"] ?? '');
    $calorias = floatval($_POST["calorias"] ?? 0);
    $proteinas = floatval($_POST["proteinas"] ?? 0);
    $carbohidratos = floatval($_POST["carbohidratos"] ?? 0);
    $grasas = floatval($_POST["grasas"] ?? 0);
    $categoria = trim($_POST["categoria"] ?? 'General');

    if (!empty($nombre)) {
        if ($id > 0) {
            // Actualizar alimento existente
            $stmt = $conexion->prepare("UPDATE alimentos SET nombre = ?, calorias = ?, proteinas = ?, carbohidratos = ?, grasas = ?, categoria = ? WHERE id = ?");
            $stmt->bind_param("sddddsi", $nombre, $calorias, $proteinas, $carbohidratos, $grasas, $categoria, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Insertar nuevo alimento
            $stmt = $conexion->prepare("INSERT INTO alimentos (nombre, calorias, proteinas, carbohidratos, grasas, categoria) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdddds", $nombre, $calorias, $proteinas, $carbohidratos, $grasas, $categoria);
            $stmt->execute();
            $stmt->close();
        }
    }
}

$conexion->close();
header("Location: ../alimentos.php");
exit();
?>
