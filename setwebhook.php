<?php

error_reporting(0);

$token = file_get_contents('./token.txt');
$dir = str_replace('/setwebhook.php', '/async.php', $_SERVER['PHP_SELF']);

if (empty($token)){
	if (!$_GET['token']){
		die("Ops , nao encontrei o token do bot , vc pode coloca-lo no arquivo token.txt ou envia via url exemplo ?token= xxxxxxxxx");
	}else{
		$token = $_GET['token'];
	}
}
$server = $_SERVER['SERVER_NAME'];
if (!$_SERVER['HTTP_X_FORWARDED_PROTO'] and $_SERVER['HTTP_X_FORWARDED_PROTO'] != "https" ){
	die("A url do bot deve ser https ");
}

$res = file_get_contents('https://api.telegram.org/bot'.$token.'/setwebhook?url='."https://".$server.$dir);




if ($res){
	if (strpos($res, 'Webhook was set') !==false || strpos($res, 'Webhook is already set')!==false){
		file_put_contents('./token.txt', trim($token));
		die("bot Online");
	}else{
		die("ocooreu um erro:".json_decode($res,true)['description']);
	}

}else{
	die("Ocorreu um erro ao seta a url !");

	echo $res;
}
