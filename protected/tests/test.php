<?php
	require_once('../core/includes/includes.php');
    HTML::startPage();
    /*
    $arr = array('1e25dsafasdfasdfa', '125', '1e4', 25.36, '44,87', FALSE, 'dsafasdfdsaf');
    foreach ($arr as $a) {
        $b = Parse::formatNumber($a, false);
        if (is_numeric( $b )) {
            echo $b . ' es numérico';
        }else{
            echo $b . '';
        }
        echo '<br />';
    }
    */
    echo date($settings->dateTimeFormat);
    
    HTML::endPage();
?>