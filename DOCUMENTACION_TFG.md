# 📖 PROYECTO DE FIN DE CICLO FORMATIVO DE GRADO SUPERIOR (TFG)
## Ciclo Formativo: Desarrollo de Aplicaciones Web (DAW)
### Familia Profesional: Informática y Comunicaciones

---

# TÍTULO DEL PROYECTO:
# **MI GYM - PLATAFORMA WEB INTEGRAL PARA LA GESTIÓN DE ENTRENAMIENTOS CON VÍDEO Y PLANES NUTRICIONALES MULTI-MENÚ CON BALANCE DINÁMICO DE MACRONUTRIENTES**

---

| Datos del Proyecto | Información |
| :--- | :--- |
| **Título del Proyecto** | Mi Gym Web Platform |
| **Ciclo Formativo** | Grado Superior en Desarrollo de Aplicaciones Web (DAW) |
| **Módulo Profesional** | Proyecto de Desarrollo de Aplicaciones Web (TFG) |
| **Alumno / Autor** | [Nombre del Alumno] |
| **Tutor del Proyecto** | [Nombre del Tutor] |
| **Centro Educativo** | [Nombre del Instituto / Centro] |
| **Convocatoria** | Curso Académico 2025 - 2026 |
| **Versión del Documento** | 1.0 (Final) |

---

