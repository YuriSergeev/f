<?php

require '/home/vagrant/code/blog/public/Dump.php';
require '/home/vagrant/code/blog/public/QueryBuilder.php';

$config = [ 'sql'       => 'mysql',
            'server'    => 'localhost',
            'dbname'    => 'querybuilder',
            'user'      => 'root',
            'password'  => 'secret',
            'charset'   => 'utf8mb4',
          ];

$db = new QueryBuilder($config);

$id = 229;
$name = 'Yes, baby';
$age = 10;

$values = [ 'id' => $id,
            'name' => $name,
            'age' => $age,
          ];



$db->insert('people')->values($values);
