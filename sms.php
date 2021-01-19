<?php

error_reporting(0);

function string($string,$start,$end,$num){
     $str = explode($start, $string);
     $str = explode($end, $str[$num]);
    return $str[0];

}

if ($_GET['gettell'] == "true"){
    $ch = curl_init();
    curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://receive-sms-online.info/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_COOKIEJAR => getcwd() . "./cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "./cookie.txt",
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    ));

   $r1 = curl_exec($ch);

    $total = substr_count($r1, '<div class="Cell" >');
    $rand = string($r1,'<div class="Cell" >','</div>',rand(0,$total));
    $number = explode("-", string($rand,'href="','"',1))[0];
    $pais = explode("-", string($rand,'href="','"',1))[1];
    die(json_encode(array("status" => "true" , "number" => $number , "pais" => $pais),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT ));
}

if ($_GET['tell']  == ""){
    die(json_encode(array("status" => "false" , "msg" => "falta o namber"),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT ));
}

$country = $_GET['c'];
$number = $_GET['tell'];


if (file_exists((getcwd().'./cookie.txt'))) {  
    unlink('cookie.txt');
}
$ch = curl_init();
curl_setopt_array($ch, array(
  CURLOPT_URL => 'https://receive-sms-online.info/'.$number.'-'.$country.'',
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_COOKIEJAR => getcwd() . "./cookie.txt",
  CURLOPT_COOKIEFILE => getcwd() . "./cookie.txt",
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
));

$r1 = curl_exec($ch);

$key =  string($r1,'/script_a.php?key=','"+n',1);

$ch = curl_init();
curl_setopt_array($ch, array(
  CURLOPT_URL => 'https://receive-sms-online.info/script_a.php?key='.$key.'&alt_x='.time(),
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_COOKIEJAR => getcwd() . "./cookie.txt",
  CURLOPT_COOKIEFILE => getcwd() . "./cookie.txt",
  CURLOPT_HTTPHEADER => array(

'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',

'cookie: __cfduid=d5ff2e7a5bd4ac85b3df46669a70273db1599425514; tlgrm=1; smsplaza=1; _ga=GA1.2.1621567258.1599425517; _gid=GA1.2.1030637927.1599425517; __gads=ID=0e83d71f3dce86b6:T=1599425516:S=ALNI_MYusfbUJIg-vTM8fAxQXt32mcwMUw',
'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36',
'x-requested-with: XMLHttpRequest',
  )
));

$r1 = curl_exec($ch);

$msg = [];


for ($i=1; $i < 11 ; $i++) { 
    $de = string($r1,'<td data-label="From   :">','</td>',$i);
    $msge = string($r1,'<td data-label="Message:">','</td>',$i);
    $chg = string($r1,'data-label="Added:">','</td>',$i);
    $msg[] = array("from" => $de , "message" => $msge , "time" => $chg);
}


echo json_encode($msg,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );

?>