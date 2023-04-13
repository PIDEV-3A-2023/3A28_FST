<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408150942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, rating DOUBLE PRECISION DEFAULT NULL, titre VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, categorie VARCHAR(255) DEFAULT NULL, nb_place INT NOT NULL, rating_number INT DEFAULT NULL, points VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, id_ev_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_D2294458AE21AD72 (id_ev_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resevation (id INT AUTO_INCREMENT NOT NULL, event_id_id INT DEFAULT NULL, INDEX IDX_6E8E407B3E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458AE21AD72 FOREIGN KEY (id_ev_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE resevation ADD CONSTRAINT FK_6E8E407B3E5F2F7B FOREIGN KEY (event_id_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458AE21AD72');
        $this->addSql('ALTER TABLE resevation DROP FOREIGN KEY FK_6E8E407B3E5F2F7B');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE resevation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
