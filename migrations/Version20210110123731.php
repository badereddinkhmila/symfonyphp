<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210110123731 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blood_pressure (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, bp_value DOUBLE PRECISION NOT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('CREATE TABLE blood_sugar (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, b_sugar_value DOUBLE PRECISION NOT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('CREATE TABLE heart_beat (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, heart_beat_value DOUBLE PRECISION NOT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('CREATE TABLE oxygen_level (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, oxygen_level DOUBLE PRECISION NOT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('CREATE TABLE temperature (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, temperature_level DOUBLE PRECISION NOT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('CREATE TABLE weight (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, weight_value DOUBLE PRECISION NOT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('DROP TABLE rapid_rate_patient_data');
        $this->addSql('DROP TABLE slow_rate_patient_data');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE rapid_rate_patient_data (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, heart_beat DOUBLE PRECISION DEFAULT NULL, oxygen_level DOUBLE PRECISION DEFAULT NULL, temperature DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('CREATE TABLE slow_rate_patient_data (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, blood_sugar DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, blood_pressure DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('DROP TABLE blood_pressure');
        $this->addSql('DROP TABLE blood_sugar');
        $this->addSql('DROP TABLE heart_beat');
        $this->addSql('DROP TABLE oxygen_level');
        $this->addSql('DROP TABLE temperature');
        $this->addSql('DROP TABLE weight');
    }
}
