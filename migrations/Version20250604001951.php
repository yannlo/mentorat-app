<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604001951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_stage ADD mentor_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_stage ADD CONSTRAINT FK_3634FEACDB403044 FOREIGN KEY (mentor_id) REFERENCES mentor (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3634FEACDB403044 ON academic_stage (mentor_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE certificate ADD mentor_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4ADB403044 FOREIGN KEY (mentor_id) REFERENCES mentor (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_219CDA4ADB403044 ON certificate (mentor_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4ADB403044
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_219CDA4ADB403044 ON certificate
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE certificate DROP mentor_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_stage DROP FOREIGN KEY FK_3634FEACDB403044
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3634FEACDB403044 ON academic_stage
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_stage DROP mentor_id
        SQL);
    }
}
