<?php






function request($url , $post = false, $headers = array(),$header = false){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	
	if ($post){
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	}
	if ($header){
		curl_setopt($curl, CURLOPT_HEADER, 1);
	}
	if ($headers){
		curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
	}

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

	return curl_exec($curl);
}




function bin($message,$bin){
	
	setcon($message);
	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];
	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);
	
	$user = getuser($message);
	
	
	$envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>$nome , espera fdp to consultando essa merda</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
    $dados = getuser($message);

    $msgid = json_decode($envia1 , true)['result']['message_id'];

    $request = json_decode(request('https://lookup.binlist.net/'.$bin,false,array()) , false);
    
    $msg = [];
    if (!$request){

    	sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => "<b>não encontrei , resultado nessa bosta</b>",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
    	die();
    }


	foreach ($request as $key => $value) {

	if (gettype($value) == arra_ob || is_array($value)){
		foreach ($value as $key2 => $value2) {

			if (gettype($value2) == arra_ob || is_array($value2)){
				foreach ($value2 as $key3 => $value3) {

					$msg[] = '<b>'.strtoupper($key3).':</b> <code>'.$value3.'</code>';
				}	
			}else{
					$msg[] = '<b>'.strtoupper($key2).':</b> <code>'.$value2.'</code>';
			}

		}
	}else{
		$msg[] = '<b>'.strtoupper($key).':</b> <code>'.$value.'</code>';
	}


	}

    $msg1 = sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg)."\n\n⚠️Esta mensagem se apagara em 10s⚠️",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));

    sleep(10);
    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $message['message_id']));

    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => json_decode($msg1 , true)['result']['message_id']));
    
}





function cep ($message,$value){
	
	setcon($message);
	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];
	
	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);
	
	
	$envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>$nome , espera fdp to consultando essa merda</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));

    $msgid = json_decode($envia1 , true)['result']['message_id'];

    $request = json_decode(request('http://viacep.com.br/ws/'.$value.'/json/',false,array()) , false);
    
    $msg = [];
    if (!$request){
    	sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => "<b>não encontrei , resultado nessa bosta</b>",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
    	die();
    }
    foreach ($request as $key => $value) {
    	$msg[] = '<b>'.strtoupper($key).':</b> <code>'.$value.'</code>';
    }

    // $envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>  implode("\n", $msg)));
      $msg1 = sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg)."\n\n⚠️Esta mensagem se apaga em 5s⚠️",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));

    sleep(5);
    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $message['message_id']));

    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => json_decode($msg1 , true)['result']['message_id']));

}


function ip ($message,$value){
	setcon($message);
	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];
	

	preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$ip,$message['text']);	

	
	$envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>$nome , espera fdp to consultando essa merda</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));


    $msgid = json_decode($envia1 , true)['result']['message_id'];

    $request = json_decode(request('http://ip-api.com/json/'.$ip,false,array()) , false);
    
    $msg = [];
    if (!$request){
    	sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => "<b>não encontrei , resultado nessa bosta</b>",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
    	die();
    }
    foreach ($request as $key => $value) {
    	$msg[] = '<b>'.strtoupper($key).':</b> <code>'.$value.'</code>';
    }

      $msg1 = sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg)."\n\n⚠️Esta mensagem se apaga em 5s⚠️",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));

    sleep(5);
    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $message['message_id']));

    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => json_decode($msg1 , true)['result']['message_id']));

}



function consultavalue($message,$cmd){

	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];
	

	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);



	$txt = substr($message['text'], strlen($cmd)+1);


	$value1212 = $txt;
	
    if (empty($value1212)){
        $error = ($confibot['consultas'][$chatid][$cmd]['modeUse'] != '') ? $confibot['consultas'][$chatid][$cmd]['modeUse'] : "<b>Error: Informe o valor a ser consultado !\nExemplo: /$cmd [valor]</b>";
        sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $error,"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
        die();
        
    }

    if (!$confibot['consultas'][$chatid][$cmd]['url'] || $confibot['consultas'][$chatid][$cmd] == ""){
    	sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "consulta em manutencao","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
    	sendMessage("sendMessage",array("chat_id" => $confibot['consultas'][$chatid][$cmd]['owner'],'text' => "error na consulta: $cmd\ncausa: url vazia",'parse_mode' => "html"));
        die();
    }


	$url = str_replace("{doc}", trim($value1212), $confibot['consultas'][$chatid][$cmd]['url']);
	
	$envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>$nome , espera fdp to consultando [$value1212]</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));


    $msgid = json_decode($envia1 , true)['result']['message_id'];
   
    $request = json_decode(request($url,false,array()) , false);

   
    if (!$request){

    	sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => "<b>Ocorreu um error na consulta !!</b>",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));

        sendMessage("sendmessage",array("chat_id" => $confibot['consultas'][$chatid][$cmd]['owner'] ,"text" =>"error na consulta: $url "));

    	die();
    }

	$msg = [];

	$arra_ob = object;


	foreach ($request as $key => $value) {
		if (gettype($value) == $arra_ob || is_array($value)){

			if (!is_numeric($key)){
				$msg[] = "$key: \n";
			}
			foreach ($value as $key2 => $value2) {
				if (gettype($value2) == $arra_ob || is_array($value2)){
					if (!is_numeric($key2)){$msg[] = "$key2: \n";}
					foreach ($value2 as $k3 => $value3) {
						if (gettype($value3) == $arra_ob || is_array($value3)){

							if (!is_numeric($key3)){$msg[] = "$key3: \n";}
							foreach ($value3 as $key4 => $value4) {

								if (gettype($value4) == $arra_ob || is_array($value4)){
									if (!is_numeric($key4)){$msg[] = "$key4: \n";}
										foreach ($value3 as $key5 => $value5) {
										if (!empty($value5)){
											$msg[] = '•'.strtoupper($key5).': '.$value5.'';
										}
									}
								}else{
									if (!empty($value4)){
										$msg[] = '•'.strtoupper($key4).': '.$value4.'';
									}
								}
							}
						}else{
							if (!empty($value3)){
								$msg[] = '•'.strtoupper($key3).': '.$value3.'';
							}
						}
					}
				}else{
					if (!empty($value2)){
						$msg[] = '•'.strtoupper($key2).': '.$value2.'';
					}
				}
			}
		}else{
			if (!empty($value)){
				$msg[] = '•'.strtoupper($key).': '.$value.'';
			}
		}
	}

	
	// sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $msg));

    $msg1 = sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg)."\n\n⚠️Esta mensagem se apaga em 5s⚠️",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));

    sleep(5);
    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $message['message_id']));

    bot("deleteMessage",array("chat_id" => $chatid , "message_id" => json_decode($msg1 , true)['result']['message_id']));
    


}