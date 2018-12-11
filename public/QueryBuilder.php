<?php

class QueryBuilder extends PDO
{
    private $sql, $server, $dbname, $user, $password, $charset, $option, $type, $values;

    public $query, $pdo;

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
        $this->type = 'select';

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
        if (is_array($where)) {
            $_where = [];
            foreach ($where as $column => $data) {
                $_where[] = $type . $column . '=' . $this->escape($data);
            }
            $where = implode(' ' . $andOr . ' ', $_where);
        } else {
            if (is_array($op)) {
                $x = explode('?', $where);
                $w = '';
                foreach ($x as $k => $v) {
                    if (! empty($v)) {
                        $w .= $type . $v . (isset($op[$k]) ? $this->escape($op[$k]) : '');
                    }
                }
                $where = $w;
            } elseif (! in_array($op, $this->op) || $op == false) {
                $where = $type . $where . ' = ' . $this->escape($op);
            } else {
                $where = $type . $where . ' ' . $op . ' ' . $this->escape($val);
            }
        }
        if ($this->grouped) {
            $where = '(' . $where;
            $this->grouped = false;
        }
        if (is_null($this->where)) {
            $this->where = $where;
        } else {
            $this->where = $this->where . ' ' . $andOr . ' ' . $where;
        }

        return $this;
    }

    public function values($values)
    {
        $keys = array_keys($values);
        $this->values = array_values($values);

        if ($this->type == 'insert')
        {
            $row = '(';
            for ($i = 0; $i < count($values); $i++)
            {
                $row .= $keys[$i];

                if ($i != count($values) - 1) {
                    $row .= ', ';
                } else {
                    $row .= ') VALUES (';
                }
            }

            for ($i = 0; $i < count($values); $i++)
            {
            	$row .= ':'.$keys[$i];

                if ($i != count($values) - 1) {
                    $row .= ', ';
                } else {
                    $row .= ')';
                }
            }

            $this->query .= $this->filter($row);
            $query = $this->pdo->prepare($this->query);

            $query->execute($this->values);
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

    public function left($condition)
    {
        $this->query .= 'LEFT JOIN '.$this->filter($condition).' ';
        return $this;
    }

    public function using($column)
    {
        $this->query .= ' USING ('.$this->filter($column).')';
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

    public function add_column($column, $datatype)
  	{
    		$this->query .= 'MODIFY COLUMN '.$this->filter($column).' '.$this->filter($datatype);
    		$this->query($this->query);
  	}

    public function drop_column($column)
  	{
    		$this->query .= 'DROP COLUMN '.$this->filter($column);
    		$this->query($this->query);
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

    public function filter($input)
    {
        $input = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $input);

        $search = '!@#$%^&*()';
        $search .= '~`";+/={}[]-_|\'\\';

        for ($i = 0; $i < strlen($search); $i++) {
            $input = preg_replace('/\\'.$search[$i].'/', '', $input);
        }
        return $input;
    }
}
