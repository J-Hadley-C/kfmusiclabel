<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105210152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collaboration CHANGE created_at createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE collaboration RENAME INDEX idx_da3ae3237db3b714 TO IDX_6944A8D67DB3B714');
        $this->addSql('ALTER TABLE follow CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE follow RENAME INDEX idx_68344470b7970cf8 TO IDX_6F984146B7970CF8');
        $this->addSql('ALTER TABLE music CHANGE file_path filePath VARCHAR(255) NOT NULL, CHANGE likes_count likesCount INT DEFAULT 0 NOT NULL, CHANGE created_at createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE music RENAME INDEX idx_cd52224ab7970cf8 TO IDX_C930D4EB7970CF8');
        $this->addSql('ALTER TABLE user CHANGE is_verified isVerified TINYINT(1) NOT NULL, CHANGE verification_token verificationToken VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_2DA17977E7927C74');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649b7970cf8 TO UNIQ_2DA17977B7970CF8');
        $this->addSql('ALTER TABLE artists CHANGE style_vocal styleVocal VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artists CHANGE styleVocal style_vocal VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Music CHANGE filePath file_path VARCHAR(255) NOT NULL, CHANGE likesCount likes_count INT DEFAULT 0 NOT NULL, CHANGE createdAt created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Music RENAME INDEX idx_c930d4eb7970cf8 TO IDX_CD52224AB7970CF8');
        $this->addSql('ALTER TABLE Follow CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE Follow RENAME INDEX idx_6f984146b7970cf8 TO IDX_68344470B7970CF8');
        $this->addSql('ALTER TABLE Collaboration CHANGE createdAt created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Collaboration RENAME INDEX idx_6944a8d67db3b714 TO IDX_DA3AE3237DB3B714');
        $this->addSql('ALTER TABLE User CHANGE isVerified is_verified TINYINT(1) NOT NULL, CHANGE verificationToken verification_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE User RENAME INDEX uniq_2da17977b7970cf8 TO UNIQ_8D93D649B7970CF8');
        $this->addSql('ALTER TABLE User RENAME INDEX uniq_2da17977e7927c74 TO UNIQ_8D93D649E7927C74');
    }
}
