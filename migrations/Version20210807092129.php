<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210807092129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE heart_beat');
        $this->addSql('ALTER TABLE blood_pressure ADD pulse DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE blood_pressure ADD systolic DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE blood_pressure RENAME COLUMN bp_value TO diastolic');
        $this->addSql('ALTER TABLE blood_sugar ADD mmol_l DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE blood_sugar RENAME COLUMN b_sugar_value TO mg_dl');
        $this->addSql('ALTER TABLE oxygen_level ADD spo2 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE oxygen_level RENAME COLUMN oxygen_level TO pulse');
        $this->addSql('ALTER TABLE temperature RENAME COLUMN temperature_level TO temperature');
        $this->addSql('ALTER TABLE weight ADD bodyfat DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE weight ADD weight DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE weight RENAME COLUMN weight_value TO bmi');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE heart_beat (device_id VARCHAR(255) NOT NULL, collect_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, heart_beat_value DOUBLE PRECISION NOT NULL, PRIMARY KEY(device_id, collect_time))');
        $this->addSql('ALTER TABLE blood_pressure ADD bp_value DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE blood_pressure DROP diastolic');
        $this->addSql('ALTER TABLE blood_pressure DROP pulse');
        $this->addSql('ALTER TABLE blood_pressure DROP systolic');
        $this->addSql('ALTER TABLE blood_sugar ADD b_sugar_value DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE blood_sugar DROP mg_dl');
        $this->addSql('ALTER TABLE blood_sugar DROP mmol_l');
        $this->addSql('ALTER TABLE oxygen_level ADD oxygen_level DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE oxygen_level DROP pulse');
        $this->addSql('ALTER TABLE oxygen_level DROP spo2');
        $this->addSql('ALTER TABLE temperature RENAME COLUMN temperature TO temperature_level');
        $this->addSql('ALTER TABLE weight ADD weight_value DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE weight DROP bmi');
        $this->addSql('ALTER TABLE weight DROP bodyfat');
        $this->addSql('ALTER TABLE weight DROP weight');
    }
}
