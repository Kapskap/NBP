<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209141447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange ADD source_id INT NOT NULL');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB079953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('CREATE INDEX IDX_D33BB079953C1C61 ON exchange (source_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB079953C1C61');
        $this->addSql('DROP INDEX IDX_D33BB079953C1C61 ON exchange');
        $this->addSql('ALTER TABLE exchange DROP source_id');
    }
}
