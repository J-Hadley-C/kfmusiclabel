<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102150807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artists ADD user_id INT DEFAULT NULL, CHANGE city city VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE artists ADD CONSTRAINT FK_68D3801EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_68D3801EA76ED395 ON artists (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artists DROP FOREIGN KEY FK_68D3801EA76ED395');
        $this->addSql('DROP INDEX IDX_68D3801EA76ED395 ON artists');
        $this->addSql('ALTER TABLE artists DROP user_id, CHANGE city city VARCHAR(50) NOT NULL');
    }
}
