<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171231150722 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, activity_id INT DEFAULT NULL, user_id INT NOT NULL, from_ VARCHAR(255) NOT NULL, to_ VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, date DATETIME NOT NULL, transport_type VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, distance VARCHAR(255) DEFAULT NULL, amount VARCHAR(255) DEFAULT NULL, tickets VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_7656F53BFE54D947 (group_id), INDEX IDX_7656F53B81C06096 (activity_id), INDEX IDX_7656F53BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, bank VARCHAR(255) NOT NULL, person_id INT NOT NULL, hash VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BFE54D947 FOREIGN KEY (group_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B81C06096 FOREIGN KEY (activity_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BFE54D947');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B81C06096');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BA76ED395');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE user');
    }
}
