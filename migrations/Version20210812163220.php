<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812163220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE paquet_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE paquet_request (id INT NOT NULL, issuer_id INT NOT NULL, gateway BOOLEAN NOT NULL, glucose_sensor BOOLEAN NOT NULL, oxygen_sensor BOOLEAN NOT NULL, blood_pressure_sensor BOOLEAN NOT NULL, temperature BOOLEAN NOT NULL, weight BOOLEAN NOT NULL, approved BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68571615BB9D6FEE ON paquet_request (issuer_id)');
        $this->addSql('ALTER TABLE paquet_request ADD CONSTRAINT FK_68571615BB9D6FEE FOREIGN KEY (issuer_id) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_users ADD ever_married BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE app_users ADD ever_smoked BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE app_users ADD active BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE paquet_request_id_seq CASCADE');
        $this->addSql('DROP TABLE paquet_request');
        $this->addSql('ALTER TABLE app_users DROP ever_married');
        $this->addSql('ALTER TABLE app_users DROP ever_smoked');
        $this->addSql('ALTER TABLE app_users DROP active');
    }
}
