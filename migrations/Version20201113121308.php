<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113121308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE patientdata_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE randezvous_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE patientdata (id INT NOT NULL, personne_id INT NOT NULL, tension INT DEFAULT NULL, oxygene INT DEFAULT NULL, glucose DOUBLE PRECISION DEFAULT NULL, poids DOUBLE PRECISION DEFAULT NULL, temperature DOUBLE PRECISION DEFAULT NULL, gatewayid VARCHAR(255) NOT NULL, longitude VARCHAR(255) DEFAULT NULL, aptitude VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A2FEC200A21BD112 ON patientdata (personne_id)');
        $this->addSql('CREATE TABLE randezvous (id INT NOT NULL, description TEXT NOT NULL, is_valid BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, dated_for TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, color VARCHAR(255) NOT NULL, end_in TIME(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE randezvous_user (randezvous_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(randezvous_id, user_id))');
        $this->addSql('CREATE INDEX IDX_D87271D33043DBDC ON randezvous_user (randezvous_id)');
        $this->addSql('CREATE INDEX IDX_D87271D3A76ED395 ON randezvous_user (user_id)');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(role_id, user_id))');
        $this->addSql('CREATE INDEX IDX_332CA4DDD60322AC ON role_user (role_id)');
        $this->addSql('CREATE INDEX IDX_332CA4DDA76ED395 ON role_user (user_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, gender VARCHAR(255) DEFAULT NULL, birthdate DATE DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, is_doctor BOOLEAN DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE patients (doctor_id INT NOT NULL, patient_id INT NOT NULL, PRIMARY KEY(doctor_id, patient_id))');
        $this->addSql('CREATE INDEX IDX_2CCC2E2C87F4FB17 ON patients (doctor_id)');
        $this->addSql('CREATE INDEX IDX_2CCC2E2C6B899279 ON patients (patient_id)');
        $this->addSql('ALTER TABLE patientdata ADD CONSTRAINT FK_A2FEC200A21BD112 FOREIGN KEY (personne_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE randezvous_user ADD CONSTRAINT FK_D87271D33043DBDC FOREIGN KEY (randezvous_id) REFERENCES randezvous (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE randezvous_user ADD CONSTRAINT FK_D87271D3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patients ADD CONSTRAINT FK_2CCC2E2C87F4FB17 FOREIGN KEY (doctor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patients ADD CONSTRAINT FK_2CCC2E2C6B899279 FOREIGN KEY (patient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE randezvous_user DROP CONSTRAINT FK_D87271D33043DBDC');
        $this->addSql('ALTER TABLE role_user DROP CONSTRAINT FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE patientdata DROP CONSTRAINT FK_A2FEC200A21BD112');
        $this->addSql('ALTER TABLE randezvous_user DROP CONSTRAINT FK_D87271D3A76ED395');
        $this->addSql('ALTER TABLE role_user DROP CONSTRAINT FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE patients DROP CONSTRAINT FK_2CCC2E2C87F4FB17');
        $this->addSql('ALTER TABLE patients DROP CONSTRAINT FK_2CCC2E2C6B899279');
        $this->addSql('DROP SEQUENCE patientdata_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE randezvous_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE patientdata');
        $this->addSql('DROP TABLE randezvous');
        $this->addSql('DROP TABLE randezvous_user');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE patients');
    }
}
