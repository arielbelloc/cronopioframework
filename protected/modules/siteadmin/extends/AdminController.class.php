<?php
/**
 * Description of Controller
 *
 * @author ariel.belloc
 */
class AdminController extends Controller
{
    protected function init() {
        $this->_model = $this->loadModel($this->_view->getController());
    }

    public function index()
    {
        $findParams = array(
            'select' => array('id'),
            'order' => 'nombre',
        );
        
        $params = array(
            'model' => $this->loadModel('Entregas')->find($findParams),
            'nombre' => 'Prueba de FindFirst',
        );
        
        $saveParams = array(
            'Unidades' => array(
                'descripcion' => 'c/u',
                'nombre' => 'Unidad',
            ),
        );
        
        $this->loadModel('Entregas')->insert($saveParams);
        
        $this->render($params);
    }
    
    public function save()
    {
        if (count($_POST)>0) {
            $this->_model->save();
            $this->render();
        }else{
            $this->render(array(), 'form');
        }
    }
    
    final protected function loadModel ($model = NULL)
    {
        if (is_null($model)) {
            $model = $this->_view->getController();
        }
        if (class_exists($model)) {
            return Model::model($model);
        }else{
            Debug::addDebugParams(array('loadModel method fail. Called from ' . get_called_class() . '. Model: ' . $model));
            return NULL;
        }
    }
}
?>
