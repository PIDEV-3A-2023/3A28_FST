<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230424130648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartitem ADD total DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE shoppingcart ADD CONSTRAINT FK_932C7444A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_932C7444A76ED395 ON shoppingcart (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartitem DROP total');
        $this->addSql('ALTER TABLE shoppingcart DROP FOREIGN KEY FK_932C7444A76ED395');
        $this->addSql('DROP INDEX UNIQ_932C7444A76ED395 ON shoppingcart');
    }
}
