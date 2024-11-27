<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113152327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial Database creation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fleet (id UUID NOT NULL, user_id_id UUID NOT NULL, label VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A05E1E479D86650F ON fleet (user_id_id)');
        $this->addSql('COMMENT ON COLUMN fleet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fleet.user_id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fleet.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN fleet.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE location (id UUID NOT NULL, ressource_id_id UUID NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, place_number INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E9E89CBEBD01AD3 ON location (ressource_id_id)');
        $this->addSql('COMMENT ON COLUMN location.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN location.ressource_id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN location.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN location.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ressource (id UUID NOT NULL, fleet_id_id UUID NOT NULL, plate_number VARCHAR(40) NOT NULL, mode VARCHAR(40) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_939F4544CA5B440D ON ressource (fleet_id_id)');
        $this->addSql('COMMENT ON COLUMN ressource.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ressource.fleet_id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ressource.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ressource.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(180) NOT NULL, email VARCHAR(255) NOT NULL, company VARCHAR(180) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE fleet ADD CONSTRAINT FK_A05E1E479D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBEBD01AD3 FOREIGN KEY (ressource_id_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544CA5B440D FOREIGN KEY (fleet_id_id) REFERENCES fleet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fleet DROP CONSTRAINT FK_A05E1E479D86650F');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CBEBD01AD3');
        $this->addSql('ALTER TABLE ressource DROP CONSTRAINT FK_939F4544CA5B440D');
        $this->addSql('DROP TABLE fleet');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE "user"');
    }
}
