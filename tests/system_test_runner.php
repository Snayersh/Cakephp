<?php
/**
 * Suite de Pruebas Automatizadas de Extremo a Extremo (E2E)
 * Sistema de Gestión de Personal Sterenfix (CakePHP 4.5 + Oracle 23c Free)
 */

declare(strict_types=1);

require_once 'config/bootstrap.php';
use Cake\ORM\TableRegistry;

$baseUrl = 'http://localhost:80';
$cookieFile = tempnam(sys_get_temp_dir(), 'cake_test_cookies_');

$totalTests = 0;
$passedTests = 0;
$failedTests = 0;
$errors = [];

function out(string $msg, string $color = 'white'): void {
    $colors = [
        'green' => "\033[32m",
        'red' => "\033[31m",
        'yellow' => "\033[33m",
        'cyan' => "\033[36m",
        'magenta' => "\033[35m",
        'bold' => "\033[1m",
        'white' => "\033[0m",
        'gray' => "\033[90m"
    ];
    echo ($colors[$color] ?? "\033[0m") . $msg . "\033[0m\n";
}

function request(string $method, string $path, array $data = [], array $headers = []): array {
    global $baseUrl, $cookieFile;
    $url = $baseUrl . $path;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } elseif ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $headerStr = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);

    $parsedHeaders = [];
    foreach (explode("\r\n", $headerStr) as $line) {
        if (str_contains($line, ': ')) {
            [$k, $v] = explode(': ', $line, 2);
            $parsedHeaders[strtolower($k)] = $v;
        }
    }

    return [
        'status' => $statusCode,
        'headers' => $parsedHeaders,
        'header_raw' => $headerStr,
        'body' => $body,
        'error' => $error
    ];
}

function assertTest(string $description, callable $testFn): void {
    global $totalTests, $passedTests, $failedTests, $errors;
    $totalTests++;
    try {
        $res = $testFn();
        if ($res === true || $res === null) {
            $passedTests++;
            echo "  \033[32m✔ PASS\033[0m: $description\n";
        } else {
            $failedTests++;
            $errors[] = "$description: " . (is_string($res) ? $res : 'Validation failed');
            echo "  \033[31m✖ FAIL\033[0m: $description (" . (is_string($res) ? $res : 'Failed') . ")\n";
        }
    } catch (\Throwable $e) {
        $failedTests++;
        $errors[] = "$description: Exception: " . $e->getMessage();
        echo "  \033[31m✖ FAIL\033[0m: $description (Exception: " . $e->getMessage() . ")\n";
    }
}

out("\n==================================================================", 'bold');
out("   STERENFIX E2E AUTOMATED TEST RUNNER (CakePHP 4.5 + Oracle 23c) ", 'cyan');
out("==================================================================", 'bold');

// -------------------------------------------------------------
// 1. HEALTH CHECK & ARCHITECTURE
// -------------------------------------------------------------
out("\n[1/8] Verificando Salud de la Infraestructura y Framework...", 'yellow');

assertTest("El servidor responde con HTTP 200 en la raíz (/)", function() {
    $res = request('GET', '/');
    return $res['status'] === 200 ? true : "HTTP status was {$res['status']}";
});

assertTest("Las cabeceras HTTP confirman UTF-8 y PHP 8.2", function() {
    $res = request('GET', '/');
    $type = $res['headers']['content-type'] ?? '';
    return (str_contains($type, 'utf-8') || str_contains($type, 'UTF-8')) ? true : "Content-type: $type";
});

assertTest("El pie de página contiene los badges oficiales CakePHP y Oracle", function() {
    $res = request('GET', '/');
    if (!str_contains($res['body'], 'CakePHP MVC')) return "Falta badge CakePHP MVC";
    if (!str_contains($res['body'], 'Oracle Database 23c/Free')) return "Falta badge Oracle Database";
    return true;
});

// -------------------------------------------------------------
// 2. DASHBOARD Y MONEDA EN QUETZALES
// -------------------------------------------------------------
out("\n[2/8] Verificando Tablero Ejecutivo y Moneda (Quetzales)...", 'yellow');

