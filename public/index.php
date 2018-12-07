<?php

require '/home/vagrant/code/blog/public/Dump.php';
require '/home/vagrant/code/blog/public/ConnectionDriver.php';
require '/home/vagrant/code/blog/public/Builder.php';
require '/home/vagrant/code/blog/public/ExecuteQuery.php';
require '/home/vagrant/code/blog/public/ExceptionProcessing.php';
use blog\ConnectionDriver as connection;
use blog\Builder as DB;

$connection = new connection('mysql', 'localhost', 'root', 'secret', 'querybuilder');
$sql = new DB($connection->driver);
$sql->select()->from('people');
$name = $sql->execute();

echo "<pre>";
dd($name);
echo "</pre>";
