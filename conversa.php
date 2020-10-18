<?php


function ofeusuario($msg,$user){
	$chatid = $msg['chat']['id'];

	//ofendido
	if ($msg['reply_to_message']){
		$nomeofrndido = $msg['reply_to_message']['from']['first_name'];
		$idofendido = $msg['reply_to_message']['from']['id'];


	}else{
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);
		$dados = $data[$user];


		if (empty($dados)){
			return;
		}

		$nomeofrndido = $dados['nome'];
		$idofendido = $user;
	}

	if (empty($nomeofrndido) and empty($idofendido)){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>😂👌🏼😭 nao consegui ofende este usuario !</b>" , "parse_mode" => "html","reply_to_message_id" => $msg['message_id']));
		die();
	}

	//ofenso 
	$nomemaldito = $msg['from']['first_name'];
	$idmaldito = $msg['from']['id'];
	$file = file('./frasesofe.txt');
	$frase = $file[array_rand($file)];
	$frase = str_replace("%nomemal","<a href=%tg://user?id=$idmaldito%>$nomemaldito</a>", $frase);
	$frase = str_replace("%nomeofe","<a href=%tg://user?id=$idofendido%>$nomeofrndido</a>", $frase);
	$textoe = str_replace("%", "'", $frase);
	sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>$textoe , "parse_mode" => "html"));

	$stickers = file_get_contents('./stickers.json');
	$data = json_decode($stickers, true);
	$sticker = array_values($data)[array_rand(array_values($data))];
	sendMessage('sendSticker',array('chat_id' => $chatid , 'sticker' => trim($sticker)));
	die();

}

function maeusuario($msg,$user){
	$chatid = $msg['chat']['id'];

	$confi = file_get_contents('./confi.json');
	$confibot = json_decode($confi, true);

	$url = $confibot['url'];

	if ($msg['reply_to_message']){
		$nomeofrndido = $msg['reply_to_message']['from']['first_name'];
		$idofendido = $msg['reply_to_message']['from']['id'];


	}else{
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);
		$dados = $data[$user];


		if (empty($dados)){
			return;
		}

		$nomeofrndido = $dados['nome'];
		$idofendido = $user;
	}

	if (empty($nomeofrndido) and empty($idofendido)){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>😂👌🏼😭 nao consegui ofende este usuario !</b>" , "parse_mode" => "html","reply_to_message_id" => $msg['message_id']));
		die();
	}

	$url = $confibot['url'];

	$xinga = file_get_contents("$url/conf/xigamen.php");
	sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"<b><a href='tg://user?id=$idofendido'>$nomeofrndido</a> $xinga</b>","parse_mode" => "html"));
	$stickers = file_get_contents('./stickers.json');
	$data = json_decode($stickers, true);
	$sticker = array_values($data)[array_rand(array_values($data))];
	sendMessage('sendSticker',array('chat_id' => $chatid , 'sticker' => trim($sticker)));
    exit;
}