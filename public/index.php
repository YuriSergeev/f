<?php

require '/home/vagrant/code/blog/public/Dump.php';
require '/home/vagrant/code/blog/public/ConnectionDriver.php';
require '/home/vagrant/code/blog/public/QueryBuilder.php';
require '/home/vagrant/code/blog/public/ExecuteQuery.php';

use builder\ExecuteQuery;
use builder\ConnectionDriver;
use builder\QueryBuilder as DB;

$sql = new DB("mysql", "localhost", "querybuilder", "root", "secret");

dd($sql->select()->from('people')->execute());
