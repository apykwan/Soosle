<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Database;

$imageUrl = $_POST['imageUrl'] ?? null;
if (isset($imageUrl)) {
  $con = Database::getInstance()->getConnection();

  $sql = 'UPDATE images SET clicks = clicks + 1 WHERE imageUrl = :imageUrl';
  $query = $con->prepare($sql);
  $query->bindParam(":imageUrl", $imageUrl, \PDO::PARAM_STR);
  $query->execute();
} else {
  echo 'No link pass to page';
}
