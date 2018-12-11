<?php

require '/home/vagrant/code/blog/public/Dump.php';
require '/home/vagrant/code/blog/public/MyQueryBuilder.php';
require '/home/vagrant/code/blog/public/QueryFactory.php';
require '/home/vagrant/code/blog/public/SqlBuilder.php';

use builder\MyQueryBuilder;



$config = [ 'sql'       => 'mysql',
            'server'    => 'localhost',
            'dbname'    => 'querybuilder',
            'user'      => 'root',
            'password'  => 'secret',
            'charset'   => 'utf8',
          ];

$driver = 'mysql';

$id = 998;
$name = 'updateNumber232';
$age = 20;

$values = [ 'id'   => $id,
            'name' => $name,
            'age'  => $age,
          ];

$db = MyQueryBuilder::sqlbuilder($driver, $config);
$db->insert('people')->values($values)->execute();
