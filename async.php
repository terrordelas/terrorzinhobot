<?php
while(ob_get_level()) ob_end_clean();
header('Connection: close');
ignore_user_abort();
ob_start();

$size = ob_get_length();
header("Content-Length: $size");
header('Connection: close');
ob_end_flush();
flush();

include("./terrorzinho.php");