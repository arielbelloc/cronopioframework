<?php
    class CHtml
    {

        /*
        * CREATE MENU
        * Create a menu from an array.
        *
        * @Params:
         *  $menu: Array. 
         * @example:
         * 
         * $menu = array(
         *      menu => array (
         *          'home' => array(
         *              'link' => FRAMEWORK_URL,
         *          ),
         *          'about us' => array(
         *              'link' => FRAMEWORK_URL . 'aboutus',
         *          ),
         *          'admin' => array (
         *              'menu' => array (
         *                  'countries' => array(
         *                      'link' => FRAMEWORK_URL . 'siteadmin/countries',
         *                  ),
         *                  'cities' => array(
         *                      'link' => FRAMEWORK_URL . 'siteadmin/cities',
         *                  ),
         *              ),
         *          ),
         *      )
         * );
        * 
        * @return: HTML.
        * @In error case: Return NULL.
        */
        final static public function createMenu(array $menu = NULL)
        {
            if (!isset($menu) || count($menu)<1) {
                return NULL;
            }
            ?>
<ul>
            <?php
            foreach($menu['menu'] as $key => $value){
                $htmlOption = '';
                if (isset($value['html'])) {
                    $htmlOption = Parse::arrayToAttributes($value['html']);
                }
                ?>
<li <?=$htmlOption?>>
                <?php
                if (isset($value['link']))
                {
                    $tagHtml = array(
                        'tag' => 'a',
                        'text' => $key,
                        'html' => array('href' => $value['link']),
                    );
                    self::tag($tagHtml);
                }else{
                    echo $key;
                }
                ?>
</li>
                <?php
                if (isset($value['menu']) && is_array($value['menu'])){  // If is Array and have subitems.
                    self::createMenu($value);
                }
            }
            ?>
</ul>
        <?php
        } 


        /*
        * TAG
        * Create a html tag
        *
        * @Params:
         * $tagHtml: Array. The parameters of the tag.
         * @example
            * $tagHtml = array(
                'tag' => 'a',
                'text' => $key,
                'html' => array(
                    'class' => menuLink,
                    'href' => '/clientes/create.php',
                )
            );
        * 
        * @return: HTML.
        * @In error case: Return NULL.
        */
        static public function tag(array $tagHtml = NULL)
        {
            if (!isset($tagHtml) || !isset($tagHtml['tag']) ) {
                return NULL;
            }
            
            $attributes = '';
            if (isset($tagHtml['html'])) {
                $attributes = Parse::arrayToAttributes($tagHtml['html']);
            }
            
            $text = '';
            if (isset($tagHtml['text'])) {
                $text = $tagHtml['text'];
            }
            
            switch ($tagHtml['tag'])
            {
                case 'a':
                    ?>
<a <?=$attributes?>><?=$text?></a>
                    <?php
                    break;
                case 'b':
                    ?>
<b <?=$attributes?>><?=$text?></b>
                    <?php
                    break;
                case 'p':
                    ?>
<p <?=$attributes?>><?=$text?></p>
                    <?php
                    break;
                case 'span':
                    ?>
<span <?=$attributes?>><?=$text?></span>
                    <?php
                    break;
                case 'h1':
                    ?>
<h1 <?=$attributes?>><?=$text?></h1>
                    <?php
                    break;
                case 'h2':
                    ?>
<h2 <?=$attributes?>><?=$text?></h2>
                    <?php
                    break;
                case 'h3':
                    ?>
<h3 <?=$attributes?>><?=$text?></h3>
                    <?php
                    break;
                case 'h4':
                    ?>
<h4 <?=$attributes?>><?=$text?></h4>
                    <?php
                    break;
            }
        }
                
        
        /*
        * FORM TITLE
        * Create a title for a form
        *
        * @Params:
         *  $title: String. The title of print.
        * 
        * @return: HTML.
        * @In error case: Return NULL.
        */
        static protected function formTitle($title = NULL)
        {
            if (!isset($title)) {
                return NULL;
            }
            ?>
<h2 title="<?=$title?>"><?=$title?></h2>
            <?php
        }
    
        /*
        * START FORM
        * Start form
        *
        * @Params:
         *  $title: String. The title of print.
        * 
        * @return: HTML.
        * @In error case: Return NULL.
        */
        static public function startForm($params = NULL, $title = NULL)
        {
        ?>
<form <?=Parse::arrayToAttributes($params)?>>
            <?php
            self::formTitle($title);
        }

        static public function endForm($submit = false, $value = NULL)
        {
            if (!$submit || !isset($value)) {
                $value = Parse::caption('submitButton');
            }else {
                $value = Parse::caption($value);
            }
                
            ?>
<input value="<?=$value?>" class="<?=Params::cssClass('submit_button')?>" type="submit"/>
</form>
            <?php
        }

        final public static function createForm(array $params)
        {
            $paramsStr = '';
            
            self::startForm($params['form']['html'], $params['form']['params']['title']);
            
            $paramsStr = '';
            foreach ($params['fields'] as $field) {
                self::createHtmlInput($field['params'], $field['html']);
            }
            self::endForm(true);
            Debug::addDebugParams(array('CREATE FORM' => $params));
        }
        
        final public static function createFormFromTable($tableName)
        {
            $formArray = array();
            $formArray['form'] = array(
                'params' => array (
                    'title' => 'Table: ' . $tableName,
                ),
                'html' => array(
                    'name' => $tableName,
                    'id' => $tableName,
                    'method' => 'post',
                    'action' => FRAMEWORK_URL. Params::ModuleFolderByKey(ADMIN_MODULE).'/cambiar_ACTION',
                ),
            );

            $query = 'SELECT * FROM ' . $tableName . ' WHERE FALSE';
            $resultFields = CDbConnection::getConnection()->query($query);
            while ($field = $resultFields->fetch_field())
            {
                if (!isset($formArray['fields'])) {
                    $formArray['fields'] = array();
                }
                $fieldType = Parse::fieldType($field);
                array_push($formArray['fields'], array (
                        'params' => array (
                            'fieldType' => $fieldType,
                            'caption' => $field->name,
                        ),
                        'html' => array (
                            'name' => $field->name,
                            'id' => $tableName.'-'.$field->name,
                            'maxlength' => $field->length,
                            'alt' => $field->name,
                        ),
                    )
                );
            }
            Html::createForm($formArray);
        }
        
        final public static function createHtmlInputsInFieldset (array $params = NULL)
        {
            if (!isset($params)) {
                throw new CustomException('Not was set the prameters of the input');
            }
            
            foreach ($params as $fieldSetName => $inputs) {
            ?>
<fieldset>
    <legend><?=$fieldSetName?></legend>
                <?php
                foreach ($inputs as $input) {
                    self::createHtmlInput($input);
                }
                ?>
</fieldset>    
            <?php
            }
        }
        
        //final public static function createHtmlInput(array $params = NULL, array $attributes = NULL)
        final public static function createHtmlInput(array $params = NULL)
        {
            if (!isset($params)) {
                throw new CustomException('Not was set the prameters of the input');
            }
            
            if (isset($params['html'])) {
                $attributes = $params['html'];
            }else{
                $attributes = NULL;
            }
            
            if (isset($params['params'])) {
                $params = $params['params'];
            }else{
                $params = NULL;
            }
            
            if (!isset($params['fieldType'])) {
                throw new CustomException('Not was set the field type of the input');
            }
            
            
            if ($params['fieldType'] == TEXT_FIELD && array_key_exists('value', $attributes)) {
                $textValue = $attributes['value'];
                unset($attributes['value']);
            }
            
            if ($params['fieldType'] == BOOL_FIELD && array_key_exists('value', $attributes)) {
                if ($attributes['value'] == Config::settings()->trueValueToWrite)
                {
                    $attributes['checked'] = 'checked';
                }
                unset($attributes['value']);
            }
            
            if (isset($attributes)) {
                $paramsStr = Parse::arrayToAttributes($attributes, $params['fieldType']);
            }
            
            if (isset($params['div_class'])) {
                $divClass = $params['div_class'];
            }else{
                $divClass = '';
            }
            
            ?>
<div class="<?=Params::cssClass('wrapper_input') . ' ' . $divClass . ' ' . self::typeFieldParams($params['fieldType'], 'wrapperHtmlClass') ?>">
            <?php
            if (isset($params['caption'])) {
                ?>
<p class = "<?=Params::cssClass('caption_input')?>"><?=$params['caption']?></p>
                <?php
            }
            
            switch ($params['fieldType'])
            {
                case ID_FIELD:
                case INT_FIELD:
                case DEC_FIELD:
                    ?>
<input type="text" <?=$paramsStr?> />
                    <?php
                    break;
                    
                case STR_FIELD:
                    ?>
<input type="text" <?=$paramsStr?> />
                    <?php
                    break;
                
                case TEXT_FIELD:
                    ?>
<textarea <?=$paramsStr?>>
<?php
                    if (isset($textValue)) {
                        echo trim($textValue);
                    }
                    ?>
</textarea>
                    <?php
                    break;
                case BOOL_FIELD:
                    ?>
<input type="checkbox" <?=$paramsStr?> />
                    <?php
                    break;
                case IMG_FIELD:
                    ?>
<input type="file" <?=$paramsStr?> />
                    <?php
                    break;
                case FOREIGN_KEY_FIELD:
                    if (isset($params['model'])) {
                        Model::model($params['model'])->listBox();
                    }elseif (isset($attributes['name'])) {
                            $model = substr($attributes['name'], 0, strlen($attributes['name'])-3);
                            Model::model($model)->listBox();
                    }else{
                        throw new CustomException(Parse::text('You must be set a parameter {param} for a {typeField}', array('param' => '"model"', 'typeField' => 'foreign key field')));
                    }
                    break;
                case DATE_FIELD:
                case TIME_FIELD:
                case DATE_TIME_FIELD:
                case YEAR_FIELD:
                    ?>
<input type="text" <?=$paramsStr?> />
                    <?php
                    break;
                case CMB_INPUT:
                    if (isset($params['data'])) {
                        if (!isset($params['selected'])) {
                            $params['selected'] = NULL;
                        }
                        self::createCmb($params['data'], $params['selected'], $attributes);
                    }else{
                        throw new CustomException(Parse::text('You must be set a parameter {param} for a {typeField}', array('param' => '"data"', 'typeField' => 'combobox field')));
                    }
                    break;
                case HIDDEN_INPUT:
                ?>
<input type="hidden" <?=$paramsStr?> />
                    <?php
                    break;
            }
            ?>
</div>
        <?php
        }

        final static function createCmb(array $content = NULL, $selected = NULL, array $selectParamsHTML = NULL)
        {
            if (!isset($content)) {
                return NULL;
            }
            $strSelectParamsHTML = '';
            if (isset($selectParamsHTML))
            {
                foreach ($selectParamsHTML as $att => $val) {
                    $strSelectParamsHTML .= ' ' . $att . '= "' . $val . '"';
                }
                unset($selectParamsHTML);
            }

            ?>
<select <?=$strSelectParamsHTML?>>
            <?php
                foreach ($content as $key => $value)
                {
                    $selectedStr = '';
                    if ($key == $selected) {
                        $selectedStr = ' selected="selected"';
                    }
                    ?>
<option <?=$selectedStr?> value="<?=$key?>"><?=$value?></option>
                    <?php
                }
                ?>
</select>
            <?php
        }
        
        static function h1($text = NULL, array $params)
        {
            if (!isset($text)) {
                return NULL;
            }
            
            if (isset($params)) {
                $params = Parse::arrayToAttributes($params);
            }else{
                $params = '';
            }
            ?>
<h1 <?=$params?>><?=$text?></h1>
            <?php
            
        }
        
        static public function typeFieldParams($typeField = NULL, $param = NULL)
        {
            if (!isset($typeField)) {
                throw new CustomException('Not was set the field type of the input');
            }
                
            $toReturn = array();
            switch ($typeField)
            {
                case ID_FIELD:
                case INT_FIELD:
                case DEC_FIELD:
                    $toReturn = array(
                        'htmlClass' => Params::cssClass('number_field_input'),
                        'wrapperHtmlClass' => Params::cssClass('number_field_wrapper'),
                    );
                    break;

                case STR_FIELD:
                    $toReturn = array(
                        'htmlClass' => Params::cssClass('string_field_input'),
                        'wrapperHtmlClass' => Params::cssClass('number_field_wrapper'),
                    );
                    break;

                case TEXT_FIELD:
                    $toReturn = array(
                        'htmlClass' => Params::cssClass('text_field_input'),
                        'wrapperHtmlClass' => Params::cssClass('number_field_wrapper'),
                    );
                    break;

                case BOOL_FIELD:
                    $toReturn = array(
                        'htmlClass' => Params::cssClass('boolean_field_input'),
                        'wrapperHtmlClass' => Params::cssClass('number_field_wrapper'),
                    );
                    break;

                case IMG_FIELD:
                    $toReturn = array(
                        'htmlClass' => Params::cssClass('image_field_input'),
                        'wrapperHtmlClass' => Params::cssClass('number_field_wrapper'),
                    );
                    break;

                case FOREIGN_KEY_FIELD:
                    $toReturn = array(
                        'htmlClass' => Params::cssClass('foreign_field_input'),
                        'wrapperHtmlClass' => Params::cssClass('number_field_wrapper'),
                    );
                    break;

                case DATE_FIELD:
                case TIME_FIELD:
                case DATE_TIME_FIELD:
                case YEAR_FIELD:
                    $toReturn = array(
                        'htmlClass' => Params::cssClass('date_field_input'),
                        'wrapperHtmlClass' => Params::cssClass('number_field_wrapper'),
                    );
                    break;

                case CMB_INPUT:
                    $toReturn = array(
                        'htmlClass' => Params::cssClass('foreign_field_input'),
                        'wrapperHtmlClass' => Params::cssClass('number_field_wrapper'),
                    );
                    break;
                case HIDDEN_INPUT:
                    $toReturn = array(
                        'htmlClass' => '',
                        'wrapperHtmlClass' => '',
                    );
                    break;
            }
            
            if (isset($param)) {
                if (isset($toReturn[$param])) {
                    return $toReturn[$param];
                }else{
                    return NULL;
                }
                    
            }else{
                return $toReturn;
            }
        }
    }
?>