<?php
// Generador autónomo de PDF nativo para la Memoria del TFG
// No requiere librerías externas ni extensiones especiales. Genera un PDF 1.4 estándar válido.

class MiniPDF {
    private $pages = [];
    private $currentPage = 0;
    private $currentStream = '';
    private $font = 'F1';
    private $fontSize = 11;
    private $x = 50;
    private $y = 790;
    private $pageWidth = 595.28;  // A4
    private $pageHeight = 841.89; // A4
    private $leftMargin = 50;
    private $rightMargin = 50;
    private $topMargin = 50;
    private $bottomMargin = 50;
    private $r = 0; private $g = 0; private $b = 0;

    public function __construct() {
        $this->addPage();
    }

    public function addPage() {
        if ($this->currentPage > 0) {
            $this->pages[$this->currentPage] = $this->currentStream;
        }
        $this->currentPage++;
        $this->currentStream = '';
        $this->y = $this->pageHeight - $this->topMargin;
        $this->x = $this->leftMargin;

        // Pie de página con número de página
        $this->drawFooter();
    }

    private function drawFooter() {
        // Guardar estado actual
        $this->currentStream .= "q\n";
        $this->currentStream .= "0.5 0.5 0.5 rg\n";
        $this->currentStream .= "BT /F1 9 Tf 50 35 Td (Mi Gym - Trabajo Fin de Grado DAW 2026) Tj ET\n";
        $this->currentStream .= "0.7 0.7 0.7 RG 0.5 w 50 48 m 545 48 l S\n";
        $this->currentStream .= "Q\n";
    }

    public function setColor($r, $g, $b) {
        $this->r = $r / 255;
        $this->g = $g / 255;
        $this->b = $b / 255;
        $this->currentStream .= sprintf("%.2f %.2f %.2f rg\n", $this->r, $this->g, $this->b);
    }

    public function setFont($name = 'F1', $size = 11) {
        $this->font = $name;
        $this->fontSize = $size;
    }

