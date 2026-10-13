<?php
// ==============================================================================
// Generador Nativo de Presentaciones Microsoft PowerPoint (.pptx) - ECMA-376
// Proyecto TFG: Mi Gym - Plataforma Integral de Entrenamiento y Nutrición
// Genera un archivo estándar Office Open XML (.pptx) 100% compatible y validado
// ==============================================================================

// Limpiar cualquier búfer previo para evitar caracteres o espacios antes del archivo binario
if (ob_get_level()) {
    ob_end_clean();
}

if (!class_exists('ZipArchive')) {
    header('Location: presentacion_tfg.html');
    exit;
}

$zip = new ZipArchive();
$tempFile = tempnam(sys_get_temp_dir(), 'pptx_');
if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Error: No se pudo crear el archivo temporal de PowerPoint en el servidor.");
}

// DEFINICIÓN DE LAS 15 DIAPOSITIVAS OFICIALES DEL TFG
$slidesData = [
    [
        'title' => 'MI GYM - PLATAFORMA DE ENTRENAMIENTO Y NUTRICION',
        'subtitle' => 'TRABAJO DE FIN DE GRADO (TFG) - CONVOCATORIA 2026',
        'badge' => 'DESARROLLO DE APLICACIONES WEB (DAW)',
        'items' => [
            'Ciclo Formativo: Grado Superior en Desarrollo de Aplicaciones Web (DAW)',
            'Familia Profesional: Informatica y Comunicaciones',
            'Centro Educativo / Convocatoria: Curso Academico 2025 - 2026',
            'Autor / Alumno: [Nombre del Alumno]  |  Tutor: [Nombre del Tutor]',
            'Objetivo: Software integral full-stack para el diseno y distribucion tecnica de planes'
        ],
        'isCover' => true
    ],
    [
        'title' => '1. Contexto, Problematica y Oportunidad',
        'subtitle' => '¿Por que era imprescindible modernizar la gestion deportiva?',
        'badge' => 'INTRODUCCION Y ANTECEDENTES',
        'items' => [
            'Metodos obsoletos: Entrenadores usando hojas de calculo dispersas y mensajes de chat desorganizados.',
            'Dudas en la ejecucion tecnica: Los clientes lesionan su postura por no contar con videos de referencia en sala.',
            'Rigidez en cargas: Las aplicaciones genericas no permiten registrar pesos piramidales por serie individual.',
            'Monotonia nutricional: Falta de menus rotativos calculados que aseguren la adherencia del deportista.',
            'Oportunidad DAW: Crear una plataforma web reactiva, centralizada, ligera y con envio en un solo clic.'
        ]
    ],
    [
        'title' => '2. Objetivos Generales y Especificos',
        'subtitle' => 'Alcance funcional y propuesta de valor diferencial',
        'badge' => 'OBJETIVOS DEL PROYECTO',
        'items' => [
            'Objetivo General: Desarrollar un ecosistema web responsivo para planificacion deportiva y nutricional.',
            'OE-1: Catalogo de alimentos editable con calculo dinamico de macros (Proteina, Carbos, Grasa, Kcal).',
            'OE-2: Planificador de rutinas con desglose exacto de peso (kg) y repeticiones serie a serie.',
            'OE-3: Gestor multimedia con subida masiva de video local sin limites de archivos o enlaces de Drive/YouTube.',
            'OE-4: Dietas multi-dia con pestañas interactivas y recuadro resumen de macronutrientes totales.',
            'OE-5: Canal de distribucion directo mediante integracion de la API Click-to-Chat de WhatsApp y PDF.'
        ]
    ],
    [
        'title' => '3. Stack Tecnologico y Arquitectura',
        'subtitle' => 'Tecnologias estandar, rendimiento optimizado y sin dependencias pesadas',
        'badge' => 'ARQUITECTURA DEL SISTEMA',
        'items' => [
            'Frontend: HTML5 Semantico, CSS3 Moderno (CSS Grid, Flexbox, @media print) y JavaScript Vanilla ES6+.',
            'Reactividad Ligera: Calculos nutricionales en vivo y filtros de busqueda en tiempo real desde el cliente.',
            'Backend: PHP 8.x estructurado, modular y preparado para recepcion multipart/form-data de hasta 1GB.',
            'Base de Datos: MariaDB / MySQL con motor InnoDB, integridad referencial y eliminacion en cascada (CASCADE).',
            'Despliegue y Servidor: Servidor local Apache bajo entorno XAMPP con configuracion optimizada en .htaccess.'
        ]
    ],
    [
        'title' => '4. Modelo de Datos y Estructura Relacional',
        'subtitle' => '7 tablas normalizadas en 3FN con claves foraneas protegidas',
        'badge' => 'BASE DE DATOS RELACIONAL',
        'items' => [
            'alimentos: Catalogo nutricional por 100 gramos (calorias, proteinas, carbohidratos, grasas).',
            'rutinas: Maestro con expediente del cliente (nombre, telefono, email, objetivo, fecha y notas).',
            'rutina_ejercicios: Detalle de ejercicios asignados, tiempo de descanso y ruta/enlace del video demostrativo.',
            'rutina_series: Desglose 1 a N de series por ejercicio con su peso especifico, repeticiones y observaciones.',
            'dietas: Maestro nutricional con totales de calorias, proteinas, carbohidratos y grasas calculadas.',
            'dieta_dias: Entidad relacional para estructurar opciones de menu alternativas (Dia 1, Dia 2, etc.).',
            'dieta_comidas: Lineas de alimentos desglosadas por comida con calculo proporcional a los gramos netos.'
        ]
    ],
    [
        'title' => '5. Modulo 1: Portal y Portada Corporativa',
        'subtitle' => 'Punto de entrada moderno y optimizado para la conversion (index.html)',
        'badge' => 'DEMOSTRACION DEL SISTEMA',
        'items' => [
            'Hero Banner de Alto Impacto: Acceso inmediato a la creacion de rutinas, dietas y catalogo.',
            'Barra de Metricas y Beneficios: Resumen visual de caracteristicas (1 Clic WhatsApp, Calculo Automatico, PDF).',
            'Flujo Guiado de Trabajo: Explicacion clara del proceso en 3 pasos: Crear plan -> Calcular -> Enviar.',
            'Navegacion Academica Integrada: Enlaces directos para descargar la Memoria PDF y la Presentacion PowerPoint.',
            'Diseño Totalmente Responsivo: Adaptabilidad fluida para pantallas moviles, tabletas y ordenadores.'
        ]
    ],
    [
        'title' => '6. Modulo 2: Base de Datos de Alimentos',
        'subtitle' => 'Mantenimiento del catalogo nutricional y calculo base (alimentos.php)',
        'badge' => 'DEMOSTRACION DEL SISTEMA',
        'items' => [
            'Operaciones CRUD: Registro, edicion y borrado de alimentos con sus 4 macronutrientes por 100g.',
            'Buscador Instantaneo: Filtrado reactivo en JavaScript a medida que el entrenador escribe el nombre.',
            'Validacion de Datos: Verificacion de tipos numericos no negativos para evitar errores de calculo.',
            'Sincronizacion Automatica: Los alimentos creados se incorporan de inmediato al selector del planificador de dietas.',
            'Feedback Visual: Notificaciones de exito o error ante cualquier modificacion en la base de datos.'
        ]
    ],
    [
        'title' => '7. Modulo 3: Planificador de Rutinas Avanzado',
        'subtitle' => 'Series personalizadas y soporte multimedia ilimitado (musculatura.php)',
        'badge' => 'DEMOSTRACION DEL SISTEMA',
        'items' => [
            'Expediente del Alumno: Captura de nombre, numero de WhatsApp y directrices de entrenamiento.',
            'Desglose Individual de Series: Boton para añadir series infinitas con su peso (kg) y repeticiones particulares.',
            'Subida de Videos Sin Limites: Cada ejercicio permite subir un video local o pegar un link de YouTube/Drive.',
            'Subida Multiple Concurrente: Permite añadir 4 o mas ejercicios con 4 videos pesados sin bloqueos de conexion.',
            'Persistencia Atomica: Guardado coordinado en base de datos de las 3 tablas relacionales en una sola transaccion.'
        ]
    ],
    [
        'title' => '8. Modulo 4: Planificador Nutricional Multi-Menu',
        'subtitle' => 'Dietas multi-dia con balance energetico en tiempo real (nutricion.php)',
        'badge' => 'DEMOSTRACION DEL SISTEMA',
        'items' => [
            'Arquitectura Multi-Dia: Pestañas para configurar menus rotativos (Dia de Entreno, Dia de Descanso, etc.).',
            'Calculo Reactivo por Gramos: Al indicar la cantidad de gramos, la aplicacion recalcula las macros al instante.',
            'Suma Total en Pantalla: Banner superior que totaliza las calorias, proteinas, carbohidratos y grasas de la dieta.',
            'Estructuracion de Comidas: Clasificacion clara entre Desayuno, Almuerzo, Comida, Merienda y Cena.',
            'Prevencion de Errores: Validacion automatica para impedir el envio de planes vacios o incompletos.'
        ]
    ],
    [
        'title' => '9. Modulo 5: Visor del Cliente y Video Integrado',
        'subtitle' => 'Visualizacion interactiva optimizada para el gimnasio (ver_rutina.php)',
        'badge' => 'DEMOSTRACION DEL SISTEMA',
        'items' => [
            'Diseño de Tarjetas Tipo App: Presentacion limpia de cada ejercicio con descansos y series destacadas.',
            'Reproductor de Video Integrado: Boton interactivo para visualizar la tecnica correcta en el telefono.',
            'Soporte Universal de Video: Reproduce archivos MP4/WEBM locales o incrusta reproductores de YouTube/Drive.',
            'Tabla Detallada de Cargas: El deportista visualiza con exactitud cuantos kilos debe cargar en cada serie.',
            'Adaptabilidad Movil: Diseñado para consulta ergonomica con una sola mano durante el entrenamiento.'
        ]
    ],
    [
        'title' => '10. Modulo 6: Visor de Dieta y Totales Nutricionales',
        'subtitle' => 'Consulta clara y balance calórico por jornadas (ver_dieta.php)',
        'badge' => 'DEMOSTRACION DEL SISTEMA',
        'items' => [
            'Selector Interactivo de Jornadas: Pestañas para conmutar rapidamente entre los distintos menus asignados.',
            'Panel de Macronutrientes Totales: Indicadores en color de Kcal totales, Gramos de Proteina, Carbos y Grasa.',
            'Desglose Proporcional por Comida: Tablas limpias con la cantidad exacta en gramos de cada alimento.',
            'Observaciones del Nutricionista: Espacio destacado para recomendaciones de hidratacion y suplementacion.',
            'Modo Imprimible Limpio: Oculta controles de navegacion innecesarios al imprimir en papel o guardar en PDF.'
        ]
    ],
    [
        'title' => '11. Modulo 7: Distribucion Omnicanal Instantanea',
        'subtitle' => 'Envio por WhatsApp en 1 Clic y generacion de PDF imprimible',
        'badge' => 'DISTRIBUCION Y EXPORTACION',
        'items' => [
            'Click-to-Chat de WhatsApp API: Genera la URL con codificacion correcta para enviar el enlace y el resumen.',
            'Plantilla con Formato Enriquecido: Emplea negritas y emoticonos para que el cliente lea el plan comodamente.',
            'Generador de PDF Independiente: Script PHP nativo que compila la memoria y las fichas tecnicas sin librerias externas.',
            'Hojas de Estilo @media print: Optimizacion para impresion fisica en blanco y negro o color sin consumo de tinta extra.',
            'Envio por Correo Electronico: Enlace mailto preparado con asunto y cuerpo preformateado para respaldo documental.'
        ]
    ],
    [
        'title' => '12. Rendimiento, Capacidad y Medidas de Seguridad',
        'subtitle' => 'Optimizacion de servidor y proteccion contra vulnerabilidades OWASP',
        'badge' => 'SEGURIDAD Y PERFORMANCE',
        'items' => [
            'Subida de Archivos Pesados: Directivas en .htaccess con post_max_size 1000M y upload_max_filesize 500M.',
            'Tiempo de Ejecucion Extendido: max_execution_time 600 segundos para transferencias lentas de videos HD.',
            'Consultas Parametrizadas (PDO / MySQLi): Proteccion integral contra inyecciones SQL en todos los endpoints.',
            'Sanitizacion de Entradas y Salidas: Uso estricto de htmlspecialchars() para neutralizar ataques XSS.',
            'Directorio Multimedia Dedicado: Carpeta uploads/ con gestion de permisos para el almacenamiento de video.'
        ]
    ],
    [
        'title' => '13. Conclusiones y Cumplimiento de Requisitos DAW',
        'subtitle' => 'Valoracion pedagogica y cumplimiento del curriculo oficial',
        'badge' => 'EVALUACION DEL PROYECTO',
        'items' => [
            'Cumplimiento del 100% de los requisitos tecnicos, funcionales y de usabilidad exigidos en el ciclo.',
            'Solucion Real y Util: Proyecto puesto en produccion en entorno local con pruebas de carga satisfactorias.',
            'Dominio del Desarrollo Web: Integracion completa de frontend semantico, logica backend y persistencia relacional.',
            'Evolucion Futura 1: Modulo de autenticacion con roles de entrenador y panel de alumno con registro de marcas.',
            'Evolucion Futura 2: Graficas interactivas de evolucion del peso corporal y conexion con pasarela Stripe.'
        ]
    ],
    [
        'title' => '14. Presentacion del Proyecto y Demostracion en Vivo',
        'subtitle' => 'Muchas gracias por su atencion - Turno de preguntas del Tribunal',
        'badge' => 'DEFENSA OFICIAL TFG 2026',
        'items' => [
            'Proyecto: Mi Gym - Plataforma Integral de Entrenamiento y Nutricion',
            'Ciclo Formativo: Grado Superior en Desarrollo de Aplicaciones Web (DAW)',
            'Autor / Alumno: [Nombre del Alumno]',
            'Tutor del Proyecto: [Nombre del Tutor]',
            'Quedo a disposicion del Tribunal Calificador para la demostracion tecnica y resolucion de dudas.'
        ],
        'isCover' => true
    ]
];

