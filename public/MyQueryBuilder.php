<?php

/*
|--------------------------------------------------------------------------
| MyQueryBuilder - мини конструктор SQL запросов
|--------------------------------------------------------------------------
|
*/

define('SERVER', 'localhost');
define('USER', 'root');
define('PASSWORD', 'secret');
define('DBNAME', 'querybuilder');

class MyQueryBuilder
{
    private $server, $user, $password, $dbname, $db, $connection, $sql;

    public function __construct($server, $user, $password, $dbname)
    {
        $this->server = $server;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->openConnection();
    }

    public function openConnection()
    {
        if(!$this->db)
        {
            $this->connection = mysqli_connect($this->server, $this->user, $this->password, $this->dbname);

            if($this->connection) {
                $selectDB = mysqli_select_db($this->connection, $this->dbname);

                if($selectDB) {
                    $this->db = true;
                    mysqli_query($this->connection,'SET NAMES UTF8');
                    return true;
                } else {
                    return false;
                }

            } else {
                return false;
            }

        } else {
            return true;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Основные запросы SQl
    |--------------------------------------------------------------------------
    |
    | SELECT - используется для извлечения данных из таблицы базы
    | INSERT - используется для добавления новых строк данных в таблицу
    | UPDATE - используется для изменения существующих записей в таблице
    | DELETE - используется для удаления существующих записей из таблицы
    |
    */

    public function select($columns = ['*'])
    {
        $this->sql = 'SELECT ';
        if(is_array($columns))
        {
            $this->sql .= $columns[0];
        } else {
            foreach (func_get_args() as $key => $value) {
                $this->sql .= $value = mysqli_real_escape_string($this->connection, $value).', ';
            }
            $this->sql = substr($this->sql, 0, -2);
        }
        return $this;
    }

    public function insert($table)
    {
        $this->sql = 'INSERT INTO '.$table;
        return $this;
    }

    public function update($table)
    {
        $this->sql = 'UPDATE '.$table;
        return $this;
    }

    public function delete($table)
    {
        $this->sql = 'DELETE FROM '.$table;
        return $this;
    }

    public function from($from)
    {
        $this->sql .= ' FROM '.$from;
        return $this;
    }

    public function value(array $values)
    {
        $this->sql .= ' VALUES (';
        foreach ($values as $key => $value) {
            $this->sql .= '"'.$value = mysqli_real_escape_string($this->connection, $value).'", ';
        }
        $this->sql = substr($this->sql, 0, -2);
        $this->sql .= ')';
        return $this;
    }

    public function set(array $values)
    {
        $this->sql .= ' SET ';
        foreach ($values as $key => $value) {
            $this->sql .= $key.' = "'.$value = mysqli_real_escape_string($this->connection, $value).'", ';
        }
        $this->sql = substr($this->sql, 0, -2);
        return $this;
    }

    public function where(array $values)
    {
        $this->sql .= ' WHERE ';
        foreach ($values as $key => $value) {
            $this->sql .= $key.' = "'.$value = mysqli_real_escape_string($this->connection, $value).'", ';
        }
        $this->sql = substr($this->sql, 0, -2);
        return $this;
    }

    public function orderBy(array $values)
    {
        $this->sql .= ' ORDER BY ';
        foreach ($values as $key => $value) {
            if($value == 'desc' || $value == 'DESC')
            {
                $this->sql = substr($this->sql, 0, -2);
                $this->sql .= ' '.$value = mysql_real_escape_string($value).'  ';
                continue;
            }
            $this->sql .= $value.', ';
        }
        $this->sql = substr($this->sql, 0, -2);
        //dd($this->sql);
        return $this;
    }

    public function limit(array $values)
    {
        $this->sql .= ' LIMIT ';
        foreach ($values as $key => $value) {
            $this->sql .= $value = mysql_real_escape_string($value).', ';
        }
        $this->sql = substr($this->sql, 0, -2);
        //dd($this->sql);
        return $this;
    }

    public function execute()
    {
        $fetched = array();
        $this->sql .= ';';
        $query = mysqli_query($this->connection, $this->sql);
        //dd(strpos($this->sql, 'FROM'));
        if($query && strpos($this->sql, 'SELECT') == 0 && strpos($this->sql, 'FROM') != false) {
            $rows = mysqli_num_rows($query);
            for($i = 0; $i < $rows; $i++)
            {
                $results = mysqli_fetch_assoc($query);
                $key = array_keys($results);
                $numKeys = count($key);

                for($x = 0; $x < $numKeys; $x++)
                {
                    $fetched[$i][$key[$x]] = $results[$key[$x]];
                }
            }
            $this->sql = null;
            mysqli_close($this->connection);
            return $fetched;
        } else {
            $this->sql = null;
            mysqli_close($this->connection);
            return false;
        }



    }

}
