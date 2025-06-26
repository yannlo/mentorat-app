<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618162409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, mentor_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_E5A02990DB403044 (mentor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE time_period (id INT AUTO_INCREMENT NOT NULL, day_id INT NOT NULL, start INT NOT NULL, end INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_9C4E51389C24126 (day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE day ADD CONSTRAINT FK_E5A02990DB403044 FOREIGN KEY (mentor_id) REFERENCES mentor (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE time_period ADD CONSTRAINT FK_9C4E51389C24126 FOREIGN KEY (day_id) REFERENCES day (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mentor ADD price INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE day DROP FOREIGN KEY FK_E5A02990DB403044
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE time_period DROP FOREIGN KEY FK_9C4E51389C24126
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE day
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE time_period
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mentor DROP price
        SQL);
    }
}
