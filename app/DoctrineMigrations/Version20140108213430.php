<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140108213430 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, ISO_A2 VARCHAR(255) DEFAULT NULL, ISO_A3 VARCHAR(255) DEFAULT NULL, INDEX IDX_F62F176F92F3E70 (country_id), UNIQUE INDEX UNIQ_F62F17664D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, post_id INT DEFAULT NULL, category INT NOT NULL, status INT DEFAULT NULL, antiqueness INT DEFAULT NULL, square_meters INT DEFAULT NULL, total_meters INT DEFAULT NULL, propertyType VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, unityType INT DEFAULT NULL, quantityAmbiences INT DEFAULT NULL, quantityBedrooms INT DEFAULT NULL, quantityBathroom INT DEFAULT NULL, quantityGarages INT DEFAULT NULL, garageCoverage VARCHAR(255) DEFAULT NULL, orientation VARCHAR(255) DEFAULT NULL, disposition VARCHAR(255) DEFAULT NULL, buildingType INT DEFAULT NULL, buildingCategory INT DEFAULT NULL, buildingCondition VARCHAR(255) DEFAULT NULL, apartmentsPerFloor INT DEFAULT NULL, floorNumber INT DEFAULT NULL, quantityBuildingFloors INT DEFAULT NULL, expenses INT DEFAULT NULL, suitableProfessional TINYINT(1) DEFAULT NULL, commercialUsage TINYINT(1) DEFAULT NULL, lightness TINYINT(1) DEFAULT NULL, quantityBuildingLifts INT DEFAULT NULL, INDEX IDX_8BF21CDEA76ED395 (user_id), INDEX IDX_8BF21CDE4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE additional_characteristics (property_id INT NOT NULL, characteristic_id INT NOT NULL, INDEX IDX_1376AC30549213EC (property_id), INDEX IDX_1376AC30DEE9D12B (characteristic_id), PRIMARY KEY(property_id, characteristic_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE additional_services (property_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_C9E35DBF549213EC (property_id), INDEX IDX_C9E35DBFED5CA9E6 (service_id), PRIMARY KEY(property_id, service_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE additional_ambiences (property_id INT NOT NULL, ambience_id INT NOT NULL, INDEX IDX_AB1B2330549213EC (property_id), INDEX IDX_AB1B2330C3EF7E80 (ambience_id), PRIMARY KEY(property_id, ambience_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, property_id INT DEFAULT NULL, category INT NOT NULL, status INT DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), UNIQUE INDEX UNIQ_5A8A6C8D549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Ambience (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, symbol VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, ISO_A2 VARCHAR(255) DEFAULT NULL, ISO_A3 VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5373C96664D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, longitude INT DEFAULT NULL, latitude INT DEFAULT NULL, UNIQUE INDEX UNIQ_5E9E89CB549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, ISO_A2 VARCHAR(255) DEFAULT NULL, ISO_A3 VARCHAR(255) DEFAULT NULL, INDEX IDX_2D5B023498260155 (region_id), UNIQUE INDEX UNIQ_2D5B023464D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Characteristic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, creation_date DATETIME NOT NULL, gender VARCHAR(1) DEFAULT NULL, firstname VARCHAR(200) DEFAULT NULL, lastname VARCHAR(200) DEFAULT NULL, facebookId INT DEFAULT NULL, avatarSm VARCHAR(500) NOT NULL, avatarBg VARCHAR(500) DEFAULT NULL, age INT DEFAULT NULL, allowEmails TINYINT(1) DEFAULT NULL, allowMarketing TINYINT(1) DEFAULT NULL, reputation INT DEFAULT NULL, telephone INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE region ADD CONSTRAINT FK_F62F176F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)");
        $this->addSql("ALTER TABLE region ADD CONSTRAINT FK_F62F17664D218E FOREIGN KEY (location_id) REFERENCES location (id)");
        $this->addSql("ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE4B89032C FOREIGN KEY (post_id) REFERENCES post (id)");
        $this->addSql("ALTER TABLE additional_characteristics ADD CONSTRAINT FK_1376AC30549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE additional_characteristics ADD CONSTRAINT FK_1376AC30DEE9D12B FOREIGN KEY (characteristic_id) REFERENCES Characteristic (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE additional_services ADD CONSTRAINT FK_C9E35DBF549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE additional_services ADD CONSTRAINT FK_C9E35DBFED5CA9E6 FOREIGN KEY (service_id) REFERENCES Service (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE additional_ambiences ADD CONSTRAINT FK_AB1B2330549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE additional_ambiences ADD CONSTRAINT FK_AB1B2330C3EF7E80 FOREIGN KEY (ambience_id) REFERENCES Ambience (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D549213EC FOREIGN KEY (property_id) REFERENCES property (id)");
        $this->addSql("ALTER TABLE country ADD CONSTRAINT FK_5373C96664D218E FOREIGN KEY (location_id) REFERENCES location (id)");
        $this->addSql("ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB549213EC FOREIGN KEY (property_id) REFERENCES property (id)");
        $this->addSql("ALTER TABLE city ADD CONSTRAINT FK_2D5B023498260155 FOREIGN KEY (region_id) REFERENCES region (id)");
        $this->addSql("ALTER TABLE city ADD CONSTRAINT FK_2D5B023464D218E FOREIGN KEY (location_id) REFERENCES location (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE city DROP FOREIGN KEY FK_2D5B023498260155");
        $this->addSql("ALTER TABLE additional_characteristics DROP FOREIGN KEY FK_1376AC30549213EC");
        $this->addSql("ALTER TABLE additional_services DROP FOREIGN KEY FK_C9E35DBF549213EC");
        $this->addSql("ALTER TABLE additional_ambiences DROP FOREIGN KEY FK_AB1B2330549213EC");
        $this->addSql("ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D549213EC");
        $this->addSql("ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB549213EC");
        $this->addSql("ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE4B89032C");
        $this->addSql("ALTER TABLE additional_ambiences DROP FOREIGN KEY FK_AB1B2330C3EF7E80");
        $this->addSql("ALTER TABLE region DROP FOREIGN KEY FK_F62F176F92F3E70");
        $this->addSql("ALTER TABLE region DROP FOREIGN KEY FK_F62F17664D218E");
        $this->addSql("ALTER TABLE country DROP FOREIGN KEY FK_5373C96664D218E");
        $this->addSql("ALTER TABLE city DROP FOREIGN KEY FK_2D5B023464D218E");
        $this->addSql("ALTER TABLE additional_characteristics DROP FOREIGN KEY FK_1376AC30DEE9D12B");
        $this->addSql("ALTER TABLE additional_services DROP FOREIGN KEY FK_C9E35DBFED5CA9E6");
        $this->addSql("ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEA76ED395");
        $this->addSql("ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395");
        $this->addSql("DROP TABLE region");
        $this->addSql("DROP TABLE property");
        $this->addSql("DROP TABLE additional_characteristics");
        $this->addSql("DROP TABLE additional_services");
        $this->addSql("DROP TABLE additional_ambiences");
        $this->addSql("DROP TABLE post");
        $this->addSql("DROP TABLE Ambience");
        $this->addSql("DROP TABLE currency");
        $this->addSql("DROP TABLE country");
        $this->addSql("DROP TABLE location");
        $this->addSql("DROP TABLE city");
        $this->addSql("DROP TABLE Characteristic");
        $this->addSql("DROP TABLE Service");
        $this->addSql("DROP TABLE user");
    }
}
