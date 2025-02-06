<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\Product;
use MyApp\Entity\Type;
use PDO;

class ProductModel
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function getAllProducts(): array
    {
        $sql = "SELECT p.id as idProduit, name, description, price, stock, t.id as idType, label
        FROM Product p inner join Type t on p.idType = t.id order by name";
        $stmt = $this->db->query($sql);
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $type = new Type($row['idType'], $row['label']);
            $products[] = new Product($row['idProduit'], $row['name'], $row['price'], $row['description'], $row['stock'], $type);
        }

        return $products;
    }

    public function getOneProduct(int $id): ?Product
    {
        $sql = "SELECT p.id as idProduit, name, description, price, stock, t.id as idType, label
    FROM Product p inner join Type t on p.idType = t.id where p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $type = new Type($row['idType'], $row['label']);
        return new Product($row['idProduit'], $row['name'], $row['price'], $row['description'],
            $row['stock'], $type);
    }

    public function updateProduct(Product $product): bool
    {
        $sql = "UPDATE Product SET name = :name, price = :price, description = :description, stock = :stock, idType = :idType WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $product->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $product->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':price', $product->getPrice(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $product->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(':stock', $product->getStock(), PDO::PARAM_STR);
        $stmt->bindValue(':idType', $product->getType()->getId(), PDO::PARAM_INT);

        return $stmt->execute();

    }

    public function createProduct(Product $product): bool
    {
        $sql = "INSERT INTO Product (name, price, description, stock, idType) VALUES (:name, :price, :description, :stock, :idType)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $product->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':price', $product->getPrice(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $product->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(':stock', $product->getStock(), PDO::PARAM_STR);
        $stmt->bindValue(':idType', $product->getType()->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteProduct(int $id): bool
    {
        $sql = "DELETE FROM Product WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
