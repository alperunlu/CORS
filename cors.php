<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$allowedOrigin = '*'; 

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

    header('Access-Control-Allow-Origin: ' . $allowedOrigin);
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: ' . $allowedOrigin);

$userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3';
$targetQuery = $_GET['query'];
if ($targetQuery) {

    $context = stream_context_create([
        'http' => [
        'method' => 'GET',
        'header' => "User-Agent: $userAgent\r\n",
        ],
        ]);

    $urlWithEncodedQuery = "https://www.google.com/search?q=" . urlencode($targetQuery);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $urlWithEncodedQuery);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
    $response = file_get_contents($urlWithEncodedQuery, false, $context);

    if ($response !== FALSE) {
        echo $response;
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        echo 'Internal Server Error';
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo 'Bad Request';
}
?>
