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

// Obtener días u opciones de la dieta
$stmtDias = $conexion->prepare("SELECT * FROM dieta_dias WHERE dieta_id = ? ORDER BY orden ASC");
$stmtDias->bind_param("i", $id);
$stmtDias->execute();
$resDias = $stmtDias->get_result();
$dias = [];
while ($d = $resDias->fetch_assoc()) {
    $diaId = $d['id'];
    // Obtener comidas de este día
    $stmtCom = $conexion->prepare("SELECT * FROM dieta_comidas WHERE dieta_id = ? AND dia_id = ? ORDER BY id ASC");
    $stmtCom->bind_param("ii", $id, $diaId);
    $stmtCom->execute();
    $resCom = $stmtCom->get_result();
    $comidas = [];
    $totK = 0; $totP = 0; $totC = 0; $totG = 0;

    while ($c = $resCom->fetch_assoc()) {
        $comidas[] = $c;
        $totK += floatval($c['calorias']);
        $totP += floatval($c['proteinas']);
        $totC += floatval($c['carbohidratos']);
        $totG += floatval($c['grasas']);
    }
    $stmtCom->close();

    $d['comidas'] = $comidas;
    $d['total_calorias'] = $totK;
    $d['total_proteinas'] = $totP;
    $d['total_carbos'] = $totC;
    $d['total_grasas'] = $totG;
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
    $totK = 0; $totP = 0; $totC = 0; $totG = 0;
    while ($cg = $resComGen->fetch_assoc()) {
        $comidasGen[] = $cg;
        $totK += floatval($cg['calorias']);
        $totP += floatval($cg['proteinas']);
        $totC += floatval($cg['carbohidratos']);
        $totG += floatval($cg['grasas']);
    }
    $stmtComGen->close();

    $dias[] = [
        'id' => 0,
        'nombre_dia' => 'Menú Principal',
        'orden' => 1,
        'comidas' => $comidasGen,
        'total_calorias' => $totK,
        'total_proteinas' => $totP,
        'total_carbos' => $totC,
        'total_grasas' => $totG
    ];
}

// Generar mensaje formateado completo para WhatsApp (Todos los días)
$textoWACompleto = "🥗 *PLAN NUTRICIONAL - MI GYM*\n";
$textoWACompleto .= "━━━━━━━━━━━━━━━━━━━━\n";
$textoWACompleto .= "👤 *Cliente:* " . $dieta['cliente_nombre'] . "\n";
$textoWACompleto .= "📋 *Plan:* " . $dieta['titulo'] . "\n";
$textoWACompleto .= "📅 *Fecha:* " . date("d/m/Y", strtotime($dieta['fecha'])) . "\n\n";

foreach ($dias as $d) {
    $textoWACompleto .= "━━━━━━━━━━━━━━━━━━━━\n";
    $textoWACompleto .= "📅 *" . mb_strtoupper($d['nombre_dia'], 'UTF-8') . "*\n";
    $textoWACompleto .= "🔥 *" . round($d['total_calorias']) . " kcal* | 🥩 P: *" . round($d['total_proteinas'], 1) . "g* | 🍚 C: *" . round($d['total_carbos'], 1) . "g* | 🥑 G: *" . round($d['total_grasas'], 1) . "g*\n\n";

    // Agrupar comidas por toma
    $comidasAgr = [];
    foreach ($d['comidas'] as $c) {
        $comidasAgr[$c['comida_tipo']][] = $c;
    }

    foreach ($comidasAgr as $tipo => $items) {
        $textoWACompleto .= "👉 *" . $tipo . ":*\n";
        foreach ($items as $it) {
            $textoWACompleto .= "  • " . $it['alimento_nombre'] . " (" . $it['gramos'] . "g) -> " . round($it['calorias']) . " kcal [P: " . round($it['proteinas'], 1) . "g | C: " . round($it['carbohidratos'], 1) . "g | G: " . round($it['grasas'], 1) . "g]\n";
        }
        $textoWACompleto .= "\n";
    }
}

if (!empty($dieta['notas'])) {
    $textoWACompleto .= "━━━━━━━━━━━━━━━━━━━━\n";
    $textoWACompleto .= "📝 *Indicaciones/Pautas:*\n" . $dieta['notas'] . "\n";
}
$textoWACompleto .= "━━━━━━━━━━━━━━━━━━━━\n";
$textoWACompleto .= "¡A cumplir los objetivos con la mejor nutrición! 🥗💪";