$numSlides = count($slidesData);

// ------------------------------------------------------------------------------
// 1. [Content_Types].xml (Valido según ECMA-376)
// ------------------------------------------------------------------------------
$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$contentTypes .= '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' . "\n";
$contentTypes .= '  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' . "\n";
$contentTypes .= '  <Default Extension="xml" ContentType="application/xml"/>' . "\n";
$contentTypes .= '  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>' . "\n";
$contentTypes .= '  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>' . "\n";
$contentTypes .= '  <Override PartName="/ppt/presentation.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.presentation.main+xml"/>' . "\n";
$contentTypes .= '  <Override PartName="/ppt/slideMasters/slideMaster1.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slideMaster+xml"/>' . "\n";
$contentTypes .= '  <Override PartName="/ppt/slideLayouts/slideLayout1.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slideLayout+xml"/>' . "\n";
$contentTypes .= '  <Override PartName="/ppt/theme/theme1.xml" ContentType="application/vnd.openxmlformats-officedocument.theme+xml"/>' . "\n";
for ($i = 1; $i <= $numSlides; $i++) {
    $contentTypes .= '  <Override PartName="/ppt/slides/slide' . $i . '.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slide+xml"/>' . "\n";
}
$contentTypes .= '</Types>';
$zip->addFromString('[Content_Types].xml', $contentTypes);

