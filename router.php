<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$file = __DIR__ . $uri;

if (is_file($file)) {
    return false;
}
include __DIR__ . "/index.php";
