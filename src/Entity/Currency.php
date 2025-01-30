<?php
declare (strict_types = 1);
namespace MyApp\Entity;

class Currency
{
    private ?int $id = null;
    private string $name;
    public function __construct(?int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $label): void
    {
        $this->label = $name;
    }
}
