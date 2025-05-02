<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

if (isset($_GET['term'])) {
  $term = $_GET['term'];
  echo $term;
} else {
  exit("you must enter search term");
}

$type = isset($_GET['type']) ? $_GET['type'] : 'sites';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <title>Welcome to Soosle</title>
</head>

<body>
  <div class="wrapper">
    <div class="header">
      <div class="headerContent">
        <div class="logoContainer">
          <a href="index.php">
            <img src="assets/images/soosle_logo.png">
          </a>
        </div>

        <div class="searchContainer">
          <form action="search.php" method="GET">
            <div class="searchBarContainer">
              <input class="searchBox" type="text" name="term">
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
    </div>
  </div>
</body>

</html>