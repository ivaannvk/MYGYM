<?php
include("php/conexion.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rutinas - Mi Gym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .bloque-ejercicio {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #2563eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .header-ejercicio {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        .tabla-series-input th {
            background: #f1f5f9;
            color: #334155;
            font-size: 13px;
            padding: 8px;
        }
        .tabla-series-input td {
            padding: 6px;
        }
        .badge-serie {
            background: #2563eb;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>

<body>

<header>
    <h1>Gestor de Rutinas y Entrenamientos</h1>
    <p>Crea tablas de ejercicios con series personalizadas (peso y repeticiones por serie) y envíalas por WhatsApp</p>
</header>

<nav>
    <a href="index.html">Inicio</a>
    <a href="musculatura.php">Rutinas</a>
    <a href="nutricion.php">Dietas</a>
    <a href="alimentos.php">Base de Alimentos</a>
    <a href="servicios.html">Servicios</a>
</nav>

<section>

    <h2>➕ Crear Nueva Rutina para Cliente</h2>

    <form action="php/guardar_rutina.php" method="POST" class="form-entrenamiento">
        
        <div class="form-grid">
            <div class="form-group">
                <label for="cliente_nombre">Nombre del Cliente *</label>
                <input type="text" id="cliente_nombre" name="cliente_nombre" placeholder="Ej: Carlos López" required>
            </div>

            <div class="form-group">
                <label for="cliente_telefono">Teléfono / WhatsApp</label>
                <input type="text" id="cliente_telefono" name="cliente_telefono" placeholder="Ej: 600123456">
            </div>

            <div class="form-group">
                <label for="cliente_email">Correo Electrónico</label>
                <input type="email" id="cliente_email" name="cliente_email" placeholder="Ej: cliente@email.com">
            </div>

            <div class="form-group">
                <label for="titulo">Título / Objetivo de la Rutina *</label>
                <input type="text" id="titulo" name="titulo" placeholder="Ej: Hipertrofia Torso-Pierna (Día 1)" required>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label for="notas">Notas / Indicaciones generales para el cliente</label>
            <textarea id="notas" name="notas" rows="2" placeholder="Ej: Calentar 10 min en elíptica antes de empezar. Mantener descanso estricto..." style="width:100%; padding:10px; border:1px solid #ccc; border-radius:8px;"></textarea>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3>🏋️ Ejercicios y Desglose de Series</h3>
            <button type="button" onclick="agregarNuevoEjercicio()" class="boton" style="background: #10b981;">➕ Añadir Otro Ejercicio</button>
        </div>

        <!-- Contenedor de bloques de ejercicios -->
        <div id="contenedorEjercicios"></div>

        <div style="margin-top: 20px;">
            <button type="button" onclick="agregarNuevoEjercicio()" class="boton" style="background: #0284c7; margin-bottom: 20px;">➕ Añadir Otro Ejercicio</button>
            <br>
            <button type="submit" class="btn-guardar">💾 Guardar Rutina y Generar Enlaces</button>
        </div>
    </form>

    <hr style="margin: 40px 0; border: 0; border-top: 1px solid #e2e8f0;">

    <h2>📋 Rutinas de Clientes Creadas</h2>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Título de la Rutina</th>
                    <th>Contacto</th>
                    <th>Fecha</th>
                    <th style="text-align: center;">Acciones & Envío</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM rutinas ORDER BY id DESC";
            $resultado = $conexion->query($sql);

            if ($resultado && $resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    $idRutina = $fila["id"];
                    
                    // Contar ejercicios y series
                    $resCount = $conexion->query("SELECT COUNT(*) as num FROM rutina_ejercicios WHERE rutina_id = $idRutina");
                    $numEj = $resCount ? $resCount->fetch_assoc()['num'] : 0;

                    $resCountSer = $conexion->query("SELECT COUNT(*) as num FROM rutina_series WHERE rutina_id = $idRutina");
                    $numSer = $resCountSer ? $resCountSer->fetch_assoc()['num'] : 0;

                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($fila["cliente_nombre"]) . "</strong><br><small style='color:#64748b;'>$numEj ejercicios ($numSer series totales)</small></td>";
                    echo "<td><strong>" . htmlspecialchars($fila["titulo"]) . "</strong></td>";
                    echo "<td>";
                    if (!empty($fila["cliente_telefono"])) {
                        echo "📱 " . htmlspecialchars($fila["cliente_telefono"]) . "<br>";
                    }
                    if (!empty($fila["cliente_email"])) {
                        echo "✉️ " . htmlspecialchars($fila["cliente_email"]);
                    }
                    if (empty($fila["cliente_telefono"]) && empty($fila["cliente_email"])) {
                        echo "<span style='color:#94a3b8;'>Sin contacto</span>";
                    }
                    echo "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fila["fecha"])) . "</td>";
                    echo "<td style='text-align: center; white-space: nowrap;'>";
                    echo "<a href='ver_rutina.php?id=" . urlencode($fila["id"]) . "' class='boton' style='background:#2563eb;'>👁️ Ver / Enviar</a> ";
                    echo "<a href='editar_rutina.php?id=" . urlencode($fila["id"]) . "' class='boton editar'>✏️ Editar</a> ";
                    echo "<a href='php/eliminar_rutina.php?id=" . urlencode($fila["id"]) . "' class='boton eliminar' onclick='return confirm(\"¿Deseas eliminar la rutina de " . htmlspecialchars(addslashes($fila["cliente_nombre"])) . "?\")'>🗑️ Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center; padding: 35px; color: #64748b;'>No hay rutinas creadas todavía. ¡Crea la primera para tu cliente arriba!</td></tr>";
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
let contadorEjercicios = 0;

function agregarNuevoEjercicio(nombreEj = '', descansoEj = '90s', seriesInit = null) {
    let ejIndex = contadorEjercicios++;
    let cont = document.getElementById("contenedorEjercicios");

    let card = document.createElement("div");
    card.className = "bloque-ejercicio";
    card.id = `ejercicio_bloque_${ejIndex}`;

    card.innerHTML = `
        <div class="header-ejercicio">
            <div style="flex: 1; display: flex; gap: 15px; flex-wrap: wrap;">
                <div style="flex: 2; min-width: 200px;">
                    <label><strong>Nombre del Ejercicio *</strong></label>
                    <input type="text" name="ejercicios[${ejIndex}][nombre]" value="${nombreEj}" placeholder="Ej: Press banca con barra, Sentadillas..." required style="margin:0; padding:10px; font-weight:bold; font-size:15px;">
                </div>
                <div style="flex: 1; min-width: 140px;">
                    <label><strong>Descanso</strong></label>
                    <input type="text" name="ejercicios[${ejIndex}][descanso]" value="${descansoEj}" placeholder="Ej: 90s, 2 min" style="margin:0; padding:10px;">
                </div>
            </div>
            <div>
                <button type="button" onclick="eliminarEjercicio(${ejIndex})" style="background:#dc2626; color:white; border:none; padding:8px 14px; border-radius:8px; cursor:pointer; font-weight:bold; margin-top:20px;">🗑️ Quitar Ejercicio</button>
            </div>
        </div>

        <div style="background: #f8fafc; border-radius: 8px; padding: 12px; margin-top: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-weight: bold; color: #1e3a8a; font-size: 14px;">📊 Desglose de Series (Peso y Repeticiones por cada serie):</span>
                <button type="button" onclick="agregarSerieAEjercicio(${ejIndex})" class="boton" style="background:#2563eb; padding:6px 12px; font-size:13px;">➕ Añadir Serie</button>
            </div>

            <table class="tabla-series-input" style="background:white; border-radius:6px; width:100%;">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align:center;">Serie</th>
                        <th>Peso (kg / lbs)</th>
                        <th>Repeticiones</th>
                        <th>Notas / RPE</th>
                        <th style="width: 50px; text-align:center;">Quitar</th>
                    </tr>
                </thead>
                <tbody id="tbody_series_${ejIndex}"></tbody>
            </table>
        </div>
    `;

    cont.appendChild(card);

    if (seriesInit && seriesInit.length > 0) {
        seriesInit.forEach(s => agregarSerieAEjercicio(ejIndex, s.peso, s.reps, s.nota));
    } else {
        // Por defecto 3 series de ejemplo
        agregarSerieAEjercicio(ejIndex, '70', '12', 'Calentamiento');
        agregarSerieAEjercicio(ejIndex, '75', '10', 'Efectiva');
        agregarSerieAEjercicio(ejIndex, '80', '8', 'Al fallo');
    }
}

function agregarSerieAEjercicio(ejIndex, peso = '', reps = '10', nota = '') {
    let tbody = document.getElementById(`tbody_series_${ejIndex}`);
    let numSerie = tbody.rows.length + 1;
    let tr = document.createElement("tr");

    tr.innerHTML = `
        <td style="text-align:center;"><span class="badge-serie">S${numSerie}</span></td>
        <td><input type="text" name="ejercicios[${ejIndex}][series][${numSerie}][peso]" value="${peso}" placeholder="Ej: 72.5" style="margin:0; padding:6px; text-align:center; font-weight:bold;"></td>
        <td><input type="text" name="ejercicios[${ejIndex}][series][${numSerie}][reps]" value="${reps}" placeholder="Ej: 8-10" required style="margin:0; padding:6px; text-align:center;"></td>
        <td><input type="text" name="ejercicios[${ejIndex}][series][${numSerie}][nota]" value="${nota}" placeholder="Ej: RPE 8, Drop set..." style="margin:0; padding:6px;"></td>
        <td style="text-align:center;"><button type="button" onclick="eliminarSerieDeFila(this)" style="background:#fee2e2; color:#dc2626; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; font-weight:bold;">✕</button></td>
    `;
    tbody.appendChild(tr);
    renumerarSeries(tbody);
}

function eliminarSerieDeFila(btn) {
    let tbody = btn.closest("tbody");
    if (tbody.rows.length > 1) {
        btn.closest("tr").remove();
        renumerarSeries(tbody);
    } else {
        alert("El ejercicio debe tener al menos una serie.");
    }
}

function renumerarSeries(tbody) {
    for (let i = 0; i < tbody.rows.length; i++) {
        let badge = tbody.rows[i].querySelector(".badge-serie");
        if (badge) badge.textContent = `S${i + 1}`;
    }
}

function eliminarEjercicio(ejIndex) {
    let cont = document.getElementById("contenedorEjercicios");
    if (cont.children.length > 1) {
        document.getElementById(`ejercicio_bloque_${ejIndex}`).remove();
    } else {
        alert("La rutina debe tener al menos un ejercicio.");
    }
}

// Cargar 2 ejercicios por defecto al iniciar
window.onload = function() {
    agregarNuevoEjercicio("Press de banca plano con barra", "90s", [
        { peso: "70", reps: "12", nota: "Aproximación" },
        { peso: "75", reps: "10", nota: "RPE 8" },
        { peso: "80", reps: "8", nota: "RPE 9" },
        { peso: "82.5", reps: "6", nota: "Al fallo" }
    ]);

    agregarNuevoEjercicio("Remo con barra", "90s", [
        { peso: "60", reps: "10", nota: "RPE 8" },
        { peso: "65", reps: "8", nota: "RPE 8.5" },
        { peso: "70", reps: "6", nota: "Al fallo" }
    ]);
};
</script>

</body>
</html>