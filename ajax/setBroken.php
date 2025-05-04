<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Database;

$src = $_POST['src'];
if (isset($src)) {

  $con = Database::getInstance()->getConnection();

  $sql = 'UPDATE images SET broken = 1 WHERE imageUrl = :src';
  $query = $con->prepare($sql);
  $query->bindParam(":src", $src, \PDO::PARAM_STR);
  $query->execute();
} else {
  echo 'No src passed to page';
}