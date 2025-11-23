<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021180334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backlog_item DROP FOREIGN KEY FK_A157C9088B2E67EA');
        $this->addSql('DROP INDEX IDX_A157C9088B2E67EA ON backlog_item');
        $this->addSql('ALTER TABLE backlog_item CHANGE backlog_id_id backlog_id INT NOT NULL');
        $this->addSql('ALTER TABLE backlog_item ADD CONSTRAINT FK_A157C908F1F06ABE FOREIGN KEY (backlog_id) REFERENCES backlog (id)');
        $this->addSql('CREATE INDEX IDX_A157C908F1F06ABE ON backlog_item (backlog_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backlog_item DROP FOREIGN KEY FK_A157C908F1F06ABE');
        $this->addSql('DROP INDEX IDX_A157C908F1F06ABE ON backlog_item');
        $this->addSql('ALTER TABLE backlog_item CHANGE backlog_id backlog_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE backlog_item ADD CONSTRAINT FK_A157C9088B2E67EA FOREIGN KEY (backlog_id_id) REFERENCES backlog (id)');
        $this->addSql('CREATE INDEX IDX_A157C9088B2E67EA ON backlog_item (backlog_id_id)');
    }
}
