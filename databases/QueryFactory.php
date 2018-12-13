<?php

abstract class QueryFactory
{
    protected $query = '';
    protected $sequence = '';
    protected $select = null;
    protected $insert = null;
    protected $update = null;
    protected $delete = null;
    protected $from = null;
    protected $where = null;
    protected $values = null;
    protected $set = null;
    protected $limit = null;
    protected $orderBy = null;
    protected $type = null;
    protected $op = ['=', '!=', '<', '>', '<=', '>=', '<>'];

    abstract protected function execute();

    abstract protected function assembly();

    abstract protected function connect($config);

    public function select($column = '*')
    {
        if($this->type != null)
              $this->checkType();

        $this->type = 'select';

        $select = (is_array($column) ? implode(', ', $column) : $column);
        $this->select = ($this->select == '*' ? $select : $this->select . ' ' . $select);

        $this->sequence .= 'select ';

        return $this;
    }

    public function insert($table)
    {
        if($this->type != null)
              $this->checkType();

        $this->type = 'insert';

        $this->insert = $table;

        $this->sequence .= 'insert ';

        return $this;
    }

    public function update($table)
    {
        if($this->type != null)
              $this->checkType();

        $this->type = 'update';

        $this->update = $table;

        $this->sequence .= 'update ';

        return $this;
    }

    public function delete($table)
    {
        if($this->type != null)
              $this->checkType();

        $this->type = 'delete';

        $this->delete = $table;

        $this->sequence .= 'delete ';

        return $this;
    }

    public function from($table)
    {
        if ($this->type != 'select')
            $this->except($this->type, "values");

        $this->from = $table;

        $this->sequence .= ' from ';

        return $this;
    }

    public function values($values)
    {
        if ($this->type != 'insert')
            $this->except($this->type, "values");

        $keys = array_keys($values);
        $vals = array_values($values);

        $row1 = '(';
        for ($i = 0; $i < count($values); $i++)
        {
            $row1 .= $keys[$i];

            if ($i != count($values) - 1) {
                $row1 .= ', ';
            } else {
                $row1 .= ')';
            }
        }
        $row2 = '(';
        for ($i = 0; $i < count($values); $i++)
        {
          $row2 .= ':'.$keys[$i];

            if ($i != count($values) - 1) {
                $row2 .= ', ';
            } else {
                $row2 .= ')';
            }
        }

        $this->values = [$row1, $row2, $vals];

        $this->sequence .= ' values ';

        return $this;
    }

    public function set($set)
    {
        $keys = array_keys($set);
        $vals = array_values($set);

        $row1 = null;

        for ($i = 0; $i < count($set); $i++) {
            $row1 .= $keys[$i].' = :'.$keys[$i].' ';
            if ($i != count($set) - 1) {
                $row1 .= ', ';
            }
        }

        $this->set = [$row1, $vals];

        $this->sequence .= ' set ';

        return $this;
    }

    public function where($where, $op = null, $val = null, $type = '', $andOr = 'AND')
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

        if (is_null($this->where)) {
            $this->where = $where;
        } else {
            $this->where = $this->where . ' ' . $andOr . ' ' . $where;
        }

        $this->sequence .= ' where ';

        return $this;
    }

    public function orderBy($condition)
    {
        if ($this->type != 'select')
            $this->except($this->type, "orderBy");

        $this->orderBy = $condition;

        $this->sequence .= ' orderBy ';

        return $this;
    }

    public function limit($limit, $limitEnd = null)
    {
        if ($this->type != 'select')
            $this->except($this->type, "limit");

        if (! is_null($limitEnd)) {
            $this->limit = $limit . ', ' . $limitEnd;
        } else {
            $this->limit = $limit;
        }

        $this->sequence .= ' limit ';

        return $this;
    }

    public function filter($input)
    {
        $input = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $input);

        $search = '!@#$%^&*';
        $search .= '~`";+/{}[]-_|\'\\';

        for ($i = 0; $i < strlen($search); $i++) {
            $input = preg_replace('/\\'.$search[$i].'/', '', $input);
        }
        return $input;
    }

    public function clear()
    {
      $this->query = '';
      $this->sequence = '';
      $this->select = null;
      $this->insert = null;
      $this->update = null;
      $this->delete = null;
      $this->from = null;
      $this->where = null;
      $this->values = null;
      $this->set = null;
      $this->limit = null;
      $this->orderBy = null;
      $this->type = null;
    }

    protected function except($type, $context)
    {
        throw new \Exception("Вы не можете использовать функцию {$type} с функцией {$context}");
    }

    protected function checkType()
    {
        throw new \Exception("Вы не можете использовать такую последовательность");
    }

}
