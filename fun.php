	<?php

	header('Content-Type: application/json');


	function salvaUser($msg){

		$id = $msg['from']['id'];
		$chatid = $msg['chat']['id'];

		$usaurios = file_get_contents('./usuarios.json');
		$users = json_decode($usaurios, true);
		if (!$users[$id]){
			$nome = $msg['from']['first_name'].' '.$msg['from']['last_name'];
			$username = $msg['from']['username'];
			if (empty($username)){$username = "undefined";}
			$time = time();
			$users[$id] = array(
			'nome' =>$nome,
			'totalmsg'=>0,
			'contador'=> 0,
			'totalcon' =>0,
			'cadastro' =>$time,
			"last_msg" =>$time,
			"username" =>$username,
			"adm" => 'false'
			);
			$dsalva = json_encode($users,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
			$salva = file_put_contents('./usuarios.json', $dsalva);
		}


		if ($msg['chat']['type'] == "private"){
			$usaurios = file_get_contents('./users.json');
			$data = json_decode($usaurios, true);
			if ($data[$id]){
				return;
			}
			$nome = $msg['from']['first_name'].' '.$msg['from']['last_name'];
			$username = $msg['from']['username'];
			if (empty($username)){$username = "undefined";}
			$time = time();
			$data[$id] = array(
			"type" => 'priv',
			'nome' =>$nome,
			'totalmsg'=>0,
			'contador'=> 0,
			'totalcon' =>0,
			'cadastro' =>$time,
			"last_msg" =>$time,
			"username" =>$username,
			"adm" => 'false'
			);

			$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
			$salva = file_put_contents('./users.json', $dsalva);
			
		}
		

		if ($msg['chat']['type'] == "supergroup"){
			$usaurios = file_get_contents('./grups.json');
			$data = json_decode($usaurios, true);

	        if ($data[$chatid]){
	        	return;
	        }
			$time = time();
			$username = ($msg['chat']['username']) ? : "undefined";
			$data[$chatid] = [
				'type' => 'group',
				'title' => $msg['chat']['title'],
				'username' => $username,
				'totalmsg'=>0,
				'totalcon' =>0,
				'contador'=> 0,
				'cadastro' =>$time,
				"last_msg" =>$time,


			];
	   		$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
			$salva = file_put_contents('./grups.json', $dsalva);

		}

		
		
	}


	function setmsg($msg){

		if ($msg['chat']['type'] == "private"){
			return;
		}
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);

	 	$id = $msg['from']['id'];

		if (!$data[$id]){
			return;
		}
		
		
		
		$data[$id]['last_msg'] = $msg['date'];
		$data[$id]['totalmsg'] = $data[$id]['totalmsg']+1;

		
		$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./usuarios.json', $dsalva);
	}


	function setcon($msg){
		

	 	$id = $msg['from']['id'];

		if (!$data[$id]){
			return;
		}


		$data[$id]['last_msg'] = $msg['date'];
		$data[$id]['totalcon'] = $data[$id]['totalcon']+1;

		if ($msg['chat']['type'] == "supergroup"){
			$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
			$salva = file_put_contents('./grups.json', $dsalva);
		}

		if ($msg['chat']['type'] == "private"){
			$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
			$salva = file_put_contents('./users.json', $dsalva);
		}

	}

	function getuser($msg){
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);
	 	$id = $msg['chat']['id'];

		if (!$data[$id]){
			salvaUser($msg);
		}
		return $data[$id];
	}

	function getuser2($msg){
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);
	 	$id = $msg['from']['id'];

		if (!$data[$id]){
			// salvaUser($msg);
		}
		return $data[$id];
	}




	function gravacon($msge,$cmds){

		if ($msge['chat']['type'] != 'supergroup'){
			sendMessage("sendMessage",array("chat_id" => '1093905382','text' =>"naum e grupo"));

			return;
		}
		$index = $msge['chat']['id'] . '.txt';
		$dados = $msge['reply_to_message'];
		
		$cmds = array_keys($cmds);

		$text = strtolower($msge['text']);
		preg_match_all('/[a-z-A-Z-0-9]*+/', $text, $args);
		$args = array_values(array_filter($args[0]))[0];

		if ($msge['reply_to_message']['sticker']['file_unique_id']){
			$per = $msge['reply_to_message']['sticker']['file_unique_id'];
		}else if ($msge['reply_to_message']['text']){
			$per = $msge['reply_to_message']['text'];
		}else{
			
			return;
		}

		if ($msge['sticker']['file_id']){
			$res = $msge['sticker']['file_id'];
				$type = 'sticker';
		}else if ($msge['text']){
			$res = $msge['text'];
			$type = 'texto';
		}else{
			
			return;
		}

		if (in_array($args, $cmds)){
		    return;
		}


		$Qpergun = $msge['reply_to_message']['from']['id'];
		$Qresp = $msge['from']['id'];

		

		if (empty($per)){
			return false;
		}

		if ($Qpergun == $Qresp){
			return;
		}
		if (empty($res)){
			return false;
		}

		if (strlen($per) > 50 and $type != 'sticker'){
			return false;
		}
		if (strlen($res) >50 and $type != 'sticker' ){
	  		return;
		}


		$per = strtolower($per);
		$per = str_replace("|", "", $per);
		$res = str_replace("|", "", $res);

		$file = fopen("./conf/conversas/$index", "a+");
		$salva = fwrite($file,"$per|$type|$res\n");

		if ($salva){
			return false;
		}else{
			sendMessage("sendMessage",array("chat_id" => '1093905382','text' =>"ocorreu um error ao salva: $per | $res"));
			return false;
		}

	}
	function procuramsg($msg,$cmds){

		$dados = $msg['reply_to_message'];
		$index = $msg['chat']['id'] . '.txt';

		
		if ($msg['chat']['type'] != 'supergroup'){
			return;
		}

		if ($msg['reply_to_message']['sticker']['file_unique_id']){
			$per = $msg['reply_to_message']['sticker']['file_unique_id'];
		}else if ($msg['reply_to_message']['text']){
			$per = $msg['reply_to_message']['text'];
		}else{
			
			return;
		}

		$file_handle = fopen("./conf/conversas/$index", "r");
		while (!feof($file_handle)) {
			$line = fgets($file_handle);

			if ((string) explode("|", $line)[0] == (string)$per){
				$msgs = explode("|", $line)[1];
				break;
			}
		}

		if (empty($msgs)){
			gravacon($msg,$cmds);
			
		}else{
			return $msgs;
			
		}
	}



	function procuramsg2($msg){


		$chatid = $msg['chat']['id'];

		$index = $chatid . '.txt';
		if ($msg['chat']['type'] != 'supergroup'){
			return;
		}
		if ($msg['sticker']['file_unique_id']){
			$per = $msg['sticker']['file_unique_id'];
		}else if ($msg['text']){
			$per = $msg['text'];
		}else{
			
			return;
		}

		if (empty($per)){
			return false;
		}
		
		
		$file_handle = fopen("./conf/conversas/$index", "a+");


		while (!feof($file_handle)) {
			$line = fgets($file_handle);

			if ((string)explode("|", $line)[0] == (string) $per){
				
				if ((string) explode("|", $line)[1] == "sticker"){
					$sticker= trim(explode("|", $line)[2]);
					sendMessage('sendSticker',array('chat_id' => $chatid , 'sticker' => $sticker,"reply_to_message_id" => $msg["message_id"]));
				}else if ((string) explode("|", $line)[1] == 'texto'){
					sendMessage("sendMessage",array("chat_id" => $chatid,'text' => explode("|", $line)[2],"reply_to_message_id" => $msg["message_id"]));
				}else{
					sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $line,"reply_to_message_id" => $msg["message_id"]));
				}
				// 
				break;
			}

		}
		return false;

	}






	function setstikeradd($msg){
		$chatid = $msg['chat']['id'];
		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		if (!$msg['reply_to_message']['sticker']){
			
			return;
		}
	  
	 	$file_unique_id = $msg['reply_to_message']['sticker']['file_unique_id'];       
	  	
	  	if ($confibot['stickers'][$file_unique_id]){
	  		return;
	  	}

	    $msgsa = $msg['reply_to_message']['sticker']['file_id'];
		$confibot['stickers'][$file_unique_id] = $msgsa; 
		$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./confi.json', $dsalva);
		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
	    	exit();
		}else{
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"errro ao salvo "));
	    	exit();
		}
	    
	}

	function setmsgsalvas($msg){
		$chatid = $msg['chat']['id'];

		if (!$msg['chat']['type'] == "supergroup" || $msg['chat']['type'] == "group" ){
			return;
		}

		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		$data = $confibot['spam'][$chatid];


	 	end($data);
	    $ultimamsg = key($data)+1; 

	    if (empty($ultimamsg)){
	    	$ultimamsg = 1;
	    }
	    $msgsa = trim(substr($msg['text'], 7));
		$confibot['spam'][$chatid][$ultimamsg] = $msgsa; 

		$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./confi.json', $dsalva);
		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "salvo"));
		}else{
			sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "error "));
		}

	}


	function deletemsg($msg,$busca){
		
		$chatid = $msg['chat']['id'];
		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi ,true);

	 	if ($confibot['spam'][$chatid][$busca]){
	 		unset($confibot['spam'][$chatid][$busca]);
	 		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"Deletada !",'parse_mode'=>'html'));
	 	}else{
	 		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"naum encontrei essa msg na db ! !",'parse_mode'=>'html'));
	 		die();
	 	}

		$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./confi.json', $dsalva);

	}


	function spam($msg){
		$chatid = $msg['chat']['id'];

		if ($msg['chat']['type'] == "supergroup" || $msg['chat']['type'] == "group" ){
			$usaurios = file_get_contents('./grups.json');
			$user = json_decode($usaurios, true);
		}

		if ($msg['chat']['type'] == "private"){
			$usaurios = file_get_contents('./users.json');
			$user = json_decode($usaurios, true);
		}

		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi ,true);

		if (!$user[$chatid]){
			die;
		}
		
		if ($user[$chatid]['contador'] >= 50){
			$msgs = $confibot['spam'][$chatid];
			$msgs = array_values($msgs);
			$spammer = $msgs[array_rand($msgs)];
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>$spammer,'parse_mode'=>'html'));

			$user[$chatid]['contador'] = 0;

			if ($msg['chat']['type'] == "supergroup"){
				$dsalva = json_encode($user,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
				$salva = file_put_contents('./grups.json', $dsalva);
			}

			if ($msg['chat']['type'] == "private"){
				$dsalva = json_encode($user,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
				$salva = file_put_contents('./users.json', $dsalva);
			}
		}else{
			$user[$chatid]['contador'] = $user[$chatid]['contador']+1;
			
			if ($msg['chat']['type'] == "supergroup"){
				$dsalva = json_encode($user,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
				$salva = file_put_contents('./grups.json', $dsalva);
			}

			if ($msg['chat']['type'] == "private"){
				$dsalva = json_encode($user,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
				$salva = file_put_contents('./users.json', $dsalva);
			}
		}
		
	}

	function listamsg($msg){
		$butao =  ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]];
		$chatid = $msg['chat']['id'];
		
		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi ,true)['spam'][$chatid];
		$lista = [];

		foreach ($confibot as $id => $msgsalva) {
			
			$lista[] = "<b>id</b>: $id » <b>msg</b>: $msgsalva\n";
		}
		 $msgs = implode('', $lista);	

		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"Lista de msgs do spam:\n\n$msgs",'parse_mode'=>'html',"reply_markup" => $butao));
	}
	 

	function getmetion ($msg){


		$chatid = $msg['chat']['id'];
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);

		if ($msg['reply_to_message']['from']['id']){
			$id = $msg['reply_to_message']['from']['id'];
		}else{
			$user = explode(" ", $msg['text'])[1];
			$user = str_replace("@", "", $user);
			foreach ($data as $key => $value) {
				
				if ((string) $value['username'] == (string) $user){
					$id = $key;
				}	
			
			}
		}

		if ($id){
			return $id;
		}else{
			return false;
		}
		
	}



	function addadmin($msg,$user){
		$chatid = $msg['chat']['id'];
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);

		if (!$data[$user]){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"usuario nao esta na db do bot !"));
			return;
		}
		
		$data[$user]['adm'] = "true";

		$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./usuarios.json', $dsalva);

		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
	    	exit();
		}
	    
	}

	function deleteadmin($msg,$user){
		$chatid = $msg['chat']['id'];
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);

		if (!$data[$user]){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"usuario nao esta na db do bot !"));
			return;
		}
		
		$data[$user]['adm'] = "false";
		$salvaUser = json_encode($data);
		$salva = file_put_contents('./usuarios.json', $salvaUser);
		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
	    	exit();
		}
	    
	}


	function showadms($msg){
		$chatid = $msg['chat']['id'];
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);
		$adms = "<b>LISTA DE ADMS DO BOT:\n</b>";

		foreach ($data as $key => $value) {
		
			if ($value['adm'] == "true"){

				$adms .= "
	<b>ID: $key</b>
	<b>Nome: $value[nome]</b>
	<b>User: $value[username]</b>
	<b>Total Msgs: $value[totalmsg]</b>
	<b>Total Consultas: $value[totalcon]</b>\n";
			}

		}

		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>$adms,"parse_mode" => "html"));
	    	exit();
	}

	function ban($msg,$id){
		$chatid = $msg['chat']['id'];

		$moder = $msg['from']['id'];
		$res =json_decode(bot("getChatMember",array("chat_id" => $chatid , "user_id" => $moder)) , true);

		if ((string)$res['result']['status'] != (string) "creator" and (string)$res['result']['status'] != (string) "administrator"){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"<b>Ops vc deve ser adm/moderador para executa este comando !</b>","parse_mode" => "html","reply_to_message_id" =>$msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
			die();
		}
		
		$kick = bot("kickChatMember" , array("chat_id" => $chatid , "user_id" => $id));
		if (json_decode($kick,true)['result'] == true){
			$butao =  array('inline_keyboard' => array(
				array(
					array('text'=>"desbanir usuario",'callback_data'=>"desban_".$id), 
				),
			)
		);
			$msgeviada = sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"<b>mandado pro quintus dus infernos \nbanidor com sucesso</b>","parse_mode" => "html","reply_markup" => $butao));
			$json = json_decode($msgeviada , true);
			// bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $json['result']['message_id'] ));
			die();
		}else{
			$msgeviada = sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"<b>Ops nao consegui banir este usuario , provavelmente nao sou um admin/ele e um admin !","parse_mode" => "html","reply_to_message_id" =>$msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
			die();
		}

	}

	function desban ($msg1,$user,$queryid){



		$chatid = $msg['chat']['id'];
		if ($queryid){
			$msg = $msg1['message'];
		}else{
			$msg = $msg1;
		}
		$admid = $msg1['from']['id'];

		$res =json_decode(bot("getChatMember",array("chat_id" => $chatid , "user_id" => $admid)) , true);

		if ((string)$res['result']['status'] != (string) "creator" and (string)$res['result']['status'] != (string) "administrator"){
			if ($queryid){
				sendMessage("answerCallbackQuery",array("callback_query_id" => $queryid , "text" => "ops vc nao pode desbanir este usuario apenas adms/moderadores podem !","show_alert"=>true,"cache_time" => 10));
			   die();
			}else{
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "ops vc nao pode desbanir este usuario apenas adms/moderadores podem !","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
				die();
			}
		}

		$unban = json_decode(bot("unbanChatMember",array("chat_id" => $chatid , "user_id" => $user)),true);

		if ($unban['result'] == true){
			if ($queryid){
				sendMessage("answerCallbackQuery",array("callback_query_id" => $queryid , "text" => "usuario desbanido !","show_alert"=>true,"cache_time" => 10));

				bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $msg['message_id']));
			    die();
			}else{
				sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "usuario desbanido !","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
			}
			die();
		}else{
			if ($queryid){
				sendMessage("answerCallbackQuery",array("callback_query_id" => $queryid , "text" => "nao consegue desbanir o usuario !","show_alert"=>true,"cache_time" => 10));
				die();
			}
			sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "nao consegue desbanir o usuario !","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
			die();
		}
		
	}

	function baine($msg,$id){
		$chatid = $msg['chat']['id'];
		$time = time();
		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		$usuarios = file_get_contents('./usuarios.json');
		$dadoUser = json_decode($usuarios , true);


		if (!$dadoUser[$id]){
			sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "nao encontrei ele na minha db"));
			die();
		}

		if ($confibot['chatsblock'][$id]){
			sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "ja ta na blacklist !"));
			die();
		}

		$confibot['chatsblock'][$id] = $time;
		$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./confi.json', $dsalva);
		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
	    	exit();
		}
	}


	function desbaine($msg,$id){
		$chatid = $msg['chat']['id'];
		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		
		if (!$confibot['chatsblock'][$id]){
			sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "ue , mas ele nao ta block skksk"));
			die();
		}

		unset($confibot['chatsblock'][$id]);


		$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./confi.json', $dsalva);
		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
	    	exit();
		}else{
	        sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"error ao salvo"));
	    	exit();
	    }
	}




	function tell($msg){
		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		$url = $confibot['url'];

		$chatid = $msg['chat']['id'];
		
		$tellrand = file_get_contents("$url/conf/sms.php?gettell=true");


		$dados = json_decode($tellrand , true);
		if ($dados['status'] == 'true'){
			$tell = $dados['number'];
			$pais = $dados['pais'];
			$bateria = rand(44,99);



			sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "

	<b>Status:</b> <i>ON-LINE</i>
	<b>Numero:</b> <code><i>+$tell</i></code>
	<b>Pais:</b> <i>$pais</i>
	<b>Bateria:</b> <i>$bateria%</i>

			","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],[['text'=>"Receber sms",'callback_data'=>"sms_".$tell."_".$pais.""]],[['text'=>"Outro Numero",'callback_data'=>"alternumber"]]]],"parse_mode" => "html"));
			die();
		}else{
			
			sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "error no diretorio "));

		}
		
		
	}

	function sms($number,$pais,$msg){
		$idquery = $msg['id'];
		$msgid = $msg['message']['message_id'];
		$chatid = $msg['message']['chat']['id'];
		$userid = $msg['from']['id'];
		$userid2 = $msg['message']['reply_to_message']['from']['id'];

		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		$url = $confibot['url'];

		
		if ($userid != $userid2){
			sendMessage("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "sem permissao ksks!","show_alert"=>false,"cache_time" => 10));
			die();
		}

		$str = '';
		$str .= "<b>« _______________________________________»</b>\n";

		$str .= "<b>10 ULTIMAS MSG \nNumero: $number\nPais: $pais</b>\n";

		$msgs = request("$url/conf/sms.php?tell=$number&c=$pais",false,array());
		$dados = json_decode($msgs , true);
		$n = 1;
		foreach ($dados as $key => $value) {
			$n = $key +1;
			$str .= "<b>« _______________________________________»</b>\n\n";
			$str .= "$n » De: {$value[from]} » Msg: {$value[message]} » Enviada a: {$value[time]}\n";
			
		}
		$str .= "<b>« _______________________________________»</b>\n";
		
		bot("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => $str,'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],[['text'=>"Atualiza Msgs",'callback_data'=>"sms_{$number}_{$pais}"]]]]));

	}

	function alternumber($msg){

		$idquery = $msg['id'];
		$msgid = $msg['message']['message_id'];
		$chatid = $msg['message']['chat']['id'];
		$userid = $msg['from']['id'];
		$userid2 = $msg['message']['reply_to_message']['from']['id'];
		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		$url = $confibot['url'];


		if ($userid != $userid2){
			sendMessage("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "sem permissao ksks!","show_alert"=>false,"cache_time" => 10));
			die();
		}
		
		sendMessage("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Aterando!","show_alert"=>false,"cache_time" => 10));

		$tellrand = request("$url/conf/sms.php?gettell=true",false,array());

		$dados = json_decode($tellrand , true);
		if ($dados['status'] == 'true'){
			$tell = $dados['number'];
			$pais = $dados['pais'];
			$bateria = rand(44,99);
			$text = bot("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" =>  "

	<b>Status:</b> <i>ON-LINE</i>
	<b>Numero:</b> <code><i>+$tell</i></code>
	<b>Pais:</b> <i>$pais</i>
	<b>Bateria:</b> <i>$bateria%</i>

			","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],[['text'=>"Outro Numero",'callback_data'=>"alternumber"]]]],"parse_mode" => "html"));
			die();
		}

		deleteautomsg($msg,$text);

	}


	function desab($msg , $value , $cmds){
		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		$chatid = $msg['chat']['id'];

		if (!$cmds[$value]){
			die(bot("sendMessage", array("chat_id" => $chatid , "text" => "<b>Sorry , comand not found !</b>" , "parse_mode" => "html" , "reply_to_message_id" => $msg['message_id'])));
		}

		if ($confibot['cmdsoff'][$value]){
			die(bot("sendMessage", array("chat_id" => $chatid , "text" => "<b>comando ja desabilitado !</b>" , "parse_mode" => "html" , "reply_to_message_id" => $msg['message_id'])));
		}
		$confibot['cmdsoff'][$value] = "offline";
		$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );

		$salva = file_put_contents('./confi.json', $dsalva);
		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
	    	exit();
		}
		
	}


	function habi($msg , $value , $cmds){

		$chatid = $msg['chat']['id'];

		if (!$cmds[$value]){
			die(bot("sendMessage", array("chat_id" => $chatid , "text" => "<b>Sorry , comand not found !</b>" , "parse_mode" => "html" , "reply_to_message_id" => $msg['message_id'])));
		}

		$confi = file_get_contents('./confi.json');
		$confibot = json_decode($confi, true);

		if (!$confibot['cmdsoff'][$value]){
			die(bot("sendMessage", array("chat_id" => $chatid , "text" => "<b>comando ja habilitado !</b>" , "parse_mode" => "html" , "reply_to_message_id" => $msg['message_id'])));
		}
		unset($confibot['cmdsoff'][$value] );
		
		$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./confi.json', $dsalva);
		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
	    	exit();
		}
		
	}



	

	function gravamsgofe($msg,$cmd){
		$frases = file_get_contents('./fraseofe.json');
		$frases = json_decode($frases, true);

		$chatid = $msg['chat']['id'];
		$txt = explode(" ", $msg['text']);
		$chatalter = $chatid;

		if ($txt[1] == "d" || $txt[1] == "default"){
			$chatalter = "default";
			$frase = substr($msg['text'], strlen($cmd) + strlen($txt[1])+2);
		}else{
			$frase = substr($msg['text'], strlen($cmd)+1);
		}

		$frases[$chatalter][] = trim($frase);
		
		$dsalva = json_encode($frases,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./fraseofe.json', $dsalva);
		if ($salva){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
	    	exit();
		}
	}

	function rank($msg){
		$chatid = $msg['chat']['id'];
		

		$userdb = file_get_contents('./usuarios.json');
		$users = json_decode($userdb, true);

		
		$emoji = ["false",'1️⃣','2️⃣','3️⃣','4️⃣','5️⃣','6️⃣','7️⃣','8️⃣','9️⃣','🔟'];
		$users2 = [];

		foreach ($users as $key => $value) {
			$users2[$key] = $value['totalmsg'];
		}
		arsort($users2);
		$count = 1;

		$userDesta = '';
		foreach ($users2 as $key => $value) {
			if ($count >= 10){
				break;
			}else{
				$count++;
			}
			$dados = $users[$key];
			if ($dados['username'] == 'undefined'){
				$username = $dados['nome'];
			}else{
				$username = $dados['username'];
			}
			$userDesta .= "$emoji[$count] - *Nome: *{$dados[nome]} - *User:* [$username](tg://user?id=$key) - *TotalMsg:*{$dados[totalmsg]}\n";;
			
			
		}


		bot("sendMessage" , array("chat_id" => $chatid, "text" =>  $userDesta , "parse_mode" => 'Markdown'));

	}	



	function deleteautomsg($msg,$msgbot=false){

		$chatid = $msg['chat']['id'];

		
		sleep(5);

		if ($msgbot){
			$msgbotid = json_decode($msgbot , true)['result']['message_id'];
		}else{
			$msgbotid = $msg['message_id'] +1;
		}

	    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $msg['message_id']));
	    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $msgbotid ));
	}

	function deletesfsf($msg,$id){

		$chatid = $msg['chat']['id'];
	    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $id));
	 
	}

