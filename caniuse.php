<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;
use LTDBeget\ConsoleTable\ConsoleTable;

$client = new Client([
  'base_uri' => 'http://caniuse.com/',
  'timeout'  => 2.0,
]);

try {

  $response = $client->get('/flexbox');
  $crawler = new Crawler($response->getBody()->getContents());
  $tbl = new LTDBeget\ConsoleTable\ConsoleTable();

  $headers = [];
  $items = $crawler->filter('.support-container > .support-list h4');
  foreach ($items as $item) {
    $headers[] = $item->nodeValue;
  }
  $tbl->setHeaders($headers);
  $items = $crawler->filter('.support-container > .support-list ol');
  foreach ($items as $item) {
    foreach ($item->childNodes as $child) {
      print_r($child);
    }
  }

  /*$tbl->addRow(array('PHP', 1994));
  $tbl->addRow(array('C',   1970));
  $tbl->addRow(array('C++', 1983));*/

  echo $tbl->getTable();

} 
catch (RequestException $e) {
  echo $e->getRequest();
  if ($e->hasResponse()) {
    echo $e->getResponse();
  }
}
