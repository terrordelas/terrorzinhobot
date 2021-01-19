<?php



// error_reporting(0);



date_default_timezone_set('America/Fortaleza');


include('./fun.php');
include('./consultas.php');
include('./conversa.php');
include('./chks.php');


$cmds = [

		"start" => ["off" => "false" ,"desc"=> "<b>start e start skks</b>" , "modeUse" =>"<b>Ola , sou o terrorzinho ...\ncomandos: [/tools]</b>","args" => "false", "users" => "normal","type" => "menu"],

		"tools" => ["off" => "false" ,"desc"=> "<b>TODOS MEUS COMANDOS</b>" , "modeUse" =>"<b>meus comandos</b>","args" => "false", "users" => "null","type" => "menu"],

		"ofe" => ["off" => "false" ,"desc"=> "<b>OEFENDE UM USUARIO</b>","modeUse" =>"<b>O FDP MARCA UM USUARIO !</b>","args" => "false","metion" => "true", "users" => "grupo","type" => "grups"],

		"xingamae" => ["off" => "false" ,"desc"=> "<b>OEFENDE A MAE DE UM USUARIO</b>" , "modeUse" =>"<b>O FDP MARCA UM USUARIO !</b>","args" => "false","metion" => "true", "users" => "grupo","type" => "grups"],


		"bin" => ["off" => "false" ,"desc"=> "<b>CONSULTA INFORMAS DO BANCO EMISSOR</b>" , "modeUse" => "<b>❌OPS cade a bin ? fdp e assim cara bin 406669❌</b>","args" => "true", "users" => "normal","type" => "consultas"],


		"addmsg" => ["off" => "false" ,"desc"=> "<b>Add msgs , que o bot envia a cada 50 msg envias no chat !</b>" , "modeUse" => "<b>comando espaco msg</b>","args" => "true","admin" => "true", "users" => "admin","type" => "admin"],

		"listmsg" => ["off" => "false" ,"desc"=> "<b>LISTA AS MSGS QUE O BOT ENVIA A CADA 50 MSG !</b>", "modeUse" => "<b>...</b>","args" => "false","admin" => "false", "users" => "admin","type" => "admin"],

		"deletemsg" => ["off" => "false" ,"desc"=> "<b>DELETA MSG QUE O BOT ENVIA A CADA 50 MSG  !</b>", "modeUse" => "<b>comando espaco idmsg</b>","args" => "true","admin" => "true", "users" => "admin","type" => "admin"],

		"addfraseofe" => ["off" => "false" ,"desc"=> "<b>ADD FRASE NO CMD /OFE!</b>", "modeUse" => "<b>comando espaco msg\nUse: %nomeofe para ser substituido pelo nome que esta sendo ofendido \nUse: %nomemal para ser substituido pelo nome que esta ofendendo\nEx: %nomeofe ja deu o cu para %nomemal</b>","args" => "true","admin" => "true", "users" => "admin","type" => "admin"],

		"addsticker" => ["off" => "false" ,"desc"=> "<b>ADD UM NOVO STICKER A DB!</b>", "modeUse" => "<b>marque o stiker que deseja add</b>","args" => "false","admin" => "true","metion" => "true", "users" => "admin","type" => "admin"],

		"addadm" => ["off" => "false" ,"desc"=> "<b>ADD UM NOVO ADM PARA O BOT!</b>", "modeUse" => "<b>MARQUE O NOVO ADM / ENVIE O USE (ELE TEM QUE TA NA MINHA DB)</b>","args" => "false","admin" => "true","metion" => "true", "users" => "superadmin"],

		"deladm" => ["off" => "false" ,"desc"=> "<b>DELETA UM ADM DO BOT !</b>", "modeUse" => "<b>marca a msg ou user com o username\nexemplo: /deladm terrordelas</b>","args" => "false","admin" => "true","metion" => "true", "users" => "superadmin"],

		"showadms" => ["off" => "false" ,"desc"=> "<b>MOSTRA OS ADMS DO BOT!</b>", "modeUse" => "<b>NADA</b>","args" => "false","admin" => "false","metion" => "false", "users" => "superadmin"],

		"ban" => ["off" => "false" ,"desc"=> "<b>BANIR UM USUARIO DO CHAT!</b>", "modeUse" => "<b>MARQUE UM USUARIO !</b>","args" => "false","admin" => "false","metion" => "true", "users" => "grupo","type" => "grups"],

		"desban" => ["off" => "false" ,"desc"=> "<b>DESBANIR UM USUARIO DE UM CHAT!</b>", "modeUse" => "<b>MARQUE UM USUARIO !</b>","args" => "false","admin" => "false","metion" => "true", "users" => "grupo","type" => "grups"],

		"banbot" => ["off" => "false" ,"desc"=> "<b>banir um usuario de usa o bot!</b>", "modeUse" => "<b>MARQUE UM USUARIO !</b>","args" => "false","admin" => "true","metion" => "true", "users" => "superadmin","type" => "admin"],

		"desbanbot" => ["off" => "false" ,"desc"=> "<b>DESBANIR UM USUARIO DO BOT!</b>", "modeUse" => "<b>MARQUE UM USUARIO !</b>","args" => "false","admin" => "true","metion" => "true", "users" => "superadmin","type" => "admin"],

		"telefones" => ["off" => "false" ,"desc"=> "<b>Receber sms *Numeros Gringos*!</b>", "modeUse" => "<b></b>","args" => "false","admin" => "false","metion" => "false", "users" => "normal","type" => "grups"],

 		"offline" => ["off" => "false" ,"desc"=> "<b>desabilita um comando !</b>", "modeUse" => "<b>offline [cmd]</b>","args" => "true","admin" => "true","metion" => "false", "users" => "superadmin","type" => "superadmin"],

 		"online" => ["off" => "false" ,"desc"=> "<b>habilita um comando !</b>", "modeUse" => "<b>online [cmd]</b>","args" => "true","admin" => "true","metion" => "false", "users" => "superadmin","type" => "superadmin"],

		"noti" => ["off" => "false" ,"desc"=> "<b>envia msg para todas da db !</b>", "modeUse" => "/noti *msg*\n\nuse os stilos de msgs:\n*bold \*text*\n_italic \*text_\n__underline__\n~strikethrough~","args" => "true","admin" => "true","metion" => "true", "users" => "superadmin	","type" => "superadmin"],

		"cep" => ["off" => "false" ,"desc"=> "<b>consulta informacoes de um cep</b>" , "modeUse" =>"<b>error use:\n/cep 05590020</b>","args" => "true", "users" => "normal","type" => "consultas"],

		"ip" => ["off" => "false" ,"desc"=> "<b>consulta informacoes de um ip</b>" , "modeUse" =>"<b>/ip 192.168.100.28</b>","args" => "true", "users" => "normal","type" => "consultas"],

		"ping" => ["off" => "false" ,"desc"=> "<b>VER O PING DE UMA REDE !</b>" , "modeUse" =>"<b>/ping ou /ping [ip]</b>","args" => "false","metion" => "false", "users" => "normal","type" => "ferramentas"],

        "source" => ["off" => "false" ,"desc"=> "<b>Minha source na github!</b>" , "modeUse" =>"<b>/source </b>","args" => "false","metion" => "false", "users" => "normal","type" => "inforbot"],

        "rank" => ["off" => "false" ,"desc"=> "<b>os fdp que mas mandam msgs</b>" , "modeUse" =>"<b>/rank </b>","args" => "false","metion" => "false", "users" => "normal","type" => "grups"],

        "voteban" => ["off" => "false" ,"desc"=> "<b>inicia uma votacao para banir um fdp</b>" , "modeUse" =>"<b>/vote -U (user do usuario) </b>","args" => "false","metion" => "true", "users" => "normal","type" => "grups"],

         "cmds" => ["off" => "false" ,"desc"=> "<b>os fdp que mas mandam msgs</b>" , "modeUse" =>"<b>/ </b>","args" => "false","metion" => "false", "users" => "null"],








         //-----------------------------------------------------------
         "addcon" => ["off" => "false" ,"desc"=> "<b>add uma consulta </b>", "modeUse" =>"<b>/addcon -name [name] -chat (opcional / porem se usa tera que espesifica no /editcon e no /dellcon ) </b>","args" => "true", "users" => "normal","admin" => "true","type" => "grups"],

         "editcon" => ["off" => "false" ,"desc"=> "<b>edita uma consulta </b>", "modeUse" =>"<b>/editcon -name [name] -options\n\nOptions:\n-chat (chat onde a consulta vai funciona / sen nao informa pegara do chat atual!)\n-u (url)\n-d (descricao)\n-e (exemplo de uso)\nEx: /edicon -name cpf -chat 737636346 -u http:exemplo.com/cpf.php={doc} -d consulta um cpf -e user /cpf 00000000000\n\nObs: A url deve retorna em json!\nO {doc} sera substituido pelo arg/valor/input na hora da consulta ,  vc dever passalo com as { } para que funcione corretamente\nVc pode usa todos os options ou apenas um de cada vez ;)</b>","args" => "true", "users" => "normal","admin" => "true","type" => "grups"],

		"dellcon" => ["off" => "false" ,"desc"=> "<b>apaga uma consulta ja existente !</b>" , "modeUse" =>"<b>/dellcon -name -chat (opcional) </b>","args" => "true", "users" => "normal","admin" => "true","type" => "grups"],
		//-------------------------------------------------------------------

		

	];
