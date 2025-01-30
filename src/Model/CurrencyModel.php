<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\Currency;
use PDO;

class CurrencyModel
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function getAllCurrency(): array
    {
        $sql = "SELECT * FROM Currency";
        $stmt = $this->db->query($sql);
        $types = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $types[] = new Currency($row['id'], $row['name']);
        }
        return $types;
    }
    public function getOneCurrency(int $id): ?Currency
    {
        $sql = "SELECT * from Currency where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Currency($row['id'], $row['name']);
    }
    public function updateCurrency(Currency $currency): bool
    {
        $sql = "UPDATE Currency SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $currency->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $currency->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function addCurrency(Currency $currency): bool
    {
        $sql = "INSERT INTO Currency (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $currency->getName(), PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function deleteCurrency(int $id): bool
    {
        $sql = "DELETE FROM Currency WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
