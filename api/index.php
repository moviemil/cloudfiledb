<?php
$token = "c3kEDGfjvT7LbY9XsRwNQA2qnMG4utn56r8zWBcZHaemJKhLYp";
$scriptURL = "https://script.google.com/macros/s/AKfycbwC8v2CwaRG4LniJp9arztxvlUzH3HADb7hHh0I7u03P22pM5AnjB_sxowZEkgBo3KW/exec";
$title = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['d']) ? $_GET['d'] : null);
$ekbtMap = array(
    "Function" => "RedPost",
    "token" => $token,
    "title" => $title
);

$params = http_build_query($ekbtMap);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $scriptURL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);

if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
$imagemLink = $response;

if (isset($_GET['d'])) {
    $dadosBase64 = explode(',', $imagemLink, 2)[1];
    preg_match('/^data:([a-zA-Z\/]+);base64/', $imagemLink, $matches);
    $tipoConteudo = isset($matches[1]) ? $matches[1] : 'application/octet-stream';
    header("Content-type: $tipoConteudo");
    $extensao = explode('/', $tipoConteudo)[1];
    header("Content-Disposition: attachment; filename=$title.$extensao");
    echo base64_decode($dadosBase64);
} else {
$tipoConteudo = mime_content_type($imagemLink);
header("Content-type: $tipoConteudo");
readfile($imagemLink);
}
}
curl_close($ch);
?>
