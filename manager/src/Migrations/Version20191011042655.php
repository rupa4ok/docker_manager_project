<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191011042655 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

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
        $this->addSql('ALTER TABLE work_projects_project_memberships ADD CONSTRAINT FK_6884CF98166D1F9C FOREIGN KEY (project_id) REFERENCES work_projects_projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_memberships ADD CONSTRAINT FK_6884CF987597D3FE FOREIGN KEY (member_id) REFERENCES work_members_members (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_membership_departments ADD CONSTRAINT FK_D94281DD1FB354CD FOREIGN KEY (membership_id) REFERENCES work_projects_project_memberships (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_membership_departments ADD CONSTRAINT FK_D94281DDAE80F5DF FOREIGN KEY (department_id) REFERENCES work_projects_project_departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_membership_roles ADD CONSTRAINT FK_42102BF81FB354CD FOREIGN KEY (membership_id) REFERENCES work_projects_project_memberships (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_membership_roles ADD CONSTRAINT FK_42102BF8D60322AC FOREIGN KEY (role_id) REFERENCES work_projects_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_departments ADD CONSTRAINT FK_F870303A166D1F9C FOREIGN KEY (project_id) REFERENCES work_projects_projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
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
        $this->addSql('ALTER TABLE shop_product_products ADD article_post VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_products ADD article VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_products ADD brand VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_products ADD measures JSON DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN shop_product_products.measures IS \'(DC2Type:json_array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE work_projects_project_memberships DROP CONSTRAINT FK_6884CF98166D1F9C');
        $this->addSql('ALTER TABLE work_projects_project_departments DROP CONSTRAINT FK_F870303A166D1F9C');
        $this->addSql('ALTER TABLE work_projects_project_membership_departments DROP CONSTRAINT FK_D94281DD1FB354CD');
        $this->addSql('ALTER TABLE work_projects_project_membership_roles DROP CONSTRAINT FK_42102BF81FB354CD');
        $this->addSql('ALTER TABLE work_projects_project_membership_departments DROP CONSTRAINT FK_D94281DDAE80F5DF');
        $this->addSql('ALTER TABLE work_projects_project_membership_roles DROP CONSTRAINT FK_42102BF8D60322AC');
        $this->addSql('DROP TABLE work_projects_projects');
        $this->addSql('DROP TABLE work_projects_project_memberships');
        $this->addSql('DROP TABLE work_projects_project_membership_departments');
        $this->addSql('DROP TABLE work_projects_project_membership_roles');
        $this->addSql('DROP TABLE work_projects_project_departments');
        $this->addSql('DROP TABLE work_projects_roles');
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
        $this->addSql('ALTER TABLE shop_product_products DROP article_post');
        $this->addSql('ALTER TABLE shop_product_products DROP article');
        $this->addSql('ALTER TABLE shop_product_products DROP brand');
        $this->addSql('ALTER TABLE shop_product_products DROP measures');
        $this->addSql('ALTER TABLE user_info ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_info ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_info ALTER user_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_info ALTER user_id DROP DEFAULT');
    }
}
