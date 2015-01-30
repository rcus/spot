<?php
/**
 * Config-file for Anax, theme related settings, return it all as array.
 * For Spotify In Sight, based on theme.php.
 */
return [

    /**
     * Settings for Which theme to use, theme directory is found by path and name.
     *
     * path: where is the base path to the theme directory, end with a slash.
     * name: name of the theme is mapped to a directory right below the path.
     */
    'settings' => [
        'path' => ANAX_INSTALL_PATH . 'theme/',
        'name' => 'spot',
    ],

    
    /** 
     * Add default views.
     */
    'views' => [
        ['region' => 'adminbar', 'template' => 'spot/adminbar', 'data' => [], 'sort' => -1],
        ['region' => 'header', 'template' => 'spot/header', 'data' => [], 'sort' => -1],
        ['region' => 'navbar', 'template' => [
                'callback' => function() { return $this->di->navbar->create(); },
            ], 'data' => [], 'sort' => -1],
        ['region' => 'footer', 'template' => 'spot/footer', 'data' => [], 'sort' => -1],
    ],


    /** 
     * Data to extract and send as variables to the main template file.
     */
    'data' => [

        // Language for this page.
        'lang' => 'sv',

        // Append this value to each <title>
        'title_append' => ' | Spotify in sight',

        // Stylesheets
        'stylesheets' => ['css/normalize.css', 'css/style.css', 'css/navbar.css',
            '//fonts.googleapis.com/css?family=Slabo+27px', '//fonts.googleapis.com/css?family=Open+Sans',
            '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'],

        // Inline style
        'style' => null,

        // Favicon
        'favicon' => null, //'favicon.ico',

        // Path to modernizr or null to disable
        'modernizr' => 'js/modernizr.js',

        // Path to jquery or null to disable
        'jquery' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',

        // Array with javscript-files to include
        'javascript_include' => [],

        // Use google analytics for tracking, set key or null to disable
        'google_analytics' => null,
    ],
];

