<?php
  require '/home/vagrant/code/blog/public/Dev.php';
  require '/home/vagrant/code/blog/public/MyQueryBuilder.php';

  $db = new MyQueryBuilder(SERVER, USER, PASSWORD, DBNAME);

  $table = $db->update('people')->set(["name" => "No", "age" => "1"])->where(["id" => "1221"]);
  //$table = $db->select()->from('people')->orderBy(['id', 'DESC']);

  $name = $db->execute();

  echo'<pre>';
  print_r($name);
  echo'</pre>';
