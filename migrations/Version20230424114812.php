<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230424114812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, tel INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE remise CHANGE nb nb INT NOT NULL');
        $this->addSql('ALTER TABLE shoppingcart ADD user_id INT NOT NULL, CHANGE sta sta VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE shoppingcart ADD CONSTRAINT FK_932C7444A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_932C7444A76ED395 ON shoppingcart (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shoppingcart DROP FOREIGN KEY FK_932C7444A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE remise CHANGE nb nb INT DEFAULT 0 NOT NULL');
        $this->addSql('DROP INDEX UNIQ_932C7444A76ED395 ON shoppingcart');
        $this->addSql('ALTER TABLE shoppingcart DROP user_id, CHANGE sta sta VARCHAR(255) DEFAULT \'en cour\' NOT NULL');
    }
}
