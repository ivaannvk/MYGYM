<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
    $cliente_nombre = trim($_POST["cliente_nombre"] ?? '');
    $cliente_telefono = trim($_POST["cliente_telefono"] ?? '');
    $cliente_email = trim($_POST["cliente_email"] ?? '');
    $titulo = trim($_POST["titulo"] ?? 'Rutina Personalizada');
    $notas = trim($_POST["notas"] ?? '');

    $ejercicios_data = $_POST["ejercicios"] ?? []; // Array de ejercicios con sus series

    if (!empty($cliente_nombre) && count($ejercicios_data) > 0) {
        if ($id > 0) {
            // Actualizar rutina
            $stmt = $conexion->prepare("UPDATE rutinas SET cliente_nombre = ?, cliente_telefono = ?, cliente_email = ?, titulo = ?, notas = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $cliente_nombre, $cliente_telefono, $cliente_email, $titulo, $notas, $id);
            $stmt->execute();
            $stmt->close();

            // Eliminar series y ejercicios previos
            $stmtDelS = $conexion->prepare("DELETE FROM rutina_series WHERE rutina_id = ?");
            $stmtDelS->bind_param("i", $id);
            $stmtDelS->execute();
            $stmtDelS->close();

            $stmtDel = $conexion->prepare("DELETE FROM rutina_ejercicios WHERE rutina_id = ?");
            $stmtDel->bind_param("i", $id);
            $stmtDel->execute();
            $stmtDel->close();

            $rutina_id = $id;
        } else {
            // Insertar nueva rutina
            $stmt = $conexion->prepare("INSERT INTO rutinas (cliente_nombre, cliente_telefono, cliente_email, titulo, notas) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $cliente_nombre, $cliente_telefono, $cliente_email, $titulo, $notas);
            $stmt->execute();
            $rutina_id = $stmt->insert_id;
            $stmt->close();
        }

        // Insertar ejercicios y sus series
        $stmtEj = $conexion->prepare("INSERT INTO rutina_ejercicios (rutina_id, ejercicio, series, repeticiones, peso, descanso, notas) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtSer = $conexion->prepare("INSERT INTO rutina_series (ejercicio_id, rutina_id, num_serie, peso, repeticiones, notas) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($ejercicios_data as $ejData) {
            $nombreEj = trim($ejData["nombre"] ?? '');
            if (!empty($nombreEj)) {
                $descanso = trim($ejData["descanso"] ?? '90s');
                $notasEj = trim($ejData["notas"] ?? '');
                $seriesList = $ejData["series"] ?? [];
                $numSeries = count($seriesList);

                // Representación resumen para compatibilidad
                $repsResumen = $seriesList[0]["reps"] ?? '10';
                $pesoResumen = $seriesList[0]["peso"] ?? '0';

                $stmtEj->bind_param("isissss", $rutina_id, $nombreEj, $numSeries, $repsResumen, $pesoResumen, $descanso, $notasEj);
                $stmtEj->execute();
                $ejercicio_id = $stmtEj->insert_id;

                // Insertar cada serie individual
                $num = 1;
                foreach ($seriesList as $ser) {
                    $pesoSerie = trim($ser["peso"] ?? '0');
                    $repsSerie = trim($ser["reps"] ?? '10');
                    $notaSerie = trim($ser["nota"] ?? '');

                    $stmtSer->bind_param("iiisss", $ejercicio_id, $rutina_id, $num, $pesoSerie, $repsSerie, $notaSerie);
                    $stmtSer->execute();
                    $num++;
                }
            }
        }

        $stmtEj->close();
        $stmtSer->close();
    }
}

$conexion->close();
header("Location: ../musculatura.php");
exit();
?>
