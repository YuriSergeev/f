<?php

class QueryBuilder extends PDO
{
    private $sql, $server, $dbname, $user, $password, $charset, $option, $type, $values;

    public $query = '', $pdo;

    public function __construct($config)
    {
        $this->sql = $config['sql'];
        $this->server = $config['server'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->dbname = $config['dbname'];
        $this->charset = $config['charset'];

        $dns = $this->sql.':host='.$this->server.';dbname='.$this->dbname.';charset='.$this->charset;

        $this->option = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dns, $this->user, $this->password, $this->option);
    }

    public function select($table)
    {
        $this->query = 'SELECT * FROM '.$this->filter($table).' ';
        return $this;
    }

    public function insert($table)
    {
        $this->type = 'insert';

        $this->query = 'INSERT INTO '.$this->filter($table).' ';
        return $this;
    }

    public function replace($table)
    {
        $this->type = 'insert';

        $this->query = 'REPLACE INTO '.$this->filter($table).' ';

        return $this;
    }

    public function update($table)
    {
        $this->type = 'update';

        $this->query = 'UPDATE '.$this->filter($table).' SET ';
        return $this;
    }

    public function delete($table)
    {
        $this->type = 'delete';
        $this->query = 'DELETE FROM '.$this->filter($table).' ';
        return $this;

    }

    public function where($condition)
    {
        $this->query .= ' WHERE '.$condition;

        if ($this->type == 'update') {
            $query = $this->pdo->prepare($this->query);
            $query->execute($this->values);

            return $this;
        } elseif($this->type == 'delete') {
            $this->pdo->query($this->query);
        } else {
            return $this;
        }
    }

    public function values($values)
    {
        $keys = array_keys($values);
        $this->values = array_values($values);

        if ($this->type == 'insert') {
            $row = '(';
            for ($i = 0; $i < count($values); $i++) {
                $row .= $keys[$i];

                if ($i != count($values) - 1) {
                    $row .= ', ';
                } else {
                    $row .= ') VALUES (';
                }
            }
            for ($i = 0; $i < count($values); $i++) {
            	$row .= ':'.$keys[$i];

                if ($i != count($values) - 1) {
                    $row .= ', ';
                } else {
                    $row .= ')';
                }
            }

            $this->query .= $this->filter($row);
            $query = $this->pdo->prepare($this->query);

            $query->execute($vals);
        }

        elseif ($this->type == 'update') {
            for ($i = 0; $i < count($values); $i++) {
                $this->query .= $this->filter($keys[$i]).' = :'.$this->filter($keys[$i]).' ';
                if ($i != count($values) - 1) {
                    $this->query .= ', ';
                }
            }

            return $this;
        }
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

    public function write()
    {
        echo $this->query;
    }

    public function result()
    {
        $stm = $this->pdo->prepare($this->query);
        $stm->execute($this->values);
        return $stm->fetchAll();
    }

    public function filter($valc)
    {
        return $valc;
    }
}
