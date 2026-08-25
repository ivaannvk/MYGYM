<?php
include("conexion.php");

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    if ($id > 0) {
        $stmt = $conexion->prepare("DELETE FROM dietas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

$conexion->close();
header("Location: ../nutricion.php");
exit();
?>
