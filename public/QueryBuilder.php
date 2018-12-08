<?php

namespace builder;

class QueryBuilder
{
    public $query = '';

    private $driver;

    public function __construct($driver, $host, $dbname, $user, $password)
    {
          $this->driver = new ConnectionDriver($driver, $host, $dbname, $user, $password);
    }

    public function select($columns = '*')
    {
        $this->query .= ' SELECT '.$this->column($columns) .' ';
        return $this;
    }

    public function insert($table)
    {
        $this->query .= ' INSERT INTO '.$this->filter($table).' ';
        return $this;
    }

    public function update($table)
    {
        $this->query .= ' UPDATE '.$this->filter($table).' ';
        return $this;
    }

    public function delete($table)
    {
        $this->query = 'DELETE FROM '.$this->filter($table).' ';
        return $this;
    }

    public function from($table)
    {
        $this->query .= ' FROM '.$this->filter($table).' ';
        return $this;
    }

    public function set($values)
    {
        $this->query .= ' SET ';
        foreach ($values as $key => $value) {
            $this->query .= $this->filter($key).' = "'.$this->filter($value).'", ';
        }
        $this->query = substr($this->query, 0, -2);
        return $this;
    }

    public function where($values)
    {
        $this->query .= ' WHERE ';
        foreach ($values as $key => $value) {
            $this->query .= $this->filter($key).' = "'.$this->filter($value).'", ';
        }
        $this->query = substr($this->query, 0, -2);
        return $this;
    }

    public function values($values)
    {
        $result = ' VALUES (';
        foreach ($values as $key => $value) {
            $result .= $value.', ';
        }
        $result = substr($result, 0, -2);
        $this->query .= $result.') ';
        return $this;
    }

    public function limit($limit = 3000)
    {
        $this->query .= ' LIMIT '.$this->filter($limit).' ';
        return $this;
    }

    public function orderBy($condition)
    {
        $this->query .= ' ORDER BY '.$this->filter($condition);
        return $this;
    }

    public function column($columns)
    {
        if(! is_array($columns))
        {
            return $columns;
        }

        $result = '';

        foreach ($columns as $key => $value) {
            $result .= $value.', ';
        }
        $result = substr($result, 0, -2);

        return $result;
    }

    public function write()
    {
        echo $this->query;
    }

    public function execute()
    {
        return ExecuteQuery::processing($this->driver->driver, $this->query);
    }

    public function filter($valc)
    {
        return mysqli_real_escape_string($this->driver->driver, $valc);
    }
}
