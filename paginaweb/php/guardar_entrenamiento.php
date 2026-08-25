<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ejercicio = trim($_POST["ejercicio"] ?? '');
    $series = intval($_POST["series"] ?? 0);
    $repeticiones = intval($_POST["repeticiones"] ?? 0);
    $peso = floatval($_POST["peso"] ?? 0);

    if (!empty($ejercicio) && $series > 0 && $repeticiones > 0 && $peso >= 0) {
        $stmt = $conexion->prepare("INSERT INTO ejercicios (ejercicio, series, repeticiones, peso) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("siid", $ejercicio, $series, $repeticiones, $peso);
            if ($stmt->execute()) {
                $stmt->close();
                $conexion->close();
                header("Location: ../musculatura.php");
                exit();
            } else {
                echo "Error al guardar: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error en la consulta: " . $conexion->error;
        }
    } else {
        echo "Por favor, completa todos los campos con valores válidos. <a href='../musculatura.php'>Volver</a>";
    }
} else {
    header("Location: ../musculatura.php");
    exit();
}

$conexion->close();
?>