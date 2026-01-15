<?php

function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $scriptDir = dirname($script);
    if ($scriptDir === '/' || $scriptDir === '\\') {
        $scriptDir = '';
    }
    $base = $protocol . '://' . $host . $scriptDir;
    $base = rtrim($base, '/');
    return $base . '/' . ltrim($path, '/');
}

function asset_url($path) {
    return base_url('frontend/assets/' . ltrim($path, '/'));
}

function route_url($route = '', $params = []) {
    $url = base_url('index.php');
    if ($route) {
        $params['route'] = $route;
    }
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    return $url;
}

