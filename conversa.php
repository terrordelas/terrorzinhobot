<?php


function ofeusuario($msg,$user){
	$chatid = $msg['chat']['id'];

	deletesfsf($msg,$msg['message_id']);


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

	$frases = file_get_contents('./fraseofe.json');
	$frases = json_decode($frases, true);
	if (!$frases[$chatid]){
		$frase = $frases['default'];
	}else{
		$frase = $frases[$chatid];
	}
	$frase = $frase[array_rand($frase)];

	$frase = str_replace("%nomemal","<a href=%tg://user?id=$idmaldito%>$nomemaldito</a>", $frase);
	$frase = str_replace("%nomeofe","<a href=%tg://user?id=$idofendido%>$nomeofrndido</a>", $frase);
	$textoe = str_replace("%", "'", $frase);
	$msgbot = sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>$textoe , "parse_mode" => "html"));


	$confi = file_get_contents('./confi.json');
	$data = json_decode($confi, true)['stickers'];
	$sticker = array_values($data)[array_rand(array_values($data))];
	$msg2 = sendMessage('sendSticker',array('chat_id' => $chatid , 'sticker' => trim($sticker)));

	// deleteautomsg($msg,$msgbot);
	// deleteautomsg($msg,$msg2);
	die();

}

function maeusuario($msg,$user){
	// deletesfsf($msg,$msg['message_id']);
	$chatid = $msg['chat']['id'];

	$confi = file_get_contents('./confi.json');
	$confibot = json_decode($confi, true);

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
	$msgbot1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"<b><a href='tg://user?id=$idofendido'>$nomeofrndido</a> $xinga</b>","parse_mode" => "html"));

	$data = $confibot['stickers'];
	$sticker = array_values($data)[array_rand(array_values($data))];
	$msgbot2 = sendMessage('sendSticker',array('chat_id' => $chatid , 'sticker' => trim($sticker)));
	// deleteautomsg($msg,$msgbot1);
	// deleteautomsg($msg,$msgbot2);
    exit;
}

