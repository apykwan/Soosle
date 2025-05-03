<?php

declare(strict_types=1);

namespace App;

class DomDocumentParser 
{
  private \DomDocument $doc;

  public function __construct($url) {
    $options = [
      'http' => [
        'method' => 'GET',
        'header' => 'User-Agent: soosleBot/0.1\n'
      ],
    ];
    $context = stream_context_create($options);

    $htmlContent = file_get_contents($url, false, $context);
    if ($htmlContent === false) {
      echo "Failed to retrieve HTML content";
      return;
    }

    $this->doc = new \DomDocument();
    @$this->doc->loadHTML($htmlContent);
  }

  public function getlinks() 
  {
    return $this->doc->getElementsByTagName("a");
  }

  public function getTitleTags()
  {
    return $this->doc->getElementsByTagName("title");
  }

  public function getMetaTags() 
  {
    return $this->doc->getElementsByTagName('meta');
  }

  public function getImages() 
  {
    return $this->doc->getElementsByTagName('img');
  }
}