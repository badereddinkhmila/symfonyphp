<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200720150337 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE randezvous (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, is_valid TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, gender VARCHAR(255) DEFAULT NULL, birthdate DATE DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_randezvous (user_id INT NOT NULL, randezvous_id INT NOT NULL, INDEX IDX_3A110C48A76ED395 (user_id), INDEX IDX_3A110C483043DBDC (randezvous_id), PRIMARY KEY(user_id, randezvous_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_randezvous ADD CONSTRAINT FK_3A110C48A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_randezvous ADD CONSTRAINT FK_3A110C483043DBDC FOREIGN KEY (randezvous_id) REFERENCES randezvous (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_randezvous DROP FOREIGN KEY FK_3A110C483043DBDC');
        $this->addSql('ALTER TABLE user_randezvous DROP FOREIGN KEY FK_3A110C48A76ED395');
        $this->addSql('DROP TABLE randezvous');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_randezvous');
    }
}
