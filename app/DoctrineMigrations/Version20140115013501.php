<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140115013501 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE4B89032C");
        $this->addSql("DROP INDEX IDX_8BF21CDE4B89032C ON property");
        $this->addSql("ALTER TABLE property DROP post_id");
        $this->addSql("ALTER TABLE location ADD address VARCHAR(255) NOT NULL, CHANGE longitude longitude VARCHAR(255) DEFAULT NULL, CHANGE latitude latitude VARCHAR(255) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE location DROP address, CHANGE longitude longitude INT DEFAULT NULL, CHANGE latitude latitude INT DEFAULT NULL");
        $this->addSql("ALTER TABLE property ADD post_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE4B89032C FOREIGN KEY (post_id) REFERENCES post (id)");
        $this->addSql("CREATE INDEX IDX_8BF21CDE4B89032C ON property (post_id)");
    }
}
