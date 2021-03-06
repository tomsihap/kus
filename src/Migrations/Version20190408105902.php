<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408105902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player ADD tournament_id INT NOT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6533D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_98197A6533D1A3E7 ON player (tournament_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6533D1A3E7');
        $this->addSql('DROP INDEX IDX_98197A6533D1A3E7 ON player');
        $this->addSql('ALTER TABLE player DROP tournament_id');
    }
}
