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

            $id = 1;
            $name = 'Dmitriy';
            $age = 22;

            $values1 = ['id'   => $id,
                        'name' => $name,
                        'age'  => $age,
                       ];

            $values2 = [
                       'name' => $name,
                       'age'  => $age,
                      ];

            $db = MyQueryBuilder::sqlbuilder($config);


            $db->update('people')->set($values2)->where('id', 1)->execute();
            $db->delete('people')->where('id', 1)->execute();
            $db->insert('people')->values($values1)->execute();

            $h = $db->select()->from('people')->getAll();
            var_dump($h);

        } catch (Exception $e){
            echo '<h2>Внимание! Обнаружена ошибка.</h2>'.
            '<h4>'.$e->getMessage().'</h4>'.
           '<pre>'.$e->getTraceAsString().'</pre>';
            exit;
        }

    }

}
