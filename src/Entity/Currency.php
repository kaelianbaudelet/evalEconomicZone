<?php
declare (strict_types = 1);
namespace MyApp\Entity;

use MyApp\Entity\EconomicZone;

class Currency
{
    private ?int $id = null;
    private string $name;
    private EconomicZone $economiczone;

    public function __construct(?int $id, string $name, $economiczone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->economiczone = $economiczone;
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
    public function getEconomicZone(): EconomicZone
    {
        return $this->economiczone;
    }
    public function setEconomicZone(EconomicZone $economiczone): void
    {
        $this->economiczone = $economiczone;
    }
}
