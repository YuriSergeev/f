<?php

define("ROOT_DIR",dirname(__FILE__).'/');

require_once "/home/vagrant/code/blog/vendor/autoload.php";
require_once "/home/vagrant/code/blog/vendor/main.php";

$application = new Application();
$application->run();