// ------------------------------------------------------------------------------
// 2. _rels/.rels
// ------------------------------------------------------------------------------
$rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$rootRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
$rootRels .= '  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="ppt/presentation.xml"/>' . "\n";
$rootRels .= '  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>' . "\n";
$rootRels .= '  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>' . "\n";
$rootRels .= '</Relationships>';
$zip->addFromString('_rels/.rels', $rootRels);

// ------------------------------------------------------------------------------
// 3. docProps/app.xml
// ------------------------------------------------------------------------------
$appXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$appXml .= '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">' . "\n";
$appXml .= '  <TotalTime>0</TotalTime>' . "\n";
$appXml .= '  <Words>0</Words>' . "\n";
$appXml .= '  <Application>Microsoft PowerPoint</Application>' . "\n";
$appXml .= '  <PresentationFormat>Widescreen</PresentationFormat>' . "\n";
$appXml .= '  <Paragraphs>0</Paragraphs>' . "\n";
$appXml .= '  <Slides>' . $numSlides . '</Slides>' . "\n";
$appXml .= '  <Notes>0</Notes>' . "\n";
$appXml .= '  <HiddenSlides>0</HiddenSlides>' . "\n";
$appXml .= '  <MMClips>0</MMClips>' . "\n";
$appXml .= '  <ScaleCrop>false</ScaleCrop>' . "\n";
$appXml .= '  <HeadingPairs>' . "\n";
$appXml .= '    <vt:vector size="2" baseType="variant"><vt:variant><vt:lpstr>Tema</vt:lpstr></vt:variant><vt:variant><vt:i4>1</vt:i4></vt:variant></vt:vector>' . "\n";
$appXml .= '  </HeadingPairs>' . "\n";
$appXml .= '  <TitlesOfParts>' . "\n";
$appXml .= '    <vt:vector size="1" baseType="lpstr"><vt:lpstr>Tema de Office</vt:lpstr></vt:vector>' . "\n";
$appXml .= '  </TitlesOfParts>' . "\n";
$appXml .= '  <Company>Mi Gym</Company>' . "\n";
$appXml .= '  <LinksUpToDate>false</LinksUpToDate>' . "\n";
$appXml .= '  <SharedDoc>false</SharedDoc>' . "\n";
$appXml .= '  <HyperlinksChanged>false</HyperlinksChanged>' . "\n";
$appXml .= '  <AppVersion>16.0000</AppVersion>' . "\n";
$appXml .= '</Properties>';
$zip->addFromString('docProps/app.xml', $appXml);

