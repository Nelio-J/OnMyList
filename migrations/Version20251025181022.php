<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251025181022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // MySQL/MariaDB
        $this->addSql('ALTER TABLE backlog_item ADD UNIQUE INDEX uniq_backlog_album (backlog_id, `type`, album_id)');
        $this->addSql('ALTER TABLE backlog_item ADD UNIQUE INDEX uniq_backlog_artist (backlog_id, `type`, artist_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_backlog_album ON backlog_item');
        $this->addSql('DROP INDEX uniq_backlog_artist ON backlog_item');
    }
}
