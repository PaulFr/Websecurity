<?php

$file = $_GET['r'];
if(!preg_match('#^([a-zA-Z0-9-_]+)\.js#', $file)){
	exit;
}
function compress($buffer) {
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
  }

$path = './files/'.$file;
if(file_exists($path)){
	$js = file_get_contents($path);
	header("Content-type: text/JavaScript"); 
	ob_start("compress");
	echo $js;
	ob_end_flush();
}
?>