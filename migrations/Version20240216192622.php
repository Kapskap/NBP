<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216192622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB07982F1BAF4');
        $this->addSql('DROP INDEX IDX_D33BB07982F1BAF4 ON exchange');
        $this->addSql('ALTER TABLE exchange CHANGE language_id currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB07938248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('CREATE INDEX IDX_D33BB07938248176 ON exchange (currency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB07938248176');
        $this->addSql('DROP INDEX IDX_D33BB07938248176 ON exchange');
        $this->addSql('ALTER TABLE exchange CHANGE currency_id language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB07982F1BAF4 FOREIGN KEY (language_id) REFERENCES currency (id)');
        $this->addSql('CREATE INDEX IDX_D33BB07982F1BAF4 ON exchange (language_id)');
    }
}
