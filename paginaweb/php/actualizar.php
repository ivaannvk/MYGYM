<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"] ?? 0);
    $ejercicio = trim($_POST["ejercicio"] ?? '');
    $series = intval($_POST["series"] ?? 0);
    $repeticiones = intval($_POST["repeticiones"] ?? 0);
    $peso = floatval($_POST["peso"] ?? 0);

    if ($id > 0 && !empty($ejercicio) && $series > 0 && $repeticiones > 0 && $peso >= 0) {
        $stmt = $conexion->prepare("UPDATE ejercicios SET ejercicio = ?, series = ?, repeticiones = ?, peso = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("siidi", $ejercicio, $series, $repeticiones, $peso, $id);
            if ($stmt->execute()) {
                $stmt->close();
                $conexion->close();
                header("Location: ../musculatura.php");
                exit();
            } else {
                echo "Error al actualizar: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error en la consulta: " . $conexion->error;
        }
    } else {
        echo "Datos inválidos. <a href='../musculatura.php'>Volver</a>";
    }
} else {
    header("Location: ../musculatura.php");
    exit();
}

$conexion->close();
?>