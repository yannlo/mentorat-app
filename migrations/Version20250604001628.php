<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604001628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE academic_stage (id INT AUTO_INCREMENT NOT NULL, school_name VARCHAR(255) NOT NULL, start_year DATE NOT NULL COMMENT '(DC2Type:date_immutable)', end_year DATE NOT NULL COMMENT '(DC2Type:date_immutable)', level VARCHAR(255) NOT NULL, degree_name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `admin` (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE certificate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, issue_date DATE NOT NULL COMMENT '(DC2Type:date_immutable)', issuer VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE mentor (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE student (id INT NOT NULL, school_name VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL, classname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthdate DATE NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `admin` ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mentor ADD CONSTRAINT FK_801562DEBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D76BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mentor DROP FOREIGN KEY FK_801562DEBF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP FOREIGN KEY FK_B723AF33BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE academic_stage
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `admin`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE certificate
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mentor
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE student
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
