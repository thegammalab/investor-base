<?php
$url = 'http://app.quotemedia.com/data/getHeadlines.json?topics='.$_GET["company"].'&webmasterId=102396&thumbnailurl=true&summary=true&summLen=300&resultsPerPage=30';
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
