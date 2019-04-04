<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404120843 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tournament_user (tournament_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BA1E647733D1A3E7 (tournament_id), INDEX IDX_BA1E6477A76ED395 (user_id), PRIMARY KEY(tournament_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament_user ADD CONSTRAINT FK_BA1E647733D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_user ADD CONSTRAINT FK_BA1E6477A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contest ADD player_id INT NOT NULL, ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE contest ADD CONSTRAINT FK_1A95CB599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE contest ADD CONSTRAINT FK_1A95CB5E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_1A95CB599E6F5DF ON contest (player_id)');
        $this->addSql('CREATE INDEX IDX_1A95CB5E48FD905 ON contest (game_id)');
        $this->addSql('ALTER TABLE game ADD tournament_id INT NOT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_232B318C33D1A3E7 ON game (tournament_id)');
        $this->addSql('ALTER TABLE team ADD tournament_id INT NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F33D1A3E7 ON team (tournament_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tournament_user');
        $this->addSql('ALTER TABLE contest DROP FOREIGN KEY FK_1A95CB599E6F5DF');
        $this->addSql('ALTER TABLE contest DROP FOREIGN KEY FK_1A95CB5E48FD905');
        $this->addSql('DROP INDEX IDX_1A95CB599E6F5DF ON contest');
        $this->addSql('DROP INDEX IDX_1A95CB5E48FD905 ON contest');
        $this->addSql('ALTER TABLE contest DROP player_id, DROP game_id');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C33D1A3E7');
        $this->addSql('DROP INDEX IDX_232B318C33D1A3E7 ON game');
        $this->addSql('ALTER TABLE game DROP tournament_id');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F33D1A3E7');
        $this->addSql('DROP INDEX IDX_C4E0A61F33D1A3E7 ON team');
        $this->addSql('ALTER TABLE team DROP tournament_id');
    }
}
