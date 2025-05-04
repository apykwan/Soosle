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

    $row = $query->fetch();
    return $row["total"];
  }

  public function getResultsHtml(int $page, int $pageSize, string $term) 
  {
    $fromLimit = ($page - 1) * $pageSize;


    $sql = <<<SQL
    SELECT *
    FROM sites 
    WHERE title LIKE :term OR url LIKE :term OR keywords LIKE :term OR description LIKE :term
    ORDER BY clicks DESC
    LIMIT :fromLimit, :pageSize
    SQL;

    $query = $this->con->prepare($sql);
    $query->bindValue(":term", "%{$term}%", \PDO::PARAM_STR);
    $query->bindParam(":fromLimit", $fromLimit, \PDO::PARAM_INT);
    $query->bindParam(":pageSize", $pageSize, \PDO::PARAM_INT);
    $query->execute();

    $resultsHtml = "<div class='siteResults'>";

    while ($row = $query->fetch()) {
      $id = $row["id"];
      $url = $row["url"];
      $title = $row["title"];
      $description = $row["description"];

      $title = $this->trimField($title, 55);
      $description = $this->trimField($description, 230);

      $resultsHtml .= "
        <div class='resultContainer'>
          <h3 class='title'>
            <a class='result' href='{$url}' target='_blank' data-linkId='{$id}'>{$title}</a>
          </h3>
          <span class='url'>{$url}</span>
          <span class='description'>{$description}</span>
        </div>
      ";
    }

    return $resultsHtml .= "</div>";
  }

  private function trimField(string $string, int $characterLimit ) 
  {
    $dots = strlen($string) > $characterLimit ? '...' : '';

    return substr($string, 0, $characterLimit) . $dots;
  }
}