<?php
include("php/conexion.php");

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($id <= 0) {
    header("Location: nutricion.php");
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM dietas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$dieta = $res->fetch_assoc();
$stmt->close();

if (!$dieta) {
    header("Location: nutricion.php");
    exit();
}

// Obtener lista de alimentos de la base de datos
$resAlimentos = $conexion->query("SELECT * FROM alimentos ORDER BY nombre ASC");
$listaAlimentos = [];
while ($a = $resAlimentos->fetch_assoc()) {
    $listaAlimentos[] = $a;
}

// Obtener días u opciones de la dieta
$stmtDias = $conexion->prepare("SELECT * FROM dieta_dias WHERE dieta_id = ? ORDER BY orden ASC");
$stmtDias->bind_param("i", $id);
$stmtDias->execute();
$resDias = $stmtDias->get_result();
$dias = [];

while ($d = $resDias->fetch_assoc()) {
    $diaId = $d['id'];
    $stmtCom = $conexion->prepare("SELECT * FROM dieta_comidas WHERE dieta_id = ? AND dia_id = ? ORDER BY id ASC");
    $stmtCom->bind_param("ii", $id, $diaId);
    $stmtCom->execute();
    $resCom = $stmtCom->get_result();
    $comidas = [];
    while ($c = $resCom->fetch_assoc()) {
        $comidas[] = $c;
    }
    $stmtCom->close();
    $d['comidas'] = $comidas;
    $dias[] = $d;
}
$stmtDias->close();

// Si no había días específicos, buscar comidas generales (compatibilidad)
if (count($dias) === 0) {
    $stmtComGen = $conexion->prepare("SELECT * FROM dieta_comidas WHERE dieta_id = ? ORDER BY id ASC");
    $stmtComGen->bind_param("i", $id);
    $stmtComGen->execute();
    $resComGen = $stmtComGen->get_result();
    $comidasGen = [];
    while ($cg = $resComGen->fetch_assoc()) {
        $comidasGen[] = $cg;
    }
    $stmtComGen->close();

    $dias[] = [
        'id' => 0,
        'nombre_dia' => 'Menú Principal',
        'orden' => 1,
        'comidas' => $comidasGen
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dieta de <?php echo htmlspecialchars($dieta['cliente_nombre']); ?> - Mi Gym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .tabs-dias-nav {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            align-items: center;
        }
        .tab-dia-btn {
            background: #e2e8f0;
            color: #334155;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: .2s;
        }
        .tab-dia-btn.activo {
            background: #10b981;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .bloque-dia-contenido {
            display: none;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #10b981;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 25px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.03);
        }
        .bloque-dia-contenido.activo {
            display: block;
        }
        .resumen-flotante {
            background: #f0fdf4;
            border: 2px solid #86efac;
            border-radius: 12px;
            padding: 15px 20px;
            margin: 15px 0 20px 0;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 15px;
            text-align: center;
        }
        .resumen-flotante h4 { margin: 0 0 5px 0; color: #166534; font-size: 13px; }
        .resumen-flotante span { font-size: 22px; font-weight: bold; color: #15803d; }
    </style>
</head>
<body>

<header>
    <h1>Editar Plan Nutricional</h1>
</header>

<nav>
    <a href="index.html">Inicio</a>
    <a href="musculatura.php">Rutinas</a>
    <a href="nutricion.php">Dietas</a>
    <a href="alimentos.php">Base de Alimentos</a>
    <a href="servicios.html">Servicios</a>
</nav>

<section>
    <div style="max-width: 1050px; margin: 0 auto 20px auto;">
        <a href="nutricion.php" class="boton cancelar">⬅️ Cancelar y Volver</a>
    </div>

    <form action="php/guardar_dieta.php" method="POST" class="form-entrenamiento" style="max-width: 1050px; margin: 0 auto;">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($dieta['id']); ?>">

        <h2>👤 Datos del Cliente y Dieta</h2>
        <div class="form-grid">
            <div class="form-group">
                <label for="cliente_nombre">Nombre del Cliente *</label>
                <input type="text" id="cliente_nombre" name="cliente_nombre" value="<?php echo htmlspecialchars($dieta['cliente_nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="cliente_telefono">Teléfono / WhatsApp</label>
                <input type="text" id="cliente_telefono" name="cliente_telefono" value="<?php echo htmlspecialchars($dieta['cliente_telefono']); ?>">
            </div>

            <div class="form-group">
                <label for="cliente_email">Correo Electrónico</label>
                <input type="email" id="cliente_email" name="cliente_email" value="<?php echo htmlspecialchars($dieta['cliente_email']); ?>">
            </div>

            <div class="form-group">
                <label for="titulo">Título de la Dieta *</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($dieta['titulo']); ?>" required>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label for="notas">Pautas / Recomendaciones para el cliente</label>
            <textarea id="notas" name="notas" rows="3" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:8px;"><?php echo htmlspecialchars($dieta['notas']); ?></textarea>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
            <h3>📅 Días y Opciones de Menú</h3>
            <button type="button" onclick="agregarNuevoDia()" class="boton" style="background: #10b981;">➕ Añadir Día / Opción</button>
        </div>

        <div class="tabs-dias-nav" id="contenedorTabsDias"></div>

        <div id="contenedorDiasContenido"></div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-guardar" style="background:#10b981;">💾 Guardar Cambios de la Dieta</button>
            <a href="nutricion.php" class="boton cancelar" style="padding: 14px 25px;">Cancelar</a>
        </div>
    </form>
</section>

<footer>
    <p style="text-align: center; padding: 25px; color: #777;">© 2026 Mi Gym</p>
</footer>

<script>
const listaAlimentosGlobal = <?php echo json_encode($listaAlimentos); ?>;
const diasExistentes = <?php echo json_encode($dias); ?>;
let contadorDias = 0;

function agregarNuevoDia(nombreDia = null, comidasInit = null) {
    let diaIndex = contadorDias++;
    if (!nombreDia) {
        nombreDia = `Día ${diaIndex + 1} (Opción ${diaIndex + 1})`;
    }

    let tabsCont = document.getElementById("contenedorTabsDias");
    let contentCont = document.getElementById("contenedorDiasContenido");

    let tabBtn = document.createElement("button");
    tabBtn.type = "button";
    tabBtn.className = "tab-dia-btn";
    tabBtn.id = `tab_btn_dia_${diaIndex}`;
    tabBtn.innerHTML = `📅 <span id="label_tab_${diaIndex}">${nombreDia}</span>`;
    tabBtn.onclick = function() { seleccionarDia(diaIndex); };
    tabsCont.appendChild(tabBtn);

    let diaDiv = document.createElement("div");
    diaDiv.className = "bloque-dia-contenido";
    diaDiv.id = `dia_bloque_${diaIndex}`;

    diaDiv.innerHTML = `
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:15px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
            <div style="flex:1; min-width:250px;">
                <label><strong>Nombre del Día / Opción *</strong></label>
                <input type="text" name="dias[${diaIndex}][nombre_dia]" value="${nombreDia}" placeholder="Ej: Día 1 - Opción A..." oninput="actualizarLabelTab(${diaIndex}, this.value)" required style="margin:0; padding:10px; font-weight:bold; font-size:15px;">
            </div>
            <div>
                <button type="button" onclick="eliminarDia(${diaIndex})" style="background:#dc2626; color:white; border:none; padding:8px 14px; border-radius:8px; cursor:pointer; font-weight:bold; margin-top:20px;">🗑️ Quitar este Día</button>
            </div>
        </div>

        <div class="resumen-flotante" id="resumen_dia_${diaIndex}">
            <div><h4>🔥 Calorías</h4><span id="totKcal_${diaIndex}">0</span> kcal</div>
            <div><h4>🥩 Proteínas</h4><span id="totProt_${diaIndex}">0</span> g</div>
            <div><h4>🍚 Carbohidratos</h4><span id="totCarb_${diaIndex}">0</span> g</div>
            <div><h4>🥑 Grasas</h4><span id="totGras_${diaIndex}">0</span> g</div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <strong style="color:#065f46;">🍴 Alimentos y Tomas:</strong>
            <button type="button" onclick="agregarFilaComidaADia(${diaIndex})" class="boton" style="background:#10b981; padding:6px 14px; font-size:13px;">➕ Añadir Alimento</button>
        </div>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th style="width: 140px;">Toma</th>
                        <th>Alimento</th>
                        <th style="width: 95px;">Gramos</th>
                        <th style="width: 90px;">Calorías</th>
                        <th style="width: 80px;">Proteínas</th>
                        <th style="width: 80px;">Carbos</th>
                        <th style="width: 80px;">Grasas</th>
                        <th style="width: 50px; text-align: center;">Quitar</th>
                    </tr>
                </thead>
                <tbody id="tbody_comidas_dia_${diaIndex}"></tbody>
                <tfoot>
                    <tr style="background: #f0fdf4; font-weight: bold; border-top: 2px solid #10b981;">
                        <td colspan="3" style="text-align: right; color: #166534; font-size: 13px;">SUMA TOTAL DE ESTE MENÚ:</td>
                        <td style="color: #15803d; font-size: 13px;"><span id="tfootKcal_${diaIndex}">0</span> kcal</td>
                        <td style="color: #15803d; font-size: 13px;"><span id="tfootProt_${diaIndex}">0</span> g</td>
                        <td style="color: #15803d; font-size: 13px;"><span id="tfootCarb_${diaIndex}">0</span> g</td>
                        <td style="color: #15803d; font-size: 13px;"><span id="tfootGras_${diaIndex}">0</span> g</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    `;

    contentCont.appendChild(diaDiv);

    if (comidasInit && comidasInit.length > 0) {
        comidasInit.forEach(c => agregarFilaComidaADia(diaIndex, c.comida_tipo || c.tipo, c.alimento_nombre || c.alimento, c.gramos));
    } else {
        agregarFilaComidaADia(diaIndex, 'Desayuno', 'Avena', 60);
        agregarFilaComidaADia(diaIndex, 'Comida', 'Pechuga de pollo', 200);
    }

    seleccionarDia(diaIndex);
}

function actualizarLabelTab(diaIndex, valor) {
    let lbl = document.getElementById(`label_tab_${diaIndex}`);
    if (lbl) {
        lbl.textContent = valor || `Día ${diaIndex + 1}`;
    }
}

function seleccionarDia(diaIndex) {
    let allTabs = document.querySelectorAll(".tab-dia-btn");
    allTabs.forEach(t => t.classList.remove("activo"));

    let allBlocks = document.querySelectorAll(".bloque-dia-contenido");
    allBlocks.forEach(b => b.classList.remove("activo"));

    let targetTab = document.getElementById(`tab_btn_dia_${diaIndex}`);
    let targetBlock = document.getElementById(`dia_bloque_${diaIndex}`);

    if (targetTab && targetBlock) {
        targetTab.classList.add("activo");
        targetBlock.classList.add("activo");
    }
}

function eliminarDia(diaIndex) {
    let allBlocks = document.querySelectorAll(".bloque-dia-contenido");
    if (allBlocks.length > 1) {
        document.getElementById(`tab_btn_dia_${diaIndex}`).remove();
        document.getElementById(`dia_bloque_${diaIndex}`).remove();
        
        let firstRemaining = document.querySelector(".bloque-dia-contenido");
        if (firstRemaining) {
            let idFirst = firstRemaining.id.replace("dia_bloque_", "");
            seleccionarDia(parseInt(idFirst));
        }
    } else {
        alert("La dieta debe tener al menos un día u opción de menú.");
    }
}

function agregarFilaComidaADia(diaIndex, toma = 'Comida', alimentoSel = '', gramos = 100) {
    let tbody = document.getElementById(`tbody_comidas_dia_${diaIndex}`);
    let tr = document.createElement("tr");
    let comIndex = tbody.rows.length;

    let opciones = '<option value="">-- Seleccionar alimento --</option>';
    listaAlimentosGlobal.forEach(a => {
        let sel = (a.nombre === alimentoSel) ? 'selected' : '';
        opciones += `<option value="${a.nombre}" data-kcal="${a.calorias}" data-prot="${a.proteinas}" data-carb="${a.carbohidratos}" data-gras="${a.grasas}" ${sel}>${a.nombre} (${a.calorias} kcal/100g)</option>`;
    });

    tr.innerHTML = `
        <td>
            <select name="dias[${diaIndex}][comidas][${comIndex}][comida_tipo]" style="margin:0; padding:6px;">
                <option value="Desayuno" ${toma === 'Desayuno' ? 'selected' : ''}>Desayuno</option>
                <option value="Media mañana" ${toma === 'Media mañana' ? 'selected' : ''}>Media mañana</option>
                <option value="Comida" ${toma === 'Comida' ? 'selected' : ''}>Comida</option>
                <option value="Merienda" ${toma === 'Merienda' ? 'selected' : ''}>Merienda</option>
                <option value="Cena" ${toma === 'Cena' ? 'selected' : ''}>Cena</option>
                <option value="Snack / Pre-entreno" ${toma === 'Snack / Pre-entreno' ? 'selected' : ''}>Snack / Pre-entreno</option>
            </select>
        </td>
        <td>
            <select name="dias[${diaIndex}][comidas][${comIndex}][alimento_nombre]" onchange="actualizarValoresFilaDia(this, ${diaIndex})" style="margin:0; padding:6px;" required>
                ${opciones}
            </select>
        </td>
        <td>
            <input type="number" name="dias[${diaIndex}][comidas][${comIndex}][gramos]" value="${gramos}" min="1" oninput="actualizarValoresFilaDia(this, ${diaIndex})" required style="margin:0; padding:6px;">
        </td>
        <td><input type="number" step="0.1" name="dias[${diaIndex}][comidas][${comIndex}][calorias]" value="0" readonly style="margin:0; padding:6px; background:#f1f5f9; border:none; font-weight:bold;"></td>
        <td><input type="number" step="0.1" name="dias[${diaIndex}][comidas][${comIndex}][proteinas]" value="0" readonly style="margin:0; padding:6px; background:#f1f5f9; border:none;"></td>
        <td><input type="number" step="0.1" name="dias[${diaIndex}][comidas][${comIndex}][carbohidratos]" value="0" readonly style="margin:0; padding:6px; background:#f1f5f9; border:none;"></td>
        <td><input type="number" step="0.1" name="dias[${diaIndex}][comidas][${comIndex}][grasas]" value="0" readonly style="margin:0; padding:6px; background:#f1f5f9; border:none;"></td>
        <td style="text-align: center;"><button type="button" onclick="eliminarFilaComidaDia(this, ${diaIndex})" style="background:#fee2e2; color:#dc2626; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; font-weight:bold;">✕</button></td>
    `;

    tbody.appendChild(tr);

    let selElem = tr.querySelector("select[name*='alimento_nombre']");
    actualizarValoresFilaDia(selElem, diaIndex);
}

function eliminarFilaComidaDia(btn, diaIndex) {
    let tbody = document.getElementById(`tbody_comidas_dia_${diaIndex}`);
    if (tbody.rows.length > 1) {
        btn.closest("tr").remove();
        calcularTotalesDia(diaIndex);
    } else {
        alert("Cada día debe tener al menos un alimento.");
    }
}

function actualizarValoresFilaDia(elemento, diaIndex) {
    let row = elemento.closest("tr");
    let select = row.querySelector("select[name*='alimento_nombre']");
    let gramosInput = row.querySelector("input[name*='gramos']");
    
    let kcalInput = row.querySelector("input[name*='calorias']");
    let protInput = row.querySelector("input[name*='proteinas']");
    let carbInput = row.querySelector("input[name*='carbohidratos']");
    let grasInput = row.querySelector("input[name*='grasas']");

    let opt = select.selectedOptions[0];
    let gramos = parseFloat(gramosInput.value) || 0;

    if (opt && opt.dataset.kcal !== undefined) {
        let k100 = parseFloat(opt.dataset.kcal) || 0;
        let p100 = parseFloat(opt.dataset.prot) || 0;
        let c100 = parseFloat(opt.dataset.carb) || 0;
        let g100 = parseFloat(opt.dataset.gras) || 0;

        kcalInput.value = (k100 * gramos / 100).toFixed(1);
        protInput.value = (p100 * gramos / 100).toFixed(1);
        carbInput.value = (c100 * gramos / 100).toFixed(1);
        grasInput.value = (g100 * gramos / 100).toFixed(1);
    } else {
        kcalInput.value = 0;
        protInput.value = 0;
        carbInput.value = 0;
        grasInput.value = 0;
    }

    calcularTotalesDia(diaIndex);
}

function calcularTotalesDia(diaIndex) {
    let tbody = document.getElementById(`tbody_comidas_dia_${diaIndex}`);
    if (!tbody) return;

    let totalKcal = 0;
    let totalProt = 0;
    let totalCarb = 0;
    let totalGras = 0;

    for (let row of tbody.rows) {
        let kcal = parseFloat(row.querySelector("input[name*='calorias']").value) || 0;
        let prot = parseFloat(row.querySelector("input[name*='proteinas']").value) || 0;
        let carb = parseFloat(row.querySelector("input[name*='carbohidratos']").value) || 0;
        let gras = parseFloat(row.querySelector("input[name*='grasas']").value) || 0;

        totalKcal += kcal;
        totalProt += prot;
        totalCarb += carb;
        totalGras += gras;
    }

    let kSpan = document.getElementById(`totKcal_${diaIndex}`);
    let pSpan = document.getElementById(`totProt_${diaIndex}`);
    let cSpan = document.getElementById(`totCarb_${diaIndex}`);
    let gSpan = document.getElementById(`totGras_${diaIndex}`);

    if (kSpan) kSpan.textContent = Math.round(totalKcal);
    if (pSpan) pSpan.textContent = totalProt.toFixed(1);
    if (cSpan) cSpan.textContent = totalCarb.toFixed(1);
    if (gSpan) gSpan.textContent = totalGras.toFixed(1);

    let fk = document.getElementById(`tfootKcal_${diaIndex}`);
    let fp = document.getElementById(`tfootProt_${diaIndex}`);
    let fc = document.getElementById(`tfootCarb_${diaIndex}`);
    let fg = document.getElementById(`tfootGras_${diaIndex}`);

    if (fk) fk.textContent = Math.round(totalKcal);
    if (fp) fp.textContent = totalProt.toFixed(1);
    if (fc) fc.textContent = totalCarb.toFixed(1);
    if (fg) fg.textContent = totalGras.toFixed(1);
}

window.onload = function() {
    if (diasExistentes && diasExistentes.length > 0) {
        diasExistentes.forEach(d => {
            agregarNuevoDia(d.nombre_dia, d.comidas);
        });
    } else {
        agregarNuevoDia("Día 1 (Opción 1)");
    }
};
</script>

</body>
</html>
