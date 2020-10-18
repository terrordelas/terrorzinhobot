<?php


// error_reporting(0);

if ($_GET['setweb']){
	$dir = $_SERVER['PHP_SELF'];
	$server = $_SERVER['SERVER_NAME'];
	if (!$_SERVER['HTTP_X_FORWARDED_PROTO'] and $_SERVER['HTTP_X_FORWARDED_PROTO'] !== "https" ){
		die("A url do bot deve ser https ");
	}
	
	$token = file_get_contents('./token.txt');
    $res = file_get_contents('https://api.telegram.org/bot'.$token.'/setwebhook?url='."https://".$server.$dir);
    if ($res){
    	echo $res;
    }else{
    	die("Ocorreu um erro ao seta a utl !");
    }
    die;
}

date_default_timezone_set('America/Fortaleza');


include('./fun.php');
include('./consultas.php');
include('./conversa.php');
// include('./chks.php');

function messages($message){

	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);

	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];

	$getuserblock = json_decode(verificauser($id) , true);

	if ($getuserblock['status'] == "true"){
		die();
	}

	if ($confibot['manutencao'] == "true" and $chatid != $confibot['dono']){
		return false;
	}
	

	$text = strtolower($message['text']);
	preg_match_all('/[a-z-A-Z-0-9]*+/', $text, $args);
	$args = array_values(array_filter($args[0]));
	$cmd = $args[0];
	spam($message);
	salvaUser($message);
	

	$cmds = [
		
		"start" => ["off" => "false" ,"desc"=> "<b>start e start skks</b>" , "modeUse" =>"<b>Ola , sou o terrorzinho ...\ncomandos: [/tools]</b>","args" => "false", "users" => "normal"],

		"tools" => ["off" => "false" ,"desc"=> "<b>TODOS MEUS COMANDOS</b>" , "modeUse" =>"<b>meus comandos</b>","args" => "false", "users" => "null"],

		"ofe" => ["off" => "false" ,"desc"=> "<b>OEFENDE UM USUARIO</b>" , "modeUse" =>"<b>O FDP MARCA UM USUARIO !</b>","args" => "false","metion" => "true", "users" => "grupo"],

		"xingamae" => ["off" => "false" ,"desc"=> "<b>OEFENDE A MAE DE UM USUARIO</b>" , "modeUse" =>"<b>O FDP MARCA UM USUARIO !</b>","args" => "false","metion" => "true", "users" => "grupo"],


		"bin" => ["off" => "false" ,"desc"=> "<b>CONSULTA INFORMAS DO BANCO EMISSOR</b>" , "modeUse" => "<b>❌OPS cade a bin ? fdp e assim cara bin 406669❌</b>","args" => "true", "users" => "normal"],

		"cpf" => ["off" => "false" ,"desc"=> "<b>CONSULTA INFORMACOES</b>" , "modeUse" => "<b>❌OPS cade o cpf ? fdp e assim cara cpf 75754690444❌</b>","args" => "true", "users" => "normal"],

		"addmsg" => ["off" => "false" ,"desc"=> "<b>ADD MSG QUE O BOT ENVIA A CADA 50 ENIADA A ELE !</b>" , "modeUse" => "<b>comando espaco msg</b>","args" => "true","admin" => "true", "users" => "admin"],

		"listmsg" => ["off" => "false" ,"desc"=> "<b>LISTA AS MSGS QUE ESTAO SALVAS !</b>", "modeUse" => "<b>...</b>","args" => "false","admin" => "false", "users" => "admin"],

		"deletemsg" => ["off" => "false" ,"desc"=> "<b>DELETA MSG SALVA !</b>", "modeUse" => "<b>comando espaco idmsg</b>","args" => "true","admin" => "true", "users" => "admin"],

		"addfraseofe" => ["off" => "false" ,"desc"=> "<b>ADD FRASE NO CMD /OFE!</b>", "modeUse" => "<b>comando espaco msg\nUse: %nomeofe de quem vai ser ofendido \nUse: %nomemal - quem esta ofendendo</b>","args" => "true","admin" => "true", "users" => "admin"],

		"addsticker" => ["off" => "false" ,"desc"=> "<b>ADD UM NOVO STICKER A DB!</b>", "modeUse" => "<b>marque o stiker que deseja add</b>","args" => "false","admin" => "true","metion" => "true", "users" => "admin"],

		"addadm" => ["off" => "false" ,"desc"=> "<b>ADD UM NOVO ADM PARA O BOT!</b>", "modeUse" => "<b>MARQUE O NOVO ADM / ENVIE O USE (ELE TEM QUE TA NA MINHA DB)</b>","args" => "false","admin" => "true","metion" => "true", "users" => "admin"],
		
		"deladm" => ["off" => "false" ,"desc"=> "<b>DELETA UM ADM DO BOT !</b>", "modeUse" => "<b>MARQUE O NOVO ADM / ENVIE O USE (ELE TEM QUE TA NA MINHA DB)</b>","args" => "false","admin" => "true","metion" => "true", "users" => "admin"],

		"showadms" => ["off" => "false" ,"desc"=> "<b>MOSTRA OS ADMS DO BOT!</b>", "modeUse" => "<b>NADA</b>","args" => "false","admin" => "false","metion" => "false", "users" => "admin"],

		"ban" => ["off" => "false" ,"desc"=> "<b>BANIR UM USUARIO DO CHAT!</b>", "modeUse" => "<b>MARQUE UM USUARIO !</b>","args" => "false","admin" => "false","metion" => "true", "users" => "grupo"],
		"desban" => ["off" => "false" ,"desc"=> "<b>DESBANIR UM USUARIO DE UM CHAT!</b>", "modeUse" => "<b>MARQUE UM USUARIO !</b>","args" => "false","admin" => "false","metion" => "true", "users" => "grupo"],

		"banbot" => ["off" => "false" ,"desc"=> "<b>banir um usuario de usa o bot!</b>", "modeUse" => "<b>MARQUE UM USUARIO !</b>","args" => "false","admin" => "true","metion" => "true", "users" => "superadmin"],

		"desbanbot" => ["off" => "false" ,"desc"=> "<b>DESBANIR UM USUARIO DO BOT!</b>", "modeUse" => "<b>MARQUE UM USUARIO !</b>","args" => "false","admin" => "true","metion" => "true", "users" => "superadmin"],
		
		"telefones" => ["off" => "false" ,"desc"=> "<b>Receber sms *Numeros Gringos*!</b>", "modeUse" => "<b></b>","args" => "false","admin" => "false","metion" => "false", "users" => "normal"],

		"gg" => ["off" => "false" ,"desc"=> "<b>check gg's!</b>", "modeUse" => "<b>Lista com uma gg \nex: gg 377790613482646|03|2022|0571
 </b>","args" => "true","admin" => "false","metion" => "false", "users" => "normal"],

 		"offline" => ["off" => "false" ,"desc"=> "<b>desabilita um comando !</b>", "modeUse" => "<b>offline [cmd]</b>","args" => "true","admin" => "true","metion" => "false", "users" => "superadmin"],

 		"online" => ["off" => "false" ,"desc"=> "<b>habilita um comando !</b>", "modeUse" => "<b>online [cmd]</b>","args" => "true","admin" => "true","metion" => "false", "users" => "superadmin"],

		"noti" => ["off" => "false" ,"desc"=> "<b>envia msg para todas da db !</b>", "modeUse" => " noti *msg*\nExtra: <b>msg em bold</b>\n<i>msg em italico</i>","args" => "true","admin" => "true","metion" => "true", "users" => "admin"],

		"cep" => ["off" => "false" ,"desc"=> "<b>consulta informacoes de um cep</b>" , "modeUse" =>"<b>error use:\n/cep 05590020</b>","args" => "true", "users" => "normal"],

		"ip" => ["off" => "false" ,"desc"=> "<b>consulta informacoes de um ip</b>" , "modeUse" =>"<b>/ip 192.168.100.28</b>","args" => "true", "users" => "normal"],

		"addcon" => ["off" => "false" ,"desc"=> "<b>add uma consulta Obs: somnete onde  for add (ex: em um grupo ou privado)\n a url deve retorna em json !</b>" , "modeUse" =>"<b>/addcon [comando] [url] </b>","args" => "true", "users" => "normal"],

		"congrup" => ["off" => "false" ,"desc"=> "<b>CONSULTAS EXCLUSIVAS DESTE CHAT !</b>" , "modeUse" =>"<b></b>","args" => "false","metion" => "false", "users" => "grupo"],

		"ping" => ["off" => "false" ,"desc"=> "<b>VER O PING DE UMA REDE !</b>" , "modeUse" =>"<b>/ping ou /ping [ip]</b>","args" => "false","metion" => "false", "users" => "grupo"],



	];

	
	if ($confibot['urlconsulta'][$chatid][$cmd] || $confibot['urlconsulta']['default'][$cmd]){

		consultavalue($message,$cmd);
		die();
	}

	if ($cmds[$cmd]){

		$key = array_search('terrorzinhoalendabot', $args);

		unset($args[$key]);

		

		$offs = file_get_contents('./cmdsoffs.json');
		$offs = json_decode($offs , true);

		$butao =  ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]];


		$tools = $cmds[$cmd];

		if ($tools['off'] == 'true' || $offs[$cmd]){
			sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>❌OPS❌:\n Este comando esta desabilitado !</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
				die();
		}

		if ($tools['admin'] == "true"){
			$userr =  getuser2($message);
			if ($userr['adm'] == "false" and (string)$id != (string) "1093905382"){
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>❌OPS❌:\n Este comando esta habilitado apenas para admin do bot !!!</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
				die();
			}

		}
		$userr =  getuser2($message);
		if ($tools['args'] == "true"){
			if (empty($args[1])){

				if ($cmd == "noti"){
					sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],"reply_markup" =>$butao));
			   		 die();
				}
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
			    die();
			}else{
				

				if ($cmd == 'bin'){
					bin($message,$args[1]);
				}else if ($cmd == 'cpf'){
					cpf($message,$args[1]);
				}else if ($cmd == 'addmsg'){
					if ($userr['adm'] == "true"){
						setmsgsalvas($message);
					}
				}else if ($cmd == 'deletemsg'){
					if ($userr['adm'] == "true"){
						deletemsg($message,$args[1]);
					}
				}else if ($cmd == 'addfraseofe'){
					if ($userr['adm'] == "true"){
					$ofe = fopen("./frasesofe.txt", "a+");
					$msgsa = substr($message['text'], 12);

					$salsamsg = fwrite($ofe, trim($msgsa)."\n");
					if ($salsamsg){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "<b>salvo</b>","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
					}else{
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "tente novamente","reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
						die;
					}
					die;
					}
				}else if ($cmd == "offline"){
					desab($message , $args[1] , $cmds);
				}
				else if ($cmd == "online"){
					habi($message , $args[1] , $cmds);
				}else if ($cmd == "gg"){
					ggtesta($message);
				}else if ($cmd == "noti"){
					$usaurios = file_get_contents('./usuarios.json');
					$data = json_decode($usaurios, true);
                    $users = array_keys($data);
                    $total = sizeof($users);
                    $porlista = array_chunk($users,100);
                    $x = sizeof($porlista);
                    $msgen = substr($message['text'] , 5);
                    $salvamsg = file_put_contents("./msgspam.txt", $msgen);
                    if (!$salvamsg){
                    	 sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "tenta dnv",'reply_markup'=>['inline_keyboard' => [[['text'=>"envia agora",'callback_data'=>"envia_0_nada"]],]]));
						die;
                    }
                    sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "enviando...\nmsg: $msgen\nTotal de users:$total\nsplit : {$x} (100)\n Now: 0\nstatus: n enviando",'reply_markup'=>['inline_keyboard' => [[['text'=>"envia agora",'callback_data'=>"envia_0_nada"]],]]));
					die;
				}else if ($cmd == "cep"){
					cep($message,$args[1]);
				}

				else if ($cmd == "ip"){
					ip($message,$args[1]);
				}else if ($cmd == "addcon"){
					addconkk($message);
				}
			}
		}else if ($tools['metion'] == "true"){
				$usermetion = getmetion($message);
				if ($cmd == 'ofe'){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();
				
					}

					ofeusuario($message,$usermetion);
				}else if ($cmd == 'xingamae'){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						return ;
					}
					maeusuario($message,$usermetion);
				}else if ($cmd == "addadm"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();
						
					}
					if ($userr['adm'] == "true" || (string)$id == (string) $confibot['dono']){
						addadmin($message,$usermetion);
					}
					die;
				}else if ($cmd == "deladm"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();
						
					}
					if ((string)$id == (string) $confibot['dono']){
						deleteadmin($message,$usermetion);
					}
					die;
				}else if ($cmd == "ban"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();
						
					}
					ban($message,$usermetion);
				}else if ($cmd == "desban"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();
						
					}
					desban($message,$usermetion,false);
				}else if ($cmd == "banbot"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();
						
					}
					baine($message,$usermetion);
				}else if ($cmd == "desbanbot"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();
						
					}
					desbaine($message,$usermetion);
				}else if ($cmd == 'addsticker'){
					if (!$message['reply_to_message']['sticker']){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();
						
					}
					if ($userr['adm'] == "true"){
						setstikeradd($message);
					}
				}



		}else{
			if ($cmd == 'start'){
		
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
				die();
			}else if ($cmd == 'listmsg'){
				if ($userr['adm'] == "true"){
					listamsg($message);
				}
			}else if ($cmd == 'tools'){
				$offs = file_get_contents('./cmdsoffs.json');
				$offs = json_decode($offs , true);

                

				$cmdslista = [];
				foreach ($cmds as $cmdl => $options) {
					$desc = $options['desc'];
					if ($chatid == $confibot['dono']){
						$cmdslista[] = "/$cmdl - $desc\n";   
					}else if ($offs[$cmdl]){
						pass;
					}else if ($options['users'] == "admin" and $userr['adm'] == "true" ){
						$cmdslista[] = "/$cmdl - $desc\n";
					}else if ($message['chat']['type'] == "private"){
						if ($options['users'] == "normal"){
							$cmdslista[] = "/$cmdl - $desc\n";
						}
					}else{
						if ($options['users'] == "grupo" || $options['users'] == "normal"){
							$cmdslista[] = "/$cmdl - $desc\n";
						}
					}
					
				}

                $enless2 = $confibot['urlconsulta'][$chatid];
                foreach ($enless2 as $key2 => $value2) {
					$cmdslista[] = "/$key2 - <b>comando deste grupo </b>\n";
			    }

                $enless22 = $confibot['urlconsulta']['default'];
                foreach ($enless22 as $key22 => $value22) {
					$cmdslista[] = "/$key22 - <b>comandos global </b>\n";
			    }


				$listamsg = implode("", $cmdslista);
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $listamsg,"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
				die();
			}else if ($cmd == 'showadms'){
				showadms($message);
			}else if ($cmd == "telefones"){
				tell($message);
			}else if ($cmd == "congrup"){
				$cmdslista = [];
                if ($chatid == $confibot['dono']){
                    $enless = $confibot['urlconsulta'];
                    foreach ($enless as $key => $value) {
					    foreach ($value as $key2 => $value2) {
					          $cmdslista[] = " chat $key - $key2 -  $value2\n\n";
			    	    }
			    	}
                }else{
                    $enless = $confibot['urlconsulta'][$chatid];
                    foreach ($enless as $key => $value) {
					    $cmdslista[] = "/$key - <b>as descricoes estao Indisponíveis </b>\n";
			    	}
                }
				
				
				$listamsg = implode("", $cmdslista);
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $listamsg,"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
				die();
			}else if ($cmd == "ping"){
				$ip = substr($text, strlen($cmd)+1);
				$ip = explode(" ",$ip);
				$ip = array_filter($ip)[1];
				$a = preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/',$text, $doamin);

				if (empty($doamin)){
					$doamin = json_decode(file_get_contents('http://ip-api.com/json/') , true)['query'];
					
				}else{
					$doamin = $doamin[0];
				}
				$ping = utf8_encode(shell_exec("ping $doamin"));

				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "ip: $doamin\n$ping"));

			}else{
				$msgssalvas = procuramsg2($message);
			
			}
		}
	}

	if ($message['reply_to_message']){
		procuramsg($message,$cmds);

	}

	procuramsg2($message);
}

