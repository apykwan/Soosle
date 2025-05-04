<?php

declare(strict_types=1);

namespace App;

class ImageResultsProvider
{
  private \PDO $con;

  public function __construct()
  {
    $this->con = Database::getInstance()->getConnection();
  }

  public function getNumResults($term)
  {
    $sql = <<<SQL
    SELECT COUNT(*) as total 
    FROM images 
    WHERE (title LIKE :term OR alt LIKE :term) AND broken=0
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
    FROM images 
    WHERE (title LIKE :term OR alt LIKE :term) AND broken=0
    ORDER BY clicks DESC
    LIMIT :fromLimit, :pageSize
    SQL;

    $query = $this->con->prepare($sql);
    $query->bindValue(":term", "%{$term}%", \PDO::PARAM_STR);
    $query->bindParam(":fromLimit", $fromLimit, \PDO::PARAM_INT);
    $query->bindParam(":pageSize", $pageSize, \PDO::PARAM_INT);
    $query->execute();

    $resultsHtml = "<div class='imageResults'>";

    $count = 0;
    while ($row = $query->fetch()) {
      $count++;
      $id = $row["id"];
      $imageUrl = $row["imageUrl"];
      $siteUrl = $row["siteUrl"];
      $alt = $row["alt"];
      $title = $row["title"];

      if ($title) {
        $displayText = $title;
      } else if ($alt) {
        $displayText = $alt;
      } else {
        $displayText = $imageUrl;
      }

      $resultsHtml .= "
        <div class='gridItem'>
          <a 
            href='{$imageUrl}' 
            class='imageLink img{$count}' 
            data-img='{$imageUrl}' 
          >
            <span class='details'>{$displayText}</span>
          </a>
        </div>
      ";
    }

    return $resultsHtml .= "</div>";
  }
}
