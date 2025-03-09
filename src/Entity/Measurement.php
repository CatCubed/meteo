<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column]
    private ?float $humidity = null;

    #[ORM\Column]
    private ?float $pressure = null;

    #[ORM\Column(length: 255)]
    private ?string $temperatureUnit = null;

    #[ORM\Column(length: 255)]
    private ?string $humidityUnit = null;

    #[ORM\Column(length: 255)]
    private ?string $pressureUnit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $placement = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $domain = null;

    #[ORM\Column]
    private ?float $lowestTemp = null;

    #[ORM\Column]
    private ?float $highestTemp = null;

    #[ORM\Column]
    private ?float $lowestHumidity = null;

    #[ORM\Column]
    private ?float $highestHumidity = null;

    #[ORM\Column]
    private ?float $lowestPressure = null;

    #[ORM\Column]
    private ?float $highestPressure = null;

    #[ORM\Column(nullable: true)]
    private ?float $rssi = null;

    #[ORM\Column]
    private ?float $voltage = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WeatherStation $weatherStation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(float $humidity): static
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getPressure(): ?float
    {
        return $this->pressure;
    }

    public function setPressure(float $pressure): static
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getTemperatureUnit(): ?string
    {
        return $this->temperatureUnit;
    }

    public function setTemperatureUnit(string $temperatureUnit): static
    {
        $this->temperatureUnit = $temperatureUnit;

        return $this;
    }

    public function getHumidityUnit(): ?string
    {
        return $this->humidityUnit;
    }

    public function setHumidityUnit(string $humidityUnit): static
    {
        $this->humidityUnit = $humidityUnit;

        return $this;
    }

    public function getPressureUnit(): ?string
    {
        return $this->pressureUnit;
    }

    public function setPressureUnit(string $pressureUnit): static
    {
        $this->pressureUnit = $pressureUnit;

        return $this;
    }

    public function getPlacement(): ?string
    {
        return $this->placement;
    }

    public function setPlacement(?string $placement): static
    {
        $this->placement = $placement;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getLowestTemp(): ?float
    {
        return $this->lowestTemp;
    }

    public function setLowestTemp(float $lowestTemp): static
    {
        $this->lowestTemp = $lowestTemp;

        return $this;
    }

    public function getHighestTemp(): ?float
    {
        return $this->highestTemp;
    }

    public function setHighestTemp(float $highestTemp): static
    {
        $this->highestTemp = $highestTemp;

        return $this;
    }

    public function getLowestHumidity(): ?float
    {
        return $this->lowestHumidity;
    }

    public function setLowestHumidity(float $lowestHumidity): static
    {
        $this->lowestHumidity = $lowestHumidity;

        return $this;
    }

    public function getHighestHumidity(): ?float
    {
        return $this->highestHumidity;
    }

    public function setHighestHumidity(float $highestHumidity): static
    {
        $this->highestHumidity = $highestHumidity;

        return $this;
    }

    public function getLowestPressure(): ?float
    {
        return $this->lowestPressure;
    }

    public function setLowestPressure(float $lowestPressure): static
    {
        $this->lowestPressure = $lowestPressure;

        return $this;
    }

    public function getHighestPressure(): ?float
    {
        return $this->highestPressure;
    }

    public function setHighestPressure(float $highestPressure): static
    {
        $this->highestPressure = $highestPressure;

        return $this;
    }

    public function getRssi(): ?float
    {
        return $this->rssi;
    }

    public function setRssi(?float $rssi): static
    {
        $this->rssi = $rssi;

        return $this;
    }

    public function getVoltage(): ?float
    {
        return $this->voltage;
    }

    public function setVoltage(float $voltage): static
    {
        $this->voltage = $voltage;

        return $this;
    }

    public function getWeatherStation(): ?WeatherStation
    {
        return $this->weatherStation;
    }

    public function setWeatherStation(?WeatherStation $weatherStation): static
    {
        $this->weatherStation = $weatherStation;

        return $this;
    }
}