function query($msg){
	$idquery = $msg['id'];
	$idfrom = $msg['from']['id'];
	$message = $msg['message'];
	$dataquery = $msg['data'];

	if ($dataquery == "notifi"){
		setnotifi($message);
		sendMessage("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "opa vc ja pode usa todos as ferramentas!","show_alert"=>false,"cache_time" => 10));
	}

	if (explode("_", $dataquery)){
		$query = explode("_", $dataquery);
		$data = $query[0];
		if ($data == "desban"){
			$userid = $query[1];
			
			desban ($msg,$userid,$idquery);
		}else if ($data == "sms"){

			sms($query[1],$query[2],$msg);
		}else if ($data == "envia"){
            $usaurios = file_get_contents('./usuarios.json');
            $data = json_decode($usaurios, true);
            $users = array_keys($data);
            $total = sizeof($users);
            $porlista = array_chunk($users,100);
            $x = sizeof($porlista);

            if ($query[1] == false){
                $index = 0;
            }

            if ($query[1] == true){
                $index = 1;
            }
            $index = $query[1];

            $opt = $index ++;

            $line = $porlista[$opt];
    
            $gsfhs = file_get_contents('./msgspam.txt');
            if (!$gsfhs){
            	bot("sendMessage" , array("chat_id" => $message['chat']['id'], "text" =>  "error na msg !"));
                die();
            }
            if (!$line){
                bot("sendMessage" , array("chat_id" => $message['chat']['id'], "text" =>  "JA MANDEI PRA TODAS NA MINHA DB"));
                die();
                
            }
            for ($i=0; $i <sizeof($line) ; $i++) {
                 bot("sendMessage" , array("chat_id" => $line[$i], "text" =>  "$gsfhs" , "parse_mode" => 'html'));
            }

            bot("sendMessage" , array("chat_id" => $message['chat']['id'], "text" =>  "enviando...\nmsg: $gsfhs\nTotal de users:$total\nsplit : {$x} (100)\n Now: $index\nstatus:  enviando",'reply_markup'=>['inline_keyboard' => [[['text'=>"continua enviando",'callback_data'=>"envia_{$index}_nada"]],]]));


        }
		
	}

	if ($dataquery == "alternumber"){
		alternumber($msg);
	}

	if ($dataquery == "apagamsg"){
		$userid = $msg['from']['id'];
		$userid2 = $msg['message']['reply_to_message']['from']['id'];
		$chatid = $msg['message']['chat']['id'];

		if ($userid != $userid2){
			sendMessage("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "sem permissao ksks!","show_alert"=>false,"cache_time" => 10));
			die();
		}
		$msgid = $msg['message']['message_id'];
		$msgid2 = $msg['message']['reply_to_message']['message_id'];

		bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $msgid2 ));
		bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $msgid ));

	}
	
	
	
}
function sendMessage($method, $parameters) {
  $options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => json_encode($parameters),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
     )
   );

   $context  = stream_context_create( $options );
   $token = file_get_contents('./token.txt');
   return file_get_contents('https://api.telegram.org/bot'.$token.'/'.$method, false, $context );
}

function bot($method, $parameters) {
  $options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => json_encode($parameters),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
     )
   );

   $context  = stream_context_create( $options );
   $token = file_get_contents('./token.txt');
   return file_get_contents('https://api.telegram.org/bot'.$token.'/'.$method, false, $context );
}

$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

if (isset($update['edited_message'])){
	$msgid = $update['edited_message']['message_id'];
	$chatid = $update['edited_message']['chat']['id'];
	
	bot("deleteMessage",array("chat_id" => $chatid , "message_id" => $msgid+1 ));

	 messages($update["edited_message"]);


}
if (isset($update["message"])) {

	messages($update["message"]);
	

}

if (isset($update["callback_query"])) {
    query($update["callback_query"]);
}

$usuarios = file_get_contents('./usuarios.json');
$usuarios = json_decode($usuarios,true);



header('Content-Type: application/json');
echo json_encode($usuarios,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );