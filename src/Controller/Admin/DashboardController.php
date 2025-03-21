<?php

namespace App\Controller\Admin;

use App\Entity\Measurement;
use App\Entity\WeatherStation;
use App\Repository\MeasurementRepository;
use App\Repository\WeatherStationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    function __construct(
        private WeatherStationRepository $weatherStationRepository,
        private MeasurementRepository $measurementRepository,
    )
    {
    }

    public function index(): Response
    {
        // Get the weather stations
        $weatherStations = $this->weatherStationRepository->findAll();

        // Use the first weather station or create a default name
        $weatherStationName = count($weatherStations) > 0 ? $weatherStations[0]->getName() : 'Default Station';

        // Get latest measurements
        $latestMeasurement = $this->measurementRepository->getLatestMeasurements();

        // Get historical data for charts
        $historicalData = $this->getHistoricalData();

        return $this->render('admin/admin_dashboard.html.twig', [
            'weatherStations' => $weatherStations,
            'weather_station_name' => $weatherStationName,
            'latest_measurement' => $latestMeasurement ? [
                'temperature' => $latestMeasurement->getTemperature(),
                'humidity' => $latestMeasurement->getHumidity(),
                'pressure' => $latestMeasurement->getPressure(),
                'createdAt' => $latestMeasurement->getCreatedAt()->format('Y-m-d H:i:s'),
            ] : null,
            'temperature_data' => $historicalData['temperature'],
            'humidity_data' => $historicalData['humidity'],
            'pressure_data' => $historicalData['pressure'],
        ]);
    }

    private function getHistoricalData(): array
    {
        // Get measurements for the last 7 days
        $measurements = $this->measurementRepository->getMeasurementsForLastDays(7);

        $temperatureData = [];
        $humidityData = [];
        $pressureData = [];

        foreach ($measurements as $measurement) {
            $timestamp = $measurement->getCreatedAt()->getTimestamp() * 1000; // Convert to milliseconds for ApexCharts

            $temperatureData[] = [
                'x' => $timestamp,
                'y' => $measurement->getTemperature()
            ];

            $humidityData[] = [
                'x' => $timestamp,
                'y' => $measurement->getHumidity()
            ];

            $pressureData[] = [
                'x' => $timestamp,
                'y' => $measurement->getPressure()
            ];
        }

        return [
            'temperature' => $temperatureData,
            'humidity' => $humidityData,
            'pressure' => $pressureData
        ];
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Weather Station Monitoring');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToRoute('Historical Data', 'fa fa-chart-line', 'admin_historical');

        yield MenuItem::section('Data');
        yield MenuItem::linkToCrud('Weather Stations', 'fa fa-satellite-dish', WeatherStation::class);
        yield MenuItem::linkToCrud('Measurements', 'fa fa-thermometer-half', Measurement::class);
    }
}