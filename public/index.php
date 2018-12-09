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

$id = 232;
$name = 'updateNumber232';
$age = 'age';

$values = [ 'name' => $name,
            'age' => $age,
          ];

$db = new QueryBuilder($config);
dd($db->select('people')->where($age.' > 1')->limit(1)->result());
