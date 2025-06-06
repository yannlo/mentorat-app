<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606201348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ban (id INT AUTO_INCREMENT NOT NULL, base_client_user_id INT NOT NULL, moderator_id INT NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_62FED0E5476627F5 (base_client_user_id), INDEX IDX_62FED0E5D0AFA354 (moderator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE base_client_user (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date DATE NOT NULL COMMENT '(DC2Type:date_immutable)', description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE manager (id INT NOT NULL, created_by_id INT DEFAULT NULL, INDEX IDX_FA2425B9B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE moderator (id INT NOT NULL, created_by_id INT NOT NULL, INDEX IDX_6A30B268B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ban ADD CONSTRAINT FK_62FED0E5476627F5 FOREIGN KEY (base_client_user_id) REFERENCES base_client_user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ban ADD CONSTRAINT FK_62FED0E5D0AFA354 FOREIGN KEY (moderator_id) REFERENCES moderator (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE base_client_user ADD CONSTRAINT FK_552AE3D3BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE manager ADD CONSTRAINT FK_FA2425B9B03A8386 FOREIGN KEY (created_by_id) REFERENCES manager (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE manager ADD CONSTRAINT FK_FA2425B9BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE moderator ADD CONSTRAINT FK_6A30B268B03A8386 FOREIGN KEY (created_by_id) REFERENCES manager (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE moderator ADD CONSTRAINT FK_6A30B268BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D76BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `admin`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_stage ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE certificate ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE `admin` (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `admin` ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ban DROP FOREIGN KEY FK_62FED0E5476627F5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ban DROP FOREIGN KEY FK_62FED0E5D0AFA354
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE base_client_user DROP FOREIGN KEY FK_552AE3D3BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE manager DROP FOREIGN KEY FK_FA2425B9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE manager DROP FOREIGN KEY FK_FA2425B9BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B268B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B268BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ban
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE base_client_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE course
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE manager
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE moderator
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_stage DROP created_at, DROP updated_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP created_at, DROP updated_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE certificate DROP created_at, DROP updated_at
        SQL);
    }
}
