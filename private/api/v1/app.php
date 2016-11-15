<?php
/**
* This file contains all api routes and configs.
*/

// The Version route.
$app->get('/version', function ($request, $response, $args) use ($app) {
    return $response->withStatus(200)->write($this->get('version'));
});


// Newsletter routes
$app->group('/newsletter', function () use ($app) {
    $app->post('/register', 'NewsletterController:register')->setName('newsletter-register');
});
