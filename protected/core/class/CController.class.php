<?php
    /**
    * Description of CController
    *
    * @author ariel.belloc
    */
    abstract class CController extends CBaseClassReadOnly {
        protected $_defaultView = true;
        protected $_model;
        protected $_view;
        
        final public function __construct() {
            $this->_view = new View(CRequest::getInstance());
            $this->init();
        }

        protected function init(){}
        
        final public function render(array $params = NULL, $view = NULL) {
            
            // If not a set view, define the default view.
            if (is_null($view)) {
                $view = Config::settings()->defaultView;
            }
            
            // Create the view params
            if (!is_null($params)) {
                foreach ($params as $par => $value)
                {
                    $this->_view->{$par} = $value;
                }
            }
            
            // Render view
            $this->_view->render($view);
        }
        
        final public function setTheme($theme) {
            Config::settings()->setTheme($theme);
        }
    }
?>
