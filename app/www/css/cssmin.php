<?php

$file = $_GET['r'];
if(!preg_match('#^([a-zA-Z0-9-_]+)\.css#', $file)){
	exit;
}
function compress($buffer) {
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
  }

$path = './'.$file;
if(file_exists($path)){
	$css = file_get_contents($path);
	header("Content-type: text/css"); 
	ob_start("compress");
	echo $css;
	ob_end_flush();
}
?>