// ------------------------------------------------------------------------------
// 4. docProps/core.xml
// ------------------------------------------------------------------------------
$coreXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$coreXml .= '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . "\n";
$coreXml .= '  <dc:title>Defensa de TFG - Mi Gym Web Platform</dc:title>' . "\n";
$coreXml .= '  <dc:subject>Proyecto Fin de Grado Superior DAW</dc:subject>' . "\n";
$coreXml .= '  <dc:creator>Alumno DAW</dc:creator>' . "\n";
$coreXml .= '  <cp:keywords>TFG, DAW, Gimnasio, Nutricion, Fitness, Web</cp:keywords>' . "\n";
$coreXml .= '  <dc:description>Presentacion oficial de diapositivas para la defensa del Trabajo de Fin de Grado</dc:description>' . "\n";
$coreXml .= '  <cp:lastModifiedBy>Alumno DAW</cp:lastModifiedBy>' . "\n";
$coreXml .= '  <dcterms:created xsi:type="dcterms:W3CDTF">2026-10-13T12:00:00Z</dcterms:created>' . "\n";
$coreXml .= '  <dcterms:modified xsi:type="dcterms:W3CDTF">2026-10-13T12:00:00Z</dcterms:modified>' . "\n";
$coreXml .= '</cp:coreProperties>';
$zip->addFromString('docProps/core.xml', $coreXml);

