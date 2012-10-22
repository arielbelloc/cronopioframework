<?php
/**
 * Description of Controller
 *
 * @author ariel.belloc
 */
class PublicController extends Controller
{
    public function index () {
        echo Parse::text('Este es el método ' . __FUNCTION__ . ' del controlador ' . get_called_class(), array(), FALSE);
    }
}

?>
