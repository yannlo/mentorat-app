<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616220814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE base_client_user ADD public_id BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_552AE3D3B5B48B91 ON base_client_user (public_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649B5B48B91 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP public_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD public_id BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649B5B48B91 ON user (public_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_552AE3D3B5B48B91 ON base_client_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE base_client_user DROP public_id
        SQL);
    }
}
