<?php
/*
* CHECK REQUEST
* Show POST and GET variables
*
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/
require_once('../core/includes/includes.php');

if (isset($_REQUEST)){	
	if (isset($_GET))
	{
		echo Parse::text('GET params:').'<br />';
		$debug->displayArray($_GET);
	}else{
		if (isset($_POST))
		{
			{
				echo Parse::text('POST params:').'<br />';
				$debug->displayArray($_POST);
			}
		}else{
			if (isset($_REQUEST))
			{
				echo Parse::text('Params (no POST and GET):').'<br />';
				$debug->displayArray($_POST);
			}
		}
	}
}else{
	echo Parse::text('Not passed POST and GET parameters').'<br />';
}
?>