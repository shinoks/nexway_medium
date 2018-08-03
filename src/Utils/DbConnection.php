<?php
namespace App\Utils;
use Doctrine\DBAL\DBALException;

class DbConnection
{
    public function getConnection($dbname = '', $user = 'root', $password = '', $host = 'localhost', $dbType = 'pdo_mysql')
    {
        $config = new \Doctrine\DBAL\Configuration();
        $driver = $this->getDbDriver($dbType);
        $connectionParams = array(
            'dbname' => $dbname,
            'user' => $user,
            'password' => $password,
            'host' => $host,
            'driver' => $driver,
        );
        try{
            $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        } catch(DBALException $e){

        }

        return $conn;
    }

    private function getDbDriver($db)
    {
        switch($db){
            case 'mysql':
                $db = 'pdo_mysql';
            break;
            case 'db2':
                $db = 'ibm_db2';
            break;
            case 'mssql':
                $db = 'pdo_sqlsrv';
            break;
            case 'mysql2':
                $db = 'pdo_mysql';
            break;
            case 'postgres':
                $db = 'pdo_pgsql';
            break;
            case 'pgsql':
                $db = 'pdo_pgsql';
            break;
            case 'sqlite':
                $db = 'pdo_sqlite';
            break;
            case 'sqlite3':
                $db = 'pdo_sqlite';
            break;
        }

        return $db;
    }
}
