<!doctype html>
<html>
    <head>
        <title><?php echo Config::settings()->titleSite . ' | ' . Config::settings()->subTitleSite; ?></title>
        <meta charset="UTF-8">

        <!--[if IE]>
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="<?=GENERAL_CSS?>reset.css" />
        <link type="text/css" rel="stylesheet" href="<?=GENERAL_CSS?>general.css" />
        <link type="text/css" rel="stylesheet" href="<?=GENERAL_CSS?>forms.css" />
        <link type="text/css" rel="stylesheet" href="<?=GENERAL_CSS?>installation.css" />
        <link type="text/css" rel="stylesheet" href="<?=GENERAL_CSS?>menus.css" />

        <!-- Javascript (jQuery) -->
        <script src="http://code.jquery.com/jquery-latest.js"></script>

        
    </head>
    <body>
        <section id='<?=Params::cssId('general-wrapper')?>'>
            <header>
                <h1><?=Config::settings()->titleSite?></h1>
                <h2><?=Config::settings()->subTitleSite?></h2>
            </header>