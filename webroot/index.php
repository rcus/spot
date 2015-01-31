<?php 
// This is a Anax frontcontroller.
// Get environment & autoloader.
require __DIR__.'/config_with_app.php'; 

// Clean up some URL's
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Theme & navbar config
$app->theme->configure(ANAX_APP_PATH . 'config/theme_spot.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_spot.php');

// Home route
$app->router->add('', function() use ($app) {
    $app->theme->addStylesheet('css/questions.css');
    $app->theme->addStylesheet('css/users.css');
    $app->theme->setTitle("Allt du vill fråga om Spotify");

    $questions = $app->questions->findQuestions(3);
    $tags = $app->questions->getTags('popular');
    $users = $app->users->findTop();

    $app->views->add('spot/index', [
            'questions' => $questions,
            'tags' => $tags,
            'users' => $users
        ]);
});

// About
$app->router->add('about', function() use ($app) {
    $app->theme->setTitle("Om");
    $content = $app->textFilter->doFilter($app->fileContent->get('about.md'), 'shortcode, markdown');
    $app->views->add('spot/page', [
        'content' => $content
    ]);
});

// Setup
$app->router->add('setup', function() use ($app) {
    $setup = new \Rcus\Setup\CSetup(require ANAX_APP_PATH . 'config/database_mysql.php');
    $setup->addDemo();
    $app->theme->setTitle("Setup");
    $app->views->addString("<h1>Databasen är återställd</h1><p>Du har nu återställt databasen.</p>", 'main');
});


// Check for matching routes and dispatch to controller/handler of route and render the page
$app->router->handle();
$app->theme->render();