    private function escapeText($text) {
        // Limpiar caracteres especiales para PDF estándar
        $text = str_replace(
            ['á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ','¿','¡'],
            ['a','e','i','o','u','A','E','I','O','U','n','N','?','!'],
            $text
        );
        $text = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $text);
        $text = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
        return $text;
    }

    public function checkPageBreak($requiredSpace = 25) {
        if (($this->y - $requiredSpace) < $this->bottomMargin) {
            $this->addPage();
        }
    }

    public function addTitle($text) {
        $this->checkPageBreak(45);
        $this->y -= 15;
        $this->setColor(30, 58, 138); // Azul institucional
        $this->setFont('F2', 18);
        $esc = $this->escapeText($text);
        $this->currentStream .= sprintf("BT /F2 18 Tf %d %d Td (%s) Tj ET\n", $this->x, $this->y, $esc);
        
        // Línea divisoria
        $this->currentStream .= sprintf("0.2 0.4 0.8 RG 1.5 w %d %d m %d %d l S\n", $this->x, $this->y - 5, $this->pageWidth - $this->rightMargin, $this->y - 5);
        $this->y -= 25;
        $this->setColor(30, 41, 59);
        $this->setFont('F1', 11);
    }

    public function addSubtitle($text) {
        $this->checkPageBreak(35);
        $this->y -= 10;
        $this->setColor(15, 23, 42);
        $this->setFont('F2', 13);
        $esc = $this->escapeText($text);
        $this->currentStream .= sprintf("BT /F2 13 Tf %d %d Td (%s) Tj ET\n", $this->x, $this->y, $esc);
        $this->y -= 18;
        $this->setColor(51, 65, 85);
        $this->setFont('F1', 10.5);
    }

    public function addParagraph($text) {
        $this->setColor(51, 65, 85);
        $this->setFont('F1', 10.5);
        $esc = $this->escapeText($text);
        
        // Dividir en líneas aproximadas de 85 caracteres
        $words = explode(' ', $esc);
        $line = '';
        foreach ($words as $w) {
            if (strlen($line . ' ' . $w) > 85) {
                $this->checkPageBreak(16);
                $this->currentStream .= sprintf("BT /%s %.1f Tf %d %d Td (%s) Tj ET\n", $this->font, $this->fontSize, $this->x, $this->y, $line);
                $this->y -= 14;
                $line = $w;
            } else {
                $line = empty($line) ? $w : $line . ' ' . $w;
            }
        }
        if (!empty($line)) {
            $this->checkPageBreak(16);
            $this->currentStream .= sprintf("BT /%s %.1f Tf %d %d Td (%s) Tj ET\n", $this->font, $this->fontSize, $this->x, $this->y, $line);
            $this->y -= 18;
        }
    }

    public function addBullet($text) {
        $this->checkPageBreak(18);
        $this->setColor(37, 99, 235);
        $this->currentStream .= sprintf("BT /F2 11 Tf %d %d Td (-) Tj ET\n", $this->x + 5, $this->y);
        
        $this->setColor(51, 65, 85);
        $esc = $this->escapeText($text);
        $this->currentStream .= sprintf("BT /F1 10.5 Tf %d %d Td (%s) Tj ET\n", $this->x + 18, $this->y, $esc);
        $this->y -= 15;
    }

    public function addTableRow($col1, $col2, $col3, $isHeader = false) {
        $this->checkPageBreak(20);
        $c1 = $this->escapeText($col1);
        $c2 = $this->escapeText($col2);
        $c3 = $this->escapeText($col3);

        if ($isHeader) {
            $this->currentStream .= sprintf("0.9 0.95 1 rg %d %d 495 16 re f\n", $this->x, $this->y - 3);
            $this->setColor(30, 58, 138);
            $font = 'F2';
        } else {
            $this->setColor(51, 65, 85);
            $font = 'F1';
        }

        $this->currentStream .= sprintf("BT /%s 9.5 Tf %d %d Td (%s) Tj ET\n", $font, $this->x + 5, $this->y, $c1);
        $this->currentStream .= sprintf("BT /%s 9.5 Tf %d %d Td (%s) Tj ET\n", $font, $this->x + 130, $this->y, $c2);
        $this->currentStream .= sprintf("BT /%s 9.5 Tf %d %d Td (%s) Tj ET\n", $font, $this->x + 280, $this->y, $c3);

        $this->currentStream .= sprintf("0.85 0.85 0.85 RG 0.5 w %d %d m %d %d l S\n", $this->x, $this->y - 5, $this->pageWidth - $this->rightMargin, $this->y - 5);
        $this->y -= 17;
    }

    public function renderCover() {
        $this->setColor(15, 23, 42);
        $this->y = 650;
        
        $this->currentStream .= "BT /F2 10 Tf 50 730 Td (MINISTERIO DE EDUCACION Y FORMACION PROFESIONAL) Tj ET\n";
        $this->currentStream .= "BT /F2 10 Tf 50 715 Td (CICLO FORMATIVO DE GRADO SUPERIOR EN DESARROLLO DE APLICACIONES WEB - DAW) Tj ET\n";
        $this->currentStream .= "0.12 0.23 0.54 RG 2 w 50 700 m 545 700 l S\n";

        $this->setColor(30, 58, 138);
        $this->currentStream .= "BT /F2 24 Tf 50 630 Td (MI GYM - PLATAFORMA WEB INTEGRAL) Tj ET\n";
        $this->currentStream .= "BT /F2 16 Tf 50 600 Td (GestiOn de Rutinas con Video y Planes Nutricionales Multi-Menu) Tj ET\n";

        $this->setColor(71, 85, 105);
        $this->currentStream .= "BT /F1 12 Tf 50 560 Td (Memoria Oficial del Trabajo de Fin de Grado - TFG) Tj ET\n";

        // Cuadro de metadatos
        $this->currentStream .= "0.96 0.98 1 rg 50 380 495 140 re f\n";
        $this->currentStream .= "0.7 0.8 0.95 RG 1 w 50 380 495 140 re s\n";

        $this->setColor(15, 23, 42);
        $this->currentStream .= "BT /F2 11 Tf 70 490 Td (DATOS DEL PROYECTO Y CONVOCATORIA:) Tj ET\n";
        $this->currentStream .= "BT /F1 11 Tf 70 465 Td (Ciclo Formativo: Desarrollo de Aplicaciones Web [DAW]) Tj ET\n";
        $this->currentStream .= "BT /F1 11 Tf 70 445 Td (Familia Profesional: Informatica y Comunicaciones) Tj ET\n";
        $this->currentStream .= "BT /F1 11 Tf 70 425 Td (Convocatoria Academica: Curso 2025 - 2026) Tj ET\n";
        $this->currentStream .= "BT /F1 11 Tf 70 405 Td (Calificacion del Tribunal: ________________________) Tj ET\n";

        $this->addPage();
    }

    public function output($filename = 'Documentacion_TFG_MiGym.pdf', $destination = 'I') {
        $this->pages[$this->currentPage] = $this->currentStream;
        $numPages = count($this->pages);

        $out = "%PDF-1.4\n";
        $out .= "%\xE2\xE3\xCF\xD3\n";

        $offsets = [];
        $objIndex = 1;

        // 1. Catálogo
        $offsets[$objIndex] = strlen($out);
        $out .= "$objIndex 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $objIndex++;

        // 2. Colección de Páginas
        $offsets[$objIndex] = strlen($out);
        $kids = [];
        for ($i = 0; $i < $numPages; $i++) {
            $kids[] = (3 + $i * 2) . " 0 R";
        }
        $out .= "$objIndex 0 obj\n<< /Type /Pages /Count $numPages /Kids [ " . implode(' ', $kids) . " ] >>\nendobj\n";
        $objIndex++;

        // Objetos de Página y Contenido
        for ($i = 1; $i <= $numPages; $i++) {
            $pageObj = $objIndex;
            $contentObj = $objIndex + 1;
            $objIndex += 2;

            // Página
            $offsets[$pageObj] = strlen($out);
            $out .= "$pageObj 0 obj\n";
            $out .= "<< /Type /Page /Parent 2 0 R /MediaBox [ 0 0 $this->pageWidth $this->pageHeight ] ";
            $out .= "/Contents $contentObj 0 R /Resources << /Font << ";
            $out .= "/F1 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> ";
            $out .= "/F2 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >> ";
            $out .= "/F3 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Oblique >> ";
            $out .= ">> >> >>\nendobj\n";

            // Stream de contenido
            $stream = $this->pages[$i];
            $streamLen = strlen($stream);
            $offsets[$contentObj] = strlen($out);
            $out .= "$contentObj 0 obj\n<< /Length $streamLen >>\nstream\n" . $stream . "\nendstream\nendobj\n";
        }

        // XRef
        $startxref = strlen($out);
        $out .= "xref\n0 $objIndex\n0000000000 65535 f \n";
        for ($i = 1; $i < $objIndex; $i++) {
            $out .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $out .= "trailer\n<< /Size $objIndex /Root 1 0 R >>\nstartxref\n$startxref\n%%EOF";

        if ($destination === 'F') {
            file_put_contents($filename, $out);
            return true;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($out));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        echo $out;
        exit;
    }
}

