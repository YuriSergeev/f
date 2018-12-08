<?php

require '/home/vagrant/code/blog/public/Dump.php';
require '/home/vagrant/code/blog/public/QueryBuilder.php';

$config = [ 'sql'       => 'mysql',
            'server'    => 'localhost',
            'dbname'    => 'querybuilder',
            'user'      => 'root',
            'password'  => 'secret',
            'charset'   => 'utf8',
          ];

$db = new QueryBuilder($config);

$id = 232;
$name = 'updateNumber232';
$age = 10;

$values = [ 'name' => $name,
            'age' => $age,
          ];


dd($db->select('people')->result());
