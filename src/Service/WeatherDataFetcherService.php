<?php

namespace App\Service;

use App\Entity\Measurement;
use App\Entity\WeatherStation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherDataFetcherService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $entityManager
    ) {}

    public function fetchAndSaveMeasurement(WeatherStation $station): ?Measurement
    {
        try {
            // Construct the URL with the station's key
            $url = "https://tmep.cz/vystup-json.php?id=9008&export_key={$station->getKey()}&extended=1";

            // Fetch the data
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();

            // Parse the date string to a DateTime object
            $createdAt = \DateTime::createFromFormat('Y-m-d H:i:s', $data['cas']);

            // Check if a measurement with this timestamp already exists
            $existingMeasurement = $this->entityManager->getRepository(Measurement::class)
                ->findOneBy([
                    'weatherStation' => $station,
                    'createdAt' => $createdAt
                ]);

            // If measurement already exists, return null
            if ($existingMeasurement) {
                return null;
            }

            // Create a new Measurement entity
            $measurement = new Measurement();
            $measurement->setTemperature((float)$data['teplota']);
            $measurement->setHumidity((float)$data['vlhkost']);
            $measurement->setPressure((float)$data['tlak']);

            $measurement->setCreatedAt($createdAt);

            // Associate with the weather station
            $measurement->setWeatherStation($station);

            // Persist the measurement
            $this->entityManager->persist($measurement);
            $this->entityManager->flush();

            return $measurement;
        } catch (\Exception $e) {
            // In a real-world scenario, you'd want to log this error
            throw $e; // Re-throw to be caught in the command
        }
    }
}