<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416110620 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contest ADD loser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contest ADD CONSTRAINT FK_1A95CB51BCAA5F6 FOREIGN KEY (loser_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_1A95CB51BCAA5F6 ON contest (loser_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contest DROP FOREIGN KEY FK_1A95CB51BCAA5F6');
        $this->addSql('DROP INDEX IDX_1A95CB51BCAA5F6 ON contest');
        $this->addSql('ALTER TABLE contest DROP loser_id');
    }
}
