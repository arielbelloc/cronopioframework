<?php
/*
* FRAMEWORK INSTALATION
* Run script to create model from C:\xampp\htdocs\framework\core\to_create\model.php
*
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/
	/*
	* @Original Script: http://webintenta.com/mostrar-tablas-y-campos-de-una-base-de-datos-mysql-con-php.html
	*/

    define('PUBLIC_PATH', substr(__DIR__, 0, strlen(__DIR__) - 12));
    
    $cronopioSetPath = '../../CronopioFramework/core/cronopioSet.php';

    if (!is_readable($cronopioSetPath)) {
        $cronopioSetPath = '../protected/core/cronopioSet.php';
        if (!is_readable($cronopioSetPath)) {
            $cronopioSetPath = '../../../CronopioFramework/core/cronopioSet.php';
        }
    }
    
    require_once($cronopioSetPath);
    unset($cronopioSetPath);
    $cssId['general-wrapper'] = Params::cssId('general-wrapper-installation');
    Debug::startDebug();
    require_once LAYOUT_DEFAULT_PATH.'header.php';
    
    $tableId = NULL;
    if (isset($_GET['table_id'])) {
        $tableId = $_GET['table_id'];
    }
    CInstallation::getInstance()->createDatabaseStructure($tableId);
    require_once LAYOUT_DEFAULT_PATH.'footer.php';
    CBootstrap::end();
    
?>