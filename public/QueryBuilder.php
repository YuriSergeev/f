<?php

// define('SERVER', 'localhost');
// define('USER', 'root');
// define('PASSWORD', 'secret');
// define('DBNAME', 'querybuilder');

class QueryBuilder
{
    private $server, $user, $password, $dbname, $db, $connection;

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

    public function select($what, $from, $where = null, $order = null)
    {
        $fetched = array();
        $sql = 'SELECT '.$what.' FROM '.$from;
        if($where != null) $sql .= ' WHERE '.$where;
        if($order != null) $sql .= ' ORDER BY '.$order;

        $query = mysqli_query($this->connection, $sql);

        if($query) {
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
            return $fetched;
        } else {
            return false;
        }
    }

    public function insert($table, $values, $rows = null)
    {
        $insert = 'INSERT INTO '.$table;

        if($rows != null)
        {
            $insert .= ' ('.$rows.')';
        }
        $numValues = count($values);

        for($i = 0; $i < $numValues; $i++)
        {
            if(is_string($values[$i])) $values[$i] = '"'.$values[$i].'"';
        }

        $values = implode(',',$values);
        $insert .= ' VALUES ('.$values.')';
        $ins = mysqli_query($this->connection, $insert);

        return ($ins) ? true : false;
    }

    public function update($table, $set, $where = null, $limit = null)
    {
        $sql = 'UPDATE '.$table.' SET '.$set;
        if($where != null) $sql .= ' WHERE '.$where;
        if($limit != null) $sql .= ' LIMIT '.$limit;

        $upd = mysqli_query($this->connection, $sql);

        return ($upd) ? true : false;
    }

    public function delete($table,$where = null)
    {
        $sql = 'DELETE FROM '.$table.' WHERE '.$where;

        if($where == null)
        {
            $sql = 'DELETE '.$table;
        }

        $deleted = mysqli_query($this->connection, $sql);

        return ($deleted)? true : false;
    }

    public function closeConnection()
    {

        mysqli_close($this->connection);
    }

}
