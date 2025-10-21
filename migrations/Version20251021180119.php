<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021180119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE backlog_item (id INT AUTO_INCREMENT NOT NULL, backlog_id_id INT NOT NULL, artist_id INT DEFAULT NULL, album_id INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_A157C9088B2E67EA (backlog_id_id), INDEX IDX_A157C908B7970CF8 (artist_id), INDEX IDX_A157C9081137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE backlog_item ADD CONSTRAINT FK_A157C9088B2E67EA FOREIGN KEY (backlog_id_id) REFERENCES backlog (id)');
        $this->addSql('ALTER TABLE backlog_item ADD CONSTRAINT FK_A157C908B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE backlog_item ADD CONSTRAINT FK_A157C9081137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE backlog CHANGE description description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backlog_item DROP FOREIGN KEY FK_A157C9088B2E67EA');
        $this->addSql('ALTER TABLE backlog_item DROP FOREIGN KEY FK_A157C908B7970CF8');
        $this->addSql('ALTER TABLE backlog_item DROP FOREIGN KEY FK_A157C9081137ABCF');
        $this->addSql('DROP TABLE backlog_item');
        $this->addSql('ALTER TABLE backlog CHANGE description description VARCHAR(255) DEFAULT NULL');
    }
}
