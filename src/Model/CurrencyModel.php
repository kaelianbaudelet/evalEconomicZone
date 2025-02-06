<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\Currency;
use MyApp\Entity\EconomicZone;
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

        $sql = "SELECT c.id AS idCurrency, c.name AS currencyName, e.id AS idEconomicZone, e.name AS economicZoneName
        FROM Currency c
        INNER JOIN EconomicZone e ON c.idEconomicZone = e.id
        ORDER BY c.name";

        $stmt = $this->db->query($sql);
        $currencys = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $economic_zone = new EconomicZone($row['idEconomicZone'], $row['economicZoneName']);
            $currencys[] = new Currency($row['idCurrency'], $row['currencyName'], $economic_zone);
        }

        var_dump($currencys);

        return $currencys;
    }

    public function getOneCurrency(int $id): ?Currency
    {

        $sql = "SELECT c.id AS idCurrency, c.name AS currencyName, e.id AS idEconomicZone, e.name AS economicZoneName
        FROM Currency c
        INNER JOIN EconomicZone e ON c.idEconomicZone = e.id
        WHERE c.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $economic_zone = new EconomicZone($row['idEconomicZone'], $row['economicZoneName']);

        return new Currency($row['idCurrency'], $row['currencyName'], $economic_zone);
    }

    public function updateCurrency(Currency $currency): bool
    {
        $sql = "UPDATE Currency SET name = :name, idEconomicZone = :idEconomicZone WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $currency->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $currency->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':idEconomicZone', $currency->getEconomicZone()->getId(), PDO::PARAM_INT);

        return $stmt->execute();

    }

    public function addCurrency(Currency $currency): bool
    {
        $sql = "INSERT INTO Currency (name, idEconomicZone) VALUES (:name, :idEconomicZone)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $currency->getName(), PDO::PARAM_STR);

        $stmt->bindValue(':idEconomicZone', $currency->getEconomicZone()->getId(), PDO::PARAM_INT);
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