// ------------------------------------------------------------------------------
// 5. ppt/presentation.xml
// ------------------------------------------------------------------------------
$pres = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$pres .= '<p:presentation xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">' . "\n";
$pres .= '  <p:sldMasterIdLst>' . "\n";
$pres .= '    <p:sldMasterId id="2147483648" r:id="rId1"/>' . "\n";
$pres .= '  </p:sldMasterIdLst>' . "\n";
$pres .= '  <p:sldIdLst>' . "\n";
for ($i = 1; $i <= $numSlides; $i++) {
    $pres .= '    <p:sldId id="' . (255 + $i) . '" r:id="rId' . ($i + 1) . '"/>' . "\n";
}
$pres .= '  </p:sldIdLst>' . "\n";
$pres .= '  <p:sldSz cx="12192000" cy="6858000" type="screen16x9"/>' . "\n";
$pres .= '  <p:notesSz cx="6858000" cy="9144000"/>' . "\n";
$pres .= '  <p:defaultTextStyle>' . "\n";
$pres .= '    <a:defPPr><a:defRPr lang="es-ES"/></a:defPPr>' . "\n";
$pres .= '  </p:defaultTextStyle>' . "\n";
$pres .= '</p:presentation>';
$zip->addFromString('ppt/presentation.xml', $pres);

