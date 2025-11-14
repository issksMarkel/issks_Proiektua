<?php
function aplicarCabecerasSeguridad() {
    // Verificar si las cabeceras ya fueron enviadas
    if (headers_sent()) {
        error_log("Advertencia: No se pudieron aplicar cabeceras de seguridad - ya se enviaron cabeceras");
        return false;
    }
    
    // NO aplicar CSP a archivos estáticos y de motores de búsqueda
    $current_file = basename($_SERVER['PHP_SELF']);
    $static_files = [
        'robots.txt', 'sitemap.xml', 'favicon.ico', 'manifest.json',
        '.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.ico', 
        '.xml', '.json', '.txt', '.webmanifest'
    ];
    
    foreach ($static_files as $static_file) {
        if (str_ends_with($current_file, $static_file)) {
            return false;
        }
    }
    
    // Aplicar solo si no estamos en modo de redirección
    $headers = headers_list();
    $isRedirect = false;
    foreach ($headers as $header) {
        if (stripos($header, 'Location:') === 0) {
            $isRedirect = true;
            break;
        }
    }
    
    if ($isRedirect) {
        return false;
    }
    
    // CSP MEJORADA
    $csp = [
        "default-src 'self'",
        "script-src 'self'",
        "style-src 'self'", 
        "img-src 'self' data:",
        "font-src 'self'",
        "connect-src 'self'",
        "frame-ancestors 'none'",
        "base-uri 'self'",
        "form-action 'self'",
        "object-src 'none'",
        "manifest-src 'self'"
    ];
    
    header("Content-Security-Policy: " . implode("; ", $csp));
    
    // Otras cabeceras de seguridad
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    
    return true;
}

// Aplicar cabeceras solo si es posible
if (!defined('HEADERS_APPLIED')) {
    define('HEADERS_APPLIED', true);
    
    // No aplicar a archivos específicos
    $excluded_files = ['robots.txt', 'sitemap.xml', 'favicon.ico', 'manifest.json'];
    $current_file = basename($_SERVER['PHP_SELF']);
    
    if (!in_array($current_file, $excluded_files)) {
        aplicarCabecerasSeguridad();
    }
}
?>
