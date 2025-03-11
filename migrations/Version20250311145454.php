<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250311145454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create MessengerMessages, Measurement and WeatherStation tables.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE measurement (id SERIAL NOT NULL, weather_station_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, temperature DOUBLE PRECISION NOT NULL, humidity DOUBLE PRECISION NOT NULL, pressure DOUBLE PRECISION NOT NULL, temperature_unit VARCHAR(255) NOT NULL, humidity_unit VARCHAR(255) NOT NULL, pressure_unit VARCHAR(255) NOT NULL, placement VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, domain VARCHAR(255) NOT NULL, lowest_temp DOUBLE PRECISION NOT NULL, highest_temp DOUBLE PRECISION NOT NULL, lowest_humidity DOUBLE PRECISION NOT NULL, highest_humidity DOUBLE PRECISION NOT NULL, lowest_pressure DOUBLE PRECISION NOT NULL, highest_pressure DOUBLE PRECISION NOT NULL, rssi DOUBLE PRECISION DEFAULT NULL, voltage DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2CE0D8119E475DA2 ON measurement (weather_station_id)');
        $this->addSql('CREATE TABLE weather_station (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, key VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D8119E475DA2 FOREIGN KEY (weather_station_id) REFERENCES weather_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement DROP CONSTRAINT FK_2CE0D8119E475DA2');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('DROP TABLE weather_station');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
