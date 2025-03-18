<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250318162145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove unused columns from Measurement table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement DROP temperature_unit');
        $this->addSql('ALTER TABLE measurement DROP humidity_unit');
        $this->addSql('ALTER TABLE measurement DROP pressure_unit');
        $this->addSql('ALTER TABLE measurement DROP placement');
        $this->addSql('ALTER TABLE measurement DROP name');
        $this->addSql('ALTER TABLE measurement DROP domain');
        $this->addSql('ALTER TABLE measurement DROP lowest_temp');
        $this->addSql('ALTER TABLE measurement DROP highest_temp');
        $this->addSql('ALTER TABLE measurement DROP lowest_humidity');
        $this->addSql('ALTER TABLE measurement DROP highest_humidity');
        $this->addSql('ALTER TABLE measurement DROP lowest_pressure');
        $this->addSql('ALTER TABLE measurement DROP highest_pressure');
        $this->addSql('ALTER TABLE measurement DROP rssi');
        $this->addSql('ALTER TABLE measurement DROP voltage');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement ADD temperature_unit VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD humidity_unit VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD pressure_unit VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD placement VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE measurement ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD domain VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD lowest_temp DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD highest_temp DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD lowest_humidity DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD highest_humidity DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD lowest_pressure DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD highest_pressure DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD rssi DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE measurement ADD voltage DOUBLE PRECISION NOT NULL');
    }
}
