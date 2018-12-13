<?php

class MyQueryBuilder
{
    public static function connectionDBMS($config)
    {
        if (! in_array($config['driver'] , PDO::getAvailableDrivers()))
        {
            throw new Exception('Драйвер '. $config['driver'] .' не найден');
        }

        try {
            $builder = new SqlBuilder($config);
        } catch (PDOException $e) {
            echo '<h2>Внимание! Обнаружена ошибка.</h2>'.
            '<h4>'.$e->getMessage().'</h4>'.
            '<pre>'.$e->getTraceAsString().'</pre>';
            exit;
        }

        return $builder;

    }
}
