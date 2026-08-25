<?php
include("conexion.php");
header('Content-Type: application/json');

$sql = "SELECT * FROM alimentos ORDER BY nombre ASC";
$resultado = $conexion->query($sql);

$alimentos = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $alimentos[] = [
            'id' => intval($fila['id']),
            'nombre' => $fila['nombre'],
            'calorias' => floatval($fila['calorias'] ?? 0),
            'proteinas' => floatval($fila['proteinas'] ?? 0),
            'carbohidratos' => floatval($fila['carbohidratos'] ?? 0),
            'grasas' => floatval($fila['grasas'] ?? 0),
            'categoria' => $fila['categoria'] ?? 'General'
        ];
    }
}

$conexion->close();
echo json_encode($alimentos);
?>
