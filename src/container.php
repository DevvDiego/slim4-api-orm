<?php

use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;
use \App\Auth\JWTManager as JWTManager;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    
    'db' => function () {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $_ENV['DB_HOST'],
            'database'  => $_ENV['DB_NAME'],
            'username'  => $_ENV['DB_USER'],
            'password'  => $_ENV['DB_PASS'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]);

        //Instance conn as "static" so can be accessed anywhere
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    },

    JWTManager::class => function (ContainerInterface $c){
        $secret = $_ENV['JWT_SECRET'];
        return new JWTManager($secret);
    },

    Capsule::class => DI\get('db'),
]);

return $builder->build();

?>