function buscaprox ($msg,$busca){
	$args = explode(" ", $msg['text']);
	$cmd = $args[1];
	$str = $msg['text'];
	
	$keys = ["-C","-D","-E" , "-DELL" , "-S","-U", "-m", "-c"];


	$vl = [];
	foreach ($keys as $value) {
		$pos  = strpos($str, $value);
		$vl[$value] = $pos;
	}
	arsort($vl);


	$positions = array_keys($vl);
	$ks = array_search($busca, $positions);

	if ($ks == 0){
		$prox = $ks;
	}else{
		$prox = $ks -1;
	}

	return $positions[$prox];
}

function addchk($msg){
	$chatid = $msg['chat']['id'];

	$args = explode(" ", $msg['text']);
	$cmd = $args[1];
	$str = $msg['text'];
	
	$user_id = $msg['from']['id'];
	$cmds = ["-C","-D","-E" , "-DELL" , "-S","-U"];
	$keys  = explode("-", $str);
	unset($keys[0]);
	$cmdstrue = 0;
	foreach ($keys as $key) {
		$key  = "-".explode(" ", $key)[0];
		if (in_array($key, $cmds)){
			$cmdstrue ++;
		}
		
		
	}
	

	if ($cmdstrue == 0){
		die(bot('sendMessage' , array( 'chat_id' => $chatid , "text" => "<b>Error !\nuse: /addchk [nome chk]\nobs:este cmd funciona atras de urls , urls dos chks ja hospedados  !\noptins: \n/addchk [cmd] -C (especifica o chat / grupo ) caso nao seja especificado ele ira recebe do chat atual\n/addchk [cmd] -D (altera a descricao de chk /ja add no bot ) \n/addchk [cmd] -E (altera o exemplo de uso do cmd)\n/addchk [cmd] -U (add a url do chk)\n/addchk [cmd] -S (altera o status do cmd on/off use: sim para fica off e nao para deixa on )\n/addchk [cmd] -DELL (apagara este cmd use: /addchk [cmd] -DELL )\nexemplo:\n/addchk ggs -C -1001330941820 -D checa ggs -E use: /ggs 4982xx|1x|2022|xx</b>","parse_mode" => "html")));
	}
	
	if (empty($cmd) || $cmd == "-D" || $cmd == "-C" || $cmd == "-E" || $cmd == "-S" || $cmd == "-DELL"){
		die(bot('sendMessage' , array( 'chat_id' => $chatid , "text" => "<b>Error !\nuse: /addchk [nome chk]\nobs:este cmd funciona atras de urls , urls dos chks ja hospedados !\noptins: \n/addchk [cmd] -C (especifica o chat / grupo ) caso nao seja especificado ele ira recebe do chat atual\n/addchk [cmd] -D (altera a descricao de chk /ja add no bot ) \n/addchk [cmd] -E (altera o exemplo de uso do cmd)\n/addchk [cmd] -U (add a url do chk)\n/addchk [cmd] -S (altera o status do cmd on/off use: sim para fica off e nao para deixa on )\n/addchk [cmd] -DELL (apagara este cmd use: /addchk [cmd] -DELL )\nexemplo:\n/addchk ggs -C -1001330941820 -D checa ggs -E use: /ggs 4982xx|1x|2022|xx</b>","parse_mode" => "html")));
	}

	




	if (in_array("-C", $args)){

		$pos = buscaprox($msg,"-C");

		if ($pos == "-C"){
			$key = explode("-C", $str)[1];
		}else{
			$key = explode("-C", $str)[1];
			$key = explode($pos, $key)[0];
		}

	}else{
		$key = $chatid;

	}
	
	
	$key = trim($key);
	
	$confi = file_get_contents('./confi.json');
	$confibot = json_decode($confi, true);

	if ($confibot['chks'][$key][$cmd]['owner']){
	 	if ($confibot['chks'][$key][$cmd]['owner'] != $user_id){
	 		return;
	 	}
	}
	if (in_array("-DELL", $args)){
		
		$pos = buscaprox($msg,"-DELL");
		if ($pos == "-DELL"){
			$opc = explode("-DELL", $str)[1];
		}else{
			$opc = explode("-DELL", $str)[1];
			$opc = explode($pos, $opc)[0];
		}

		
		if (strpos($opc, "sim") !== false || strpos($opc, "s") !== false || strpos($opc, "y") !== false){

			if (!$confibot['chks'][$key][$cmd]){
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"cmd not found"));
				exit();
			}
			unset($confibot['chks'][$key][$cmd]); 
			$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
			$salva = file_put_contents('./confi.json', $dsalva);
			if ($salva){
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"cmd apagado !"));
				exit();
			}
		}
	}

	if (in_array("-D", $args)){
		
		$pos = buscaprox($msg,"-D");
		if ($pos == "-D"){
			$desc = explode("-D", $str)[1];
		}else{
			$desc = explode("-D", $str)[1];
			$desc = explode($pos, $desc)[0];
		}
	}else{
		if ($confibot['chks'][$key][$cmd]['desc']){
			$desc = $confibot['chks'][$key][$cmd]['desc'];
		}else{
			$desc = "CHECKER";
		}
		
	}
	
	if (in_array("-E", $args)){
		$pos = buscaprox($msg,"-E");
		if ($pos == "-E"){
			$modeUse = explode("-E", $str)[1];
		}else{
			$modeUse = explode("-E", $str)[1];
			$modeUse = explode($pos, $modeUse)[0];
		}
	}else{
		if ($confibot['chks'][$key][$cmd]['modeUse']){
			$modeUse = $confibot['chks'][$key][$cmd]['modeUse'];
		}else{
			$modeUse = "use: $cmd [arg]";
		}

		
	}

	if (in_array("-S", $args)){
		$pos = buscaprox($msg,"-S");
		if ($pos == "-S"){
			$onoff = explode("-S", $str)[1];
		}else{
			$onoff = explode("-S", $str)[1];
			$onoff = explode($pos, $onoff)[0];
		}
	}else{
		if ($confibot['chks'][$key][$cmd]['off']){
			$onoff = $confibot['chks'][$key][$cmd]['off'];
		}else{
			$onoff = "false";
		}

		
	}

	if (in_array("-U", $args)){
		$pos = buscaprox($msg,"-U");
		if ($pos == "-U"){
			$U = explode("-U", $str)[1];
		}else{
			$U = explode("-U", $str)[1];
			$U = explode($pos, $U)[0];
		}
	}else{
		if ($confibot['chks'][$key][$cmd]['url']){
			$U = $confibot['chks'][$key][$cmd]['url'];
		}else{
			$U = "false";
		}

	}


	$realdados = $confibot['chks'][$key][$cmd] = array(
		"url" => trim($U),
		"modeUse" => trim($modeUse),
		"off" => trim($onoff),
		"desc" => trim($desc),
		"type" => trim("chk"),
		"owner" => trim($user_id)
	);
	$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./confi.json', $dsalva);
	if ($salva){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
    	exit();
	}

}



