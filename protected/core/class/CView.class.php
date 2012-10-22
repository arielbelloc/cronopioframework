<?php
class CView {
    protected $_controller;
    
    final public function getController() {
        return $this->_controller;
    }
    
    final public function __construct(CRequest $request) {
        $this->_controller = $request->controller;
    }

    final public function render($view = NULL)
    {
        $this->startPage();
        $this->content($view);
        $this->endPage();
        CBootstrap::end();
    }
    
    final protected function callLayoutSection($section, $theme = NULL)
    {    
        $section = $section . '.php';
        
        if (is_null($theme)) {
            $theme = LAYOUT_DEFAULT_PATH.$section;
        } else {
            $theme = THEME_PATH.$theme.DS.LAYOUTS_FOLDER.$section;
        }

        if (is_readable($theme)) {
            require_once $theme;
        } else {
            throw new CustomException(Parse::text('Error to read the {section} file of the {theme} theme.', array('section' => $section, 'theme'=>  Config::settings()->theme)));
        }
    }

    final protected function startPage($theme = NULL)
    {
        $this->callLayoutSection('header', $theme);
    }
    
    final protected function endPage($theme = NULL)
    {
        $this->callLayoutSection('footer', $theme);
    }

    final protected function content($view)
    {
        if (is_null($view)) {
            $view = Config::settings()->defaultView;
        }
        $toRender = VIEWS_DEFAULT_PATH . $view  . '.php';
        
        if (is_readable($toRender)) {
            require_once $toRender;
        } else {
            throw new CustomException(Parse::text('Error to read the view {file}', array('file' => $view)));
        }
    }
}
?>
