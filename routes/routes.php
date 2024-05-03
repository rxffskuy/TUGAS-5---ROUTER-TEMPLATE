<?php

class Routes
{
    public function run()
    {
        $router = new App();
        $router->setDefaultController('DashboardController');
        $router->setDefaultMethod('index');

        $router->get('/dashboard', ['DashboardController', 'index']);

        $router->get('/mobil', ['MobilController', 'index']);
        $router->get('/mobil/add', ['MobilController', 'create']);
        $router->post('/mobil/store', ['MobilController', 'store']);
        $router->get('/mobil/update/{id}', ['MobilController', 'show']);
        $router->post('/mobil/edit/{id}', ['MobilController', 'update']);
        $router->get('/mobil/destroy/{id}', ['MobilController', 'destroy']);
        $router->run();
    }
}