function messages($message){

	try{
	salvaUser($message);


	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);

	$chatid = $message['chat']['id'];
	$id = $message['from']['id'];

    $adms = file_get_contents('./usuarios.json');
	$adms = json_decode($adms, true);

	if ($confibot['chatsblock'][$id] and $adms[$id]['adm'] != "true" ){
		die;
	}

	if ($confibot['manutencao'] == "true" and $chatid != $confibot['dono']){
		return false;
	}



	$text = strtolower($message['text']);
	preg_match_all('/[a-z-A-Z-0-9]*+/', $text, $args);
	$args = array_values(array_filter($args[0]));
	$cmd = $args[0];



	$cmds = $GLOBALS['cmds'];

	if ($confibot['consultas'][$chatid][$cmd] || $confibot['consultas']['default'][$cmd]){
		// setcon($message);
		consultavalue($message,$cmd);
		die();
	}

	if ($confibot['chks'][$chatid][$cmd] || $confibot['chks']['default'][$cmd]){
		// setcon($message);
		chk($message,$cmd);
		die();
	}

	if ($cmd == 'enviaimg'){
		senDocs('sendPhoto',array( "type" => "photo" ,"url" => "" , "chat_id" => $chatid , "caption" => "* kkkkkkkkkkk *" , "parse_mode" => "markdown" ));
	}

	if ($cmds[$cmd]){

		$search = $confibot['userBot'];
		$key = array_search($search, $args);

		unset($args[$key]);


		$butao =  ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]];


		$tools = $cmds[$cmd];

		if ($tools['off'] == 'true' || $confibot['cmdsoff'][$cmd]){
			return;
			die;
		}

		if ($tools['admin'] == "true"){
			// $userr =  getuser2($message);
			// if ($userr['adm'] == "false" || (string) $id != (string) $confibot['dono']){

			// 	die();
			// }

		}
		$userr =  getuser2($message);
		if ($tools['args'] == "true"){

			if (empty($args[1])){

				if ($cmd == "noti"){
					sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],"reply_markup" =>$butao));
			   		 die();
				}
				$msgbot = sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
				// deleteautomsg($message,$msgbot);
			    die();
			}else{


				if ($cmd == 'bin'){
					bin($message,$args[1]);
					die();
				}else if ($cmd == "addcon"){
					if ($userr['adm'] == "true" || (string) $id == (string) $confibot['dono']){
						addconkk($message);
						die();
					}
					
				}else if ($cmd == "editcon"){
					if ($userr['adm'] == "true" || (string) $id == (string) $confibot['dono']){
						editcon($message);
						die();
					}
					
				}else if ($cmd == "dellcon"){
					if ($userr['adm'] == "true" || (string) $id == (string) $confibot['dono']){
						dellcon($message);
						die();
					}
					
				}else if ($cmd == 'addmsg'){
					if ($userr['adm'] == "true"){
						setmsgsalvas($message);
						die();
					}
					die();
				}else if ($cmd == 'deletemsg'){
					if ($userr['adm'] == "true"){
						deletemsg($message,$args[1]);
						die();
					}
					die();
				}else if ($cmd == 'addchk'){
					if ($userr['adm'] == "true"){
						addchk($message);
						die();
					}
					die();
				}else if ($cmd == 'addfraseofe'){
					if ($userr['adm'] == "true"){
						gravamsgofe($message,$cmd);
						die;
					}
					die();
				}else if ($cmd == "offline"){
					if ($userr['adm'] == "true" || (string) $id == (string) $confibot['dono']){
						desab($message , $args[1] , $cmds);
						die();
					}
					
				}
				else if ($cmd == "online"){
					if ($userr['adm'] == "true" || (string) $id == (string) $confibot['dono']){
						habi($message , $args[1] , $cmds);
						die();
					}
					
				}else if ($cmd == "noti"){

     //                $users = file_get_contents('./users.json');
					// $users = json_decode($users, true);
					// $grups = file_get_contents('./grups.json');
					// $grups = json_decode($grups, true);


					// $str = $message['text'];

					// $args = explode(" ", $str);
					// if (in_array("-c", $args)){
					// 	$prox = 	($message,"-c");

					// 	if ($prox == "-c"){
					// 		$text = explode("-c", $str)[1];

					// 	}else{
					// 		$text = explode("-c", $str)[1];
					// 		$text = explode($prox, $text)[0];
					// 	}

					// 	$chats = explode(" ", $text);
					// 	$data = array_values(array_filter($chats));

					// }else{
					// 	$data = array_merge(array_keys($users),array_keys($grups));
					// }

					// if (in_array("-m", $args)){
					// 	$prox = buscaprox($message,"-m");
					// 	if ($prox == "-m"){
					// 		$mens = explode("-m", $str)[1];
					// 	}else{

					// 		$mens = explode("-m", $str)[1];
					// 		$mens = explode($prox, $mens)[0];
					// 	}
					// }else{
					// 	$mens = "geral";
					// }


					// $arrayValues = [];

     //                $total = sizeof($data);
     //                $porlista = array_chunk($data,100);
     //                $x = sizeof($porlista);


     //                $salvad = json_encode(array(
     //                	"chats" => $data,
     //                	"msg" => trim($mens)
     //                ),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
     //                $salvamsg = file_put_contents("./msgspam.json", $salvad);
     //                if (!$salvamsg){
     //                	 sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "tenta dnv",'reply_markup'=>['inline_keyboard' => [[['text'=>"envia agora",'callback_data'=>"envia_0_nada"]],]]));
					// 	die;
     //                }
     //                sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "enviando...\nmsg: $mens\nTotal de users:$total\nsplit : {$x} (100)\n Now: 0\nstatus: n enviando",'reply_markup'=>['inline_keyboard' => [[['text'=>"envia agora",'callback_data'=>"envia_0_nada"]],]]));
					die;
				}else if ($cmd == "cep"){
					cep($message,$args[1]);
					die();
				}

				else if ($cmd == "ip"){
					ip($message,$args[1]);
					die();
				}
				
			}
		}else if ($tools['metion'] == "true"){
				$usermetion = getmetion($message);
				if ($cmd == 'ofe'){
					if (!$usermetion){
						// sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();

					}

					ofeusuario($message,$usermetion);
				}else if ($cmd == 'xingamae'){
					if (!$usermetion){
						// sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						return ;
					}
					maeusuario($message,$usermetion);
					die();
				}else if ($cmd == 'voteban'){
					if (!$usermetion){


						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						return ;
					}


					voteban($message,$usermetion,false);
					die();


				}else if ($cmd == "addadm"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();

					}

					if ($userr['adm'] == "true" || (string) $id == (string) $confibot['dono']){
						addadmin($message,$usermetion);
						die();
					}
					die;
				}else if ($cmd == "deladm"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();

					}
					
					if ((string)$id == (string) $confibot['dono']){
						deleteadmin($message,$usermetion);
						die();
					}
					die;
				}else if ($cmd == "ban"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();

					}
					ban($message,$usermetion);
					die();
				}else if ($cmd == "desban"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();

					}
					desban($message,$usermetion,false);
					die();
				}else if ($cmd == "banbot"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();

					}
					baine($message,$usermetion);
					die();
				}else if ($cmd == "desbanbot"){
					if (!$usermetion){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();

					}
					desbaine($message,$usermetion);
					die();
				}else if ($cmd == 'addsticker'){
					if (!$message['reply_to_message']['sticker']){
						sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $tools['modeUse'],"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html","reply_markup" =>$butao));
						die();

					}
					if ($userr['adm'] == "true"){
						setstikeradd($message);
					}
					die();
				}



		}else{
			if ($cmd == 'start' || $cmd == 'tools'){


				$menu =  ['inline_keyboard' => [[
					['text'=>"⚙️FERRAMENTAS⚙️",'callback_data'=>"ferramentas"],
					['text'=>"🔎CONSULTAS🔎",'callback_data'=>"consultas"]
				],[
					['text'=>"🎉ADMS BOT🎉",'callback_data'=>"adminsbot"],
					['text'=>"♨️FUNC . GRUPOS♨️",'callback_data'=>"grups"]
				],[
					['text'=>"💰CHECKERS💰",'callback_data'=>"chks"]
				],[
					['text'=>"💈INFOR BOT💈",'callback_data'=>"infobot"],
					['text'=>"🚮APAGA MSG🚮",'callback_data'=>"apagamsg"]
				],]];

				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "*eai , sou o terrorzinho 😳👌🏼*\nuse meus botões para conhece minhas funcionalidades ","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));

				die();
			}else if ($cmd == "rank"){
				rank($message);
				die();
			}else if ($cmd == "cmds"){
				$txt = '';
				foreach ($cmds as $key => $value) {
					$txt .= "/$key - {$value['desc']}\n";
				}
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' =>$txt,"parse_mode" => "html"));
				die();
			}else if ($cmd == 'listmsg'){
				if ($userr['adm'] == "true"){
					listamsg($message);
					die();
				}
				die();
			}else if ($cmd == 'source'){
				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => 'link da minha source: https://github.com/terrordelas/terrorzinhobot',"reply_to_message_id"=> $message['message_id'],'parse_mode' => "html"));
				die();
			}else if ($cmd == 'showadms'){
				showadms($message);
				die();
			}else if ($cmd == "telefones"){
				tell($message);
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

				$msg = json_decode(sendMessage("sendMessage",array("chat_id" => $chatid,'text' => "pingando $doamin...","reply_to_message_id"=> $message['message_id'])) , true)['result'];

				if (strpos(PHP_OS, 'WIN') !==false){
					$ping = utf8_encode(shell_exec("ping $doamin"));
					deletesfsf($msg,$msg['message_id']);
				}else{
					$ping = utf8_encode(shell_exec("ping -c 4 $doamin"));
					deletesfsf($msg,$msg['message_id']);
				}
				

				sendMessage("sendMessage",array("chat_id" => $chatid,'text' => $ping,"reply_to_message_id"=> $message['message_id']));
				die();
			}else{
				$msgssalvas = procuramsg2($message);
				die();
			}
		}

	setmsg($message);

	}else{

		setmsg($message);

		spam($message);
		if ($message['reply_to_message']){
			procuramsg($message,$cmds);
		}
		procuramsg2($message);
	}


	}catch (Throwable $t)
	{
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);
		$token = file_get_contents('./token.txt');
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$t.'');
	}
	catch (Exception $e)
	{
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);
		$token = file_get_contents('./token.txt');
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$e.'');
	}
}