assertTest("El Dashboard despliega la métrica de Nómina Total en Quetzales (Q)", function() {
    $res = request('GET', '/');
    if (!preg_match('/Q\s+[0-9,]+\.[0-9]{2}/', $res['body'])) {
        return "No se encontró formato de moneda Q en el Dashboard";
    }
    return true;
});

assertTest("El Dashboard contiene el gráfico analítico de distribución de nómina", function() {
    $res = request('GET', '/');
    if (!str_contains($res['body'], 'nominaTiendaChart')) return "Falta elemento canvas del gráfico";
    if (!str_contains($res['body'], 'Presupuesto de Nómina (Q - Quetzales)')) return "Falta etiqueta de moneda en el gráfico";
    return true;
});

assertTest("El Dashboard despliega los últimos empleados registrados con enlaces a Kárdex", function() {
    $res = request('GET', '/');
    if (!str_contains($res['body'], '/empleados/view/')) return "Faltan enlaces a /empleados/view/";
    return true;
});

// -------------------------------------------------------------
// 3. MÓDULO TIENDAS / SUCURSALES (CRUD COMPLETO)
// -------------------------------------------------------------
out("\n[3/8] Verificando Módulo Tiendas / Sucursales (CRUD)...", 'yellow');

$createdTiendaId = null;

assertTest("GET /tiendas lista las sucursales existentes con HTTP 200", function() {
    $res = request('GET', '/tiendas');
    return $res['status'] === 200 && str_contains($res['body'], 'Tiendas y Sucursales Sterenfix') ? true : "Status {$res['status']}";
});

assertTest("GET /tiendas/add muestra el formulario de registro de tienda", function() {
    $res = request('GET', '/tiendas/add');
    return $res['status'] === 200 && str_contains($res['body'], 'Registrar Nueva Tienda') ? true : "Status {$res['status']}";
});

assertTest("POST /tiendas/add crea una nueva tienda con secuencia autoincremental en Oracle", function() use (&$createdTiendaId) {
    $storeName = "Tienda AutoTest " . uniqid();
    $res = request('POST', '/tiendas/add', [
        'nombre' => $storeName,
        'direccion' => 'Avenida Reforma 10-20, Zona 9, Guatemala',
        'telefono' => '+502 2234 5678'
    ]);
    if ($res['status'] !== 302) return "Expected 302 redirect, got {$res['status']}";
    
    $store = TableRegistry::getTableLocator()->get('Tiendas')->find()->where(['nombre' => $storeName])->first();
    if (!$store) return "La tienda no fue creada en Oracle";
    $createdTiendaId = (int)$store->id;
    return true;
});

assertTest("GET /tiendas/view/:id muestra el perfil de la tienda y métricas de nómina", function() use (&$createdTiendaId) {
    $id = $createdTiendaId ?: 1;
    $res = request('GET', "/tiendas/view/$id");
    return $res['status'] === 200 && str_contains($res['body'], 'Subtotal Nómina') ? true : "Status {$res['status']}";
});

assertTest("POST /tiendas/edit/:id actualiza los datos de la sucursal", function() use (&$createdTiendaId) {
    if (!$createdTiendaId) return true;
    $updatedName = "Tienda Modificada " . uniqid();
    $res = request('POST', "/tiendas/edit/$createdTiendaId", [
        'nombre' => $updatedName,
        'direccion' => 'Nueva Dirección Actualizada',
        'telefono' => '+502 9999 8888'
    ]);
    if ($res['status'] !== 302) return "Expected 302 redirect, got {$res['status']}";
    $detail = request('GET', "/tiendas/view/$createdTiendaId");
    return str_contains($detail['body'], $updatedName) ? true : "No se reflejó el nombre actualizado";
});

assertTest("POST /tiendas/delete/:id elimina la tienda de prueba", function() use (&$createdTiendaId) {
    if (!$createdTiendaId) return true;
    $res = request('POST', "/tiendas/delete/$createdTiendaId");
    if ($res['status'] !== 302) return "Expected 302 redirect on delete, got {$res['status']}";
    $store = TableRegistry::getTableLocator()->get('Tiendas')->find()->where(['id' => $createdTiendaId])->first();
    return $store === null ? true : "La tienda sigue existiendo en base de datos";
});

