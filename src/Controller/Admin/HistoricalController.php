<?php

namespace App\Controller\Admin;

use App\Entity\Measurement;
use App\Repository\MeasurementRepository;
use App\Repository\WeatherStationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoricalController extends AbstractDashboardController
{
    private MeasurementRepository $measurementRepository;
    private WeatherStationRepository $weatherStationRepository;

    public function __construct(
        MeasurementRepository $measurementRepository,
        WeatherStationRepository $weatherStationRepository
    ) {
        $this->measurementRepository = $measurementRepository;
        $this->weatherStationRepository = $weatherStationRepository;
    }

    #[Route('/admin/historical', name: 'admin_historical')]
    public function index(): Response
    {
        // Get the weather stations
        $weatherStations = $this->weatherStationRepository->findAll();

        // Use the first weather station or create a default name
        $weatherStationName = count($weatherStations) > 0 ? $weatherStations[0]->getName() : 'Default Station';

        // Get today's and yesterday's data for comparison
        $todayData = $this->getDailyData(0);
        $yesterdayData = $this->getDailyData(1);

        // Get monthly data
        $monthlyData = $this->getMonthlyData();

        // Get yearly data
        $yearlyData = $this->getYearlyData();

        return $this->render('admin/historical_dashboard.html.twig', [
            'weather_station_name' => $weatherStationName,
            'today_data' => $todayData,
            'yesterday_data' => $yesterdayData,
            'monthly_data' => $monthlyData,
            'yearly_data' => $yearlyData
        ]);
    }

    private function formatDate(\DateTime $date, bool $includeDay = true): string
    {
        if ($includeDay) {
            // Format for today/yesterday with day name
            $today = new \DateTime('today');
            $yesterday = new \DateTime('yesterday');

            if ($date->format('Y-m-d') === $today->format('Y-m-d')) {
                return 'Today, ' . $date->format('F j');
            } elseif ($date->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                return 'Yesterday, ' . $date->format('F j');
            }
        }

        // Standard date formatting
        return $date->format('F j');
    }

    private function getDailyData(int $daysAgo): array
    {
        $date = new \DateTime();
        if ($daysAgo > 0) {
            $date->modify("-{$daysAgo} days");
        }

        $startOfDay = clone $date;
        $startOfDay->setTime(0, 0, 0);

        $endOfDay = clone $date;
        $endOfDay->setTime(23, 59, 59);

        $measurements = $this->measurementRepository->createQueryBuilder('m')
            ->where('m.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        // Calculate averages, min and max values
        $temperatureValues = [];
        $humidityValues = [];
        $pressureValues = [];
        $timeLabels = [];

        foreach ($measurements as $measurement) {
            $temperatureValues[] = $measurement->getTemperature();
            $humidityValues[] = $measurement->getHumidity();
            $pressureValues[] = $measurement->getPressure();
            $timeLabels[] = $measurement->getCreatedAt()->format('H:i');
        }

        return [
            'date' => $this->formatDate($date),
            'date_raw' => $date->format('Y-m-d'),
            'temperature' => [
                'values' => $temperatureValues,
                'avg' => !empty($temperatureValues) ? array_sum($temperatureValues) / count($temperatureValues) : 0,
                'min' => !empty($temperatureValues) ? min($temperatureValues) : 0,
                'max' => !empty($temperatureValues) ? max($temperatureValues) : 0,
            ],
            'humidity' => [
                'values' => $humidityValues,
                'avg' => !empty($humidityValues) ? array_sum($humidityValues) / count($humidityValues) : 0,
                'min' => !empty($humidityValues) ? min($humidityValues) : 0,
                'max' => !empty($humidityValues) ? max($humidityValues) : 0,
            ],
            'pressure' => [
                'values' => $pressureValues,
                'avg' => !empty($pressureValues) ? array_sum($pressureValues) / count($pressureValues) : 0,
                'min' => !empty($pressureValues) ? min($pressureValues) : 0,
                'max' => !empty($pressureValues) ? max($pressureValues) : 0,
            ],
            'time_labels' => $timeLabels,
            'count' => count($measurements),
        ];
    }

    private function getMonthlyData(): array
    {
        $startDate = new \DateTime('first day of this month');
        $startDate->setTime(0, 0, 0);

        $endDate = new \DateTime();

        return $this->getAggregatedData($startDate, $endDate, 'P1D', 'Y-m-d');
    }

    private function getYearlyData(): array
    {
        $startDate = new \DateTime('first day of January this year');
        $startDate->setTime(0, 0, 0);

        $endDate = new \DateTime();

        return $this->getAggregatedData($startDate, $endDate, 'P1M', 'Y-m');
    }

    private function getAggregatedData(\DateTime $startDate, \DateTime $endDate, string $interval, string $format): array
    {
        $measurements = $this->measurementRepository->createQueryBuilder('m')
            ->where('m.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        // Group measurements by period
        $groupedData = [];

        foreach ($measurements as $measurement) {
            $periodKey = $measurement->getCreatedAt()->format($format);

            if (!isset($groupedData[$periodKey])) {
                $groupedData[$periodKey] = [
                    'temperature' => [],
                    'humidity' => [],
                    'pressure' => [],
                ];
            }

            $groupedData[$periodKey]['temperature'][] = $measurement->getTemperature();
            $groupedData[$periodKey]['humidity'][] = $measurement->getHumidity();
            $groupedData[$periodKey]['pressure'][] = $measurement->getPressure();
        }

        // Calculate averages for each period
        $result = [
            'labels' => [],
            'temperature' => [],
            'humidity' => [],
            'pressure' => [],
        ];

        foreach ($groupedData as $periodKey => $data) {
            $result['labels'][] = $this->formatPeriodLabel($periodKey, $format);
            $result['temperature'][] = !empty($data['temperature']) ? array_sum($data['temperature']) / count($data['temperature']) : 0;
            $result['humidity'][] = !empty($data['humidity']) ? array_sum($data['humidity']) / count($data['humidity']) : 0;
            $result['pressure'][] = !empty($data['pressure']) ? array_sum($data['pressure']) / count($data['pressure']) : 0;
        }

        return $result;
    }

    private function formatPeriodLabel(string $key, string $format): string
    {
        if ($format === 'Y-m-d') {
            // For daily format
            $date = \DateTime::createFromFormat('Y-m-d', $key);
            return $this->formatDate($date, true);
        } elseif ($format === 'Y-m') {
            // For monthly format
            $date = \DateTime::createFromFormat('Y-m', $key);
            return $date->format('F Y');
        }

        return $key;
    }
}