// Formatear teléfono para WhatsApp
$telefonoLimpio = preg_replace('/[^0-9]/', '', $dieta['cliente_telefono']);
if (strlen($telefonoLimpio) === 9 && in_array(substr($telefonoLimpio, 0, 1), ['6', '7'])) {
    $telefonoLimpio = '34' . $telefonoLimpio;
}

$linkWACompleto = "https://api.whatsapp.com/send?phone=" . urlencode($telefonoLimpio) . "&text=" . urlencode($textoWACompleto);
$asuntoEmail = "Tu Plan Nutricional - " . $dieta['titulo'];
$linkEmail = "mailto:" . urlencode($dieta['cliente_email']) . "?subject=" . urlencode($asuntoEmail) . "&body=" . urlencode($textoWACompleto);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dieta de <?php echo htmlspecialchars($dieta['cliente_nombre']); ?> - Mi Gym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .ficha-plan {
            background: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            margin: 30px auto;
            max-width: 900px;
        }
        .ficha-header {
            border-bottom: 2px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 15px;
        }
        .ficha-titulo {
            font-size: 28px;
            color: #065f46;
            margin-bottom: 5px;
        }
        .ficha-cliente {
            font-size: 18px;
            color: #475569;
        }
        .banner-macros {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .macro-card {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }
        .macro-card h4 {
            font-size: 14px;
            color: #166534;
            margin-bottom: 6px;
        }
        .macro-card p {
            font-size: 22px;
            font-weight: bold;
            color: #15803d;
            margin: 0;
        }
        .bloque-comida {
            background: #f8fafc;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 18px;
            border-left: 5px solid #10b981;
        }
        .bloque-comida h4 {
            color: #0f766e;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .acciones-envio {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }
        .btn-wa {
            background: #25D366;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .2s;
        }
        .btn-wa:hover { background: #1ebe5d; }
        .btn-email {
            background: #0284c7;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .2s;
        }
        .btn-email:hover { background: #0369a1; }
        .btn-print {
            background: #475569;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .2s;
        }
        .btn-print:hover { background: #334155; }

        .selector-dias-view {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 25px;
            background: #f1f5f9;
            padding: 10px;
            border-radius: 12px;
        }
        .btn-dia-view {
            background: white;
            border: 1px solid #cbd5e1;
            color: #334155;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: .2s;
        }
        .btn-dia-view.activo {
            background: #10b981;
            color: white;
            border-color: #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .vista-dia-content {
            display: none;
        }
        .vista-dia-content.activo {
            display: block;
        }

        @media print {
            header, nav, footer, .acciones-envio, .btn-volver, .selector-dias-view {
                display: none !important;
            }
            .vista-dia-content {
                display: block !important;
                page-break-after: always;
            }
            body {
                background: white;
                color: black;
            }
            .ficha-plan {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Mi Gym - Plataforma de Nutrición</h1>
</header>

<nav>
    <a href="index.html">Inicio</a>
    <a href="musculatura.php">Rutinas</a>
    <a href="nutricion.php">Dietas</a>
    <a href="alimentos.php">Base de Alimentos</a>
    <a href="servicios.html">Servicios</a>
</nav>

<section>
    <div style="max-width: 900px; margin: 0 auto 20px auto;" class="btn-volver">
        <a href="nutricion.php" class="boton cancelar">⬅️ Volver a Dietas</a>
    </div>

    <div class="ficha-plan">
        <div class="acciones-envio">
            <a href="<?php echo $linkWACompleto; ?>" target="_blank" class="btn-wa">
                📲 Enviar Dieta Completa (WhatsApp)
            </a>
            <a href="<?php echo $linkEmail; ?>" class="btn-email">
                ✉️ Enviar por Email
            </a>
            <button onclick="window.print()" class="btn-print">
                🖨️ Imprimir / Guardar PDF
            </button>
        </div>

        <div class="ficha-header">
            <div>
                <h2 class="ficha-titulo">🥗 <?php echo htmlspecialchars($dieta['titulo']); ?></h2>
                <p class="ficha-cliente"><strong>Cliente:</strong> <?php echo htmlspecialchars($dieta['cliente_nombre']); ?></p>
                <?php if (!empty($dieta['cliente_telefono'])): ?>
                    <p style="color: #64748b; font-size: 14px;"><strong>Teléfono:</strong> <?php echo htmlspecialchars($dieta['cliente_telefono']); ?></p>
                <?php endif; ?>
                <?php if (!empty($dieta['cliente_email'])): ?>
                    <p style="color: #64748b; font-size: 14px;"><strong>Email:</strong> <?php echo htmlspecialchars($dieta['cliente_email']); ?></p>
                <?php endif; ?>
            </div>
            <div style="text-align: right; color: #64748b; font-size: 14px;">
                <p><strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($dieta['fecha'])); ?></p>
                <p style="margin-top: 5px; color: #10b981; font-weight: bold;">Mi Gym Nutrition</p>
            </div>
        </div>

        <!-- TOTALES GLOBALES DEL PLAN / OPCIÓN SELECCIONADA (ARRIBA DEL TODO) -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                <h3 style="color: #065f46; margin: 0; font-size: 19px; display: flex; align-items: center; gap: 8px;">
                    📊 Balance Nutricional Total: <span id="label_dia_totales" style="color: #047857; font-weight: bold;"><?php echo htmlspecialchars($dias[0]['nombre_dia'] ?? 'Menú Principal'); ?></span>
                </h3>
                <span style="font-size: 13px; color: #64748b; background: #e2e8f0; padding: 4px 10px; border-radius: 20px; font-weight: 500;">Totales acumulados</span>
            </div>

            <div class="banner-macros" style="margin-bottom: 0;">
                <div class="macro-card">
                    <h4>🔥 Calorías Totales</h4>
                    <p id="top_total_kcal"><?php echo round($dias[0]['total_calorias'] ?? $dieta['total_calorias']); ?> <small style="font-size: 14px;">kcal</small></p>
                </div>
                <div class="macro-card">
                    <h4>🥩 Proteínas Totales</h4>
                    <p id="top_total_prot"><?php echo round($dias[0]['total_proteinas'] ?? $dieta['total_proteinas'], 1); ?> <small style="font-size: 14px;">g</small></p>
                </div>
                <div class="macro-card">
                    <h4>🍚 Carbohidratos Totales (HC)</h4>
                    <p id="top_total_carb"><?php echo round($dias[0]['total_carbos'] ?? $dieta['total_carbos'], 1); ?> <small style="font-size: 14px;">g</small></p>
                </div>
                <div class="macro-card">
                    <h4>🥑 Grasas Totales</h4>
                    <p id="top_total_gras"><?php echo round($dias[0]['total_grasas'] ?? $dieta['total_grasas'], 1); ?> <small style="font-size: 14px;">g</small></p>
                </div>
            </div>
        </div>

        <!-- SELECTOR INTERACTIVO DE DÍAS / OPCIONES -->
        <h3 style="color: #1e3a8a; margin-bottom: 12px;">📅 Opciones de Menú Disponibles:</h3>
        <div class="selector-dias-view">
            <?php foreach ($dias as $index => $d): ?>
                <button type="button" class="btn-dia-view <?php echo ($index === 0) ? 'activo' : ''; ?>" onclick="cambiarDiaVista(<?php echo $index; ?>)" id="btn_dia_view_<?php echo $index; ?>">
                    📅 <?php echo htmlspecialchars($d['nombre_dia']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- CONTENIDO DE CADA DÍA / OPCIÓN -->
        <?php foreach ($dias as $index => $d): ?>
            <?php
            // Generar enlace WhatsApp específico para este día
            $textoWADia = "🥗 *PLAN NUTRICIONAL - MI GYM*\n";
            $textoWADia .= "👤 *Cliente:* " . $dieta['cliente_nombre'] . "\n";
            $textoWADia .= "📅 *" . mb_strtoupper($d['nombre_dia'], 'UTF-8') . "*\n";
            $textoWADia .= "🔥 *" . round($d['total_calorias']) . " kcal* | 🥩 P: *" . round($d['total_proteinas'], 1) . "g* | 🍚 C: *" . round($d['total_carbos'], 1) . "g* | 🥑 G: *" . round($d['total_grasas'], 1) . "g*\n\n";

            $comidasAgrDia = [];
            foreach ($d['comidas'] as $c) {
                $comidasAgrDia[$c['comida_tipo']][] = $c;
            }

            foreach ($comidasAgrDia as $tipo => $items) {
                $textoWADia .= "👉 *" . $tipo . ":*\n";
                foreach ($items as $it) {
                    $textoWADia .= "  • " . $it['alimento_nombre'] . " (" . $it['gramos'] . "g) -> " . round($it['calorias']) . " kcal\n";
                }
                $textoWADia .= "\n";
            }
            $linkWADia = "https://api.whatsapp.com/send?phone=" . urlencode($telefonoLimpio) . "&text=" . urlencode($textoWADia);
            ?>

            <div class="vista-dia-content <?php echo ($index === 0) ? 'activo' : ''; ?>" id="vista_dia_<?php echo $index; ?>">
                
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:15px;">
                    <h3 style="color: #065f46; margin:0;">Menú: <?php echo htmlspecialchars($d['nombre_dia']); ?></h3>
                    <a href="<?php echo $linkWADia; ?>" target="_blank" class="boton" style="background:#25D366; font-size:13px; padding:6px 14px;">
                        📲 Enviar solo este día a WhatsApp
                    </a>
                </div>

                <div class="banner-macros">
                    <div class="macro-card">
                        <h4>🔥 Calorías</h4>
                        <p><?php echo round($d['total_calorias']); ?> <small style="font-size: 14px;">kcal</small></p>
                    </div>
                    <div class="macro-card">
                        <h4>🥩 Proteínas</h4>
                        <p><?php echo round($d['total_proteinas'], 1); ?> <small style="font-size: 14px;">g</small></p>
                    </div>
                    <div class="macro-card">
                        <h4>🍚 Carbohidratos</h4>
                        <p><?php echo round($d['total_carbos'], 1); ?> <small style="font-size: 14px;">g</small></p>
                    </div>
                    <div class="macro-card">
                        <h4>🥑 Grasas</h4>
                        <p><?php echo round($d['total_grasas'], 1); ?> <small style="font-size: 14px;">g</small></p>
                    </div>
                </div>

                <?php if (count($comidasAgrDia) > 0): ?>
                    <?php foreach ($comidasAgrDia as $tipo => $items): ?>
                        <div class="bloque-comida">
                            <h4>🍴 <?php echo htmlspecialchars($tipo); ?></h4>
                            <table style="background: white; border-radius: 8px;">
                                <thead>
                                    <tr>
                                        <th>Alimento</th>
                                        <th>Cantidad</th>
                                        <th>Calorías</th>
                                        <th>Proteínas</th>
                                        <th>Carbos</th>
                                        <th>Grasas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $it): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($it['alimento_nombre']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($it['gramos']); ?> g</td>
                                            <td><?php echo round($it['calorias'], 1); ?> kcal</td>
                                            <td><?php echo round($it['proteinas'], 1); ?> g</td>
                                            <td><?php echo round($it['carbohidratos'], 1); ?> g</td>
                                            <td><?php echo round($it['grasas'], 1); ?> g</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; padding: 20px; color: #64748b;">No hay comidas añadidas a este día.</p>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

        <?php if (!empty($dieta['notas'])): ?>
            <div style="background: #f8fafc; border-left: 4px solid #10b981; padding: 18px; border-radius: 8px; margin-top: 25px;">
                <h4 style="color: #065f46; margin-bottom: 8px;">📝 Pautas y Recomendaciones:</h4>
                <p style="white-space: pre-line; color: #334155; font-size: 15px;"><?php echo htmlspecialchars($dieta['notas']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<footer>
    <p style="text-align: center; padding: 25px; color: #777;">© 2026 Mi Gym - Planes Nutricionales Personalizados</p>
</footer>

<script>
const diasData = <?php echo json_encode($dias); ?>;

function cambiarDiaVista(index) {
    let allBtns = document.querySelectorAll(".btn-dia-view");
    allBtns.forEach(b => b.classList.remove("activo"));

    let allContents = document.querySelectorAll(".vista-dia-content");
    allContents.forEach(c => c.classList.remove("activo"));

    let targetBtn = document.getElementById(`btn_dia_view_${index}`);
    let targetContent = document.getElementById(`vista_dia_${index}`);

    if (targetBtn && targetContent) {
        targetBtn.classList.add("activo");
        targetContent.classList.add("activo");
    }

    if (diasData && diasData[index]) {
        let d = diasData[index];
        let lbl = document.getElementById("label_dia_totales");
        if (lbl) lbl.textContent = d.nombre_dia;
        
        let kSpan = document.getElementById("top_total_kcal");
        let pSpan = document.getElementById("top_total_prot");
        let cSpan = document.getElementById("top_total_carb");
        let gSpan = document.getElementById("top_total_gras");

        if (kSpan) kSpan.innerHTML = Math.round(d.total_calorias) + ' <small style="font-size: 14px;">kcal</small>';
        if (pSpan) pSpan.innerHTML = parseFloat(d.total_proteinas).toFixed(1) + ' <small style="font-size: 14px;">g</small>';
        if (cSpan) cSpan.innerHTML = parseFloat(d.total_carbos).toFixed(1) + ' <small style="font-size: 14px;">g</small>';
        if (gSpan) gSpan.innerHTML = parseFloat(d.total_grasas).toFixed(1) + ' <small style="font-size: 14px;">g</small>';
    }
}
</script>

</body>
</html>
