<?php

require '/home/vagrant/code/blog/public/Dump.php';
require '/home/vagrant/code/blog/public/ConnectionDriver.php';
require '/home/vagrant/code/blog/public/QueryFactory.php';
require '/home/vagrant/code/blog/public/Builder.php';
require '/home/vagrant/code/blog/public/Select.php';
use blog\ConnectionDriver as connection;
use blog\Builder as DB;

$connection = new connection('mysql', 'localhost', 'root', 'secret', 'querybuilder');
$sql = new DB($connection->driver);
dd($sql->table('people')->select(['*']));
