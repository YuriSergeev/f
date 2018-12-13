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
        if(preg_match("/select/", $this->sequence)) {
            $this->query .= 'SELECT '.$this->select.' ';
        }

        if(preg_match("/insert/", $this->sequence)) {
            $this->query .= 'INSERT INTO '.$this->insert.' ';
        }

        if(preg_match("/update/", $this->sequence)) {
            $this->query .= 'UPDATE '.$this->update.' ';
        }

        if(preg_match("/delete/", $this->sequence)) {
            $this->query .= 'DELETE FROM '.$this->delete.' ';
        }

        if(preg_match("/set/", $this->sequence)) {
            $this->query .= ' SET '.$this->set[0];
        }

        if(preg_match("/from/", $this->sequence)) {
            $this->query .= ' FROM '.$this->from;
        }

        if(preg_match("/where/", $this->sequence)) {
            $this->query .= ' WHERE '.$this->where;
        }

        if(preg_match("/values/", $this->sequence)) {
            $this->query .= $this->values[0].' VALUES '.$this->values[1];
        }

        if(preg_match("/orderBy/", $this->sequence)) {
            $this->query .= ' ORDER BY '.$this->orderBy;
        }

        if(preg_match("/limit/", $this->sequence)) {
            $this->query .= ' LIMIT '.$this->limit;
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