function buscap ($msg,$busca,$keys){
	$args = explode(" ", $msg['text']);
	$cmd = $args[1];
	$str = $msg['text'];
	
	$vl = [];
	foreach ($keys as $value) {
		$pos  = strpos($str, $value);
		$vl[$value] = $pos;
	}
	arsort($vl);

	$positions = array_keys($vl);
	$ks = array_search($busca, $positions);

	if ($ks == 0){
		$prox = $ks;
	}else{
		$prox = $ks -1;
	}

	$pos1 = $positions[$prox];

	if ($pos1 == $busca){
		$ttt = explode($busca, $str)[1];
	}else{
		$ttt = explode($busca, $str)[1];
		$ttt = explode($pos1, $ttt)[0];
	}

	return $ttt;

}
function addconkk($msg){

	$chatid = $msg['chat']['id'];
	$fromid = $msg['from']['id'];
	$confi = file_get_contents('./confi.json');
	$confibot = json_decode($confi, true);

	$keys = ["-name" , "-c" , "-NAME" , "-C"];

	$cmd = (buscap($msg , "-name" , $keys)) ? (buscap($msg , "-name" , $keys)) : (buscap($msg , "-CMD" , $keys)) ;

	$chat = (buscap($msg , "-chat" , $keys)) ? (buscap($msg , "-chat" , $keys)) : (buscap($msg , "-CHAT" , $keys)) ;

	if (empty($cmd)){
		die(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "o -name e obrigatorio !")));	
	}

	$chat = (!empty($chat))? $chat : $chatid;

	$cmd = trim($cmd);
	$chat = trim($chat);

	if ($confibot['consultas'][$chat][$cmd]){
		die(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "ja existi uma consulta com esse name/cmd")));
	}
	

	$confibot['consultas'][$chat][$cmd]= array("owner" => $fromid , "off" => "false");
	$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./confi.json', $dsalva);

	if ($salva){
		
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "o cmd: $cmd \nfoi salvo user o edit para realiza alteracoes"));
    	exit();
	}
	
}


