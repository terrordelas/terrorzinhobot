<?php







function chk($msg,$cmd){
	$chatid = $msg['chat']['id'];


	$msgen = json_decode($msgen , true)['result'];
	$msgid = $msgen['message_id'];

	$lista = $msg['text'];
	$lista = substr($lista, strlen($cmd)+1);
    $lista = explode("\n", $lista);


    $confibot = file_get_contents('./confi.json');
    $confibot = json_decode($confibot, true);


    if ($confibot['chks'][$chatid][$cmd]){
        $url = $confibot['chks'][$chatid][$cmd]['url'];
        $mode = $confibot['chks'][$chatid][$cmd]['modeUse'];

    }else if ($confibot['chks'][$chatid][$cmd]['default']){
        $url = $confibot['chks']['default'][$cmd]['url'];
        $mode = $confibot['chks']['default'][$cmd]['modeUse'];

    }else{
        die($msgen = bot('sendMessage' , array('chat_id' => $chatid , "text" => "Url not found" , "reply_to_message_id" => $msg['message_id'])));
    }

    if (sizeof($lista) > 10){
	    die($msgen = bot('sendMessage' , array('chat_id' => $chatid , "text" => "sorry, but you crossed the limit (10)" , "reply_to_message_id" => $msg['message_id'])));

    }


    if ($lista[0] == ''){
         die($msgen = bot('sendMessage' , array('chat_id' => $chatid , "text" => $mode , "reply_to_message_id" => $msg['message_id'] , "parse_mode" => 'Markdown')));
    }

   
  
    $msgen = bot('sendMessage' , array('chat_id' => $chatid , "text" => "Arguade um pouco !", "reply_to_message_id" => $msg['message_id'] ));

    $msgid = json_decode($msgen ,true )['result']['message_id'];
    $apr = 0;
    $rep = 0;
    $erros = 0;
    $lives = [];
    $status = 'parado';
    for ($i=0; $i < sizeof($lista) ; $i++) { 
     
    	$lista2 = str_replace(array(" ",'/','!',":"), "|", trim($lista[$i]));
        if (empty($lista2)){
            die($msgen = bot('sendMessage' , array('chat_id' => $chatid , "text" => $mode , "reply_to_message_id" => $msg['message_id'] , "parse_mode" => 'Markdown')));
            break;
        }

        
    	$request = request($url . $lista2,false);
    	
    	$rand = ".....";
    	$ponto = substr($rand, 0,rand(0,strlen($rand)));
    	if (strpos($request, 'live') !==false || strpos($request, 'LIVE') !==false || strpos($request, 'Aprovada') !==false || strpos($request, 'APROVADA') !==false || strpos($request, 'aprovada') !==false || strpos($request, 'aprovada') !==false || strpos($request, '"status":200')!==false){

    		$apr ++;
    		$status = "Aprovada";

            if (json_decode($request , true)){
                $request = json_decode($request , true);
                $result = '';
                foreach ($request as $key => $value) {
                    $result .= "$key : $value\n";

                }
            }else{
                $result = $request;
                   

            }

            

    		$lives[] = $result;

    	}else if (strpos($request, 'die') !==false || strpos($request, 'Die') !==false || strpos($request, 'Reprovada') !==false || strpos($request, 'REPROVADA') !==false || strpos($request, 'reprovada')!==false || strpos($request, "400") !==false){
    		$rep ++;
    		$status = "Reprovada";
    	}else{
    		$erros ++;
    		$status = "error na api";
    	}

        bot("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid, "text" => 
        	"<b>em fila: $lista2</b>\n<b>status</b>: $status $ponto
        	\n<b>Aprovadas:</b> $apr\n<b>Reprovadas:</b> $rep\n<b>Erros:</b> $erros" , "parse_mode" => "html"));
        sleep(2);
    }


 
    $lives = implode($lives, "\n");


    bot("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid, "text" => 
        	"$lives\n\n<b>status</b>: Finalizado!
        	\n<b>Aprovadas:</b> $apr\n<b>Reprovadas:</b> $rep\n<b>Erros:</b> $erros" , "parse_mode" => "html"));

}

