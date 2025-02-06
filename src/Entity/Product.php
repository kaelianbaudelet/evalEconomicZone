<?php
declare (strict_types = 1);
namespace MyApp\Entity;

use MyApp\Entity\Type;

class Product
{
    private ?int $id = null;
    private string $name;
    private float $price;
    private string $description;
    private int $stock;
    private Type $type;

    public function __construct(?int $id, string $name, float $price, string $description, int $stock, Type $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->stock = $stock;
        $this->type = $type;
    }
    # Id
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    # Name
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    # Price
    public function getPrice(): float
    {
        return $this->price;
    }
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
    # Description
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    # Stock
    public function getStock(): int
    {
        return $this->stock;
    }
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }
    public function getType(): Type
    {
        return $this->type;
    }
    public function setType(Type $type): void
    {
        $this->type = $type;
    }
}