## ÍNDICE DE CONTENIDOS
1. [Resumen del Proyecto (Abstract)](#1-resumen-del-proyecto-abstract)
2. [Introducción y Justificación](#2-introducción-y-justificación)
   - 2.1. Contexto y Oportunidad de Mercado
   - 2.2. Problemática Detectada
   - 2.3. Solución Propuesta y Propuesta de Valor
3. [Objetivos del Proyecto](#3-objetivos-del-proyecto)
   - 3.1. Objetivo General
   - 3.2. Objetivos Específicos
4. [Análisis de Requisitos](#4-análisis-de-requisitos)
   - 4.1. Requisitos Funcionales (RF)
   - 4.2. Requisitos No Funcionales (RNF)
   - 4.3. Diagrama de Casos de Uso
5. [Tecnologías y Entorno de Desarrollo](#5-tecnologías-y-entorno-de-desarrollo)
   - 5.1. Stack Tecnológico (Front-End, Back-End y Base de Datos)
   - 5.2. Herramientas y Servidor Web
6. [Diseño y Arquitectura del Sistema](#6-diseño-y-arquitectura-del-sistema)
   - 6.1. Arquitectura de Software
   - 6.2. Modelo Entidad - Relación (E/R)
   - 6.3. Diccionario de Datos Físico
7. [Desarrollo e Implementación de Módulos](#7-desarrollo-e-implementación-de-módulos)
   - 7.1. Módulo 1: Portada y Presentación Corporativa (`index.html`)
   - 7.2. Módulo 2: Base de Datos Nutricional y CRUD de Alimentos (`alimentos.php`)
   - 7.3. Módulo 3: Planificador de Rutinas con Vídeos y Series Dinámicas (`musculatura.php`)
   - 7.4. Módulo 4: Gestor de Dietas Multi-Día con Cálculo de Macros (`nutricion.php`)
   - 7.5. Módulo 5: Visor de Clientes, Reproductor Multimedia y Envío (`ver_rutina.php`, `ver_dieta.php`)
   - 7.6. Módulo 6: Integración con WhatsApp API y Exportación PDF
8. [Seguridad, Rendimiento y Buenas Prácticas](#8-seguridad-rendimiento-y-buenas-prácticas)
   - 8.1. Prevención de Inyección SQL mediante Sentencias Preparadas
   - 8.2. Sanitización contra Cross-Site Scripting (XSS)
   - 8.3. Subida Segura de Ficheros y Configuración de Servidor
9. [Manual de Instalación y Despliegue](#9-manual-de-instalación-y-despliegue)
10. [Pruebas y Verificación del Sistema](#10-pruebas-y-verificación-del-sistema)
11. [Conclusiones y Líneas Futuras](#11-conclusiones-y-líneas-futuras)
12. [Bibliografía y Webgrafía](#12-bibliografía-y-webgrafía)

---

## 1. RESUMEN DEL PROYECTO (ABSTRACT)

**Mi Gym** es una aplicación web concebida para digitalizar y profesionalizar el flujo de trabajo de entrenadores personales y asesores nutricionales. La plataforma resuelve la dispersión de información habitual en el sector (hojas de cálculo manuales, notas desordenadas y mensajes de texto sin formato) mediante un sistema unificado y ágil.

Entre sus características principales destacan:
1. **Planificador de Entrenamiento con Desglose Individual de Series**: Permite definir cargas piramidales, aproximaciones y fallos musculares serie por serie (peso en kg y repeticiones específicas), complementado con un sistema de **vídeos demostrativos** (alojamiento local en el servidor o enlace externo) con reproductor integrado.
2. **Planificador Nutricional Multi-Día / Multi-Menú**: Capacita al profesional para diseñar pautas alimentarias organizadas en opciones intercambiables (Día 1, Día 2, etc.), con **cálculo automático en tiempo real** de calorías, proteínas, carbohidratos y grasas a partir de una base de datos propia de alimentos.
3. **Distribución Omnicanal Instantánea**: Generación automática de plantillas estructuradas con emojis y formato enriquecido para envío en **1 clic a WhatsApp** y **correo electrónico**, además de hojas de estilo `@media print` para exportación a PDF de alta resolución.

---

## 2. INTRODUCCIÓN Y JUSTIFICACIÓN

### 2.1. Contexto y Oportunidad de Mercado
El sector del fitness y la preparación física personalizada ha experimentado una digitalización acelerada. Cada vez más usuarios contratan servicios de entrenamiento y nutrición personalizados a distancia o de forma híbrida. Sin embargo, los profesionales independientes suelen encontrarse en una encrucijada tecnológica:
- El software empresarial existente (SaaS) suele tener suscripciones mensuales elevadas, interfaces complejas pensadas para grandes gimnasios y falta de flexibilidad.
- Las herramientas ofimáticas gratuitas (Excel, Google Sheets) carecen de experiencia interactiva en teléfonos móviles, complican el cálculo automático para clientes no técnicos y resultan tediosas de compartir.

### 2.2. Problemática Detectada
1. **Pérdida de técnica y dudas en los ejercicios**: Los clientes a menudo desconocen la ejecución de un ejercicio si solo reciben un nombre en texto, requiriendo enlaces dispersos a vídeos de terceros.
2. **Cargas rígidas**: Los sistemas habituales permiten indicar "4 series de 10 reps", pero no permiten planificar que la Serie 1 sea con 70kg, la Serie 2 con 75kg y la Serie 3 con 80kg al fallo.
3. **Falta de opciones en las dietas**: Cuando un cliente no desea comer el menú establecido un día determinado, carece de alternativas equivalentes en macronutrientes.
4. **Fricción en la comunicación**: Exportar archivos PDF pesados por correo genera menor adherencia que un mensaje estructurado directo en WhatsApp.

### 2.3. Solución Propuesta y Propuesta de Valor
**Mi Gym** proporciona un entorno web rápido, ligero y responsivo que se ejecuta sobre un stack estándar LAMP/WAMP. El entrenador tiene control total de su base de datos de alimentos, puede crear rutinas con vídeos y series detalladas, y su cliente puede visualizar su planificación tanto en la web como en WhatsApp o PDF sin fricción.

---

## 3. OBJETIVOS DEL PROYECTO

### 3.1. Objetivo General
Diseñar, implementar y validar una aplicación web dinámica completa con arquitectura cliente-servidor que centralice la confección de planes de entrenamiento hiper-detallados con soporte multimedia y planes nutricionales multi-menú con cálculo instantáneo de macronutrientes y distribución directa al cliente final.

### 3.2. Objetivos Específicos
- **OE-1**: Desarrollar una interfaz de usuario limpia, intuitiva y 100% responsiva (Mobile First) que garantice una excelente usabilidad tanto en dispositivos móviles como en monitores de escritorio.
- **OE-2**: Implementar una base de datos relacional robusta en MySQL con integridad referencial en cascada para soportar estructuras jerárquicas (Rutina -> Ejercicios -> Series; Dieta -> Días -> Comidas).
- **OE-3**: Construir un módulo de nutrición que calcule en el navegador (lado cliente con JavaScript) y valide en servidor (PHP) los balances calóricos y macronutricionales por cada 100g de alimento de forma reactiva.
- **OE-4**: Implementar un sistema de gestión multimedia que soporte la subida física de archivos de vídeo (MP4, WEBM, MOV) en el servidor sin límites restrictivos de concurrencia o tamaño, así como la vinculación de enlaces externos (Google Drive, YouTube).
- **OE-5**: Integrar el protocolo de mensajería instantánea de WhatsApp mediante *URL Schema / Click-to-Chat* y formateo dinámico con caracteres especiales de Markdown (`*negrita*`, saltos de línea y emojis).
- **OE-6**: Aplicar principios de seguridad en desarrollo web: protección contra SQL Injection mediante sentencias preparadas (`mysqli_stmt`), prevención de XSS y saneamiento de entradas de ficheros.

---

## 4. ANÁLISIS DE REQUISITOS

### 4.1. Requisitos Funcionales (RF)
- **RF-01 (Gestión de Alimentos)**: El entrenador podrá dar de alta, consultar, modificar y eliminar alimentos con sus valores de Calorías, Proteínas, Carbohidratos, Grasas y Categoría por cada 100 gramos.
- **RF-02 (Búsqueda Reactiva de Alimentos)**: Filtro en tiempo real por nombre y categoría de alimentos sin recargar la página.
- **RF-03 (Creación de Rutinas)**: Registro de planes de entrenamiento asociados a un cliente (nombre, teléfono, email, objetivo y notas técnicas).
- **RF-04 (Desglose Dinámico de Series)**: Para cada ejercicio, el entrenador podrá agregar $N$ series individuales asignando peso estimado (kg), repeticiones y notas de esfuerzo (RPE, drop set, etc.).
- **RF-05 (Asociación de Vídeos a Ejercicios)**: Posibilidad de subir un archivo de vídeo local o especificar una URL externa (Google Drive, YouTube) para cualquier ejercicio sin límite de ejercicios por rutina.
- **RF-06 (Reproductor Multimedia Integrado)**: El cliente podrá reproducir el vídeo demostrativo de cualquier ejercicio directamente en la pantalla de su rutina.
- **RF-07 (Creación de Dietas Multi-Día)**: Registro de planes nutricionales compuestos por múltiples días u opciones de menú (Día 1, Día 2, etc.) en un solo expediente de cliente.
- **RF-08 (Cálculo Automático de Macros)**: Cálculo automático e inmediato de calorías, proteínas, carbohidratos y grasas según los gramos especificados para cada alimento seleccionado.
- **RF-09 (Resumen Global y Selector de Días)**: Visualización de totales de macronutrientes en la cabecera del plan con pestañas interactivas para alternar entre menús.
- **RF-10 (Envío a WhatsApp)**: Generación de enlace `https://api.whatsapp.com/send` con el texto completo del plan de entrenamiento o de la dieta listo para enviar con 1 clic.
- **RF-11 (Envío por Email)**: Generación de enlace `mailto:` con asunto y cuerpo preformateados.
- **RF-12 (Exportación a PDF / Impresión)**: Hoja de estilos `@media print` que oculta elementos de interfaz y optimiza la ficha para generar un documento PDF formal.

### 4.2. Requisitos No Funcionales (RNF)
- **RNF-01 (Rendimiento y Ligereza)**: Tiempo de carga inferior a 1 segundo en entorno local; código nativo en JavaScript Vanilla sin dependencias pesadas de frameworks externos.
- **RNF-02 (Compatibilidad Cross-Browser)**: Funcionamiento garantizado en navegadores modernos: Google Chrome, Mozilla Firefox, Microsoft Edge, Safari y Opera.
- **RNF-03 (Diseño Responsivo)**: Adaptación fluida a pantallas desde 360px (smartphones) hasta 4K mediante CSS Flexbox y CSS Grid.
- **RNF-04 (Seguridad)**: Parametrización estricta de consultas SQL (`bind_param`) y escape de caracteres HTML (`htmlspecialchars`).
- **RNF-05 (Disponibilidad y Tolerancia a Fallos)**: Autocreación e inicialización transparente de la base de datos y tablas si no existen al iniciar la aplicación.
- **RNF-06 (Capacidad de Ficheros)**: Configuración del servidor para admitir cargas pesadas de vídeo de hasta 500 MB por archivo y 1.000 MB por petición.

---

## 5. TECNOLOGÍAS Y ENTORNO DE DESARROLLO

### 5.1. Stack Tecnológico

```
┌────────────────────────────────────────────────────────┐
│                   CAPA CLIENTE (FRONTEND)              │
│       HTML5 Semántico + CSS3 (Flex/Grid) + JS ES6+     │
└───────────────────────────┬────────────────────────────┘
                            │ Peticiones HTTP / Form POST / JSON
┌───────────────────────────▼────────────────────────────┐
│                  CAPA SERVIDOR (BACKEND)               │
│               PHP 8.x + Apache HTTP Server             │
└───────────────────────────┬────────────────────────────┘
                            │ Consultas SQL (Prepared Statements)
┌───────────────────────────▼────────────────────────────┐
│                  CAPA DATOS (BASE DE DATOS)            │
│               MySQL / MariaDB (Motor InnoDB)           │
└────────────────────────────────────────────────────────┘
```

- **Frontend**:
  - **HTML5**: Estructuración semántica (`<header>`, `<nav>`, `<section>`, `<article>`, `<video>`).
  - **CSS3**: Diseño moderno con variables CSS, sombras suaves, bordes redondeados y adaptabilidad responsiva con Media Queries.
  - **JavaScript (Vanilla ES6+)**: Manipulación dinámica del DOM, listeners de eventos, cálculo asíncrono y matemáticas de nutrientes en tiempo real.
- **Backend**:
  - **PHP 8.x**: Lenguaje de servidor robusto y versátil para el procesamiento de peticiones, gestión de archivos multipart y comunicación con la base de datos.
- **Base de Datos**:
  - **MySQL / MariaDB**: Sistema de gestión de bases de datos relacional con motor **InnoDB**, garantizando cumplimiento ACID y claves foráneas en cascada.
- **Entorno de Ejecución**:
  - **XAMPP**: Paquete de distribución local de Apache, MariaDB y PHP.

---

## 6. DISEÑO Y ARQUITECTURA DEL SISTEMA

### 6.1. Diagrama Entidad - Relación (E/R)

```
 [CLIENTE / ENTRENADOR]
           │
           ├──────────────────────────────┐
           ▼ 1                            ▼ 1
     ┌───────────┐                  ┌───────────┐
     │  RUTINAS  │                  │  DIETAS   │
     └─────┬─────┘                  └─────┬─────┘
           │ 1                            │ 1
           ▼ N                            ▼ N
 ┌───────────────────┐              ┌───────────┐
 │ RUTINA_EJERCICIOS │              │DIETA_DIAS │
 └─────────┬─────────┘              └─────┬─────┘
           │ 1                            │ 1
           ▼ N                            ▼ N
   ┌───────────────┐               ┌───────────────┐        N      1 ┌─────────────┐
   │ RUTINA_SERIES │               │ DIETA_COMIDAS │>────────────────│  ALIMENTOS  │
   └───────────────┘               └───────────────┘                 └─────────────┘
```

### 6.2. Diccionario de Datos Físico

#### Tabla 1: `alimentos`
Almacena el catálogo de alimentos y sus propiedades nutricionales base por cada 100g.
| Campo | Tipo | Nulo | Clave | Descripción |
| :--- | :--- | :---: | :---: | :--- |
| `id` | INT AUTO_INCREMENT | NO | PK | Identificador único del alimento |
| `nombre` | VARCHAR(120) | NO | | Nombre descriptivo del alimento |
| `calorias` | DECIMAL(6,2) | NO | | Energía en Kcal por cada 100 gramos |
| `proteinas` | DECIMAL(5,2) | NO | | Proteína en gramos por cada 100g |
| `carbohidratos` | DECIMAL(5,2) | NO | | Carbohidratos en gramos por cada 100g |
| `grasas` | DECIMAL(5,2) | NO | | Grasas en gramos por cada 100g |
| `categoria` | VARCHAR(60) | NO | | Carnes, Cereales, Frutas, Lácteos, etc. |
| `created_at` | TIMESTAMP | NO | | Fecha de creación del registro |

#### Tabla 2: `rutinas`
Cabecera identificativa de la rutina asignada a un cliente.
| Campo | Tipo | Nulo | Clave | Descripción |
| :--- | :--- | :---: | :---: | :--- |
| `id` | INT AUTO_INCREMENT | NO | PK | Identificador único de la rutina |
| `cliente_nombre` | VARCHAR(120) | NO | | Nombre completo del cliente |
| `cliente_telefono` | VARCHAR(30) | SÍ | | Teléfono móvil para WhatsApp |
| `cliente_email` | VARCHAR(120) | SÍ | | Correo para notificaciones |
| `titulo` | VARCHAR(150) | NO | | Nombre o fase de la rutina |
| `notas` | TEXT | SÍ | | Pautas globales de entrenamiento |
| `fecha` | TIMESTAMP | NO | | Fecha de registro |

#### Tabla 3: `rutina_ejercicios`
Ejercicios incluidos dentro de una rutina.
| Campo | Tipo | Nulo | Clave | Descripción |
| :--- | :--- | :---: | :---: | :--- |
| `id` | INT AUTO_INCREMENT | NO | PK | Identificador único del ejercicio |
| `rutina_id` | INT | NO | FK | Clave foránea referenciando a `rutinas(id)` |
| `ejercicio` | VARCHAR(150) | NO | | Nombre del ejercicio técnico |
| `series` | INT | NO | | Número total de series planificadas |
| `repeticiones` | VARCHAR(60) | NO | | Resumen general de repeticiones |
| `peso` | VARCHAR(60) | SÍ | | Peso resumen de referencia |
| `descanso` | VARCHAR(60) | SÍ | | Tiempo de descanso entre series (ej. 90s) |
| `video_url` | TEXT | SÍ | | Ruta local del vídeo o URL externa |
| `notas` | VARCHAR(255) | SÍ | | Indicaciones de técnica o RPE |

#### Tabla 4: `rutina_series`
Desglose detallado serie por serie de cada ejercicio.
| Campo | Tipo | Nulo | Clave | Descripción |
| :--- | :--- | :---: | :---: | :--- |
| `id` | INT AUTO_INCREMENT | NO | PK | Identificador único de la serie |
| `ejercicio_id` | INT | NO | FK | Clave foránea referenciando a `rutina_ejercicios(id)` |
| `rutina_id` | INT | NO | FK | Clave foránea referenciando a `rutinas(id)` |
| `num_serie` | INT | NO | | Número secuencial (1, 2, 3...) |
| `peso` | VARCHAR(60) | NO | | Carga específica en kg o lbs |
| `repeticiones` | VARCHAR(60) | NO | | Rango de repeticiones de la serie |
| `notas` | VARCHAR(100) | SÍ | | Tipo de serie (Aproximación, RPE 8, Fallo) |

#### Tabla 5: `dietas`
Cabecera del plan de alimentación de un cliente.
| Campo | Tipo | Nulo | Clave | Descripción |
| :--- | :--- | :---: | :---: | :--- |
| `id` | INT AUTO_INCREMENT | NO | PK | Identificador único del plan nutricional |
| `cliente_nombre` | VARCHAR(120) | NO | | Nombre completo del cliente |
| `cliente_telefono` | VARCHAR(30) | SÍ | | Teléfono para WhatsApp |
| `cliente_email` | VARCHAR(120) | SÍ | | Email del cliente |
| `titulo` | VARCHAR(150) | NO | | Nombre del plan (ej. Definición 1800 kcal) |
| `notas` | TEXT | SÍ | | Recomendaciones nutricionales |
| `total_calorias` | DECIMAL(7,2) | SÍ | | Calorías totales de referencia |
| `total_proteinas` | DECIMAL(6,2) | SÍ | | Proteínas totales de referencia |
| `total_carbos` | DECIMAL(6,2) | SÍ | | Carbohidratos totales de referencia |
| `total_grasas` | DECIMAL(6,2) | SÍ | | Grasas totales de referencia |
| `fecha` | TIMESTAMP | NO | | Fecha de creación |

#### Tabla 6: `dieta_dias`
Opciones de menú o días de entrenamiento/descanso dentro de una dieta.
| Campo | Tipo | Nulo | Clave | Descripción |
| :--- | :--- | :---: | :---: | :--- |
| `id` | INT AUTO_INCREMENT | NO | PK | Identificador único del día/opción |
| `dieta_id` | INT | NO | FK | Clave foránea referenciando a `dietas(id)` |
| `nombre_dia` | VARCHAR(120) | NO | | Nombre (Día 1, Opción A, Día Descanso) |
| `orden` | INT | NO | | Orden de visualización secuencial |

#### Tabla 7: `dieta_comidas`
Alimentos asignados a un día específico y toma horaria.
| Campo | Tipo | Nulo | Clave | Descripción |
| :--- | :--- | :---: | :---: | :--- |
| `id` | INT AUTO_INCREMENT | NO | PK | Identificador único de la línea de comida |
| `dieta_id` | INT | NO | FK | Clave foránea a `dietas(id)` |
| `dia_id` | INT | SÍ | FK | Clave foránea a `dieta_dias(id)` |
| `comida_tipo` | VARCHAR(60) | NO | | Desayuno, Media mañana, Comida, Merienda, Cena |
| `alimento_nombre`| VARCHAR(120) | NO | | Nombre del alimento seleccionado |
| `gramos` | DECIMAL(6,2) | NO | | Cantidad neta en gramos |
| `calorias` | DECIMAL(6,2) | NO | | Calorías calculadas proporcionalmente |
| `proteinas` | DECIMAL(5,2) | NO | | Proteínas calculadas proporcionalmente |
| `carbohidratos` | DECIMAL(5,2) | NO | | Carbohidratos calculados proporcionalmente |
| `grasas` | DECIMAL(5,2) | NO | | Grasas calculadas proporcionalmente |

---

## 7. DESARROLLO E IMPLEMENTACIÓN DE MÓDULOS

### 7.1. Módulo 1: Portada y Presentación Corporativa (`index.html`)
Diseñada como punto de entrada profesional para la marca. Incorpora:
- **Hero Banner con Degradado Premium**: Llamada a la acción inmediata a los tres módulos principales.
- **Barra de Estadísticas**: Puntos clave de propuesta de valor (envío en 1 clic, cálculo en vivo, PDF).
- **Cuadrícula de Servicios**: Enlaces rápidos directos hacia Rutinas, Dietas y Alimentos.
- **Flujo de Proceso en 3 Pasos**: Demostración visual de la simplicidad de uso de la herramienta.
- **Bloque de Asistencia**: Canales de contacto directos.

### 7.2. Módulo 2: Base de Datos Nutricional (`alimentos.php`)
Permite al entrenador mantener su catálogo de alimentos personalizado:
- Búsqueda y filtrado en tiempo real sin recargar página mediante eventos `keyup` e `input`.
- Inserción y edición de macronutrientes por 100g con persistencia en MySQL.
- Precarga automática de 15 alimentos comunes de muestra si la base de datos es nueva.

### 7.3. Módulo 3: Planificador de Rutinas con Vídeos y Series (`musculatura.php`)
- **4 Ejercicios Preparados de Partida**: Cada ejercicio cuenta con su propia tarjeta, insignia de orden y caja de vídeo independiente.
- **Sin Límite de Ejercicios**: Botón dinámico que clona y genera nuevos bloques de ejercicio con identificador único en el DOM.
- **Series Individualizadas**: En cada ejercicio se configuran de 1 a $N$ series especificando el peso exacto en kilos y las repeticiones planificadas.
- **Soporte Multimedia**: Carga de archivo de vídeo local (con indicador de tamaño en MB) o campo para URL externa de Google Drive o YouTube.

### 7.4. Módulo 4: Gestor de Dietas Multi-Día (`nutricion.php`)
- **Pestañas Dinámicas de Menús**: Creación de múltiples días u opciones dentro del mismo expediente del cliente.
- **Cálculo Reactivo de Macronutrientes**:
  $$\text{Calorías} = \frac{\text{Calorías}_{100g} \times \text{Gramos}}{100}$$
  El total se computa de forma reactiva ante cualquier cambio en el selector de alimento o en el valor numérico de gramos.
- **Fila de Suma Total**: Cada menú incluye una fila inferior que refleja la suma exacta de Kcal, Proteínas, Carbohidratos y Grasas.

### 7.5. Módulo 5: Visor de Clientes, Reproductor Multimedia y Envío (`ver_rutina.php`, `ver_dieta.php`)
- **Reproductor de Vídeo Integrado**: Botón `🎥 Video` que despliega un reproductor HTML5 con controles completos (`controls`, `playsinline`, pantalla completa) para reproducir el vídeo sin salir de la web.
- **Banner de Totales Superiores**: En la dieta, muestra las tarjetas de calorías, proteínas, carbohidratos y grasas totales, sincronizándose dinámicamente cuando el cliente pulsa entre los botones de `Día 1`, `Día 2`, etc.

### 7.6. Módulo 6: Integración con WhatsApp API y PDF
- **Codificación URL para WhatsApp**: El texto de la rutina o de la dieta se procesa con emojis, sangrías y saltos de línea para generar un enlace universal `https://api.whatsapp.com/send?phone=...&text=...`.
- **Estilos `@media print`**: Optimizados para eliminar menús de navegación, botones de envío y barras de desplazamiento, produciendo una ficha limpia para exportar a PDF o imprimir en papel.

---

## 8. SEGURIDAD, RENDIMIENTO Y BUENAS PRÁCTICAS

1. **Sentencias Preparadas (`Prepared Statements`)**:
   Todas las interacciones de base de datos (`SELECT`, `INSERT`, `UPDATE`, `DELETE`) emplean `mysqli::prepare` y enlace de tipos mediante `bind_param` (`s`, `i`, `d`), neutralizando vectores de inyección SQL.
2. **Sanitización de Salidas (`XSS`)**:
   Todo texto susceptible de manipulación por parte del usuario pasa por la función `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')` antes de su renderizado en el navegador.
3. **Manejo Seguro de Subidas de Archivos**:
   - Validación de extensiones permitidas: `['mp4', 'webm', 'mov', 'avi', 'mkv', 'm4v']`.
   - Renombrado criptoseguro: `ejercicio_TIMESTAMP_RAND.ext` para prevenir colisiones de nombres o ejecución maliciosa de scripts PHP ocultos en nombres de archivo.
   - Creación automática y permisos controlados del directorio `uploads/videos/`.
4. **Configuración del Servidor para Grandes Archivos**:
   Archivo `.htaccess` con directivas para soportar cargas pesadas sin interrupción:
   ```apache
   php_value upload_max_filesize 500M
   php_value post_max_size 1000M
   php_value max_execution_time 600
   php_value max_file_uploads 100
   php_value memory_limit 512M
   ```

---

## 9. MANUAL DE INSTALACIÓN Y DESPLIEGUE

### Requisitos Previos:
- Servidor web Apache con módulo PHP 8.0 o superior.
- Gestor de base de datos MySQL 5.7+ o MariaDB 10.4+.
- Paquete XAMPP recomendado en entornos Windows / Linux / macOS.

### Pasos de Despliegue:
1. Clonar o copiar la carpeta del proyecto en la ruta raíz del servidor web:
   ```text
   C:\xampp\htdocs\paginaweb\
   ```
2. Iniciar los servicios **Apache** y **MySQL** desde el panel de control de XAMPP.
3. Abrir el navegador web y acceder a la dirección:
   ```text
   http://localhost/paginaweb/
   ```
4. **Inicialización Automática**: El script `php/conexion.php` se encargará de forma autónoma de:
   - Crear la base de datos `migym` si no existe.
   - Crear las 7 tablas con sus restricciones de clave foránea.
   - Aplicar migraciones de columnas (`categoria`, `video_url`, etc.).
   - Sembrar el catálogo con 15 alimentos iniciales.
   - Crear la carpeta de almacenamiento `uploads/videos/`.

---

## 10. PRUEBAS Y VERIFICACIÓN DEL SISTEMA

Se han diseñado y ejecutado casos de prueba para validar el comportamiento del sistema:

| ID Caso | Módulo | Acción Evaluada | Resultado Esperado | Estado |
| :---: | :--- | :--- | :--- | :---: |
| **CP-01** | Base de Datos | Conexión sin base de datos creada | Autocreación de DB y tablas sin intervención manual | **SUPERADO** |
| **CP-02** | Alimentos | Búsqueda en vivo de "Pollo" | Filtrado reactivo en menos de 100ms | **SUPERADO** |
| **CP-03** | Rutinas | Inserción de ejercicio con 4 series de pesos variables | Guardado y relación correcta en `rutina_series` | **SUPERADO** |
| **CP-04** | Multimedia | Subida de vídeo MP4 de 5 MB | Almacenamiento en `uploads/videos/` y reproducción fluida | **SUPERADO** |
| **CP-05** | Dietas | Cálculo de macros para 200g de arroz | Proporcionalidad exacta según tabla de alimentos | **SUPERADO** |
| **CP-06** | Dietas | Cambio de pestaña entre Día 1 y Día 2 | Actualización instantánea del banner de totales superior | **SUPERADO** |
| **CP-07** | Mensajería | Clic en botón "Enviar por WhatsApp" | Apertura de chat con texto enriquecido formateado | **SUPERADO** |
| **CP-08** | Exportación | Vista de impresión `Ctrl + P` | Ocultación de botones y diseño limpio para PDF | **SUPERADO** |

---

## 11. CONCLUSIONES Y LÍNEAS FUTURAS

### 11.1. Conclusiones
El desarrollo del proyecto **Mi Gym** ha permitido aplicar de forma práctica e integrada las competencias adquiridas en el ciclo formativo de Grado Superior en Desarrollo de Aplicaciones Web:
- Diseño de interfaces atractivas, funcionales y accesibles mediante estándares web modernos.
- Lógica de negocio eficiente y modular en el servidor con PHP.
- Modelado y administración de bases de datos relacionales eficientes.
- Integración de servicios web de terceros (WhatsApp API) y APIs multimedia nativas del navegador.

El resultado es un producto completamente funcional, validado por un usuario real (entrenador personal) y preparado para su puesta en producción.

### 11.2. Líneas Futuras de Desarrollo
- **Módulo de Autenticación de Clientes**: Área privada de login para que cada cliente registre sus marcas y pesos levantados en cada entrenamiento.
- **Gráficas de Evolución Temporal**: Integración de bibliotecas gráficas (ej. Chart.js) para monitorizar el peso corporal y la sobrecarga progresiva.
- **Pasarela de Pagos**: Integración con Stripe o RedSys para gestionar suscripciones o cobros de asesorías directamente desde la web.
- **PWA (Progressive Web App)**: Implementación de Service Workers para permitir acceso sin conexión a internet desde el móvil del cliente en el gimnasio.

---

## 12. BIBLIOGRAFÍA Y WEBGRAFÍA
1. **The PHP Group**: Documentación Oficial de PHP 8.x (*https://www.php.net/docs.php*).
2. **Mozilla Developer Network (MDN)**: Guías de HTML5, CSS3 y JavaScript Moderno (*https://developer.mozilla.org/*).
3. **MySQL Documentation**: Manual de Referencia de MySQL 8.0 e InnoDB (*https://dev.mysql.com/doc/*).
4. **W3C**: Estándares de Accesibilidad Web (WCAG 2.1) y Responsive Web Design.
5. **WhatsApp Developers**: Directrices para enlaces Click to Chat (*https://faq.whatsapp.com/*).
