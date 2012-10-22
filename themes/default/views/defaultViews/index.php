<?php
    if (isset($this->model))
    {
        echo '<pre>';
        print_r($this->model);
        echo '</pre>';
        
        Entregas::model()->listBox();
    }else{
        echo 'Modelo no instanciado';
    }
?>
