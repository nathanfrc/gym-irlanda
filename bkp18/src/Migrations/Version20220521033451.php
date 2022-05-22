<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220521033451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE check_workouts DROP FOREIGN KEY FK_489FDF8E217BBB47');
        $this->addSql('DROP INDEX IDX_489FDF8E217BBB47 ON check_workouts');
        $this->addSql('ALTER TABLE check_workouts DROP person_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE check_workouts ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE check_workouts ADD CONSTRAINT FK_489FDF8E217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_489FDF8E217BBB47 ON check_workouts (person_id)');
    }
}
