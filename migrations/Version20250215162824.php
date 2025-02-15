<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215162824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX tblProductData_UN ON tblProductData');
        $this->addSql('ALTER TABLE tblProductData ADD stock INT DEFAULT NULL, ADD price NUMERIC(10, 2) DEFAULT NULL, DROP stmTimestamp, CHANGE intProductDataId intProductDataId INT UNSIGNED AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProductData ADD stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, DROP stock, DROP price, CHANGE intProductDataId intProductDataId INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX tblProductData_UN ON tblProductData (strProductCode)');
    }
}
