<?php

namespace App\Command;

use App\Repository\WeatherStationRepository;
use App\Service\WeatherDataFetcherService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch-weather-data',
    description: 'Continuously fetches weather data for configured stations'
)]
class FetchWeatherDataCommand extends Command
{
    public function __construct(
        private WeatherStationRepository $weatherStationRepository,
        private WeatherDataFetcherService $weatherDataFetcherService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'interval',
            'i',
            InputOption::VALUE_OPTIONAL,
            'Interval between data fetches in seconds',
            300 // Default 5 minutes
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $interval = (int)$input->getOption('interval');

        // Fetch all weather stations
        $stations = $this->weatherStationRepository->findAll();

        if (empty($stations)) {
            $io->error('No weather stations configured.');
            return Command::FAILURE;
        }

        $io->success("Starting continuous weather data fetch. Press Ctrl+C to stop.");
        $io->note("Fetching data every {$interval} seconds.");

        // Infinite loop until manually stopped
        while (true) {
            $io->writeln("\n" . date('Y-m-d H:i:s') . " - Fetching weather data:");

            // Fetch data for each station
            foreach ($stations as $station) {
                try {
                    $measurement = $this->weatherDataFetcherService->fetchAndSaveMeasurement($station);

                    if ($measurement) {
                        $io->writeln("  ✓ Saved new data for station: {$station->getName()}");
                    } else {
                        $io->writeln("  ○ No new data for station: {$station->getName()}");
                    }
                } catch (\Exception $e) {
                    $io->error("  ✗ Error fetching data for station {$station->getName()}: " . $e->getMessage());
                }
            }

            // Sleep for the specified interval
            sleep($interval);
        }
    }
}