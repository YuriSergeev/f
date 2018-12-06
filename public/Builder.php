<?php
namespace blog;

class Builder
{
    private $driver;

    public $bindings = [
        'select' => [],
        'insert'   => [],
        'join'   => [],
        'from'   => [],
        'where'  => [],
        'order by'  => [],
    ];

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function select($table = null, array $columns = null)
    {
        return $this->injectBuilder(QueryFactory::createSelect($table, $columns));
    }

    public function insert($table = null, array $values = null)
    {
        return $this->injectBuilder(QueryFactory::createInsert($table, $values));
    }

    public function update($table = null, array $values = null)
    {
        return $this->injectBuilder(QueryFactory::createUpdate($table, $values));
    }

    public function delete($table = null)
    {
        return $this->injectBuilder(QueryFactory::createDelete($table));
    }

    protected function injectBuilder(AbstractBaseQuery $query)
    {
        return $query->setBuilder($this);
    }
}