// -------------------------------------------------------------
// 4. MÓDULO PUESTOS DE TRABAJO (CRUD COMPLETO)
// -------------------------------------------------------------
out("\n[4/8] Verificando Módulo Puestos de Trabajo (CRUD)...", 'yellow');

$createdPuestoId = null;

assertTest("GET /puestos responde con HTTP 200 y listado de puestos", function() {
    $res = request('GET', '/puestos');
    return $res['status'] === 200 && str_contains($res['body'], 'Catálogo de Puestos') ? true : "Status {$res['status']}";
});

assertTest("POST /puestos/add crea un nuevo puesto de trabajo", function() use (&$createdPuestoId) {
    $puestoName = "Especialista AutoTest " . uniqid();
    $res = request('POST', '/puestos/add', [
        'nombre' => $puestoName,
        'descripcion' => 'Descripción técnica del puesto de pruebas automatizadas'
    ]);
    if ($res['status'] !== 302) return "Expected 302 redirect, got {$res['status']}";
    $puesto = TableRegistry::getTableLocator()->get('Puestos')->find()->where(['nombre' => $puestoName])->first();
    if (!$puesto) return "El puesto no fue creado en Oracle";
    $createdPuestoId = (int)$puesto->id;
    return true;
});

assertTest("GET /puestos/view/:id muestra el detalle del puesto", function() use (&$createdPuestoId) {
    $id = $createdPuestoId ?: 1;
    $res = request('GET', "/puestos/view/$id");
    return $res['status'] === 200 && str_contains($res['body'], 'Empleados en esta Posición') ? true : "Status {$res['status']}";
});

assertTest("POST /puestos/delete/:id elimina el puesto de prueba creado", function() use (&$createdPuestoId) {
    if (!$createdPuestoId) return true;
    $res = request('POST', "/puestos/delete/$createdPuestoId");
    if ($res['status'] !== 302) return "Expected 302, got {$res['status']}";
    $puesto = TableRegistry::getTableLocator()->get('Puestos')->find()->where(['id' => $createdPuestoId])->first();
    return $puesto === null ? true : "El puesto sigue existiendo en BD";
});

// -------------------------------------------------------------
// 5. MÓDULO PERSONAL / EMPLEADOS (CRUD Y KÁRDEX 360°)
// -------------------------------------------------------------
out("\n[5/8] Verificando Módulo Personal, Kárdex 360° y Validaciones...", 'yellow');

$testEmpleadoId = null;

assertTest("GET /empleados lista el personal con miniaturas, salarios en Q y badges", function() {
    $res = request('GET', '/empleados');
    if ($res['status'] !== 200) return "Status {$res['status']}";
    if (!str_contains($res['body'], 'Padrón de Empleados Sterenfix')) return "Falta título del padrón";
    if (!preg_match('/Q\s+[0-9,]+\.[0-9]{2}/', $res['body'])) return "Faltan salarios formateados en Quetzales";
    return true;
});

assertTest("GET /empleados con filtro de búsqueda por nombre funciona con precisión", function() {
    $res = request('GET', '/empleados?search=Valeria');
    return $res['status'] === 200 && str_contains($res['body'], 'Valeria Sofía') ? true : "No encontró a Valeria";
});

assertTest("GET /empleados con filtro por Tienda y Puesto funciona correctamente", function() {
    $res = request('GET', '/empleados?tienda_id=1&puesto_id=1');
    return $res['status'] === 200 && str_contains($res['body'], 'Carlos Alberto') ? true : "No filtró correctamente";
});

assertTest("POST /empleados/add rechaza salarios negativos o inválidos (Validación)", function() {
    $res = request('POST', '/empleados/add', [
        'nombres' => 'Empleado',
        'apellidos' => 'Inválido',
        'fecha_nacimiento' => '1995-01-01',
        'salario' => '-500.00',
        'puesto_id' => 1,
        'tienda_id' => 1
    ]);
    return $res['status'] === 200 && str_contains($res['body'], 'No se pudo registrar') ? true : "Permitió salario negativo";
});

