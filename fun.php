<?php

header('Content-Type: application/json');


function salvaUser($msg){
	$usaurios = file_get_contents('./usuarios.json');
	$data = json_decode($usaurios, true);
    $id = $msg['from']['id'];
	if ($data[$id]){
		return false;
	}
	
	$nome = $msg['from']['first_name'].' '.$msg['from']['last_name'];
	$username = $msg['from']['username'];
	if (empty($username)){$username = "undefined";}
	$time = time();
	$data[$id] = array(
		"type" => 'priv',
		'nome' =>$nome,
		'totalmsg'=>0,
		'totalcon' =>0,
		'cadastro' =>$time,
		"last_msg" =>$time,
		"username" =>$username,
		"adm" => 'false'
	);

    $dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./usuarios.json', $dsalva);

	$chatid = $msg['chat']['id'];

	
    $usaurios = file_get_contents('./usuarios.json');
	$data = json_decode($usaurios, true);

    if ($data[$chatid]){
	    	return;
	}
	if ($msg['chat']['type'] == "supergroup"){
       
		$time = time();
		$username = ($msg['chat']['username']) ? : "undefined";
		$data[$chatid] = [
			'type' => 'group',
			'title' => $msg['chat']['title'],
			'username' => $username,
			'totalmsg'=>0,
			'totalcon' =>0,
			'cadastro' =>$time,
			"last_msg" =>$time,


		];
    $dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./usuarios.json', $dsalva);

	}

	
	
}


function setmsg($msg){
    return ;
	$usaurios = file_get_contents('./usuarios.json');
	$data = json_decode($usaurios, true);
 	$id = $msg['chat']['id'];

	if (!$data[$id]){
		return;
	}
	if ($msg['from']['id']){
		$id = $msg['from']['id'];
	}
	
	
	$data[$id]['last_msg'] = $msg['date'];
	$data[$id]['totalmsg'] = $data[$id]['totalmsg']+1;

	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./usuarios.json', $dsalva);

}


function setcon($msg){
	$usaurios = file_get_contents('./usuarios.json');
	$data = json_decode($usaurios, true);
 	$id = $msg['chat']['id'];

	if (!$data[$id]){
		salvaUser($msg);
	}


	$type = $data[$id]['type'];

	if ($type == "priv"){
		$nome = $data[$id]['nome'];
		$totalcon = $data[$id]['totalcon'];
		$username = $data[$id]['username'];
		$adm = $data[$id]['adm'];

		$data[$id]['type'] = $type;
		$data[$id]['nome'] = $nome;
		$data[$id]['totalcon'] = $totalcon;
		$data[$id]['username'] = $username;
		$data[$id]['adm'] = $adm;
	}else{
		$nome = $data[$id]['title'];
		$totalcon = $data[$id]['totalcon'];
		$username = $data[$id]['username'];
		$adm = $data[$id]['adm'];

		$data[$id]['type'] = $type;
		$data[$id]['title'] = $nome;
		$data[$id]['totalcon'] = $totalcon;
		$data[$id]['usergrup'] = $username;

	}
	$data[$id]['last_msg'] = $msg['date'];
	$data[$id]['totalcon'] = $data[$id]['totalcon']+1;

	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./usuarios.json', $dsalva);

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
		salvaUser($msg);
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


function contador($msg){
	$usaurios = file_get_contents('./contador.json');
	$data = json_decode($usaurios, true);
 	$id = $msg['chat']['id'];

	if ($data[$id]){
		return;
	}
	
	$data[$id] = array('contador' => 1);
	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./contador.json', $dsalva);

}


function setcontador2($msg){
	$usaurios = file_get_contents('./contador.json');
	$data = json_decode($usaurios, true);
 	$id = $msg['chat']['id'];

	if (!$data[$id]){
		return;
	}
	
	$data[$id]['contador'] = $data[$id]['contador']+1;
	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./contador.json', $dsalva);

}

function getcontador($id){
	$usaurios = file_get_contents('./contador.json');
	$data = json_decode($usaurios, true);
	return $data[$id]; 
}

function zeracontado($msg){
	$usaurios = file_get_contents('./contador.json');
	$data = json_decode($usaurios, true);
 	$id = $msg['chat']['id'];


	$data[$id]['contador'] = 0;
	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./contador.json', $dsalva);

}


function setmsgsalvas($msg){
	$usaurios = file_get_contents('./msg.json');
	$data = json_decode($usaurios, true);
 	end($data);         // move the internal pointer to the end of the array
    $ultimamsg = key($data)+1; 
    if (empty($ultimamsg)){
    	$ultimamsg = 1;
    }
    $msgsa = trim(substr($msg['text'], 7));
	$data[$ultimamsg] = array($msgsa); 
	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./msg.json', $dsalva);

}


function setstikeradd($msg){
	$chatid = $msg['chat']['id'];
	$usaurios = file_get_contents('./stickers.json');
	$data = json_decode($usaurios, true);

	if (!$msg['reply_to_message']['sticker']){
		
		return;
	}
  
 	$file_unique_id = $msg['reply_to_message']['sticker']['file_unique_id'];       
  	
  	if ($data[$file_unique_id]){
  		return;
  	}

    $msgsa = $msg['reply_to_message']['sticker']['file_id'];
	$data[$file_unique_id] = $msgsa; 
	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./stickers.json', $dsalva);
	if ($salva){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
    	exit();
	}else{
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"errro ao salvo "));
    	exit();
	}
    
}
function deletemsg($msg,$busca){
	
	$chatid = $msg['chat']['id'];
	$usaurios = file_get_contents('./msg.json');
	$data = json_decode($usaurios, true);
 	if ($data[$busca]){
 		unset($data[$busca]);
 		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"Deletada !",'parse_mode'=>'html'));
 	}else{
 		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"naum encontrei essa msg na db ! !",'parse_mode'=>'html'));
 		die();
 	}

	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./msg.json', $dsalva);

}


