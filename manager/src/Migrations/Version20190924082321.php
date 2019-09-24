<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190924082321 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE user_company (id VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, inn INT NOT NULL, name_full VARCHAR(255) NOT NULL, name_short VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17B21745A7C90ED7 ON user_company (name_full)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17B21745E93323CB ON user_company (inn)');
        $this->addSql('COMMENT ON COLUMN user_company.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_users ADD new_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_users ADD new_email_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_users ADD name_first VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_users ADD name_last VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE user_company');
        $this->addSql('ALTER TABLE user_users DROP new_email');
        $this->addSql('ALTER TABLE user_users DROP new_email_token');
        $this->addSql('ALTER TABLE user_users DROP name_first');
        $this->addSql('ALTER TABLE user_users DROP name_last');
    }
}
