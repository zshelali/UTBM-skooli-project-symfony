<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426094836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE post ADD id_ue_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D8C6DC281 FOREIGN KEY (id_ue_id) REFERENCES ue (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A8A6C8D8C6DC281 ON post (id_ue_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D8C6DC281
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5A8A6C8D8C6DC281
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post DROP id_ue_id
        SQL);
    }
}
