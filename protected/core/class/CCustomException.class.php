<?php
/**
 * Description of CustomException
 *
 * @author ariel.belloc
 */
class CCustomException extends Exception
{
    public function __construct($message, $code = NULL, $previous = NULL) {
        parent::__construct($message, $code, $previous);
        echo '<pre>';
            debug_print_backtrace();
        echo '</pre>';
        Debug::showDebugParams();
    }
}

?>
