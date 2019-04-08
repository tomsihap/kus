<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408104125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contest ADD tournament_id INT NOT NULL');
        $this->addSql('ALTER TABLE contest ADD CONSTRAINT FK_1A95CB533D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_1A95CB533D1A3E7 ON contest (tournament_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contest DROP FOREIGN KEY FK_1A95CB533D1A3E7');
        $this->addSql('DROP INDEX IDX_1A95CB533D1A3E7 ON contest');
        $this->addSql('ALTER TABLE contest DROP tournament_id');
    }
}
