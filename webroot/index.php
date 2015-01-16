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
    $app->views->add('spot/index');
    $app->theme->setTitle("All you want to know about Spotify");
});

// Route to show welcome to dice
$app->router->add('dice', function() use ($app) {
    $app->views->add('spot/index');
    $app->theme->setTitle("Roll a dice");
});

// Route to roll dice and show results
$app->router->add('dice/roll', function() use ($app) {

    // Check how many rolls to do
    $roll = $app->request->getGet('roll', 1);
    $app->validate->check($roll, ['int', 'range' => [1, 100]])
        or die("Roll out of bounds");

    // Make roll and prepare reply
    $dice = new \Mos\Dice\CDice();
    $dice->roll($roll);

    $app->views->add('dice/index', [
        'roll'      => $dice->getNumOfRolls(),
        'results'   => $dice->getResults(),
        'total'     => $dice->getTotal(),
    ]);

    $app->theme->setTitle("Rolled a dice");

});


// Check for matching routes and dispatch to controller/handler of route and render the page
$app->router->handle();
$app->theme->render();