// CONSTRUIR EL DOCUMENTO COMPLETO
$pdf = new MiniPDF();

// 1. PORTADA
$pdf->renderCover();

// 2. RESUMEN
$pdf->addTitle("1. Resumen Ejecutivo (Abstract)");
$pdf->addParagraph("Mi Gym es una aplicacion web full-stack concebida para profesionalizar y automatizar el trabajo diario de entrenadores personales y asesores de nutricion. Centraliza en una herramienta intuitiva la planificacion de ejercicios con desglose pormenorizado de series y cargas, videos demostrativos tecnicos y calculo dinamico de dietas multi-menu.");
$pdf->addParagraph("La plataforma solventa la dispersion de documentos mediante interfaces reactivas en JavaScript Vanilla y almacenamiento estructurado en MySQL, integrando la difusion en un solo clic por WhatsApp, correo electronico y exportacion formal a PDF.");

// 3. OBJETIVOS
$pdf->addTitle("2. Objetivos del Proyecto");
$pdf->addSubtitle("2.1. Objetivo General");
$pdf->addParagraph("Desarrollar un sistema web integral bajo arquitectura LAMP/WAMP que optimice el tiempo de diseno de pautas fisicas y nutricionales reduciendo la creacion de planes de 45 minutos a menos de 5 minutos.");
$pdf->addSubtitle("2.2. Objetivos Especificos");
$pdf->addBullet("OE-1: Catalogo de alimentos editable con calculo por cada 100g.");
$pdf->addBullet("OE-2: Diseñador de rutinas con series de peso piramidal y videos.");
$pdf->addBullet("OE-3: Sistema de dietas con opciones de menu alternativas intercambiables.");
$pdf->addBullet("OE-4: Reproductor de video integrado para visualizacion in-situ.");
$pdf->addBullet("OE-5: Enlace universal con WhatsApp API y reglas de impresion.");

