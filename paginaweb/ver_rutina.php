<?php
include("php/conexion.php");

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($id <= 0) {
    header("Location: musculatura.php");
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM rutinas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$rutina = $res->fetch_assoc();
$stmt->close();

if (!$rutina) {
    header("Location: musculatura.php");
    exit();
}

// Obtener ejercicios
$stmtEj = $conexion->prepare("SELECT * FROM rutina_ejercicios WHERE rutina_id = ? ORDER BY id ASC");
$stmtEj->bind_param("i", $id);
$stmtEj->execute();
$resEj = $stmtEj->get_result();
$ejercicios = [];

while ($row = $resEj->fetch_assoc()) {
    $ejId = $row['id'];
    // Obtener series individuales de este ejercicio
    $stmtS = $conexion->prepare("SELECT * FROM rutina_series WHERE ejercicio_id = ? ORDER BY num_serie ASC");
    $stmtS->bind_param("i", $ejId);
    $stmtS->execute();
    $resS = $stmtS->get_result();
    $seriesList = [];
    while ($s = $resS->fetch_assoc()) {
        $seriesList[] = $s;
    }
    $stmtS->close();
    
    $row['series_desglose'] = $seriesList;
    $ejercicios[] = $row;
}
$stmtEj->close();

// Generar mensaje para WhatsApp
$textoWA = "🏋️ *PLAN DE ENTRENAMIENTO - MI GYM*\n";
$textoWA .= "━━━━━━━━━━━━━━━━━━━━\n";
$textoWA .= "👤 *Cliente:* " . $rutina['cliente_nombre'] . "\n";
$textoWA .= "📋 *Rutina:* " . $rutina['titulo'] . "\n";
$textoWA .= "📅 *Fecha:* " . date("d/m/Y", strtotime($rutina['fecha'])) . "\n\n";
$textoWA .= "💪 *TABLA DE EJERCICIOS Y SERIES:*\n\n";

$num = 1;
foreach ($ejercicios as $ej) {
    $desc = !empty($ej['descanso']) ? " (Descanso: " . $ej['descanso'] . ")" : "";
    $textoWA .= "*" . $num . ". " . $ej['ejercicio'] . "*" . $desc . "\n";

    if (!empty($ej['series_desglose']) && count($ej['series_desglose']) > 0) {
        foreach ($ej['series_desglose'] as $s) {
            $pesoTxt = (!empty($s['peso']) && $s['peso'] !== '0') ? $s['peso'] . "kg" : "Peso corporal";
            $notaTxt = !empty($s['notas']) ? " [" . $s['notas'] . "]" : "";
            $textoWA .= "   • *Serie " . $s['num_serie'] . ":* " . $pesoTxt . " x " . $s['repeticiones'] . " reps" . $notaTxt . "\n";
        }
    } else {
        $textoWA .= "   • " . $ej['series'] . " series x " . $ej['repeticiones'] . " reps (" . $ej['peso'] . "kg)\n";
    }
    $textoWA .= "\n";
    $num++;
}

if (!empty($rutina['notas'])) {
    $textoWA .= "━━━━━━━━━━━━━━━━━━━━\n";
    $textoWA .= "📝 *Indicaciones/Notas:*\n" . $rutina['notas'] . "\n";
}
$textoWA .= "━━━━━━━━━━━━━━━━━━━━\n";
$textoWA .= "¡A darlo todo en cada serie! 💪🔥";

// Formatear teléfono para enlace WhatsApp
$telefonoLimpio = preg_replace('/[^0-9]/', '', $rutina['cliente_telefono']);
if (strlen($telefonoLimpio) === 9 && in_array(substr($telefonoLimpio, 0, 1), ['6', '7'])) {
    $telefonoLimpio = '34' . $telefonoLimpio;
}

$linkWhatsApp = "https://api.whatsapp.com/send?phone=" . urlencode($telefonoLimpio) . "&text=" . urlencode($textoWA);

// Enlace Mailto
$asuntoEmail = "Tu Plan de Entrenamiento - " . $rutina['titulo'];
$linkEmail = "mailto:" . urlencode($rutina['cliente_email']) . "?subject=" . urlencode($asuntoEmail) . "&body=" . urlencode($textoWA);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rutina de <?php echo htmlspecialchars($rutina['cliente_nombre']); ?> - Mi Gym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .ficha-plan {
            background: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            margin: 30px auto;
            max-width: 850px;
        }
        .ficha-header {
            border-bottom: 2px solid #2563eb;
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
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        .ficha-cliente {
            font-size: 18px;
            color: #475569;
        }
        .ejercicio-card-view {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #2563eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .ejercicio-card-view h4 {
            color: #1e3a8a;
            font-size: 20px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
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

        @media print {
            header, nav, footer, .acciones-envio, .btn-volver {
                display: none !important;
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
    <h1>Mi Gym - Plataforma de Entrenamiento</h1>
</header>

<nav>
    <a href="index.html">Inicio</a>
    <a href="musculatura.php">Rutinas</a>
    <a href="nutricion.php">Dietas</a>
    <a href="alimentos.php">Base de Alimentos</a>
    <a href="servicios.html">Servicios</a>
</nav>

<section>
    <div style="max-width: 850px; margin: 0 auto 20px auto;" class="btn-volver">
        <a href="musculatura.php" class="boton cancelar">⬅️ Volver a Rutinas</a>
    </div>

    <div class="ficha-plan">
        <div class="acciones-envio">
            <a href="<?php echo $linkWhatsApp; ?>" target="_blank" class="btn-wa">
                📲 Enviar por WhatsApp
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
                <h2 class="ficha-titulo">🏋️ <?php echo htmlspecialchars($rutina['titulo']); ?></h2>
                <p class="ficha-cliente"><strong>Cliente:</strong> <?php echo htmlspecialchars($rutina['cliente_nombre']); ?></p>
                <?php if (!empty($rutina['cliente_telefono'])): ?>
                    <p style="color: #64748b; font-size: 14px;"><strong>Teléfono:</strong> <?php echo htmlspecialchars($rutina['cliente_telefono']); ?></p>
                <?php endif; ?>
                <?php if (!empty($rutina['cliente_email'])): ?>
                    <p style="color: #64748b; font-size: 14px;"><strong>Email:</strong> <?php echo htmlspecialchars($rutina['cliente_email']); ?></p>
                <?php endif; ?>
            </div>
            <div style="text-align: right; color: #64748b; font-size: 14px;">
                <p><strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($rutina['fecha'])); ?></p>
                <p style="margin-top: 5px; color: #2563eb; font-weight: bold;">Mi Gym Training</p>
            </div>
        </div>

        <h3 style="color: #1e3a8a; margin-bottom: 15px;">Tabla de Ejercicios y Series</h3>

        <?php if (count($ejercicios) > 0): ?>
            <?php $i = 1; foreach ($ejercicios as $ej): ?>
                <div class="ejercicio-card-view">
                    <h4>
                        <span><?php echo $i++; ?>. <?php echo htmlspecialchars($ej['ejercicio']); ?></span>
                        <?php if (!empty($ej['descanso'])): ?>
                            <span style="font-size: 14px; font-weight: normal; background: #e0e7ff; color: #3730a3; padding: 4px 12px; border-radius: 20px;">⏱️ Descanso: <?php echo htmlspecialchars($ej['descanso']); ?></span>
                        <?php endif; ?>
                    </h4>

                    <table style="background: white; border-radius: 8px; margin-top: 10px;">
                        <thead>
                            <tr>
                                <th style="width: 90px; text-align: center;">Serie</th>
                                <th>Peso</th>
                                <th>Repeticiones</th>
                                <th>Pautas / Notas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ej['series_desglose']) && count($ej['series_desglose']) > 0): ?>
                                <?php foreach ($ej['series_desglose'] as $s): ?>
                                    <tr>
                                        <td style="text-align: center;"><strong style="background: #2563eb; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">Serie <?php echo $s['num_serie']; ?></strong></td>
                                        <td><strong><?php echo htmlspecialchars(!empty($s['peso']) && $s['peso'] !== '0' ? $s['peso'] . ' kg' : 'Peso corporal'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($s['repeticiones']); ?> reps</td>
                                        <td><span style="color: #64748b;"><?php echo htmlspecialchars(!empty($s['notas']) ? $s['notas'] : '-'); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td style="text-align: center;"><strong style="background: #2563eb; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;"><?php echo $ej['series']; ?> series</strong></td>
                                    <td><strong><?php echo htmlspecialchars($ej['peso']); ?> kg</strong></td>
                                    <td><?php echo htmlspecialchars($ej['repeticiones']); ?> reps</td>
                                    <td><span style="color: #64748b;">-</span></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; padding: 20px; color: #64748b;">No hay ejercicios registrados en esta rutina.</p>
        <?php endif; ?>

        <?php if (!empty($rutina['notas'])): ?>
            <div style="background: #f8fafc; border-left: 4px solid #2563eb; padding: 18px; border-radius: 8px; margin-top: 20px;">
                <h4 style="color: #1e3a8a; margin-bottom: 8px;">📝 Indicaciones y Notas:</h4>
                <p style="white-space: pre-line; color: #334155; font-size: 15px;"><?php echo htmlspecialchars($rutina['notas']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<footer>
    <p style="text-align: center; padding: 25px; color: #777;">© 2026 Mi Gym - Planes de Entrenamiento Personalizados</p>
</footer>

</body>
</html>
