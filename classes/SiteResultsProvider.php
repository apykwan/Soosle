<?php

declare(strict_types=1);

namespace App;

class SiteResultsProvider {
  private \PDO $con;

  public function __construct() 
  {
    $this->con = Database::getInstance()->getConnection();
  }

  public function getNumResults($term)
  {
    $sql = <<<SQL
    SELECT COUNT(*) as total 
    FROM sites 
    WHERE title LIKE :term OR url LIKE :term OR keywords LIKE :term OR description LIKE :term
    SQL;
    $query = $this->con->prepare($sql);

    $query->bindValue(":term", "%{$term}%", \PDO::PARAM_STR);
    $query->execute();

    $row = $query->fetch(\PDO::FETCH_ASSOC);
    return $row["total"];
  }
}