// ------------------------------------------------------------------------------
// 6. ppt/_rels/presentation.xml.rels
// ------------------------------------------------------------------------------
$presRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$presRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
$presRels .= '  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideMaster" Target="slideMasters/slideMaster1.xml"/>' . "\n";
for ($i = 1; $i <= $numSlides; $i++) {
    $presRels .= '  <Relationship Id="rId' . ($i + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slide" Target="slides/slide' . $i . '.xml"/>' . "\n";
}
$presRels .= '</Relationships>';
$zip->addFromString('ppt/_rels/presentation.xml.rels', $presRels);

// ------------------------------------------------------------------------------
// 7. ppt/theme/theme1.xml
// ------------------------------------------------------------------------------
$theme = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$theme .= '<a:theme xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" name="Office Theme">' . "\n";
$theme .= '  <a:themeElements>' . "\n";
$theme .= '    <a:clrScheme name="MiGym">' . "\n";
$theme .= '      <a:dk1><a:srgbClr val="0F172A"/></a:dk1>' . "\n";
$theme .= '      <a:lt1><a:srgbClr val="FFFFFF"/></a:lt1>' . "\n";
$theme .= '      <a:dk2><a:srgbClr val="1E293B"/></a:dk2>' . "\n";
$theme .= '      <a:lt2><a:srgbClr val="F8FAFC"/></a:lt2>' . "\n";
$theme .= '      <a:accent1><a:srgbClr val="1E3A8A"/></a:accent1>' . "\n";
$theme .= '      <a:accent2><a:srgbClr val="10B981"/></a:accent2>' . "\n";
$theme .= '      <a:accent3><a:srgbClr val="2563EB"/></a:accent3>' . "\n";
$theme .= '      <a:accent4><a:srgbClr val="E11D48"/></a:accent4>' . "\n";
$theme .= '      <a:accent5><a:srgbClr val="64748B"/></a:accent5>' . "\n";
$theme .= '      <a:accent6><a:srgbClr val="F59E0B"/></a:accent6>' . "\n";
$theme .= '      <a:hlink><a:srgbClr val="2563EB"/></a:hlink>' . "\n";
$theme .= '      <a:folHlink><a:srgbClr val="1E3A8A"/></a:folHlink>' . "\n";
$theme .= '    </a:clrScheme>' . "\n";
$theme .= '    <a:fontScheme name="Office">' . "\n";
$theme .= '      <a:majorFont><a:latin typeface="Segoe UI"/></a:majorFont>' . "\n";
$theme .= '      <a:minorFont><a:latin typeface="Segoe UI"/></a:minorFont>' . "\n";
$theme .= '    </a:fontScheme>' . "\n";
$theme .= '    <a:fmtScheme name="Office">' . "\n";
$theme .= '      <a:fillStyleLst><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:fillStyleLst>' . "\n";
$theme .= '      <a:lnStyleLst><a:ln w="9525"><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:ln></a:lnStyleLst>' . "\n";
$theme .= '      <a:effectStyleLst><a:effectStyle><a:effectLst/></a:effectStyle></a:effectStyleLst>' . "\n";
$theme .= '      <a:bgFillStyleLst><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:bgFillStyleLst>' . "\n";
$theme .= '    </a:fmtScheme>' . "\n";
$theme .= '  </a:themeElements>' . "\n";
$theme .= '</a:theme>';
$zip->addFromString('ppt/theme/theme1.xml', $theme);

// ------------------------------------------------------------------------------
// 8. ppt/slideMasters/slideMaster1.xml y sus rels
// ------------------------------------------------------------------------------
$slideMaster = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$slideMaster .= '<p:sldMaster xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">' . "\n";
$slideMaster .= '  <p:cSld>' . "\n";
$slideMaster .= '    <p:spTree>' . "\n";
$slideMaster .= '      <p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr>' . "\n";
$slideMaster .= '      <p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr>' . "\n";
$slideMaster .= '    </p:spTree>' . "\n";
$slideMaster .= '  </p:cSld>' . "\n";
$slideMaster .= '  <p:clrMap bg1="lt1" tx1="dk1" bg2="lt2" tx2="dk2" accent1="accent1" accent2="accent2" accent3="accent3" accent4="accent4" accent5="accent5" accent6="accent6" hlink="hlink" folHlink="folHlink"/>' . "\n";
$slideMaster .= '  <p:sldLayoutIdLst>' . "\n";
$slideMaster .= '    <p:sldLayoutId id="2147483649" r:id="rId1"/>' . "\n";
$slideMaster .= '  </p:sldLayoutIdLst>' . "\n";
$slideMaster .= '  <p:txStyles>' . "\n";
$slideMaster .= '    <p:titleStyle><a:lvl1pPr algn="l"><a:defRPr sz="4000" b="1"><a:solidFill><a:schemeClr val="accent1"/></a:solidFill><a:latin typeface="Segoe UI"/></a:defRPr></a:lvl1pPr></p:titleStyle>' . "\n";
$slideMaster .= '    <p:bodyStyle><a:lvl1pPr algn="l"><a:defRPr sz="1800"><a:solidFill><a:schemeClr val="tx1"/></a:solidFill><a:latin typeface="Segoe UI"/></a:defRPr></a:lvl1pPr></p:bodyStyle>' . "\n";
$slideMaster .= '    <p:otherStyle><a:defPPr><a:defRPr lang="es-ES"/></a:defPPr></p:otherStyle>' . "\n";
$slideMaster .= '  </p:txStyles>' . "\n";
$slideMaster .= '</p:sldMaster>';
$zip->addFromString('ppt/slideMasters/slideMaster1.xml', $slideMaster);

$masterRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$masterRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
$masterRels .= '  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideLayout" Target="../slideLayouts/slideLayout1.xml"/>' . "\n";
$masterRels .= '  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="../theme/theme1.xml"/>' . "\n";
$masterRels .= '</Relationships>';
$zip->addFromString('ppt/slideMasters/_rels/slideMaster1.xml.rels', $masterRels);

// ------------------------------------------------------------------------------
// 9. ppt/slideLayouts/slideLayout1.xml y rels
// ------------------------------------------------------------------------------
$slideLayout = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$slideLayout .= '<p:sldLayout xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main" type="blank" preserve="1">' . "\n";
$slideLayout .= '  <p:cSld>' . "\n";
$slideLayout .= '    <p:spTree>' . "\n";
$slideLayout .= '      <p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr>' . "\n";
$slideLayout .= '      <p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr>' . "\n";
$slideLayout .= '    </p:spTree>' . "\n";
$slideLayout .= '  </p:cSld>' . "\n";
$slideLayout .= '  <p:clrMapOvr><a:masterClrMap/></p:clrMapOvr>' . "\n";
$slideLayout .= '</p:sldLayout>';
$zip->addFromString('ppt/slideLayouts/slideLayout1.xml', $slideLayout);

$layoutRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
$layoutRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
$layoutRels .= '  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideMaster" Target="../slideMasters/slideMaster1.xml"/>' . "\n";
$layoutRels .= '</Relationships>';
$zip->addFromString('ppt/slideLayouts/_rels/slideLayout1.xml.rels', $layoutRels);

// ------------------------------------------------------------------------------
// 10. GENERACIÓN DE CADA SLIDE (1 a 15) CON ESTILO PROFESIONAL Y FORMAS VALIDAS
// ------------------------------------------------------------------------------
foreach ($slidesData as $idx => $s) {
    $num = $idx + 1;
    $isCover = !empty($s['isCover']);

    $bgFill = $isCover ? '0F172A' : 'FFFFFF';
    $badgeColor = $isCover ? '38BDF8' : '2563EB';
    $titleColor = $isCover ? '60A5FA' : '1E3A8A';
    $subtitleColor = $isCover ? '94A3B8' : '64748B';
    $cardBg = $isCover ? '1E293B' : 'F8FAFC';
    $cardBorder = $isCover ? '334155' : 'E2E8F0';
    $textColor = $isCover ? 'F1F5F9' : '1E293B';
    $bulletColor = $isCover ? '38BDF8' : '10B981';

    $slideXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
    $slideXml .= '<p:sld xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">' . "\n";
    $slideXml .= '  <p:cSld>' . "\n";
    $slideXml .= '    <p:bg><p:bgPr><a:solidFill><a:srgbClr val="' . $bgFill . '"/></a:solidFill></p:bgPr></p:bg>' . "\n";
    $slideXml .= '    <p:spTree>' . "\n";
    $slideXml .= '      <p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr>' . "\n";
    $slideXml .= '      <p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr>' . "\n";

    // 1. Badge / Categoría Superior
    $slideXml .= '      <p:sp>' . "\n";
    $slideXml .= '        <p:nvSpPr><p:cNvPr id="2" name="Badge"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>' . "\n";
    $slideXml .= '        <p:spPr>' . "\n";
    $slideXml .= '          <a:xfrm><a:off x="800000" y="450000"/><a:ext cx="10592000" cy="350000"/></a:xfrm>' . "\n";
    $slideXml .= '          <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>' . "\n";
    $slideXml .= '          <a:noFill/>' . "\n";
    $slideXml .= '        </p:spPr>' . "\n";
    $slideXml .= '        <p:txBody>' . "\n";
    $slideXml .= '          <a:bodyPr rtlCol="0" anchor="ctr"/>' . "\n";
    $slideXml .= '          <a:lstStyle/>' . "\n";
    $slideXml .= '          <a:p>' . "\n";
    $slideXml .= '            <a:r><a:rPr lang="es-ES" sz="1300" b="1"><a:solidFill><a:srgbClr val="' . $badgeColor . '"/></a:solidFill><a:latin typeface="Segoe UI"/></a:rPr><a:t>' . htmlspecialchars($s['badge']) . '</a:t></a:r>' . "\n";
    $slideXml .= '          </a:p>' . "\n";
    $slideXml .= '        </p:txBody>' . "\n";
    $slideXml .= '      </p:sp>' . "\n";

    // 2. Título Principal
    $slideXml .= '      <p:sp>' . "\n";
    $slideXml .= '        <p:nvSpPr><p:cNvPr id="3" name="Title"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>' . "\n";
    $slideXml .= '        <p:spPr>' . "\n";
    $slideXml .= '          <a:xfrm><a:off x="800000" y="800000"/><a:ext cx="10592000" cy="900000"/></a:xfrm>' . "\n";
    $slideXml .= '          <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>' . "\n";
    $slideXml .= '          <a:noFill/>' . "\n";
    $slideXml .= '        </p:spPr>' . "\n";
    $slideXml .= '        <p:txBody>' . "\n";
    $slideXml .= '          <a:bodyPr rtlCol="0" anchor="ctr"/>' . "\n";
    $slideXml .= '          <a:lstStyle/>' . "\n";
    $slideXml .= '          <a:p>' . "\n";
    $slideXml .= '            <a:r><a:rPr lang="es-ES" sz="2600" b="1"><a:solidFill><a:srgbClr val="' . $titleColor . '"/></a:solidFill><a:latin typeface="Segoe UI"/></a:rPr><a:t>' . htmlspecialchars($s['title']) . '</a:t></a:r>' . "\n";
    $slideXml .= '          </a:p>' . "\n";
    $slideXml .= '        </p:txBody>' . "\n";
    $slideXml .= '      </p:sp>' . "\n";

    // 3. Subtítulo Explicativo
    $slideXml .= '      <p:sp>' . "\n";
    $slideXml .= '        <p:nvSpPr><p:cNvPr id="4" name="Subtitle"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>' . "\n";
    $slideXml .= '        <p:spPr>' . "\n";
    $slideXml .= '          <a:xfrm><a:off x="800000" y="1700000"/><a:ext cx="10592000" cy="450000"/></a:xfrm>' . "\n";
    $slideXml .= '          <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>' . "\n";
    $slideXml .= '          <a:noFill/>' . "\n";
    $slideXml .= '        </p:spPr>' . "\n";
    $slideXml .= '        <p:txBody>' . "\n";
    $slideXml .= '          <a:bodyPr rtlCol="0" anchor="ctr"/>' . "\n";
    $slideXml .= '          <a:lstStyle/>' . "\n";
    $slideXml .= '          <a:p>' . "\n";
    $slideXml .= '            <a:r><a:rPr lang="es-ES" sz="1500" i="1"><a:solidFill><a:srgbClr val="' . $subtitleColor . '"/></a:solidFill><a:latin typeface="Segoe UI"/></a:rPr><a:t>' . htmlspecialchars($s['subtitle']) . '</a:t></a:r>' . "\n";
    $slideXml .= '          </a:p>' . "\n";
    $slideXml .= '        </p:txBody>' . "\n";
    $slideXml .= '      </p:sp>' . "\n";

    // 4. Tarjeta Contenedora Principal con Puntos Clave
    $slideXml .= '      <p:sp>' . "\n";
    $slideXml .= '        <p:nvSpPr><p:cNvPr id="5" name="ContentBox"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>' . "\n";
    $slideXml .= '        <p:spPr>' . "\n";
    $slideXml .= '          <a:xfrm><a:off x="800000" y="2250000"/><a:ext cx="10592000" cy="4000000"/></a:xfrm>' . "\n";
    $slideXml .= '          <a:prstGeom prst="roundRect"><a:avLst><a:gd name="adj" fmla="val 2000"/></a:avLst></a:prstGeom>' . "\n";
    $slideXml .= '          <a:solidFill><a:srgbClr val="' . $cardBg . '"/></a:solidFill>' . "\n";
    $slideXml .= '          <a:ln w="12700"><a:solidFill><a:srgbClr val="' . $cardBorder . '"/></a:solidFill></a:ln>' . "\n";
    $slideXml .= '        </p:spPr>' . "\n";
    $slideXml .= '        <p:txBody>' . "\n";
    $slideXml .= '          <a:bodyPr rtlCol="0" tIns="250000" bIns="250000" lIns="350000" rIns="350000" anchor="t"/>' . "\n";
    $slideXml .= '          <a:lstStyle/>' . "\n";

    foreach ($s['items'] as $item) {
        $slideXml .= '          <a:p>' . "\n";
        $slideXml .= '            <a:pPr marL="350000" indent="-350000">' . "\n";
        $slideXml .= '              <a:spcBfr><a:spcPts val="1200"/></a:spcBfr>' . "\n";
        $slideXml .= '              <a:spcAft><a:spcPts val="600"/></a:spcAft>' . "\n";
        $slideXml .= '              <a:buClr><a:srgbClr val="' . $bulletColor . '"/></a:buClr>' . "\n";
        $slideXml .= '              <a:buChar char="✔"/>' . "\n";
        $slideXml .= '            </a:pPr>' . "\n";
        $slideXml .= '            <a:r>' . "\n";
        $slideXml .= '              <a:rPr lang="es-ES" sz="1600"><a:solidFill><a:srgbClr val="' . $textColor . '"/></a:solidFill><a:latin typeface="Segoe UI"/></a:rPr>' . "\n";
        $slideXml .= '              <a:t>' . htmlspecialchars($item) . '</a:t>' . "\n";
        $slideXml .= '            </a:r>' . "\n";
        $slideXml .= '          </a:p>' . "\n";
    }

    $slideXml .= '        </p:txBody>' . "\n";
    $slideXml .= '      </p:sp>' . "\n";

    // 5. Pie de Página con Numeración de Diapositiva
    $slideXml .= '      <p:sp>' . "\n";
    $slideXml .= '        <p:nvSpPr><p:cNvPr id="6" name="Footer"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>' . "\n";
    $slideXml .= '        <p:spPr>' . "\n";
    $slideXml .= '          <a:xfrm><a:off x="800000" y="6350000"/><a:ext cx="10592000" cy="300000"/></a:xfrm>' . "\n";
    $slideXml .= '          <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>' . "\n";
    $slideXml .= '          <a:noFill/>' . "\n";
    $slideXml .= '        </p:spPr>' . "\n";
    $slideXml .= '        <p:txBody>' . "\n";
    $slideXml .= '          <a:bodyPr rtlCol="0" anchor="ctr"/>' . "\n";
    $slideXml .= '          <a:lstStyle/>' . "\n";
    $slideXml .= '          <a:p>' . "\n";
    $slideXml .= '            <a:r><a:rPr lang="es-ES" sz="1000"><a:solidFill><a:srgbClr val="94A3B8"/></a:solidFill><a:latin typeface="Segoe UI"/></a:rPr><a:t>Mi Gym - Trabajo Fin de Grado DAW 2026  |  Diapositiva ' . $num . ' de ' . $numSlides . '</a:t></a:r>' . "\n";
    $slideXml .= '          </a:p>' . "\n";
    $slideXml .= '        </p:txBody>' . "\n";
    $slideXml .= '      </p:sp>' . "\n";

    $slideXml .= '    </p:spTree>' . "\n";
    $slideXml .= '  </p:cSld>' . "\n";
    $slideXml .= '  <p:clrMapOvr><a:masterClrMap/></p:clrMapOvr>' . "\n";
    $slideXml .= '</p:sld>' . "\n";

    $zip->addFromString('ppt/slides/slide' . $num . '.xml', $slideXml);

    // Relaciones para esta diapositiva
    $slideRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
    $slideRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
    $slideRels .= '  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideLayout" Target="../slideLayouts/slideLayout1.xml"/>' . "\n";
    $slideRels .= '</Relationships>';
    $zip->addFromString('ppt/slides/_rels/slide' . $num . '.xml.rels', $slideRels);
}

$zip->close();

// ENVIAR EL ARCHIVO GENERADO AL NAVEGADOR
$filesize = filesize($tempFile);
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation');
header('Content-Disposition: attachment; filename="Presentacion_TFG_MiGym.pptx"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $filesize);

readfile($tempFile);
@unlink($tempFile);
exit;
?>
