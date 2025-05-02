<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\DomDocumentParser;

$alreadyCrawled = [];
$crawling = [];

/**
 *  filter links
 *  //www.example.com => http://www.example.com
 *  /about/aboutUs.php => http://www.example.com
 *  ./about/aboutus.php => http://www.example.com/about/aboutus.php
 *  ../about/aboutus.php => http://www.example.com/about/aboutus.php
 */
function createLink(string $src, string $url) 
{
  $scheme = parse_url($url)['scheme'];  // http
  $host = parse_url($url)['host'];      // www.example.com

  if (substr($src, 0, 2) == '//') {
    $src = $scheme . ":" . $src;
  } 
  else if (substr($src, 0, 1) == '/') {
    $src = $scheme . "://" . $host . $src;
  }
  else if (substr($src, 0, 2) == './') {
    $src = $scheme . "://" . $host . dirname(parse_url($url)['path']) . substr($src, 1);
  } 
  else if (substr($src, 0, 3) == '../') {
    $src = $scheme . "://" . $host . "/" . $src;
  } 
  else if (substr($src, 0, 5) != 'https' && substr($src, 0, 4) != 'http') {
    $src = $scheme . "://" . $host . "/" . $src;
  }

  return $src;
}

function getDetails(string $url)
{
  $parser = new DomDocumentParser($url);
  $titleArray = $parser->getTitleTags();

  if (sizeof($titleArray) == 0 || $titleArray->item(0) == null) return;

  $title = $titleArray->item(0)->nodeValue;
  $title = str_replace("\n", "", $title);

  if ($title == "") return;

  $description = "";
  $keywords = "";

  $metasArray = $parser->getMetaTags();

  foreach($metasArray as $meta) {
    if ($meta->getAttribute('name') == 'description') {
      $description = $meta->getAttribute('content');
    }

    if ($meta->getAttribute('name') == 'keywords') {
      $keywords = $meta->getAttribute('content');
    }
  }

  $description = str_replace('\n', '', $description);
  $keywords = str_replace('\n', '', $keywords);

  echo "Url: {$url}, Description: {$description}, Keyword: {$keywords} <br>";
}

function followLinks(string $url) 
{
  global $alreadyCrawled;
  global $crawling;

  $parser = new DomDocumentParser($url);

  $linkList = $parser->getLinks();
  foreach ($linkList as $link) {
    $href = $link->getAttribute('href');

    if (strpos($href, '#') !== false ) {
      continue;
    } else if (substr($href, 0, 11) == 'javascript:') {
      continue;
    }

    $href = createLink($href, $url);

    if (!in_array($href, $alreadyCrawled)) {
      $alreadyCrawled[] = $href;
      $crawling[] = $href;

      getDetails($href);
    }
    else return;
  }
  array_shift($crawling);

  foreach($crawling as $site) {
    followLinks($site);
  }
}

$startUrl = "http://ccyp.com";
followLinks($startUrl);