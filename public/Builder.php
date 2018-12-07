<?php
namespace blog;

use blog\ExecuteQuery;

class Builder
{
    private $driver, $sql = null;

    // public $alias = [
    //     'select' => ['*'],
    //     'insert' => [],
    //     'update' => [],
    //     'delete' => [],
    //     'from' => [],
    //     'where' => [],
    //     'value' => [],
    //     'set' => [],
    //     'limit' => [],
    //     'orderBy' => [],
    //     'intersec' => [],
    //     'minus' => [],
    //     'union' => [],
    //     'union all' => [],
    // ];

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function select($columns = ['*'])
    {
        $this->sql .= ' SELECT ';

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
        $this->sql .= ' INSERT INTO '.$table.' ';
        return $this;
    }

    public function update($table)
    {
        $this->sql .= ' UPDATE '.$table.' ';
        return $this;
    }

    public function delete($table)
    {
        $this->sql .= ' DELETE FROM '.$table.' ';
        return $this;
    }

    public function from($from)
    {
        $this->sql .= ' FROM '.$from.' ';
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
        $this->sql = ' ';
        return $this;
    }

    public function set(array $values)
    {
        $this->sql .= ' SET ';
        foreach ($values as $key => $value) {
            $this->sql .= $key.' = "'.$value = mysqli_real_escape_string($this->connection, $value).'", ';
        }
        $this->sql = substr($this->sql, 0, -2);
        $this->sql = ' ';
        return $this;
    }

    public function where(array $values)
    {
        $this->sql .= ' WHERE ';
        foreach ($values as $key => $value) {
            $this->sql .= $key.' = "'.$value = mysqli_real_escape_string($this->connection, $value).'", ';
        }
        $this->sql = substr($this->sql, 0, -2);
        $this->sql = ' ';
        return $this;
    }

    public function orderBy(array $values)
    {
        $this->sql .= ' ORDER BY ';
        foreach ($values as $key => $value) {
            if($value == 'desc' || $value == 'DESC')
            {
                $this->sql = substr($this->sql, 0, -2);
                $this->sql .= ' '.$value = mysqli_real_escape_string($this->connection, $value).'  ';
                continue;
            }
            $this->sql .= $value.', ';
        }
        $this->sql = substr($this->sql, 0, -2);
        $this->sql = ' ';
        return $this;
    }

    public function limit(array $values)
    {
        $this->sql .= ' LIMIT ';
        foreach ($values as $key => $value) {
            $this->sql .= $value = mysqli_real_escape_string($this->connection, $value).', ';
        }
        $this->sql = substr($this->sql, 0, -2);
        $this->sql = ' ';
        return $this;
    }


    public function execute()
    {
        return ExecuteQuery::processing($this->driver, $this->sql);
    }
}
