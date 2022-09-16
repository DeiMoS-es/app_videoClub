<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916074631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, foto_actor VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pelicula (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, tipo VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, foto VARCHAR(255) DEFAULT NULL, fecha_alta DATETIME NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_73BC7095A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pelicula_actor (pelicula_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_7B27FA7170713909 (pelicula_id), INDEX IDX_7B27FA7110DAF24A (actor_id), PRIMARY KEY(pelicula_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, update_on DATETIME NOT NULL, created_on DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pelicula ADD CONSTRAINT FK_73BC7095A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pelicula_actor ADD CONSTRAINT FK_7B27FA7170713909 FOREIGN KEY (pelicula_id) REFERENCES pelicula (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pelicula_actor ADD CONSTRAINT FK_7B27FA7110DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pelicula DROP FOREIGN KEY FK_73BC7095A76ED395');
        $this->addSql('ALTER TABLE pelicula_actor DROP FOREIGN KEY FK_7B27FA7170713909');
        $this->addSql('ALTER TABLE pelicula_actor DROP FOREIGN KEY FK_7B27FA7110DAF24A');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE pelicula');
        $this->addSql('DROP TABLE pelicula_actor');
        $this->addSql('DROP TABLE user');
    }
}
