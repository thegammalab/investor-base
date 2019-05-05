<?php
$url = 'http://app.quotemedia.com/data/getSymbolList.json?webmasterId=102396&symbolcount=10000&exgroup='.$_GET["exchange"].'&instrumentType=Equity';
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
// TSXVC
// $url = 'http://app.quotemedia.com/data/getSymbolList.json?webmasterId=102396&symbolcount=10000&exgroup=TSXC&instrumentType=Equity';
// header('Content-Type:application/json');
// // create curl resource
// $ch = curl_init();
// // set url
// curl_setopt($ch, CURLOPT_URL, $url);
// //return the transfer as a string
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// // $output contains the output string
// $output = curl_exec($ch);
// // close curl resource to free up system resources
// curl_close($ch);
//
// echo $output;
//
// echo"----------------------------------";
//
// $url = 'http://app.quotemedia.com/data/getSymbolList.json?webmasterId=102396&symbolcount=10000&exgroup=cnq&instrumentType=Equity';
// header('Content-Type:application/json');
// // create curl resource
// $ch = curl_init();
// // set url
// curl_setopt($ch, CURLOPT_URL, $url);
// //return the transfer as a string
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// // $output contains the output string
// $output = curl_exec($ch);
// // close curl resource to free up system resources
// curl_close($ch);
//
// echo $output;
// echo"----------------------------------";
//
// $url = 'http://app.quotemedia.com/data/getSymbolList.json?webmasterId=102396&symbolcount=10000&exgroup=AQLL&instrumentType=Equity';
// header('Content-Type:application/json');
// // create curl resource
// $ch = curl_init();
// // set url
// curl_setopt($ch, CURLOPT_URL, $url);
// //return the transfer as a string
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// // $output contains the output string
// $output = curl_exec($ch);
// // close curl resource to free up system resources
// curl_close($ch);
//
// echo $output;
