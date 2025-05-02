<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

ob_start();

$con = null;

try {
  $dsn = "mysql:dbname={$_ENV['DB_NAME']};host={$_ENV['DB_HOST']};charset=utf8mb4";
  $options = [
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ];
  if ($con == null) {
    $con = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
  }
} catch (PDOException $e) {
  echo "Connection failed: {$e->getMessage()}";
}