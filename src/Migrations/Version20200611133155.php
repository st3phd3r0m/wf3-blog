<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611133155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE FULLTEXT INDEX IDX_499C95FE2B36786BFEC530A9 ON posts (title, content)');
        $this->addSql('ALTER TABLE posts RENAME INDEX uniq_885dbafa989d9b62 TO UNIQ_499C95FE989D9B62');
        $this->addSql('ALTER TABLE posts RENAME INDEX idx_885dbafabcf5e72d TO IDX_499C95FEBCF5E72D');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_499C95FE2B36786BFEC530A9 ON Posts');
        $this->addSql('ALTER TABLE Posts RENAME INDEX idx_499c95febcf5e72d TO IDX_885DBAFABCF5E72D');
        $this->addSql('ALTER TABLE Posts RENAME INDEX uniq_499c95fe989d9b62 TO UNIQ_885DBAFA989D9B62');
    }
}
