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
        return $this->render('admin/admin_dashboard.html.twig', [
            'weatherStations' => $this->weatherStationRepository->findAll(),
            'measurements' => $this->measurementRepository->findAll(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Weather Station Monitoring');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Data');
        yield MenuItem::linkToCrud('Weather Stations', 'fa fa-satellite-dish', WeatherStation::class);
        yield MenuItem::linkToCrud('Measurements', 'fa fa-thermometer-half', Measurement::class);
    }
}
