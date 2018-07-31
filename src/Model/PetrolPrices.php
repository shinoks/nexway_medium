<?php
namespace App\Model;

class PetrolPrices
{
    private $city;

    private $pb95;

    private $pb98;

    private $on;

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
     * @return float
     */
    public function getPb95(): float
    {
        return $this->pb95;
    }

    /**
     * @param $pb95
     */
    public function setPb95($pb95): void
    {
        $this->pb95 = $pb95;
    }

    /**
     * @return float
     */
    public function getPb98(): float
    {
        return $this->pb98;
    }

    /**
     * @param $pb98
     */
    public function setPb98($pb98): void
    {
        $this->pb98 = $pb98;
    }

    /**
     * @return float
     */
    public function getOn(): float
    {
        return $this->on;
    }

    /**
     * @param $on
     */
    public function setOn($on): void
    {
        $this->on = $on;
    }
}