function editcon($msg){

	$chatid = $msg['chat']['id'];
	$fromid = $msg['from']['id'];

	$confi = file_get_contents('./confi.json');
	$confibot = json_decode($confi, true);

	$keys = ["-c" , "-d" ,"-e" , "-u", "-C" , "-D" , "-E" ,"-U" , "-name" , "-c" , "-NAME" , "-C"];

	
	$cmd = (buscap($msg , "-name" , $keys)) ? (buscap($msg , "-name" , $keys)) : (buscap($msg , "-CMD" , $keys)) ;

	$chat = (buscap($msg , "-chat" , $keys)) ? (buscap($msg , "-chat" , $keys)) : (buscap($msg , "-CHAT" , $keys)) ;

	$desc = (buscap($msg , "-d" , $keys)) ? (buscap($msg , "-d" , $keys)) : (buscap($msg , "-D" , $keys)) ;

	$url = (buscap($msg , "-u" , $keys)) ? (buscap($msg , "-u" , $keys)) : (buscap($msg , "-U" , $keys)) ;

	$modeUse = (buscap($msg , "-e" , $keys)) ? (buscap($msg , "-e" , $keys)) : (buscap($msg , "-E" , $keys)) ;

	if (empty($cmd)){
		die(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "o -name e obrigatorio !")));	
	}

	$chat = (!empty($chat))? $chat : $chatid;

	$cmd = trim($cmd);
	$chat = trim($chat);
	$desc = trim($desc);
	$url = trim($url);
	$modeUse = trim($modeUse);


	if (!$confibot['consultas'][$chat][$cmd]){
		die(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "Nao existi uma consulta com esse name/cmd")));
	}
	
	if ($confibot['consultas'][$chat][$cmd]['owner'] != $fromid){
		die(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "Error , o chatId de quem add esta consulta e diferente do seu !\nvc so pode edita consultas que vc adicionou!")));
	}
	$txt = "Alteracoes Feitas\n\n";

	if (!empty($desc)){
		$txt .= "descricao: $desc\n";
		$confibot['consultas'][$chat][$cmd]['desc'] = $desc;
	}

	if (!empty($url)){
		$txt .= "Url: $url\n";
		$confibot['consultas'][$chat][$cmd]['url'] = $url;
	}

	if (!empty($modeUse)){
		$txt .= "exemplo de uso: $modeUse\n";
		$confibot['consultas'][$chat][$cmd]['modeUse'] = $modeUse;
	}


	$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./confi.json', $dsalva);

	if ($salva){
		
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $txt));
    	exit();
	}
	
}


