<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\EconomicZone;
use PDO;

class EconomicZoneModel
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function getAllEconomicZones(): array
    {
        $sql = "SELECT * FROM EconomicZone";
        $stmt = $this->db->query($sql);
        $economiczones = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $economiczones[] = new EconomicZone($row['id'], $row['name']);
        }
        return $economiczones;
    }
    public function getOneEconomicZone(int $id): ?EconomicZone
    {
        $sql = "SELECT * from EconomicZone where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new EconomicZone($row['id'], $row['name']);
    }
    public function updateEconomicZone(EconomicZone $economiczone): bool
    {
        $sql = "UPDATE EconomicZone SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $economiczone->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $economiczone->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function addEconomicZone(EconomicZone $economiczone): bool
    {
        $sql = "INSERT INTO EconomicZone (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $economiczone->getName(), PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function deleteEconomicZone(int $id): bool
    {
        $sql = "DELETE FROM EconomicZone WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
