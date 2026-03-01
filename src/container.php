<?php

use DI\ContainerBuilder;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    
    PDO::class => function(){
        $host = $_ENV["DB_HOST"];
        $dbname = $_ENV["DB_NAME"];
        $user = $_ENV["DB_USER"];
        $pass = $_ENV["DB_PASS"];

        return new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    },
]);

return $builder->build();

?>