function dellcon($msg){

	$chatid = $msg['chat']['id'];
	$fromid = $msg['from']['id'];

	$confi = file_get_contents('./confi.json');
	$confibot = json_decode($confi, true);

	$keys = ["-chat" , "-name","-NAME" , "-CHAT"];
	$cmd = (buscap($msg , "-name" , $keys)) ? (buscap($msg , "-name" , $keys)) : (buscap($msg , "-CMD" , $keys)) ;
	$chat = (buscap($msg , "-chat" , $keys)) ? (buscap($msg , "-chat" , $keys)) : (buscap($msg , "-CHAT" , $keys)) ;

	if (empty($cmd)){
		die(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "o -name e obrigatorio !")));	
	}
	
	$chat = (!empty($chat))? $chat : $chatid;
	
	$cmd = trim($cmd);
	$chat = trim($chat);
	
	if (!$confibot['consultas'][$chat][$cmd]){
		die(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "Nao existi uma consulta com esse name/cmd")));
	}
	if ($confibot['consultas'][$chat][$cmd]['owner'] != $fromid){
		die(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "Error , o chatId de quem add esta consulta e diferente do seu !\nvc so pode edita consultas que vc adicionou!")));
	}
	
	unset($confibot['consultas'][$chat][$cmd]);

	$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./confi.json', $dsalva);

	if ($salva){
		
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "cmd deletado"));
    	exit();
	}
	
}