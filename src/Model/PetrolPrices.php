<?php
namespace App\Model;

class PetrolPrices
{
    private $city;

    private $fuels;

    public function __construct()
    {
        $this->fuels = [];
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }


    /**
     * @return array
     */
    public function getFuels(): array
    {
        return $this->fuels;
    }


    /**
     * @param $name
     * @param $price
     */
    public function setFuel($name, $price): void
    {
        $this->fuels []= [$name => $price];
    }
}
