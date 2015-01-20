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
    $app->theme->setTitle("Allt du vill fråga om Spotify");
    $app->views->add('spot/index');
});

// About
$app->router->add('about', function() use ($app) {
    $app->theme->setTitle("Om");
    $content = $app->textFilter->doFilter($app->fileContent->get('about.md'), 'shortcode, markdown');
    $app->views->add('spot/page', [
        'content' => $content
    ]);
});




// Check for matching routes and dispatch to controller/handler of route and render the page
$app->router->handle();
$app->theme->render();
