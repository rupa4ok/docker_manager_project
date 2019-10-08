<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191008200834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE user_users ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER company_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER company_id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER new_email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER new_email DROP DEFAULT');
        $this->addSql('ALTER TABLE user_user_networks ALTER user_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_user_networks ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_info ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_info ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_info ALTER user_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_info ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_company ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_company ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_company ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_company ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_user_networks ALTER user_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_user_networks ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER company_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER company_id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER new_email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER new_email DROP DEFAULT');
        $this->addSql('ALTER TABLE user_info ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_info ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_info ALTER user_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_info ALTER user_id DROP DEFAULT');
    }
}
