<?php
$url = 'http://app.quotemedia.com/data/getFinancialsEnhancedBySymbol.json?symbol='.$_GET["company"].'&webmasterId=102396&reportType=A&numberOfReports=5';
//$url = 'http://app.quotemedia.com/data/getQuotes.json?symbols=AGF695:CA&webmasterId=102396';
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
