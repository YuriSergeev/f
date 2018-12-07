<?php
namespace blog;

class ExceptionProcessing
{
    public static function exceptionConnect($driver)
    {
        if (!$driver)
        {
          echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
          echo "Код ошибки error: " . mysqli_connect_errno() . PHP_EOL;
          echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
          exit;
        }
    }

    public static function query()
    {
        echo "Запрос не был обработан";
        exit;
    }
}
