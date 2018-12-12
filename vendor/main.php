<?php

class Application {

    public function run(){
            $this->Loader();
    }

    public function Loader(){
        spl_autoload_register(['ClassLoader', 'autoload'], true, true);

        try {

            $config = [ 'driver'    => 'mysql',
                        'server'    => 'localhost',
                        'dbname'    => 'querybuilder',
                        'user'      => 'root',
                        'password'  => 'secret',
                        'charset'   => 'utf8',
                      ];

            $id = 997;
            $name = 'updateNumber232';
            $age = 20;

            $values = [ 'id'   => $id,
                        'name' => $name,
                        'age'  => $age,
                      ];

            $db = MyQueryBuilder::sqlbuilder($config);
            $db->update('people')->from('desc')->values($values)->execute();

        } catch (Exception $e){
            echo '<h2>Внимание! Обнаружена ошибка.</h2>'.
            '<h4>'.$e->getMessage().'</h4>'.
           '<pre>'.$e->getTraceAsString().'</pre>';
            exit;
        }

    }

}
