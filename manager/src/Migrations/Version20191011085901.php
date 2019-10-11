<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191011085901 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE user_users (id VARCHAR(255) NOT NULL, company_id VARCHAR(255) DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, email VARCHAR(255) DEFAULT NULL, password_hash VARCHAR(255) DEFAULT NULL, confirm_token VARCHAR(255) DEFAULT NULL, new_email VARCHAR(255) DEFAULT NULL, new_email_token VARCHAR(255) DEFAULT NULL, status VARCHAR(16) NOT NULL, role VARCHAR(16) NOT NULL, name_first VARCHAR(255) NOT NULL, name_last VARCHAR(255) NOT NULL, reset_token_token VARCHAR(255) DEFAULT NULL, reset_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F6415EB1979B1AD6 ON user_users (company_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON user_users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB186EC69F0 ON user_users (reset_token_token)');
        $this->addSql('COMMENT ON COLUMN user_users.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_users.role IS \'(DC2Type:user_user_role)\'');
        $this->addSql('COMMENT ON COLUMN user_users.reset_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_user_networks (id UUID NOT NULL, user_id VARCHAR(255) NOT NULL, network VARCHAR(32) DEFAULT NULL, identity VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D7BAFD7BA76ED395 ON user_user_networks (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D7BAFD7B608487BC6A95E9C4 ON user_user_networks (network, identity)');
        $this->addSql('CREATE TABLE user_info (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, position VARCHAR(255) DEFAULT NULL, fax INT DEFAULT NULL, delivery_address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1087D9EA76ED395 ON user_info (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1087D9E9123CD68 ON user_info (fax)');
        $this->addSql('CREATE TABLE user_company (id VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, inn VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, name_full VARCHAR(255) NOT NULL, name_short VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17B21745A7C90ED7 ON user_company (name_full)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17B21745E93323CB ON user_company (inn)');
        $this->addSql('COMMENT ON COLUMN user_company.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_company.inn IS \'(DC2Type:company_user_inn)\'');
        $this->addSql('CREATE TABLE shop_product_products (id VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) DEFAULT NULL, article_post VARCHAR(30) DEFAULT NULL, article VARCHAR(30) DEFAULT NULL, brand VARCHAR(20) DEFAULT NULL, measures JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN shop_product_products.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN shop_product_products.measures IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE work_projects_projects (id UUID NOT NULL, name VARCHAR(255) NOT NULL, sort INT NOT NULL, status VARCHAR(16) NOT NULL, version INT DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN work_projects_projects.id IS \'(DC2Type:work_projects_project_id)\'');
        $this->addSql('COMMENT ON COLUMN work_projects_projects.status IS \'(DC2Type:work_projects_project_status)\'');
        $this->addSql('CREATE TABLE work_projects_project_memberships (id UUID NOT NULL, project_id UUID NOT NULL, member_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6884CF98166D1F9C ON work_projects_project_memberships (project_id)');
        $this->addSql('CREATE INDEX IDX_6884CF987597D3FE ON work_projects_project_memberships (member_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6884CF98166D1F9C7597D3FE ON work_projects_project_memberships (project_id, member_id)');
        $this->addSql('COMMENT ON COLUMN work_projects_project_memberships.project_id IS \'(DC2Type:work_projects_project_id)\'');
        $this->addSql('COMMENT ON COLUMN work_projects_project_memberships.member_id IS \'(DC2Type:work_members_member_id)\'');
        $this->addSql('CREATE TABLE work_projects_project_membership_departments (membership_id UUID NOT NULL, department_id UUID NOT NULL, PRIMARY KEY(membership_id, department_id))');
        $this->addSql('CREATE INDEX IDX_D94281DD1FB354CD ON work_projects_project_membership_departments (membership_id)');
        $this->addSql('CREATE INDEX IDX_D94281DDAE80F5DF ON work_projects_project_membership_departments (department_id)');
        $this->addSql('COMMENT ON COLUMN work_projects_project_membership_departments.department_id IS \'(DC2Type:work_projects_project_department_id)\'');
        $this->addSql('CREATE TABLE work_projects_project_membership_roles (membership_id UUID NOT NULL, role_id UUID NOT NULL, PRIMARY KEY(membership_id, role_id))');
        $this->addSql('CREATE INDEX IDX_42102BF81FB354CD ON work_projects_project_membership_roles (membership_id)');
        $this->addSql('CREATE INDEX IDX_42102BF8D60322AC ON work_projects_project_membership_roles (role_id)');
        $this->addSql('COMMENT ON COLUMN work_projects_project_membership_roles.role_id IS \'(DC2Type:work_projects_role_id)\'');
        $this->addSql('CREATE TABLE work_projects_project_departments (id UUID NOT NULL, project_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F870303A166D1F9C ON work_projects_project_departments (project_id)');
        $this->addSql('COMMENT ON COLUMN work_projects_project_departments.id IS \'(DC2Type:work_projects_project_department_id)\'');
        $this->addSql('COMMENT ON COLUMN work_projects_project_departments.project_id IS \'(DC2Type:work_projects_project_id)\'');
        $this->addSql('CREATE TABLE work_projects_roles (id UUID NOT NULL, name VARCHAR(255) NOT NULL, permissions JSON NOT NULL, version INT DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24B53355E237E06 ON work_projects_roles (name)');
        $this->addSql('COMMENT ON COLUMN work_projects_roles.id IS \'(DC2Type:work_projects_role_id)\'');
        $this->addSql('COMMENT ON COLUMN work_projects_roles.permissions IS \'(DC2Type:work_projects_role_permissions)\'');
        $this->addSql('CREATE TABLE work_members_groups (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN work_members_groups.id IS \'(DC2Type:work_members_group_id)\'');
        $this->addSql('CREATE TABLE work_members_members (id UUID NOT NULL, group_id UUID NOT NULL, email VARCHAR(255) NOT NULL, status VARCHAR(16) NOT NULL, name_first VARCHAR(255) NOT NULL, name_last VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_30039B6DFE54D947 ON work_members_members (group_id)');
        $this->addSql('COMMENT ON COLUMN work_members_members.id IS \'(DC2Type:work_members_member_id)\'');
        $this->addSql('COMMENT ON COLUMN work_members_members.group_id IS \'(DC2Type:work_members_group_id)\'');
        $this->addSql('COMMENT ON COLUMN work_members_members.email IS \'(DC2Type:work_members_member_email)\'');
        $this->addSql('COMMENT ON COLUMN work_members_members.status IS \'(DC2Type:work_members_member_status)\'');
        $this->addSql('CREATE TABLE oauth2_access_token (identifier CHAR(80) NOT NULL, client VARCHAR(32) NOT NULL, expiry TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_identifier VARCHAR(128) DEFAULT NULL, scopes TEXT DEFAULT NULL, revoked BOOLEAN NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('CREATE INDEX IDX_454D9673C7440455 ON oauth2_access_token (client)');
        $this->addSql('COMMENT ON COLUMN oauth2_access_token.scopes IS \'(DC2Type:oauth2_scope)\'');
        $this->addSql('CREATE TABLE oauth2_authorization_code (identifier CHAR(80) NOT NULL, client VARCHAR(32) NOT NULL, expiry TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_identifier VARCHAR(128) DEFAULT NULL, scopes TEXT DEFAULT NULL, revoked BOOLEAN NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('CREATE INDEX IDX_509FEF5FC7440455 ON oauth2_authorization_code (client)');
        $this->addSql('COMMENT ON COLUMN oauth2_authorization_code.scopes IS \'(DC2Type:oauth2_scope)\'');
        $this->addSql('CREATE TABLE oauth2_client (identifier VARCHAR(32) NOT NULL, secret VARCHAR(128) NOT NULL, redirect_uris TEXT DEFAULT NULL, grants TEXT DEFAULT NULL, scopes TEXT DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('COMMENT ON COLUMN oauth2_client.redirect_uris IS \'(DC2Type:oauth2_redirect_uri)\'');
        $this->addSql('COMMENT ON COLUMN oauth2_client.grants IS \'(DC2Type:oauth2_grant)\'');
        $this->addSql('COMMENT ON COLUMN oauth2_client.scopes IS \'(DC2Type:oauth2_scope)\'');
        $this->addSql('CREATE TABLE oauth2_refresh_token (identifier CHAR(80) NOT NULL, access_token CHAR(80) DEFAULT NULL, expiry TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, revoked BOOLEAN NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('CREATE INDEX IDX_4DD90732B6A2DD68 ON oauth2_refresh_token (access_token)');
        $this->addSql('ALTER TABLE user_users ADD CONSTRAINT FK_F6415EB1979B1AD6 FOREIGN KEY (company_id) REFERENCES user_company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user_networks ADD CONSTRAINT FK_D7BAFD7BA76ED395 FOREIGN KEY (user_id) REFERENCES user_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9EA76ED395 FOREIGN KEY (user_id) REFERENCES user_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_memberships ADD CONSTRAINT FK_6884CF98166D1F9C FOREIGN KEY (project_id) REFERENCES work_projects_projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_memberships ADD CONSTRAINT FK_6884CF987597D3FE FOREIGN KEY (member_id) REFERENCES work_members_members (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_membership_departments ADD CONSTRAINT FK_D94281DD1FB354CD FOREIGN KEY (membership_id) REFERENCES work_projects_project_memberships (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_membership_departments ADD CONSTRAINT FK_D94281DDAE80F5DF FOREIGN KEY (department_id) REFERENCES work_projects_project_departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_membership_roles ADD CONSTRAINT FK_42102BF81FB354CD FOREIGN KEY (membership_id) REFERENCES work_projects_project_memberships (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_membership_roles ADD CONSTRAINT FK_42102BF8D60322AC FOREIGN KEY (role_id) REFERENCES work_projects_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_departments ADD CONSTRAINT FK_F870303A166D1F9C FOREIGN KEY (project_id) REFERENCES work_projects_projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_members_members ADD CONSTRAINT FK_30039B6DFE54D947 FOREIGN KEY (group_id) REFERENCES work_members_groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth2_access_token ADD CONSTRAINT FK_454D9673C7440455 FOREIGN KEY (client) REFERENCES oauth2_client (identifier) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth2_authorization_code ADD CONSTRAINT FK_509FEF5FC7440455 FOREIGN KEY (client) REFERENCES oauth2_client (identifier) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth2_refresh_token ADD CONSTRAINT FK_4DD90732B6A2DD68 FOREIGN KEY (access_token) REFERENCES oauth2_access_token (identifier) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_user_networks DROP CONSTRAINT FK_D7BAFD7BA76ED395');
        $this->addSql('ALTER TABLE user_info DROP CONSTRAINT FK_B1087D9EA76ED395');
        $this->addSql('ALTER TABLE user_users DROP CONSTRAINT FK_F6415EB1979B1AD6');
        $this->addSql('ALTER TABLE work_projects_project_memberships DROP CONSTRAINT FK_6884CF98166D1F9C');
        $this->addSql('ALTER TABLE work_projects_project_departments DROP CONSTRAINT FK_F870303A166D1F9C');
        $this->addSql('ALTER TABLE work_projects_project_membership_departments DROP CONSTRAINT FK_D94281DD1FB354CD');
        $this->addSql('ALTER TABLE work_projects_project_membership_roles DROP CONSTRAINT FK_42102BF81FB354CD');
        $this->addSql('ALTER TABLE work_projects_project_membership_departments DROP CONSTRAINT FK_D94281DDAE80F5DF');
        $this->addSql('ALTER TABLE work_projects_project_membership_roles DROP CONSTRAINT FK_42102BF8D60322AC');
        $this->addSql('ALTER TABLE work_members_members DROP CONSTRAINT FK_30039B6DFE54D947');
        $this->addSql('ALTER TABLE work_projects_project_memberships DROP CONSTRAINT FK_6884CF987597D3FE');
        $this->addSql('ALTER TABLE oauth2_refresh_token DROP CONSTRAINT FK_4DD90732B6A2DD68');
        $this->addSql('ALTER TABLE oauth2_access_token DROP CONSTRAINT FK_454D9673C7440455');
        $this->addSql('ALTER TABLE oauth2_authorization_code DROP CONSTRAINT FK_509FEF5FC7440455');
        $this->addSql('DROP TABLE user_users');
        $this->addSql('DROP TABLE user_user_networks');
        $this->addSql('DROP TABLE user_info');
        $this->addSql('DROP TABLE user_company');
        $this->addSql('DROP TABLE shop_product_products');
        $this->addSql('DROP TABLE work_projects_projects');
        $this->addSql('DROP TABLE work_projects_project_memberships');
        $this->addSql('DROP TABLE work_projects_project_membership_departments');
        $this->addSql('DROP TABLE work_projects_project_membership_roles');
        $this->addSql('DROP TABLE work_projects_project_departments');
        $this->addSql('DROP TABLE work_projects_roles');
        $this->addSql('DROP TABLE work_members_groups');
        $this->addSql('DROP TABLE work_members_members');
        $this->addSql('DROP TABLE oauth2_access_token');
        $this->addSql('DROP TABLE oauth2_authorization_code');
        $this->addSql('DROP TABLE oauth2_client');
        $this->addSql('DROP TABLE oauth2_refresh_token');
    }
}
