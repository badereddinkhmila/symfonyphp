<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200809102851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patientdata (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, tension INT DEFAULT NULL, oxygene INT DEFAULT NULL, glucose DOUBLE PRECISION DEFAULT NULL, poids DOUBLE PRECISION DEFAULT NULL, temperature DOUBLE PRECISION DEFAULT NULL, gatewayid VARCHAR(255) NOT NULL, INDEX IDX_A2FEC200A21BD112 (personne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patientdata ADD CONSTRAINT FK_A2FEC200A21BD112 FOREIGN KEY (personne_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE patientdata');
    }
}
