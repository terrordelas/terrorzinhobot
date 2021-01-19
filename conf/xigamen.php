<?php

error_reporting(0);

$mae = file("./xingamae.txt");

$lista = [];
$file_handle = fopen("./xingamae.txt", "r");
	while (!feof($file_handle)) {
		$line = fgets($file_handle);

		$line = str_replace(array('\r\n',"\r\n","\r","\n"), '', $line);
		if (!empty($line)){
			$lista[] = $line;
		}
}

$valor = rand(2,6);
$valor2 = array_rand($lista ) * $valor;
if ($lista[$valor2]){
	echo $lista[$valor2];
}else{
	echo $lista[array_rand($lista)]; 
}