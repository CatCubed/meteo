<?php

namespace App\Repository;

use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @extends ServiceEntityRepository<Measurement>
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    /**
     * @return Measurement|null
     */
    public function getLatestMeasurements(): ?Measurement
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get measurements for the last N days
     *
     * @param int $days Number of days to retrieve data for
     * @return array
     */
    public function getMeasurementsForLastDays(int $days = 7): array
    {
        $date = new DateTime();
        $date->modify("-{$days} days");

        return $this->createQueryBuilder('m')
            ->where('m.createdAt >= :date')
            ->setParameter('date', $date)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}