// 4. STACK TECNOLOGICO
$pdf->addTitle("3. Stack Tecnologico y Arquitectura");
$pdf->addParagraph("El proyecto utiliza una arquitectura Cliente-Servidor limpia y ligera sin frameworks pesados, garantizando tiempos de carga inferiores a 1 segundo:");
$pdf->addTableRow("Capa", "Tecnologia", "Proposito / Utilidad", true);
$pdf->addTableRow("Frontend", "HTML5 + CSS3 (Grid/Flex)", "Interfaz moderna y responsiva");
$pdf->addTableRow("Frontend", "JavaScript Vanilla ES6+", "Calculos reactivos en vivo y DOM");
$pdf->addTableRow("Backend", "PHP 8.x Modular", "Servidor, routing y subida multipart");
$pdf->addTableRow("Base de Datos", "MySQL / MariaDB", "Persistencia relacional e integridad ACID");
$pdf->addTableRow("Servidor Web", "Apache (XAMPP)", "Configuracion .htaccess para 1000MB");

// 5. BASE DE DATOS
$pdf->addTitle("4. Base de Datos Relacional (MySQL)");
$pdf->addParagraph("El esquema consta de 7 tablas normalizadas enlazadas mediante claves foraneas con eliminacion en cascada (ON DELETE CASCADE):");
$pdf->addTableRow("Tabla", "Campos Clave", "Descripcion", true);
$pdf->addTableRow("alimentos", "id, nombre, calorias, P, C, G", "Catálogo nutricional editable");
$pdf->addTableRow("rutinas", "id, cliente_nombre, titulo, fecha", "Cabecera del plan de entrenamiento");
$pdf->addTableRow("rutina_ejercicios", "id, rutina_id, ejercicio, video_url", "Ejercicios con enlace/archivo de video");
$pdf->addTableRow("rutina_series", "id, ejercicio_id, peso, reps, notas", "Desglose serie por serie (1 a N)");
$pdf->addTableRow("dietas", "id, cliente_nombre, total_calorias", "Expediente del plan de alimentacion");
$pdf->addTableRow("dieta_dias", "id, dieta_id, nombre_dia, orden", "Menus y dias opcionales (Dia 1, 2...)");
$pdf->addTableRow("dieta_comidas", "id, dia_id, alimento, gramos, macros", "Tomas del dia con calculo proporcional");

// 6. MODULOS Y MULTIMEDIA
$pdf->addTitle("5. Modulos Clave y Soporte Multimedia");
$pdf->addSubtitle("5.1. Desglose de Series y Videos Ilimitados");
$pdf->addParagraph("Cada ejercicio admite series individuales permitiendo indicar por ejemplo Serie 1 con 70kg, Serie 2 con 75kg y Serie 3 con 80kg al fallo. Se acompania de un apartado para subir video en MP4 (con servidor ajustado a 1000MB de limite de envio) o enlaces directos de Google Drive y YouTube.");
$pdf->addSubtitle("5.2. Dietas Multi-Dia con Balance de Macros");
$pdf->addParagraph("El cliente puede alternar comodamente entre opciones de menu si un dia determinado no desea consumir los alimentos de la primera opcion. El visor superior refleja las Calorias, Proteinas, Carbohidratos y Grasas totales en tiempo real.");

// 7. SEGURIDAD
$pdf->addTitle("6. Seguridad y Buenas Practicas");
$pdf->addBullet("Consultas Preparadas: Todas las sentencias emplean mysqli_stmt con bind_param.");
$pdf->addBullet("Sanitizacion XSS: Salidas procesadas con htmlspecialchars() contra inyeccion de scripts.");
$pdf->addBullet("Manejo Seguro de Archivos: Renombrado criptoseguro y filtrado estricto por extension.");

// 8. CONCLUSIONES
$pdf->addTitle("7. Conclusiones y Trabajo Futuro");
$pdf->addParagraph("Mi Gym ha superado con exito todas las pruebas funcionales, demostrando el dominio de las competencias curriculares del Grado Superior en Desarrollo de Aplicaciones Web (DAW).");
$pdf->addParagraph("Como lineas de futuro se contempla la incorporacion de autenticacion de clientes para registro de cargas y cobro automatizado de asesorias con Stripe.");

// Generar PDF y enviarlo al navegador como descarga directa
$pdf->output("Documentacion_TFG_MiGym.pdf", "I");
?>
