<?php
namespace blog;

class Select
{
      private $table, $columns;

      public function __construct($table = null, array $columns = null)
      {
          $this->table = $table;
          $this->$columns = $columns;
      }

      public function from($columns)
      {
          $this->$columns = $columns;
      }
}
