<?php

class SqlBuilder extends QueryFactory
{
    private $driver;

    public function __construct($config)
    {
        $this->connect($config);
    }

    public function getAll()
    {
        $this->assembly();

        $stm = $this->driver->prepare($this->query);
        $stm->execute($this->values);
        return $stm->fetchAll();
    }



    public function execute()
    {

        $this->assembly();

        $this->query = $this->filter($this->query);
        $query = $this->driver->prepare($this->query);

        if($this->type == 'insert') {
            $query->execute($this->values[2]);
        } else if($this->type == 'update') {
            $query->execute($this->set[1]);
        } else if($this->type == 'delete') {
            $this->driver->query($this->query);
        } else {
            $query->execute($this->set[1]);
        }

        $this->clear();
    }

    public function assembly()
    {
        if(! is_null($this->select)) {
            $this->query .= 'SELECT '.$this->select;
        }

        if(! is_null($this->insert)) {
            $this->query .= 'INSERT INTO '.$this->insert.' ';
        }

        if(! is_null($this->update)) {
            $this->query .= 'UPDATE '.$this->update.' ';
        }

        if(! is_null($this->delete)) {
            $this->query .= 'DELETE FROM '.$this->delete.' ';
        }

        if(! is_null($this->set)) {
            $this->query .= ' SET '.$this->set[0];
        }

        if(! is_null($this->from)) {
            $this->query .= ' FROM '.$this->from;
        }

        if(! is_null($this->where)) {
            $this->query .= ' WHERE '.$this->where;
        }

        if(! is_null($this->values)) {
            if($this->type == 'insert') {
              $this->query .= $this->values[0].' VALUES '.$this->values[1];
            } else if($this->type == 'update') {
              $this->query .= $this->values;
            }
        }

        if(! is_null($this->limit)) {
            $this->query .= ' LIMIT '.$this->limit;
        }

        if(! is_null($this->orderBy)) {
            $this->query .= ' ORDER BY '.$this->orderBy;
        }
    }

    public function connect($config)
    {
        $sql = $config['driver'];
        $server = $config['server'];
        $user = $config['user'];
        $password = $config['password'];
        $dbname = $config['dbname'];
        $charset = $config['charset'];

        $dns = $sql.':host='.$server.';dbname='.$dbname.';charset='.$charset;

        $option = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->driver = new PDO($dns, $user, $password, $option);
    }

    public function escape($data)
    {
        if ($data === null) {
            return 'NULL';
        }
        return $this->driver->quote(trim($data));
    }
}
