<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503101419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_workshop (id INT AUTO_INCREMENT NOT NULL, workshops_id INT DEFAULT NULL, categorie VARCHAR(255) NOT NULL, date_reservation DATE NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, userid INT NOT NULL, INDEX IDX_6DAE0D0BEDD5CD7 (workshops_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workshop (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, nom_artiste VARCHAR(255) NOT NULL, duree INT NOT NULL, date DATE NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, nb_places INT NOT NULL, categorie VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, niveau VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, mots_cles VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, userid INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation_workshop ADD CONSTRAINT FK_6DAE0D0BEDD5CD7 FOREIGN KEY (workshops_id) REFERENCES workshop (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_workshop DROP FOREIGN KEY FK_6DAE0D0BEDD5CD7');
        $this->addSql('DROP TABLE reservation_workshop');
        $this->addSql('DROP TABLE workshop');
    }
}
