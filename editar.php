<?php
include("php/conexion.php");

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id <= 0) {
    header("Location: musculatura.php");
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM ejercicios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();

if (!$fila) {
    header("Location: musculatura.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar entrenamiento - Mi Gym</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>Zona de Musculación</h1>
</header>

<nav>
    <a href="index.html">Inicio</a>
    <a href="servicios.html">Servicios</a>
    <a href="musculatura.php">Musculación</a>
    <a href="nutricion.html">Nutrición</a>
    <a href="index.html#contacto">Contacto</a>
</nav>

<section>
    <h2>✏️ Editar entrenamiento</h2>

    <form action="php/actualizar.php" method="POST" class="form-entrenamiento">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($fila['id']); ?>">

        <div class="form-grid">
            <div class="form-group">
                <label for="ejercicio">Ejercicio</label>
                <input type="text" id="ejercicio" name="ejercicio" value="<?php echo htmlspecialchars($fila['ejercicio']); ?>" required>
            </div>

            <div class="form-group">
                <label for="series">Series</label>
                <input type="number" id="series" name="series" min="1" value="<?php echo htmlspecialchars($fila['series']); ?>" required>
            </div>

            <div class="form-group">
                <label for="repeticiones">Repeticiones</label>
                <input type="number" id="repeticiones" name="repeticiones" min="1" value="<?php echo htmlspecialchars($fila['repeticiones']); ?>" required>
            </div>

            <div class="form-group">
                <label for="peso">Peso (kg)</label>
                <input type="number" step="0.1" id="peso" name="peso" min="0" value="<?php echo htmlspecialchars($fila['peso']); ?>" required>
            </div>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <button type="submit" class="btn-guardar">💾 Guardar cambios</button>
            <a href="musculatura.php" class="boton cancelar" style="padding: 14px 25px; border-radius: 8px;">Cancelar</a>
        </div>
    </form>
</section>

</body>
</html>