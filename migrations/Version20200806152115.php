<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806152115 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE randezvous_user (randezvous_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D87271D33043DBDC (randezvous_id), INDEX IDX_D87271D3A76ED395 (user_id), PRIMARY KEY(randezvous_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE randezvous_user ADD CONSTRAINT FK_D87271D33043DBDC FOREIGN KEY (randezvous_id) REFERENCES randezvous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE randezvous_user ADD CONSTRAINT FK_D87271D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_randezvous');
        $this->addSql('ALTER TABLE randezvous ADD created_at DATETIME NOT NULL, ADD dated_for DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD address VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_randezvous (user_id INT NOT NULL, randezvous_id INT NOT NULL, INDEX IDX_3A110C483043DBDC (randezvous_id), INDEX IDX_3A110C48A76ED395 (user_id), PRIMARY KEY(user_id, randezvous_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_randezvous ADD CONSTRAINT FK_3A110C483043DBDC FOREIGN KEY (randezvous_id) REFERENCES randezvous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_randezvous ADD CONSTRAINT FK_3A110C48A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE randezvous_user');
        $this->addSql('ALTER TABLE randezvous DROP created_at, DROP dated_for');
        $this->addSql('ALTER TABLE user DROP address, DROP phone');
    }
}
