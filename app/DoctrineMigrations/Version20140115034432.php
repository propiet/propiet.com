<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140115034432 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE property ADD category_id INT DEFAULT NULL, DROP category");
        $this->addSql("ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)");
        $this->addSql("CREATE INDEX IDX_8BF21CDE12469DE2 ON property (category_id)");
        $this->addSql("ALTER TABLE post ADD category_id INT DEFAULT NULL, DROP category");
        $this->addSql("ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)");
        $this->addSql("CREATE INDEX IDX_5A8A6C8D12469DE2 ON post (category_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE12469DE2");
        $this->addSql("ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D12469DE2");
        $this->addSql("DROP TABLE category");
        $this->addSql("DROP INDEX IDX_5A8A6C8D12469DE2 ON post");
        $this->addSql("ALTER TABLE post ADD category INT NOT NULL, DROP category_id");
        $this->addSql("DROP INDEX IDX_8BF21CDE12469DE2 ON property");
        $this->addSql("ALTER TABLE property ADD category INT NOT NULL, DROP category_id");
    }
}
