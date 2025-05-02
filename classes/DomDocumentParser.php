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

    $this->doc = new \DomDocument();
    @$this->doc->loadHTML(file_get_contents($url, false, $context));
  }

  public function getlinks() 
  {
    return $this->doc->getElementsByTagName("a");
  }

  public function getTitleTags()
  {
    return $this->doc->getElementsByTagName("title");
  }

  public function getMetaTags() {
    return $this->doc->getElementsByTagName('meta');
  }
}