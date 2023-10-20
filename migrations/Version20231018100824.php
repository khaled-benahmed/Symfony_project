<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018100824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author ADD descriptions LONGTEXT NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE book ADD author_id INT DEFAULT NULL, CHANGE ref ref INT NOT NULL, CHANGE title title VARCHAR(150) NOT NULL, CHANGE publication_date publication_date DATE NOT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331F675F31B ON book (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author DROP descriptions, CHANGE username username VARCHAR(150) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331F675F31B');
        $this->addSql('DROP INDEX IDX_CBE5A331F675F31B ON book');
        $this->addSql('ALTER TABLE book DROP author_id, CHANGE ref ref VARCHAR(255) NOT NULL, CHANGE title title VARCHAR(100) NOT NULL, CHANGE publication_date publication_date DATE DEFAULT NULL');
    }
}