function query($msg){
	try{

	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);

	$idquery = $msg['id'];
	$idfrom = $msg['from']['id'];
	$message = $msg['message'];
	$dataquery = $msg['data'];

	$userid = $msg['from']['id'];
	$userid2 = $msg['message']['reply_to_message']['from']['id'];
	$chatid = $msg['message']['chat']['id'];

	if (explode("_", $dataquery)){
		$query = explode("_", $dataquery);
		$data = $query[0];
		if ($data == "desban"){
			$userid = $query[1];

			desban ($msg,$userid,$idquery);
		}else if ($data == 'voto'){
			voto($message,$query[1],$msg);
			voteban($message,'',$msg);


			die();
		}else if ($data == "sms"){

			sms($query[1],$query[2],$msg);
		}else if ($data == "envia"){


			$cnfnot = file_get_contents('./msgspam.json');
			$cnfnot = json_decode($cnfnot, true);

			$data = $cnfnot['chats'];
			$gsfhs = $cnfnot['msg'];

            $total = sizeof($data);
            $porlista = array_chunk($data,100);
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


            if (!$gsfhs){
            	bot("sendMessage" , array("chat_id" => $message['chat']['id'], "text" =>  "error na msg !"));
                die();
            }
            if (!$line){
                bot("sendMessage" , array("chat_id" => $message['chat']['id'], "text" =>  "JA MANDEI PRA TODAS NA MINHA DB"));
                die();

            }
            for ($i=0; $i <sizeof($line) ; $i++) {
                $envia = bot("sendMessage" , array("chat_id" => $line[$i], "text" => $gsfhs , "parse_mode" => 'markdown'));

                if (!$envia){
                	bot("sendMessage" , array("chat_id" => $message['chat']['id'], "text" => "erorr ao envia: ". $gsfhs." para: ". $line[$i]));

                }

            }
            bot("sendMessage" , array("chat_id" => $message['chat']['id'], "text" =>  "enviando...\nmsg: $gsfhs\nTotal de users:$total\nsplit : {$x} (100)\n Now: $index\nstatus:  enviando",'reply_markup'=>['inline_keyboard' => [[['text'=>"continua enviando",'callback_data'=>"envia_{$index}_nada"]],]]));


        }

	}

	if ($dataquery == "ferramentas"){
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);

		$cmds = $GLOBALS['cmds'];
		$cmdlista = "⚙️<b>MINHAS FERRAMENTAS</b>⚙️\n\n";
		foreach ($cmds as $key => $value) {
			if ($value['type'] == "ferramentas" and !$confibot['cmdsoff'][$key]['offline']){



				$desc = strtoupper($value[desc]);
				$desc = str_replace('<B>', "<b>", $desc);
				$desc = str_replace('</B>', "</b>", $desc);
				$cmdlista .= "<b>/$key</b> - {$desc}\n";
			}
		}
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);

		foreach ($confibot['consultas']['default'] as $key => $value) {
			if ($value['type'] == "ferramentas" and !$confibot['cmdsoff'][$key]['offline'] ){
				$desc = strtoupper($value[desc]);
				$desc = str_replace('<B>', "<b>", $desc);
				$desc = str_replace('</B>', "</b>", $desc);
				$cmdlista .= "<b>/$key</b> - {$desc}\n";
			}
		}
		sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $message['message_id'],"text" => $cmdlista,'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
		die;
	}


	if ($dataquery == "chks"){
		$cmds = $GLOBALS['cmds'];
		$cmdlista = "<b>💰CHECKERS💰</b>\n\n";

		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);

		foreach ($confibot['chks'][$chatid] as $key => $value) {
				if ($value['off'] == "false"){
					$desc = strtoupper($value[desc]);
					$desc = str_replace('<B>', "<b>", $desc);
					$desc = str_replace('</B>', "</b>", $desc);
					$cmdlista .= "<b>/$key</b> - {$desc}\n";
				}


		}

		foreach ($confibot['chks']['default'] as $key => $value) {
				if ($value['off'] == "false"){
					$desc = strtoupper($value[desc]);
					$desc = str_replace('<B>', "<b>", $desc);
					$desc = str_replace('</B>', "</b>", $desc);
					$cmdlista .= "<b>/$key</b> - {$desc}\n";
				}


		}

		sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $message['message_id'],"text" => $cmdlista,'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
		die;
	}


	if ($dataquery == "consultas"){
		$cmds = $GLOBALS['cmds'];
		$cmdlista = "🔎<b>MINHAS CONSULTAS</b>🔎\n\n";
		foreach ($cmds as $key => $value) {
			if ($value['type'] == "consultas" and !$confibot['cmdsoff'][$key]['offline'] ){
				$desc = strtoupper($value[desc]);
				$desc = str_replace('<B>', "<b>", $desc);
				$desc = str_replace('</B>', "</b>", $desc);

				$cmdlista .= "<b>/$key</b> - {$desc}\n";
			}
		}

		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);

		foreach ($confibot['consultas']['default'] as $key => $value) {
			if ($value['type'] == "consultas" and !$confibot['cmdsoff'][$key]['offline'] ){
				$desc = strtoupper($value[desc]);
				$desc = str_replace('<B>', "<b>", $desc);
				$desc = str_replace('</B>', "</b>", $desc);
				$cmdlista .= "<b>/$key</b> - {$desc}\n";
			}
		}

		sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $message['message_id'],"text" => $cmdlista,'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
		die;
	}

	if ($dataquery == "grups"){
		$cmds = $GLOBALS['cmds'];
		$cmdlista = "<b>☄️FUNCOES PARA GRUPOS☄️</b>\n\n";
		foreach ($cmds as $key => $value) {
			if ($value['type'] == "grups" and !$confibot['cmdsoff'][$key]['offline'] ){
				$desc = strtoupper($value[desc]);
				$desc = str_replace('<B>', "<b>", $desc);
				$desc = str_replace('</B>', "</b>", $desc);

				$cmdlista .= "<b>/$key</b> - {$desc}\n";
			}
		}

		sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $message['message_id'],"text" => $cmdlista,'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
		die;
	}

	if ($dataquery == "adminsbot"){
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);

		if ($data[$userid]['adm'] != "true"){
			sendMessage("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "vc nao e um adm do bot fdp!","show_alert"=>false,"cache_time" => 10));
			die;
		}

		$cmds = $GLOBALS['cmds'];
		$cmdlista = "<b>📊MINHAS FUNCOES DE ADMIN📊</b>\n\n";
		foreach ($cmds as $key => $value) {
			if ($value['type'] == "admin"){
				$desc = strtoupper($value[desc]);
				$desc = str_replace('<B>', "<b>", $desc);
				$desc = str_replace('</B>', "</b>", $desc);

				$cmdlista .= "<b>/$key</b> - {$desc}\n";
			}
		}

		sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $message['message_id'],"text" => $cmdlista,'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
		die;
	}
	if ($dataquery == "infobot"){
		$usaurios = file_get_contents('./usuarios.json');
		$data = json_decode($usaurios, true);


		$cmds = $GLOBALS['cmds'];
		$cmdlista = "💈<b>MINHAS INFORMACOES</b>💈\n\n";
		$cmdlista .= "Foi criado no dia: esqueci ksk\n";
		$cmdlista .= "meu criador e: esqueci o nome dele ksks\n";
		$cmdlista .= "a ultima atualizacao que tive foi: 11/11/2020 as 13:53:09 pm\n";




		foreach ($cmds as $key => $value) {
			if ($value['type'] == "inforbot"){
				$desc = strtoupper($value[desc]);
				$desc = str_replace('<B>', "<b>", $desc);
				$desc = str_replace('</B>', "</b>", $desc);

				$cmdlista .= "<b>/$key</b> - {$desc}\n";
			}
		}

		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);

		foreach ($confibot['consultas']['default'] as $key => $value) {
			if ($value['type'] == "inforbot"){
				$desc = strtoupper($value[desc]);
				$desc = str_replace('<B>', "<b>", $desc);
				$desc = str_replace('</B>', "</b>", $desc);
				$cmdlista .= "<b>/$key</b> - {$desc}\n";
			}
		}
		sendMessage("editMessageText",array("chat_id" => $chatid , "message_id" => $message['message_id'],"text" => $cmdlista,'parse_mode'=>'html',"reply_markup" => ['inline_keyboard' => [[['text'=>"apaga msg",'callback_data'=>"apagamsg"]],]]));
		die;
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

	}catch (Throwable $t)
	{
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);
		$token = file_get_contents('./token.txt');
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$t.'');
	}
	catch (Exception $e)
	{
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);
		$token = file_get_contents('./token.txt');
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$e.'');
	}

}
function sendMessage($method, $parameters) {
  try{
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
	}catch (Throwable $t)
	{
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);
		$token = file_get_contents('./token.txt');
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$t.'');
	}
	catch (Exception $e)
	{
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);
		$token = file_get_contents('./token.txt');
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$e.'');
	}
}
function senDocs($method,$params){

	$confibot = file_get_contents('./conf.json');
	$confibot = json_decode($confibot, true);

	$token = file_get_contents('./token.txt');

	$bory = [];

	$bot_url  = "https://api.telegram.org/bot$token/";
	$url = $bot_url . $method ;

	$t = $params['type'];

	$bory['chat_id'] = $params['chat_id'];

	if (in_array("url", array_keys($params))){
		$bory[$t] = $params['url'];
	}else{
		$name = $params['file'];
		$bory[$t] =  new CURLFile(realpath($name));
	}

	if (in_array("caption", array_keys($params))){
		$bory['caption'] = $params['caption'];
	}

	if (in_array("parse_mode", array_keys($params))){
		$bory['parse_mode'] = $params['parse_mode'];
	}

	if (in_array("disable_notification", array_keys($params))){
		$bory['disable_notification'] = $params['disable_notification'];
	}

	if (in_array("reply_to_message_id", array_keys($params))){
		$bory['reply_to_message_id'] = $params['reply_to_message_id'];
	}

	if (in_array("allow_sending_without_reply", array_keys($params))){
		$bory['allow_sending_without_reply'] = $params['allow_sending_without_reply'];
	}

	if (in_array("reply_markup", array_keys($params))){
		$bory['reply_markup'] = $params['reply_markup'];
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type:multipart/form-data",
		"Content-Type: application/json"
	));
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bory));

	return curl_exec($ch);


}

function bot($method, $parameters) {
   try{
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
	}catch (Throwable $t)
	{
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);
		$token = file_get_contents('./token.txt');
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$t.'');
	}
	catch (Exception $e)
	{
		$confibot = file_get_contents('./confi.json');
		$confibot = json_decode($confibot, true);
		$token = file_get_contents('./token.txt');
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$e.'');
	}
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
