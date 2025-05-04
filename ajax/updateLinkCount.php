<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Database;

$id = $_POST['linkId'] ?? null;
if(isset($id)) {
  $con = Database::getInstance()->getConnection();

  $sql = 'UPDATE sites SET clicks = clicks + 1 WHERE id = :id';
  $query = $con->prepare($sql);
  $query->bindParam(":id", $id, \PDO::PARAM_INT);
  $query->execute();
} else {
  echo 'No link pass to page';
}