<?php
/* CONSTANTS PATHS AND URL */
    define('DS', DIRECTORY_SEPARATOR); // Define constant DS with directory separator constant.
    
    /* FRAMEWORK_PATH: Use to locate file path */
	define('FRAMEWORK_PATH', substr(__DIR__,0,-4)); // Define constant FRAMEWOK_PATH (rest 4 characters: 'core'),
    
    /* FRAMEWORK_URL: Use to locate url */
    $frameworkURL = explode('/', $_SERVER['PHP_SELF']); // Convert string in array
    $frameworkURL = array_filter($frameworkURL); // filter array (erase the blank row)
    $frameworkURL = array_shift($frameworkURL); // Take the first row.
    define('FRAMEWORK_URL', '/' . $frameworkURL . '/');
    unset($frameworkURL);

    $corePath = FRAMEWORK_PATH . 'core'. DS;

    /* MVC PATHS */
    define('MODELS_FOLDER', 'models'.DS); // Define constant MODELS_FOLDER with the name of the folders of models.
    define('MODELS_DEFAULT_PATH', FRAMEWORK_PATH.MODELS_FOLDER); // Define constant MODELS_DEFAULT_PATH with the path to access the default models path.

    define('MODULES_FOLDER','modules' . DS);
    
    define('CONTROLLERS_FOLDER', 'controllers'.DS); // Define constant CONTROLLER_FOLDER with the name of the folders of controllers.
    
    /* TO CREATE */
	// text using to create models, controller and views
	// Only define the folder constant.
    define('TO_CREATE_PATH', $corePath.'to_create'.DS);

/* FUNCTIONS */
		require_once($corePath . 'functions' . DS . 'functions.inc.php'); // Define the data provider (set a defaut dataprovider in $settings)

/* ERROR HENDLER */
    set_error_handler('myErrorHandler'); // Set the function to handle errors.
    
/* CLASS */
    $classPath = $corePath . 'class' . DS;
        addFile($classPath . 'CBaseClassReadOnly.class.php'); // The Base Class for Classes with read only properties.
        addFile($classPath . 'CBaseClassSingleton.class.php'); // The Base Class for Classes with read only properties.
        addFile($classPath . 'CCustomException.class.php'); // Custom Exception class extended from Exception class.
		addFile($classPath . 'CDataProvider.class.php'); // Define the data provider (set a defaut dataprovider in $settings)
        addFile($classPath . 'CConnectionData.class.php'); // Define the data of connection
        addFile($classPath . 'CSettings.class.php'); // Define the settings parameters
        addFile($classPath . 'CDbConnection.class.php'); // Class to create the Db Connection.
		addFile($classPath . 'CHtml.class.php'); // HTML Helper.
		addFile($classPath . 'CForm.class.php'); // Form Helper.
		addFile($classPath . 'CParse.class.php'); // Parse Helper. Convert text, compare vars, etc...
        addFile($classPath . 'CDebug.class.php'); // Debug Methods.
        addFile($classPath . 'CRequest.class.php'); // Analyzes requests and performs..
        addFile($classPath . 'CBootstrap.class.php'); // Analyzes requests and redirect.
        addFile($classPath . 'CWrappers.class.php');

/* MVC CLASS */
		addFile($classPath . 'CModel.class.php');
        addFile($classPath . 'CView.class.php');
        addFile($classPath . 'CController.class.php');

/* GLOBAL VARIABLES */
    $globalPath = $corePath.'global'.DS;
		require_once $globalPath . 'constants.inc.php'; // Define the general constants
		require_once $globalPath . 'css.inc.php'; // Define the general settings

/* CONFIG */
    $configPath = FRAMEWORK_PATH.'config'.DS;
		addFile($configPath . 'ConnectionData.class.php'); // Define the connection settings
		addFile($configPath . 'Settings.class.php'); // Define the general settings
        require_once $configPath . 'declarations.inc.php';

/* WRAPPERS */
    require_all_folder($corePath . 'wrappers' . DS);

/* EXTENDS */
	define('EXTENDS_PATH', FRAMEWORK_PATH.'extends'.DS); // Class extends by users.
		require_all_folder(EXTENDS_PATH);

/* TEXTS */
		require_once FRAMEWORK_PATH . 'texts' . DS . Config::settings()->defaultLanguage . DS . 'texts.php';

/* MVC PATHS */
    require_all_folder(MODELS_DEFAULT_PATH);

/* THEME DEFAULT  */
    define('THEME_PATH', PUBLIC_PATH . 'themes' . DS . Config::settings()->theme . DS); // Define constant THEME_PATH
    
    $viewFolder =  'views'.DS;
    define('VIEWS_DEFAULT_FOLDER', $viewFolder.'defaultViews'.DS);
    $layoutFolder = $viewFolder.'layout'.DS;
    
    define('VIEWS_DEFAULT_PATH', THEME_PATH.VIEWS_DEFAULT_FOLDER);
    
    define('LAYOUT_DEFAULT_PATH', THEME_PATH.$layoutFolder);
    
    define('THEME_URL', FRAMEWORK_URL.Config::settings()->theme.'/');
    define('THEME_CSS', THEME_URL.'css/');
    define('THEME_IMG', THEME_URL.'img/');
    define('THEME_JS', THEME_URL.'js/');
    
    define('GENERAL_CSS', FRAMEWORK_URL.'css/');
    define('GENERAL_IMG', FRAMEWORK_URL.'img/');
    define('GENERAL_JS', FRAMEWORK_URL.'js/');

    unset($corePath);
    unset($classPath);
    unset($globalPath);
    unset($configPath);
    unset($viewFolder);
    unset($layoutFolder);
?>