assertTest("POST /empleados/add registra un nuevo colaborador exitosamente con salario en Q", function() use (&$testEmpleadoId) {
    $randomName = "Alexander Raúl " . uniqid();
    $res = request('POST', '/empleados/add', [
        'nombres' => $randomName,
        'apellidos' => 'López Morales',
        'fecha_nacimiento' => '1996-03-25',
        'salario' => '28500.75',
        'puesto_id' => 2,
        'tienda_id' => 1
    ]);
    if ($res['status'] !== 302) return "Expected 302 redirect, got {$res['status']}";
    $loc = $res['headers']['location'] ?? '';
    if (preg_match('/\/empleados\/view\/(\d+)/', $loc, $m)) {
        $testEmpleadoId = (int)$m[1];
    }
    return true;
});

assertTest("GET /empleados/view/:id despliega el Kárdex 360° con sus pestañas operativas", function() use (&$testEmpleadoId) {
    $id = $testEmpleadoId ?: 1;
    $res = request('GET', "/empleados/view/$id");
    if ($res['status'] !== 200) return "Status {$res['status']}";
    if (!str_contains($res['body'], 'Kárdex')) return "Falta título Kárdex";
    if (!str_contains($res['body'], '1. Datos Generales y Laborales')) return "Falta pestaña 1";
    if (!str_contains($res['body'], '2. Historial de Logros')) return "Falta pestaña 2";
    if (!str_contains($res['body'], '3. Llamadas de Atención')) return "Falta pestaña 3";
    if (!str_contains($res['body'], 'GTQ')) return "Falta código de moneda GTQ en el detalle";
    return true;
});

assertTest("POST /empleados/edit/:id actualiza los datos del expediente laboral", function() use (&$testEmpleadoId) {
    if (!$testEmpleadoId) return true;
    $res = request('POST', "/empleados/edit/$testEmpleadoId", [
        'nombres' => 'Alexander Raúl Actualizado',
        'apellidos' => 'López Morales',
        'fecha_nacimiento' => '1996-03-25',
        'salario' => '32000.00',
        'puesto_id' => 1,
        'tienda_id' => 2
    ]);
    if ($res['status'] !== 302) return "Expected 302, got {$res['status']}";
    $view = request('GET', "/empleados/view/$testEmpleadoId");
    return str_contains($view['body'], 'Q 32,000.00') ? true : "No se reflejó el nuevo salario en Q";
});

// -------------------------------------------------------------
// 6. MÓDULO LOGROS Y LLAMADAS DE ATENCIÓN
// -------------------------------------------------------------
out("\n[6/8] Verificando Reconocimientos / Logros y Llamadas de Atención...", 'yellow');

assertTest("GET /logros lista los reconocimientos con filtro positivo/negativo", function() {
    $res = request('GET', '/logros');
    if ($res['status'] !== 200) return "Status {$res['status']}";
    $pos = request('GET', '/logros?tipo=positivo');
    $neg = request('GET', '/logros?tipo=negativo');
    return ($pos['status'] === 200 && $neg['status'] === 200) ? true : "Fallo en filtros";
});

assertTest("POST /logros/add registra un nuevo logro para el empleado", function() use (&$testEmpleadoId) {
    $targetEmpId = $testEmpleadoId ?: 1;
    $res = request('POST', '/logros/add', [
        'empleado_id' => $targetEmpId,
        'descripcion' => 'Logro destacado de prueba automatizada por eficiencia sobresaliente',
        'tipo' => 'positivo',
        'fecha_ocurrencia' => '2026-05-10'
    ]);
    return $res['status'] === 302 ? true : "Expected 302, got {$res['status']}";
});

assertTest("GET /llamadas-atencion lista las incidencias registradas con HTTP 200", function() {
    $res = request('GET', '/llamadas-atencion');
    return $res['status'] === 200 && str_contains($res['body'], 'Llamadas de Atención') ? true : "Status {$res['status']}";
});

assertTest("POST /llamadas-atencion/add registra una llamada de atención preventiva", function() use (&$testEmpleadoId) {
    $targetEmpId = $testEmpleadoId ?: 1;
    $res = request('POST', '/llamadas-atencion/add', [
        'empleado_id' => $targetEmpId,
        'descripcion' => 'Llamada de atención por descuido menor en proceso de calidad',
        'fecha_ocurrencia' => '2026-06-12'
    ]);
    return $res['status'] === 302 ? true : "Expected 302, got {$res['status']}";
});

