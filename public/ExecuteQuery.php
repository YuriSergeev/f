<?php
namespace blog;

class ExecuteQuery
{
    static private $driver, $sql;

    static public function processing($driver = null, $sql = null)
    {
        self::$driver = $driver;
        self::$sql = $sql;
        if($driver != null) return self::query();
    }

    static private function query()
    {
        self::$sql .= ';';
        $query = mysqli_query(self::$driver, self::$sql);
        
        if(!$query) {
            ExceptionProcessing::query();
        }

        if($query && strpos(self::$sql, ' SELECT ') == 0 && strpos(self::$sql, ' FROM ') != false) {
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

            self::$sql = null;
            mysqli_close(self::$driver);
        } else {
            self::$sql = null;
            mysqli_close(self::$driver);
        }
        return $fetched;
    }

}
