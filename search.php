<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\{SiteResultsProvider, ImageResultsProvider};

if (isset($_GET['term'])) {
  $term = $_GET['term'];
} else {
  exit("you must enter search term");
}

$type = isset($_GET['type']) ? $_GET['type'] : 'sites';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <title>Welcome to Soosle</title>
</head>

<body>
  <div class="wrapper">
    <header class="header">
      <div class="headerContent">
        <div class="logoContainer">
          <a href="index.php">
            <img src="assets/images/soosle_logo.png">
          </a>
        </div>

        <div class="searchContainer">
          <form action="search.php" method="GET">
            <div class="searchBarContainer">
              <input type="hidden" name="type" value="<?php echo $type ?>">
              <input class="searchBox" type="text" name="term" value="<?php echo $term; ?>">
              <button class="searchButton">
                <img src="assets/images/search_icon.png">
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="tabsContainer">
        <ul class="tabList">
          <li class='<?php echo $type == "sites" ? 'active' : ''; ?>'>
            <a href='<?php echo "search.php?term={$term}&type=sites"; ?>'>Sites</a>
          </li>
          <li class='<?php echo $type == "images" ? 'active' : ''; ?>'>
            <a href='<?php echo "search.php?term={$term}&type=images"; ?>'>Images</a>
          </li>
        </ul>
      </div>
    </header>

    <main class="mainResultSection">
      <?php
      if ($type == 'sites') {
        $resultsProvider = new SiteResultsProvider;
        $pageSize = 20;
      } else {
        $resultsProvider = new ImageResultsProvider;
        $pageSize = 30;
      }

      $numResults = $resultsProvider->getNumResults($term);
      $s = $numResults > 1 ? 's' : '';

      echo "<p class='resultsCount'>{$numResults} result{$s} found</p>";

      echo $resultsProvider->getResultsHtml($page, $pageSize, $term);
      ?>
    </main>

    <footer class="paginationContainer">
      <div class="pageButtons">
        <div class="pageNumberContainer">
          <img src="assets/images/pageStart.png">
        </div>

        <?php
        $pagesToShow = 10;
        $totalPages = ceil($numResults / $pageSize);
        $pagesToRender = min($pagesToShow, $totalPages);

        $startingPage = max(1, $page - floor($pagesToShow / 2));

        if ($startingPage + $pagesToRender > $totalPages + 1) {
          $startingPage = $totalPages + 1 - $pagesToRender;
        }

        $currentRenderPage = $startingPage;

        while ($pagesToRender > 0 && $currentRenderPage <= $totalPages) {
          if ($currentRenderPage == $page) {
            echo "
                <div class='pageNumberContainer'>
                  <img src='assets/images/pageSelected.png'>
                  <span class='pageNumber'>{$currentRenderPage}</span>
                </div>
              ";
          } else {
            echo "
                <div class='pageNumberContainer'>
                  <a href='search.php?term={$term}&type={$type}&page={$currentRenderPage}'>
                    <img src='assets/images/page.png'>
                    <span class='pageNumber'>{$currentRenderPage}</span>
                  </a>
                </div>
              ";
          }

          $currentRenderPage++;
          $pagesToRender--;
        }
        ?>

        <div class="pageNumberContainer">
          <img src="assets/images/pageEnd.png">
        </div>
      </div>
    </footer>
  </div>

  <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
  <script type="module" src="assets/js/script.js"></script>
</body>

</html>