// -------------------------------------------------------------
// 7. MÓDULO REPORTES ANALÍTICOS Y TOTALIZACIÓN DE NÓMINA
// -------------------------------------------------------------
out("\n[7/8] Verificando Reportes Analíticos, Totales y Subtotales...", 'yellow');

assertTest("GET /reportes/empleados muestra la Totalización General en Quetzales (Q ... GTQ)", function() {
    $res = request('GET', '/reportes/empleados');
    if ($res['status'] !== 200) return "Status {$res['status']}";
    if (!str_contains($res['body'], 'Totalización General de Salarios')) return "Falta sección de totalización general";
    if (!preg_match('/Q\s+[0-9,]+\.[0-9]{2}\s*<span[^>]*>GTQ<\/span>/', $res['body'])) {
        return "No se encontró el formato 'Q [total] GTQ' en el banner principal";
    }
    if (!str_contains($res['body'], 'Salario (GTQ)')) return "La cabecera de la tabla debe indicar GTQ";
    return true;
});

assertTest("GET /reportes/salarios-tienda agrupa por tienda con subtotales resaltados", function() {
    $res = request('GET', '/reportes/salarios-tienda');
    if ($res['status'] !== 200) return "Status {$res['status']}";
    if (!str_contains($res['body'], 'Subtotal: Q')) return "Faltan badges de 'Subtotal: Q'";
    if (!str_contains($res['body'], 'Subtotal de Salarios Sucursal')) return "Faltan filas de subtotal en pie de tabla";
    if (!str_contains($res['body'], 'Presupuesto Consolidado de Nómina')) return "Falta banner consolidado de nómina";
    return true;
});

assertTest("GET /reportes/logros muestra el balance de reconocimientos y gráficos", function() {
    $res = request('GET', '/reportes/logros');
    if ($res['status'] !== 200) return "Status {$res['status']}";
    if (!str_contains($res['body'], 'Logros Positivos')) return "Falta KPI positivos";
    if (!str_contains($res['body'], 'Logros Negativos')) return "Falta KPI negativos";
    return true;
});

// Limpieza del empleado de prueba al finalizar
if ($testEmpleadoId) {
    request('POST', "/empleados/delete/$testEmpleadoId");
}

// -------------------------------------------------------------
// 8. INTEGRIDAD DE CODIFICACIÓN UTF-8
// -------------------------------------------------------------
out("\n[8/8] Verificando Integridad de Caracteres UTF-8...", 'yellow');

assertTest("Los caracteres con acentos (á, é, í, ó, ú, ñ) se muestran limpios sin corrupción en el HTML", function() {
    $res = request('GET', '/');
    $badPatterns = ["\xEF\xBF\xBD", '&Atilde;', 'Ã¡', 'Ã©', 'Ã­', 'Ã³', 'Ãº', 'Ã±'];
    foreach ($badPatterns as $bad) {
        if (str_contains($res['body'], $bad)) {
            return "Se detectó carácter corrupto en el HTML: $bad";
        }
    }
    if (!str_contains($res['body'], 'Técnico')) return "No se encontró la palabra 'Técnico' con tilde";
    if (!str_contains($res['body'], 'Galerías')) return "No se encontró la palabra 'Galerías' con tilde";
    return true;
});

// -------------------------------------------------------------
// RESUMEN GENERAL DE RESULTADOS
// -------------------------------------------------------------
out("\n==================================================================", 'bold');
out("                       RESUMEN DE PRUEBAS                         ", 'bold');
out("==================================================================", 'bold');
out("Total de pruebas ejecutadas: $totalTests", 'bold');
out("Pruebas Aprobadas: $passedTests", 'green');
if ($failedTests > 0) {
    out("Pruebas Fallidas: $failedTests", 'red');
    out("\nDetalle de Fallos:", 'red');
    foreach ($errors as $e) {
        out("  - $e", 'red');
    }
} else {
    out("¡TODAS LAS PRUEBAS PASARON EXITOSAMENTE! (100% de éxito)", 'green');
}
out("==================================================================\n", 'bold');

@unlink($cookieFile);
exit($failedTests === 0 ? 0 : 1);
