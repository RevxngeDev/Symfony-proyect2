<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250201191142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE film_likes (film_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(film_id, user_id))');
        $this->addSql('CREATE INDEX IDX_D0A6B02D567F5183 ON film_likes (film_id)');
        $this->addSql('CREATE INDEX IDX_D0A6B02DA76ED395 ON film_likes (user_id)');
        $this->addSql('ALTER TABLE film_likes ADD CONSTRAINT FK_D0A6B02D567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE film_likes ADD CONSTRAINT FK_D0A6B02DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE film ADD likes INT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE film_likes DROP CONSTRAINT FK_D0A6B02D567F5183');
        $this->addSql('ALTER TABLE film_likes DROP CONSTRAINT FK_D0A6B02DA76ED395');
        $this->addSql('DROP TABLE film_likes');
        $this->addSql('ALTER TABLE film DROP likes');
    }
}
