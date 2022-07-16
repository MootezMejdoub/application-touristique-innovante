<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220412134142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog CHANGE title title VARCHAR(255) DEFAULT \'NULL\', CHANGE description description VARCHAR(255) DEFAULT \'NULL\', CHANGE date date DATE DEFAULT \'NULL\', CHANGE photo photo VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reclamation CHANGE description description VARCHAR(255) DEFAULT \'NULL\', CHANGE date date DATE DEFAULT \'NULL\', CHANGE rec_reference rec_reference VARCHAR(255) DEFAULT \'NULL\', CHANGE etat etat VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reponse CHANGE rec_reference rec_reference VARCHAR(255) DEFAULT \'NULL\', CHANGE datecreation datecreation DATE DEFAULT \'NULL\', CHANGE message message VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE type type VARCHAR(20) DEFAULT \'NULL\', CHANGE description description VARCHAR(20) DEFAULT \'NULL\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE rec_reference rec_reference VARCHAR(255) DEFAULT NULL, CHANGE etat etat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE rec_reference rec_reference VARCHAR(255) DEFAULT NULL, CHANGE datecreation datecreation DATE DEFAULT NULL, CHANGE message message VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE type type VARCHAR(20) DEFAULT NULL, CHANGE description description VARCHAR(20) DEFAULT NULL');
    }
}
