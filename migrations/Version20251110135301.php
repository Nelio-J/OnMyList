<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251110135301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_269205989D9B62 ON backlog');
        $this->addSql('ALTER TABLE backlog_item DROP FOREIGN KEY FK_A157C908F1F06ABE');
        $this->addSql('ALTER TABLE backlog_item ADD date_added DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE backlog_item ADD CONSTRAINT FK_A157C908F1F06ABE FOREIGN KEY (backlog_id) REFERENCES backlog (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_269205989D9B62 ON backlog (slug)');
        $this->addSql('ALTER TABLE backlog_item DROP FOREIGN KEY FK_A157C908F1F06ABE');
        $this->addSql('ALTER TABLE backlog_item DROP date_added');
        $this->addSql('ALTER TABLE backlog_item ADD CONSTRAINT FK_A157C908F1F06ABE FOREIGN KEY (backlog_id) REFERENCES backlog (id)');
    }
}
