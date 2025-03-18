<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250318163240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop updated_at column from Measurement table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement DROP updated_at');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }
}
