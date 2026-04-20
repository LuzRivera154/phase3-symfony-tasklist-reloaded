<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260420115221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT OR IGNORE INTO priority (name, importance) VALUES ('normal', 1)");
        $this->addSql("INSERT OR IGNORE INTO priority (name, importance) VALUES ('important', 2)");
        $this->addSql("INSERT OR IGNORE INTO priority (name, importance) VALUES ('urgent', 3)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