function spam($msg){
	$chatid = $msg['chat']['id'];
	$msgs = file_get_contents('./msg.json');
	$msgs = json_decode($msgs , true);
	$msgs = array_values($msgs);
	$msgspam = $msgs[array_rand($msgs)][0];

	$conatdor = getcontador($chatid);
	if ($conatdor['contador'] >= 50){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $msgspam , "parse_mode" => 'html'));	
		 zeracontado($msg);
	}else{
		setcontador2($msg);

	}
	return;
	
}

function listamsg($msg){
	$butao =  ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]];
	$chatid = $msg['chat']['id'];
	$msgs = file_get_contents('./msg.json');
	$msgs = json_decode($msgs , true);
	$lista = [];

	foreach ($msgs as $id => $msgsalva) {
		$msgsad = $msgsalva[0];
		$lista[] = "ID: $id » MSG: $msgsad\n";
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
				if ($value['type'] == 'priv'){
					$id = $key;
				}else{
					break;
				}
				
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
	if ($data[$user]['type'] != "priv"){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"ops apenas usuarios podem ser adms !"));
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
	if ($data[$user]['type'] != "priv"){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"ops apenas usuarios podem ser adms !"));
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
		bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $json['result']['message_id']-1 ));
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
	$user = file_get_contents('./chatsbloques.json');
	$usuarios = file_get_contents('./usuarios.json');
	$dadoUser = json_decode($usuarios , true);

	if (!$dadoUser[$id]){
		sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "<b>deu merda1</b>","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]],"parse_mode" => "html"));
		die();
	}

	$data = json_decode($user , true);

	if ($data[$id]){
		sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "<b>deu merda2</b>","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]],"parse_mode" => "html"));
		die();
	}

	$data[$id] = array("status" => "banido");
	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./chatsbloques.json', $dsalva);
	if ($salva){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
    	exit();
	}
}


function desbaine($msg,$id){
	$chatid = $msg['chat']['id'];
	$user = file_get_contents('./chatsbloques.json');
	
	$data = json_decode($user , true);

	if (!$data[$id]){
		sendMessage("sendMessage",array("chat_id" => $chatid , "text" => "<b>deu merda2</b>","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]],"parse_mode" => "html"));
		die();
	}

	unset($data[$id]);


	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./chatsbloques.json', $dsalva);
	if ($salva){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
    	exit();
	}
}


function verificauser($id){
	$user = file_get_contents('./chatsbloques.json');

	$data = json_decode($user , true);
	if ($data[$id]){
		return json_encode(array("status" => "true"));
	}else{
		return json_encode(array("status" => "false"));
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
		bot("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" =>  "

<b>Status:</b> <i>ON-LINE</i>
<b>Numero:</b> <code><i>+$tell</i></code>
<b>Pais:</b> <i>$pais</i>
<b>Bateria:</b> <i>$bateria%</i>

		","reply_to_message_id" => $msg['message_id'],"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],[['text'=>"Outro Numero",'callback_data'=>"alternumber"]]]],"parse_mode" => "html"));
		die();
	}

}


function desab($msg , $value , $cmds){
	$chatid = $msg['chat']['id'];

	if (!$cmds[$value]){
		die(bot("sendMessage", array("chat_id" => $chatid , "text" => "<b>Sorry , comand not found !</b>" , "parse_mode" => "html" , "reply_to_message_id" => $msg['message_id'])));
	}

	$user = file_get_contents('./cmdsoffs.json');
	$data = json_decode($user , true);

	if ($data[$value]){
		die(bot("sendMessage", array("chat_id" => $chatid , "text" => "<b>comando ja desabilitado !</b>" , "parse_mode" => "html" , "reply_to_message_id" => $msg['message_id'])));
	}
	$data[$value] = "offline";
	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./cmdsoffs.json', $dsalva);
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

	$user = file_get_contents('./cmdsoffs.json');
	$data = json_decode($user , true);

	if (!$data[$value]){
		die(bot("sendMessage", array("chat_id" => $chatid , "text" => "<b>comando ja habilitado !</b>" , "parse_mode" => "html" , "reply_to_message_id" => $msg['message_id'])));
	}
	unset($data[$value] );
	
	$dsalva = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./cmdsoffs.json', $dsalva);
	if ($salva){
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
    	exit();
	}
	
}


function addconkk($msg){
	$chatid = $msg['chat']['id'];
	$confi = file_get_contents('./confi.json');
	$confibot = json_decode($confi, true);


	
	$txt = explode(" ", $msg['text']);

	$cmd = $txt[1];
	$url = $txt[2];
	$de = $txt[3];

	if ($de == 'default' || $de == "d"){
		$confibot['urlconsulta']['default'][$cmd] = $url;
	}else{
		$confibot['urlconsulta'][$chatid][$cmd] = $url;
	}
	
	
	
	$dsalva = json_encode($confibot,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./confi.json', $dsalva);
	$msgid = $msg['message_id'];

	if ($salva){
		bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $msgid ));
		sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>"salvo"));
    	exit();
	}
}