<?php
    /*
	* LANGUAGE ARRAY
	* Contain the list of languages to use in the site.
     * 
     * NOTE:
     *      Set a default language in Settings.class.php
	*/

    $languages = array(
        'es_ar',
        'es_cl',
        'en_us',
    );

    /*
	* MODULES ARRAY
	* Contain the list of modules to use in the site.
	*/
    $modules = array(
        ADMIN_MODULE => array( // WARNING: The first module is the admin module.
            'folder' => 'siteadmin',
            'defaultController' => 'empresa',
            'defaultMethod' => 'index',
        ),
        PUBLIC_MODULE => array( // WARNING: The second module is the public module.
            'folder' => 'public',
            'defaultController' => 'index',
            'defaultMethod' => 'index',
        ),
    );
    
    $captions = array(
        'submitButton' => 'Send',
    );
?>
