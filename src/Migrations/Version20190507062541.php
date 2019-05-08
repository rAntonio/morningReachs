<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190507062541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, id_auteur_id INT NOT NULL, titre VARCHAR(255) NOT NULL, created DATETIME NOT NULL, contenu LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, presentation LONGTEXT NOT NULL, imggm VARCHAR(255) NOT NULL, level SMALLINT NOT NULL, INDEX IDX_23A0E6612469DE2 (category_id), INDEX IDX_23A0E66E08ED3C1 (id_auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, catnom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, mots VARCHAR(50) NOT NULL, INDEX IDX_6FBC94267294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E08ED3C1 FOREIGN KEY (id_auteur_id) REFERENCES auteur (id)');
        $this->addSql('ALTER TABLE tags ADD CONSTRAINT FK_6FBC94267294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tags DROP FOREIGN KEY FK_6FBC94267294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E08ED3C1');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE tags');
    }
}
