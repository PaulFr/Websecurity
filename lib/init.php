<?php
session_start();
session_regenerate_id();
/**
* Include files
**/
if(file_exists(APPROOT.SEPARATOR.'config'.SEPARATOR.'configuration.php'))
	require APPROOT.SEPARATOR.'config'.SEPARATOR.'configuration.php';
require LIB.SEPARATOR.'functions.php';
require CORE.SEPARATOR.'http'.SEPARATOR.'Request.php';
require CORE.SEPARATOR.'http'.SEPARATOR.'Response.php';
require CORE.SEPARATOR.'http'.SEPARATOR.'Router.php';
require CORE.SEPARATOR.'Controller_SW.php';
require CORE.SEPARATOR.'Model_SW.php';
require CORE.SEPARATOR.'View_SW.php';
require CORE.SEPARATOR.'Plugin_SW.php';
require APPROOT.SEPARATOR.'core'.SEPARATOR.'AppController.php';
require APPROOT.SEPARATOR.'core'.SEPARATOR.'AppModel.php';
require APPROOT.SEPARATOR.'core'.SEPARATOR.'AppView.php';
require APPROOT.SEPARATOR.'core'.SEPARATOR.'AppPlugin.php';
require APPROOT.SEPARATOR.'config'.SEPARATOR.'routes.php';
require CORE.SEPARATOR.'Core.php';


Core::launch();
?>