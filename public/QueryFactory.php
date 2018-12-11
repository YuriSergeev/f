<?php

namespace builder;

abstract class QueryFactory
{
    protected $select = null;
    protected $insert = null;
    protected $update = null;
    protected $delete = null;
    protected $from = null;
    protected $where = null;
    protected $values = null;
    protected $limit = null;
    protected $orderBy = null;
    protected $type = null;
    protected $op = ['=', '!=', '<', '>', '<=', '>=', '<>'];

    abstract protected function execute();

    abstract protected function connect($config);

    public function select($column = '*')
    {
        $this->type = 'select';

        $select = (is_array($column) ? implode(', ', $column) : $column);
        $this->select = ($this->select == '*' ? $select : $this->select . ', ' . $select);

        return $this;
    }

    public function insert($table)
    {
        $this->type = 'insert';

        $this->insert = $table;

        return $this;
    }

    public function update($table)
    {
        $this->type = 'update';

        $this->update = $table;

        return $this;
    }

    public function delete($table)
    {
        $this->type = 'delete';

        $this->delete = $table;

        return $this;
    }

    public function from($table)
    {
        $this->from = $table;

        return $this;
    }

    public function values($values)
    {
        $keys = array_keys($values);
        $vals = array_values($values);

        if ($this->type == 'insert')
        {
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
            return $this;
        }

        elseif ($this->type == 'update') {
            for ($i = 0; $i < count($values); $i++) {
                $this->values .= $keys[$i].' = :'.$keys[$i].' ';
                if ($i != count($values) - 1) {
                    $this->values .= ', ';
                }
            }

            return $this;
        }
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
        return $this;
    }

    public function orderBy($condition)
    {
        $this->orderBy = $condition;

        return $this;
    }

    public function limit($limit, $limitEnd = null)
    {
        if (! is_null($limitEnd)) {
            $this->limit = $limit . ', ' . $limitEnd;
        } else {
            $this->limit = $limit;
        }

        return $this;
    }

    public function filter($input)
    {
        $input = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $input);

        $search = '!@#$%^&*';
        $search .= '~`";+/={}[]-_|\'\\';

        for ($i = 0; $i < strlen($search); $i++) {
            $input = preg_replace('/\\'.$search[$i].'/', '', $input);
        }
        return $input;
    }
}
