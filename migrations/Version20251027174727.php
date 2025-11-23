<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027174727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backlog ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_269205D17F50A6 ON backlog (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_269205989D9B62 ON backlog (slug)');
        $this->addSql('DROP INDEX uniq_backlog_artist ON backlog_item');
        $this->addSql('DROP INDEX uniq_backlog_album ON backlog_item');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_269205D17F50A6 ON backlog');
        $this->addSql('DROP INDEX UNIQ_269205989D9B62 ON backlog');
        $this->addSql('ALTER TABLE backlog DROP uuid, DROP slug');
        $this->addSql('CREATE UNIQUE INDEX uniq_backlog_artist ON backlog_item (backlog_id, type, artist_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_backlog_album ON backlog_item (backlog_id, type, album_id)');
    }
}
