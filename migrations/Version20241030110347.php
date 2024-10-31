<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030110347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE musicien (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, producteur_id INT DEFAULT NULL, beatmaker_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, instrument VARCHAR(100) NOT NULL, genre_musical VARCHAR(100) NOT NULL, photo VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, city VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_551D8423E7927C74 (email), UNIQUE INDEX UNIQ_551D8423A76ED395 (user_id), INDEX IDX_551D8423AB9BB300 (producteur_id), INDEX IDX_551D8423AD72BF (beatmaker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE musicien_collaborations (musicien_source INT NOT NULL, musicien_target INT NOT NULL, INDEX IDX_315E06B3B96E419C (musicien_source), INDEX IDX_315E06B3A08B1113 (musicien_target), PRIMARY KEY(musicien_source, musicien_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE musicien ADD CONSTRAINT FK_551D8423A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE musicien ADD CONSTRAINT FK_551D8423AB9BB300 FOREIGN KEY (producteur_id) REFERENCES producteur (id)');
        $this->addSql('ALTER TABLE musicien ADD CONSTRAINT FK_551D8423AD72BF FOREIGN KEY (beatmaker_id) REFERENCES beatmaker (id)');
        $this->addSql('ALTER TABLE musicien_collaborations ADD CONSTRAINT FK_315E06B3B96E419C FOREIGN KEY (musicien_source) REFERENCES musicien (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE musicien_collaborations ADD CONSTRAINT FK_315E06B3A08B1113 FOREIGN KEY (musicien_target) REFERENCES musicien (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musicien DROP FOREIGN KEY FK_551D8423A76ED395');
        $this->addSql('ALTER TABLE musicien DROP FOREIGN KEY FK_551D8423AB9BB300');
        $this->addSql('ALTER TABLE musicien DROP FOREIGN KEY FK_551D8423AD72BF');
        $this->addSql('ALTER TABLE musicien_collaborations DROP FOREIGN KEY FK_315E06B3B96E419C');
        $this->addSql('ALTER TABLE musicien_collaborations DROP FOREIGN KEY FK_315E06B3A08B1113');
        $this->addSql('DROP TABLE musicien');
        $this->addSql('DROP TABLE musicien_collaborations');
    }
}
