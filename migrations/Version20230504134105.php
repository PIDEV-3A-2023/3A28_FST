<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504134105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cartitem (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, panier_id INT NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_62602FA7F347EFB (produit_id), INDEX IDX_62602FA7F77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE remise (code INT NOT NULL, nom VARCHAR(255) NOT NULL, nb INT NOT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shoppingcart (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenoom VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, adresse VARCHAR(600) NOT NULL, code_postale INT NOT NULL, order_date DATE NOT NULL, total_price DOUBLE PRECISION NOT NULL, sta VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_932C7444A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cartitem ADD CONSTRAINT FK_62602FA7F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE cartitem ADD CONSTRAINT FK_62602FA7F77D927C FOREIGN KEY (panier_id) REFERENCES shoppingcart (id)');
        $this->addSql('ALTER TABLE shoppingcart ADD CONSTRAINT FK_932C7444A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE reset_token reset_token VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartitem DROP FOREIGN KEY FK_62602FA7F347EFB');
        $this->addSql('ALTER TABLE cartitem DROP FOREIGN KEY FK_62602FA7F77D927C');
        $this->addSql('ALTER TABLE shoppingcart DROP FOREIGN KEY FK_932C7444A76ED395');
        $this->addSql('DROP TABLE cartitem');
        $this->addSql('DROP TABLE remise');
        $this->addSql('DROP TABLE shoppingcart');
        $this->addSql('ALTER TABLE user CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL');
    }
}
