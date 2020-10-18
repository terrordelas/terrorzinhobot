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
	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];
	
	$user = getuser($message);
	
	setcon($message);
	
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

	if (gettype($value) == object || is_array($value)){
		foreach ($value as $key2 => $value2) {

			if (gettype($value2) == object || is_array($value2)){
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

    sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg),'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
    
}



function cpf($message,$cpf){

	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];

	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);

	$user = getuser($message);
	
	setcon($message);

	$cpf = str_replace(array(".","-", " "),"", $cpf);
	
	$envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>$nome , espera fdp to consultando essa merda</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
    $dados = getuser($message);

    $msgid = json_decode($envia1 , true)['result']['message_id'];
    $url = $confibot['urlconsulta']['default']['cpf'];

    $request = json_decode(request($url.$cpf,false,array()) , false);


	$msg = [];

	foreach ($request as $key => $value) {

		if (gettype($value) == object || is_array($value)){
			foreach ($value as $key2 => $value2) {

			if (gettype($value2) == object || is_array($value2)){
				foreach ($value2 as $key3 => $value3) {
					if (!empty($value3)){
						$msg[] = '<b>'.strtoupper($key3).':</b> <code>'.$value3.'</code>';

					}

				}	
			}else{
				if (!empty($value2)){
					$msg[] = '<b>'.strtoupper($key2).':</b> <code>'.$value2.'</code>';
				}

			}
		}

		}else{

			if (!empty($value)){
			$msg[] = '<b>'.strtoupper($key).':</b> <code>'.$value.'</code>';
			}

		}
	}

    sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg),'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
    


}

function cep ($message,$value){
	
	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];
	
	$user = getuser($message);
	
	setcon($message);
	
	$envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>$nome , espera fdp to consultando essa merda</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
    $dados = getuser($message);

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
    sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg),'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));

}


function ip ($message,$value){
	
	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];
	

	preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$ip,$message['text']);

	$user = getuser($message);
	
	setcon($message);

	
	$envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>$nome , espera fdp to consultando essa merda</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
    $dados = getuser($message);

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

    sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg),'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));

}



function consultavalue($message,$cmd){

	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];
	$nome = $message['from']['first_name'].' '.$message['from']['last_name'];
	

	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);

    if ($confibot['urlconsulta'][$chatid][$cmd]){
        $url = $confibot['urlconsulta'][$chatid][$cmd];
    }else if ($confibot['urlconsulta']['default'][$cmd]){
        $url = $confibot['urlconsulta']['default'][$cmd];
    }else{
         sendMessage("sendmessage",array("chat_id" => $chatid ,"text" => "error: url nao encontrada !"));

    }
   
	$user = getuser($message);
	
	setcon($message);

	$txt = substr($message['text'], strlen($cmd)+1);


	$value1212 = str_replace(array(".","-"," ", "+","(",")"),"", $txt);
	
    if (empty($value1212)){
        sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>error falta o doc/input a ser consultado use: /cpf [input]</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
        die();
    }

	$envia1 = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>$nome , espera fdp to consultando [$value1212]</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));

    $dados = getuser($message);

    $msgid = json_decode($envia1 , true)['result']['message_id'];
   
    $request = json_decode(request($url.$value1212,false,array()) , false);

    if (!$request){

    	sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => "<b>Ocorreu um error na consulta !!</b>",'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
        sendMessage("sendmessage",array("chat_id" => $confibot['dono'] ,"text" =>"error na consulta: $url "));

    	die();
    }

	$msg = [];

	foreach ($request as $key => $value) {

		if (gettype($value) == object || is_array($value)){
			foreach ($value as $key2 => $value2) {

			if (gettype($value2) == object || is_array($value2)){
				foreach ($value2 as $key3 => $value3) {
					if (!empty($value3)){
						$msg[] = '<b>'.strtoupper($key3).':</b> <code>'.$value3.'</code>';

					}

				}	
			}else{
				if (!empty($value2)){
					$msg[] = '<b>'.strtoupper($key2).':</b> <code>'.$value2.'</code>';
				}

			}
		}

		}else{

			if (!empty($value)){
				$msg[] = '<b>'.strtoupper($key).':</b> <code>'.$value.'</code>';
			}

		}
	}

    sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid,"text" => implode("\n", $msg),'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
    


}