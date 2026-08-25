<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
    $cliente_nombre = trim($_POST["cliente_nombre"] ?? '');
    $cliente_telefono = trim($_POST["cliente_telefono"] ?? '');
    $cliente_email = trim($_POST["cliente_email"] ?? '');
    $titulo = trim($_POST["titulo"] ?? 'Plan Nutricional');
    $notas = trim($_POST["notas"] ?? '');

    $dias_data = $_POST["dias"] ?? []; // Array de días con sus comidas

    if (!empty($cliente_nombre) && count($dias_data) > 0) {
        $total_calorias_global = 0;
        $total_proteinas_global = 0;
        $total_carbos_global = 0;
        $total_grasas_global = 0;
        $num_dias = count($dias_data);

        // Calcular totales promedio/primer día
        foreach ($dias_data as $d) {
            foreach ($d["comidas"] ?? [] as $c) {
                $total_calorias_global += floatval($c["calorias"] ?? 0);
                $total_proteinas_global += floatval($c["proteinas"] ?? 0);
                $total_carbos_global += floatval($c["carbohidratos"] ?? 0);
                $total_grasas_global += floatval($c["grasas"] ?? 0);
            }
            break; // Tomamos el primer día como referencia principal de cabecera
        }

        if ($id > 0) {
            // Actualizar dieta
            $stmt = $conexion->prepare("UPDATE dietas SET cliente_nombre = ?, cliente_telefono = ?, cliente_email = ?, titulo = ?, notas = ?, total_calorias = ?, total_proteinas = ?, total_carbos = ?, total_grasas = ? WHERE id = ?");
            $stmt->bind_param("sssssddddi", $cliente_nombre, $cliente_telefono, $cliente_email, $titulo, $notas, $total_calorias_global, $total_proteinas_global, $total_carbos_global, $total_grasas_global, $id);
            $stmt->execute();
            $stmt->close();

            // Eliminar comidas y días previos
            $stmtDelC = $conexion->prepare("DELETE FROM dieta_comidas WHERE dieta_id = ?");
            $stmtDelC->bind_param("i", $id);
            $stmtDelC->execute();
            $stmtDelC->close();

            $stmtDelD = $conexion->prepare("DELETE FROM dieta_dias WHERE dieta_id = ?");
            $stmtDelD->bind_param("i", $id);
            $stmtDelD->execute();
            $stmtDelD->close();

            $dieta_id = $id;
        } else {
            // Insertar nueva dieta
            $stmt = $conexion->prepare("INSERT INTO dietas (cliente_nombre, cliente_telefono, cliente_email, titulo, notas, total_calorias, total_proteinas, total_carbos, total_grasas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssdddd", $cliente_nombre, $cliente_telefono, $cliente_email, $titulo, $notas, $total_calorias_global, $total_proteinas_global, $total_carbos_global, $total_grasas_global);
            $stmt->execute();
            $dieta_id = $stmt->insert_id;
            $stmt->close();
        }

        // Insertar días y sus comidas
        $stmtDia = $conexion->prepare("INSERT INTO dieta_dias (dieta_id, nombre_dia, orden) VALUES (?, ?, ?)");
        $stmtCom = $conexion->prepare("INSERT INTO dieta_comidas (dieta_id, dia_id, comida_tipo, alimento_nombre, gramos, calorias, proteinas, carbohidratos, grasas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $ordenDia = 1;
        foreach ($dias_data as $dData) {
            $nombreDia = trim($dData["nombre_dia"] ?? "Día $ordenDia");
            if (empty($nombreDia)) {
                $nombreDia = "Día $ordenDia";
            }

            $stmtDia->bind_param("isi", $dieta_id, $nombreDia, $ordenDia);
            $stmtDia->execute();
            $dia_id = $stmtDia->insert_id;

            // Insertar comidas de este día
            foreach ($dData["comidas"] ?? [] as $comData) {
                $nomAli = trim($comData["alimento_nombre"] ?? '');
                if (!empty($nomAli)) {
                    $tipo = trim($comData["comida_tipo"] ?? 'Comida');
                    $gramos = floatval($comData["gramos"] ?? 100);
                    $kcal = floatval($comData["calorias"] ?? 0);
                    $prot = floatval($comData["proteinas"] ?? 0);
                    $carb = floatval($comData["carbohidratos"] ?? 0);
                    $gras = floatval($comData["grasas"] ?? 0);

                    $stmtCom->bind_param("iissddddd", $dieta_id, $dia_id, $tipo, $nomAli, $gramos, $kcal, $prot, $carb, $gras);
                    $stmtCom->execute();
                }
            }
            $ordenDia++;
        }

        $stmtDia->close();
        $stmtCom->close();
    }
}

$conexion->close();
header("Location: ../nutricion.php");
exit();
?>
