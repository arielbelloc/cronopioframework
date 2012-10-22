<?php

/*
 * -------------------------------------
 * www.dlancedu.com | Jaisiel Delance
 * framework mvc basico
 * Bootstrap.php
 * -------------------------------------
 */
// Original Code: http://www.youtube.com/user/dlancedu / www.dlancedu.com
class CBootstrap
{
    public static function run(CRequest $request)
    {
        try{
            /**** TRY *****/
            debug::startDebug();
            $module = '';
            if ($request->module) {
                $module = $request->module . DS;
            }

            $controller = $request->controller . Config::settings()->postfixController;
            $controllerPath = MODULE_PATH . CONTROLLERS_FOLDER . $controller . '.php';
            $method = $request->method;
            $params = $request->params;

            if(is_readable($controllerPath)){
                require_once $controllerPath;
                $controller = new $controller;

                if(empty($method)) {
                    $method = 'index';
                }
                if(isset($params)){
                    call_user_func_array(array($controller, $method), $params);
                }else{
                    call_user_func(array($controller, $method));
                }
                
            } else {
                throw new CustomException(Parse::text('{path} not found', array('path' => $controllerPath)));
            }
            
            self::end();
            
        } catch (CustomException $ex) {
            /**** CATCH *****/
            echo '<br /><b>'.'Error :</b> '. $ex->getMessage().PHP_EOL.'<br />';
        }
    }
    
    public static function end($msg = NULL)
    {
        if (isset($msg)) {
            echo '<br />';
            echo '<i><b>' . $msg . '</b></i>';
            echo '<br />';
        }
        Debug::showDebugParams();
        exit();
    }
            
}

?>