<?php

require '/home/vagrant/code/blog/public/Dump.php';
require '/home/vagrant/code/blog/public/ConnectionDriver.php';
require '/home/vagrant/code/blog/public/Builder.php';
use blog\ConnectionDriver as connection;
use blog\Builder as DB;

$connect = new connection('mysql', 'localhost', 'root', 'secret', 'querybuilder');



dd($connect);
