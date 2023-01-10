<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221201152223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, create_dt DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param (id INT AUTO_INCREMENT NOT NULL, exam_id INT NOT NULL, name VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_A4FA7C89578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE param ADD CONSTRAINT FK_A4FA7C89578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE param DROP FOREIGN KEY FK_A4FA7C89578D5E91');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE param');
    }
}
