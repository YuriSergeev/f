<?php
namespace blog;

class ConnectionDriver
{
    private $server, $user, $password, $dbname;

    public $driver;

    public function __construct($driver, $server, $user, $password, $dbname)
    {
        $this->server = $server;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->createConnector($driver);
    }

    public function createConnector($driver)
    {
        if (! isset($driver)) {
            dd('Драйвер не инициализорован');
        }

        switch ($driver) {
            case 'mysql':
                return $this->MySqlConnector();
            case 'pgsql':
                return $this->PostgresConnector();
            // case 'sqlite':
            //     return $this->SQLiteConnector();
            // case 'sqlsrv':
            //     return $this->SqlServerConnector();
            default:
                return dd('Такого драйвера не существует');
        }
    }

    public function MySqlConnector()
    {
      $this->driver = mysqli_connect($this->server, $this->user, $this->password, $this->dbname);
      ExceptionProcessing::exceptionConnect($this->driver);

    }

    public function PostgresConnector()
    {
        $this->driver = pg_connect($this->server, $this->user, $this->password, $this->dbname);
        ExceptionProcessing::exceptionConnect($this->driver);
    }

    public function SQLiteConnector()
    {
        $this->driver = sqlite_open($this->server, $this->user, $this->password, $this->dbname);
        ExceptionProcessing::exceptionConnect($this->driver);
    }

    public function SqlServerConnector()
    {
        $this->driver = sqlsrv_connect($this->server, $this->user, $this->password, $this->dbname);
        ExceptionProcessing::exceptionConnect($this->driver);
    }
}
