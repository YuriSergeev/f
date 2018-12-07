<?php
namespace blog;

use blog\QueryFactory;

class Builder
{
    private $driver, $table;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function table($table = null)
    {
        $this->table = $table;
        return $this;
    }

    public function select(array $columns = null)
    {
        return $this->execute(QueryFactory::createSelect($this->table, $columns));
    }

    public function insert(array $values = null)
    {
        return $this->execute(QueryFactory::createInsert($this->table, $values));
    }

    public function update(array $values = null)
    {
        return $this->execute(QueryFactory::createUpdate($this->table, $values));
    }

    public function delete($table = null)
    {
        return $this->execute(QueryFactory::createDelete($table));
    }

    protected function execute()
    {

    }
}
