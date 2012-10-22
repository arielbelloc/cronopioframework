<?php
	/*
	* CRONOPIO FRAMEWORK v0.1
	* Index Page 
	*
	* @Author: Ariel Belloc
	* @Contact: arielbelloc@gmail.com
	*/

    define('PUBLIC_PATH', __DIR__.DIRECTORY_SEPARATOR);
    
    $cronopioSetPath = '../CronopioFramework/core/cronopioSet.php';
    if (!is_readable($cronopioSetPath)) {
        $cronopioSetPath = 'protected/core/cronopioSet.php';
        if (!is_readable($cronopioSetPath)) {
            $cronopioSetPath = '../../CronopioFramework/core/cronopioSet.php';
        }
    }
    
    require_once($cronopioSetPath);
    unset($cronopioSetPath);

    CBootstrap::run(CRequest::getInstance());
?>
