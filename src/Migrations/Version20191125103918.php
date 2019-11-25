<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191125103918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking ADD fk_cat_id INT NOT NULL, ADD fk_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEC603C1D3 FOREIGN KEY (fk_cat_id) REFERENCES cat (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E00CEDDEC603C1D3 ON booking (fk_cat_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE5741EEB9 ON booking (fk_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEC603C1D3');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE5741EEB9');
        $this->addSql('DROP INDEX UNIQ_E00CEDDEC603C1D3 ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDE5741EEB9 ON booking');
        $this->addSql('ALTER TABLE booking DROP fk_cat_id, DROP fk_user_id');
    }
}
