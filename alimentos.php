<?php
include("php/conexion.php");

// Modo edición si se pasa un ID por GET
$editarAlimento = null;
if (isset($_GET['editar'])) {
    $idEditar = intval($_GET['editar']);
    $stmtEd = $conexion->prepare("SELECT * FROM alimentos WHERE id = ?");
    $stmtEd->bind_param("i", $idEditar);
    $stmtEd->execute();
    $resEd = $stmtEd->get_result();
    if ($resEd && $resEd->num_rows > 0) {
        $editarAlimento = $resEd->fetch_assoc();
    }
    $stmtEd->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base de Datos de Alimentos - Mi Gym</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Base de Datos de Alimentos</h1>
    <p>Añade, edita y gestiona tus propios alimentos y valores nutricionales por cada 100g</p>
</header>

<nav>
    <a href="index.html">Inicio</a>
    <a href="musculatura.php">Rutinas</a>
    <a href="nutricion.php">Dietas</a>
    <a href="alimentos.php">Base de Alimentos</a>
    <a href="servicios.html">Servicios</a>
</nav>

<section>

    <h2><?php echo $editarAlimento ? '✏️ Editar Alimento' : '➕ Añadir Nuevo Alimento a la Base de Datos'; ?></h2>

    <form action="php/guardar_alimento.php" method="POST" class="form-entrenamiento">
        <?php if ($editarAlimento): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($editarAlimento['id']); ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-group" style="grid-column: span 2;">
                <label for="nombre">Nombre del Alimento *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Pechuga de pavo, Queso cottage..." value="<?php echo htmlspecialchars($editarAlimento['nombre'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="categoria">Categoría</label>
                <input type="text" id="categoria" name="categoria" placeholder="Ej: Carnes, Frutas, Lácteos..." value="<?php echo htmlspecialchars($editarAlimento['categoria'] ?? 'General'); ?>">
            </div>

            <div class="form-group">
                <label for="calorias">Calorías (kcal / 100g) *</label>
                <input type="number" step="0.1" id="calorias" name="calorias" min="0" placeholder="Ej: 165" value="<?php echo htmlspecialchars($editarAlimento['calorias'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="proteinas">Proteínas (g / 100g) *</label>
                <input type="number" step="0.1" id="proteinas" name="proteinas" min="0" placeholder="Ej: 31" value="<?php echo htmlspecialchars($editarAlimento['proteinas'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="carbohidratos">Carbohidratos (g / 100g) *</label>
                <input type="number" step="0.1" id="carbohidratos" name="carbohidratos" min="0" placeholder="Ej: 0" value="<?php echo htmlspecialchars($editarAlimento['carbohidratos'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="grasas">Grasas (g / 100g) *</label>
                <input type="number" step="0.1" id="grasas" name="grasas" min="0" placeholder="Ej: 3.6" value="<?php echo htmlspecialchars($editarAlimento['grasas'] ?? ''); ?>" required>
            </div>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <button type="submit" class="btn-guardar">
                <?php echo $editarAlimento ? '💾 Guardar Cambios' : '➕ Guardar Alimento'; ?>
            </button>
            <?php if ($editarAlimento): ?>
                <a href="alimentos.php" class="boton cancelar" style="padding: 14px 25px;">Cancelar</a>
            <?php endif; ?>
        </div>
    </form>

    <hr style="margin: 35px 0; border: 0; border-top: 1px solid #e2e8f0;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
        <h2>📋 Alimentos Registrados</h2>
        <input type="text" id="filtroAlimento" placeholder="🔍 Buscar alimento..." onkeyup="filtrarTabla()" style="max-width: 300px; padding: 10px; margin: 0; border: 1px solid #cbd5e1; border-radius: 8px;">
    </div>

    <div class="tabla-contenedor">
        <table id="tablaAlimentos">
            <thead>
                <tr>
                    <th>Alimento</th>
                    <th>Categoría</th>
                    <th>Calorías (100g)</th>
                    <th>Proteínas (100g)</th>
                    <th>Carbohidratos (100g)</th>
                    <th>Grasas (100g)</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM alimentos ORDER BY nombre ASC";
            $resultado = $conexion->query($sql);

            if ($resultado && $resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    $cat = !empty($fila["categoria"]) ? $fila["categoria"] : 'General';
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($fila["nombre"]) . "</strong></td>";
                    echo "<td><span style='background: #e2e8f0; padding: 4px 10px; border-radius: 12px; font-size: 13px;'>" . htmlspecialchars($cat) . "</span></td>";
                    echo "<td>" . htmlspecialchars($fila["calorias"]) . " kcal</td>";
                    echo "<td>" . htmlspecialchars($fila["proteinas"]) . " g</td>";
                    echo "<td>" . htmlspecialchars($fila["carbohidratos"]) . " g</td>";
                    echo "<td>" . htmlspecialchars($fila["grasas"]) . " g</td>";
                    echo "<td style='text-align: center; white-space: nowrap;'>";
                    echo "<a href='alimentos.php?editar=" . urlencode($fila["id"]) . "' class='boton editar'>✏️ Editar</a> ";
                    echo "<a href='php/eliminar_alimento.php?id=" . urlencode($fila["id"]) . "' class='boton eliminar' onclick='return confirm(\"¿Deseas eliminar este alimento?\")'>🗑️ Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center; padding: 30px; color: #666;'>No hay alimentos registrados en la base de datos.</td></tr>";
            }
            $conexion->close();
            ?>
            </tbody>
        </table>
    </div>

</section>

<footer>
    <p style="text-align: center; padding: 25px; color: #777;">© 2026 Mi Gym</p>
</footer>

<script>
function filtrarTabla() {
    let input = document.getElementById("filtroAlimento");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("tablaAlimentos");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let tdNombre = tr[i].getElementsByTagName("td")[0];
        let tdCategoria = tr[i].getElementsByTagName("td")[1];
        if (tdNombre || tdCategoria) {
            let txtNombre = tdNombre ? tdNombre.textContent || tdNombre.innerText : "";
            let txtCategoria = tdCategoria ? tdCategoria.textContent || tdCategoria.innerText : "";
            if (txtNombre.toLowerCase().indexOf(filter) > -1 || txtCategoria.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>

</body>
</html>
