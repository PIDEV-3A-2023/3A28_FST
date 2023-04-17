<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416133203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC4AEED04E');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC4AEED04E FOREIGN KEY (id_s_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE statut ADD image VARCHAR(255) DEFAULT NULL, ADD type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC4AEED04E');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC4AEED04E FOREIGN KEY (id_s_id) REFERENCES statut (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE statut DROP image, DROP type');
    }
}
