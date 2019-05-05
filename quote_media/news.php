<?php
// $url = 'https://www.whatismyip.net/';
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// $output = curl_exec($ch);
// curl_close($ch);
//
// echo $output;
//
// die();

$url = 'http://app.quotemedia.com/data/getHeadlines.json?topics=SHOWALLNEWS&webmasterId=102396&thumbnailurl=true&summary=true&summLen=300';
header('Content-Type:application/json');
// create curl resource
$ch = curl_init();
// set url
curl_setopt($ch, CURLOPT_URL, $url);
//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// $output contains the output string
$output = curl_exec($ch);
// close curl resource to free up system resources
curl_close($ch);

echo $output;
