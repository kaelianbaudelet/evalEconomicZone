<?php
namespace MyApp\Service;

use MyApp\Model\CurrencyModel;
use MyApp\Model\EconomicZoneModel;
use MyApp\Model\ProductModel;
use MyApp\Model\TypeModel;
use MyApp\Model\UserModel;
use PDO;

class DependencyContainer
{
    private $instances = [];

    public function __construct()
    {
    }

    public function get($key)
    {
        if (!isset($this->instances[$key])) {
            $this->instances[$key] = $this->createInstance($key);
        }

        return $this->instances[$key];
    }

    private function createInstance($key)
    {
        switch ($key) {
            case 'PDO':
                return $this->createPDOInstance();
            case 'TypeModel':
                $pdo = $this->get('PDO');
                return new TypeModel($pdo);
            case 'ProductModel':
                $pdo = $this->get('PDO');
                return new ProductModel($pdo);
            case 'UserModel':
                $pdo = $this->get('PDO');
                return new UserModel($pdo);
            case 'CurrencyModel':
                $pdo = $this->get('PDO');
                return new CurrencyModel($pdo);
            case 'EconomicZoneModel':
                $pdo = $this->get('PDO');
                return new EconomicZoneModel($pdo);
            default:
                throw new \Exception("No service found for key: " . $key);
        }
    }

    private function createPDOInstance()
    {
        try {
            $pdo = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' .
                $_ENV['DB_NAME'] . ';charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            throw new \Exception("PDO connection error: " . $e->getMessage());
        }
    }

}
