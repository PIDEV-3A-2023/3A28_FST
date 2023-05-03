<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503093220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shoppingcart CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenoom prenoom VARCHAR(255) NOT NULL, CHANGE ville ville VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(600) NOT NULL, CHANGE code_postale code_postale INT NOT NULL, CHANGE order_date order_date DATE NOT NULL, CHANGE total_price total_price DOUBLE PRECISION NOT NULL, CHANGE sta sta VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shoppingcart CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenoom prenoom VARCHAR(255) DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(600) DEFAULT NULL, CHANGE code_postale code_postale INT DEFAULT NULL, CHANGE order_date order_date DATE DEFAULT \'CURRENT_TIMESTAMP\', CHANGE total_price total_price DOUBLE PRECISION DEFAULT NULL, CHANGE sta sta VARCHAR(255) DEFAULT \'en cour\'');
    }
}