function voto($msg,$voto,$callback){
	$chatid = $msg['chat']['id'];
	$user_id = $callback['from']['id'];
	$nome = $callback['from']['first_name'];
	$idban = explode("_", $callback['data'])[2];
	$file = file_get_contents("./voteban.json");
	$dados = json_decode($file , true);

	if (!$dados[$chatid][$idban]){
		return;
	}

	if ($voto == 'sim'){
		if ($dados[$chatid][$idban]['votossim'][$user_id]){
			return;
		}

		if ($dados[$chatid][$idban]['votosnao'][$user_id]){
			unset($dados[$chatid][$idban]['votosnao'][$user_id]);
		}

		$dados[$chatid][$idban]['votossim'][$user_id] = "[$nome](tg://user?id=$user_id)";
		$dados[$chatid][$idban]['time'] = $dados[$chatid][$idban]['time']+1;
	}

	if ($voto == 'nao'){
		if ($dados[$chatid][$idban]['votosnao'][$user_id]){
			return;
		}

		if ($dados[$chatid][$idban]['votossim'][$user_id]){

			unset($dados[$chatid][$idban]['votossim'][$user_id]);
		}

		$dados[$chatid][$idban]['votosnao'][$user_id] =  "[$nome](tg://user?id=$user_id)";
		$dados[$chatid][$idban]['time'] = $dados[$chatid][$idban]['time']+1;
	}

	$dsalva = json_encode($dados,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
	$salva = file_put_contents("./voteban.json", $dsalva);
}

function voteban($msg,$id,$callback = false){

	return ;
	
	$chatid = $msg['chat']['id'];
	$fromid = $msg['from']['id'];




	if ($callback){
		$chatid = $msg['chat']['id'];
		$fromid = ['from']['id'];
		$user_id = explode("_",$callback['data'])[2];

		$file = file_get_contents("./voteban.json");
		$dados = json_decode($file , true);



		if (!$dados[$chatid][$user_id]){
			bot("editMessageText" , ['chat_id' => $chatid ,'message_id' => $msg['message_id'], "text" => "votacao nao encontrada / encerada !"]);
			die();
		}


		while (true) {
			$file = file_get_contents("./voteban.json");
			$dados = json_decode($file , true);

			$time = $dados[$chatid][$user_id]['time'];

			$butoes =  ['inline_keyboard' => [
			[['text'=>"vota sim ",'callback_data'=>"voto_sim_{$user_id}"]],
			[['text'=>"vota nao",'callback_data'=>"voto_nao_{$user_id}"]]

			]];
			if ($time <= 0 ){
				$totaln =  sizeof(array_values($votosnao));
				$totals = sizeof(array_values($votossim));

				if ($totals > $totaln){
					$result = "usuario sera banido em 2s! ";
				}else{
					$result = "usuario permanece no grupo! ";
				}

				$vtosn = implode("\n", array_values($votosnao));
				$vtoss =  implode("\n", array_values($votossim));

				$ENVIADS = bot("editMessageText" , ['chat_id' => $chatid ,'message_id' => $msg['message_id'], "text" => "
*fim da votação * ,
*resultado: $result*

*votos a favor:* $totals ($vtoss)

*votos contra:* $totaln ($vtosn)

					", "parse_mode" => "Markdown"]);

				sleep(2);

				$totaln =  sizeof(array_values($dados[$chatid][$user_id]['votosnao']));
				$totals = sizeof(array_values($dados[$chatid][$user_id]['votossim']));

				if ($totals > $totaln){
					$json = json_decode($ENVIADS , true);
					$kick = bot("kickChatMember" , array("chat_id" => $chatid , "user_id" => $user_id));
					if (json_decode($kick,true)['result'] == true){
						bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $json['result']['message_id']));
						die();
					}else{
						bot('sendMessage',array("chat_id" => $chatid , "text" => "nao consegui banir este usuario"));

					}
				}

				unset($dados[$chatid][$user_id]);
				$dsalva = json_encode($dados,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
				$salva = file_put_contents("./voteban.json", $dsalva);

				die();

				break;
			}

			// $ENVIADS = bot("editMessageText" , ['chat_id' => $chatid ,'message_id' => $msg['message_id'], "text" => "votacao acabara em $time s", "parse_mode" => "Markdown","reply_markup" => $butoes]);
			$votossim = $dados[$chatid][$user_id]['votossim'];
			$votosnao = $dados[$chatid][$user_id]['votosnao'];

			$dados[$chatid][$user_id]['time'] = $dados[$chatid][$user_id]['time'] -1 ;
			$dsalva = json_encode($dados,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);

			$salva = file_put_contents("./voteban.json", $dsalva);
			$time = $dados[$chatid][$user_id]['time'];
			sleep(1);

		}
	}





	if ($msg['reply_to_message']['from']['id']){
		if (sizeof(explode(" ",$msg['text']))>=2){
			$motivo = substr($msg['text'], strlen('voteban')+1);
		}else{
			$motivo = false;
		}

		$nome = $msg['reply_to_message']['from']['first_name'].' '.$msg['reply_to_message']['from']['last_name'];
	}else{
		$motivo = false;
		$u7sers = file_get_contents('./usuarios.json');
		$usuarios = json_decode($u7sers, true);

		$nome = $usuarios[$id]['nome'];
	}

	if ($motivo){
		$motivo = "*motivo:* $motivo";
	}



	$butoes =  ['inline_keyboard' => [
		[['text'=>"vota sim ",'callback_data'=>"voto_sim_{$id}"]],
		[['text'=>"vota nao",'callback_data'=>"voto_nao_{$id}"]]

	]];

	if (!$callback){


		$nome = str_replace(array("[",']','-','#'), '', $nome);
		$msgbot = bot("sendMessage" , array(
			'chat_id' => $chatid,
			"text" => "votação iniciada para banir o usuario [$nome](tg://user?id=$id)\n$motivo",
			"reply_markup" => $butoes,
			"parse_mode" => "MarkdownV2"
			)
		);





		$msg_id = json_decode($msgbot,true)['result']['message_id'];
		$file = file_get_contents("./voteban.json");
		$dados = json_decode($file , true);

		$dados[$chatid][$id] = [
			'idban' => $id,
			'idinic' => $fromid,
			'time' => 5,
			'message_id' => $msg_id,
			'timeexpirete' => 0

		];

		$dsalva = json_encode($dados,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
		$salva = file_put_contents("./voteban.json", $dsalva);
		if (!$salva){
			$confi = file_get_contents('./confi.json');
			$confibot = json_decode($confi, true);
			bot("sendMessage" , ['chat_id' => $confibot['dono'] , "text" => "error ao grava uma votacao , \n chat: $chatid"]);
			bot("sendMessage" , ['chat_id' => $chatid , "text" => "error na api interna!"]);
			die();
		}

	}


}
