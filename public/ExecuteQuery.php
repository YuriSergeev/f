<?php
namespace builder;

class ExecuteQuery
{
    static private $driver, $query;

    static public function processing($driver = null, $query = null)
    {
        self::$driver = $driver;
        self::$query = $query;
        if($driver != null) return self::query();
    }

    static private function query()
    {
        self::$query .= ';';

        $query = mysqli_query(self::$driver, self::$query);

        if($query && strpos(self::$query, ' SELECT ') == 0 && strpos(self::$query, ' FROM ') != false) {
            $fetched = [];
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

            self::$query = null;
            mysqli_close(self::$driver);
        } else {
            self::$query = null;
            mysqli_close(self::$driver);
        }
        
        return $fetched;
    }

}
