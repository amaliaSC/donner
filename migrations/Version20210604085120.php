<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210604085120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `add` DROP FOREIGN KEY FK_FD1A73E73F2316F3');
        $this->addSql('CREATE TABLE `ad` (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, city_id INT DEFAULT NULL, condition_ad_id INT DEFAULT NULL, user_id INT NOT NULL, ad_type_id INT NOT NULL, sub_category_id INT NOT NULL, recipient_user_id INT DEFAULT NULL, title VARCHAR(150) NOT NULL, content VARCHAR(255) NOT NULL, duration INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, INDEX IDX_77E0ED586BF700BD (status_id), INDEX IDX_77E0ED588BAC62AF (city_id), INDEX IDX_77E0ED583EF319D (condition_ad_id), INDEX IDX_77E0ED58A76ED395 (user_id), INDEX IDX_77E0ED588066517 (ad_type_id), INDEX IDX_77E0ED58F7BFE87C (sub_category_id), INDEX IDX_77E0ED58B15EFB97 (recipient_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ad_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `ad` ADD CONSTRAINT FK_77E0ED586BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE `ad` ADD CONSTRAINT FK_77E0ED588BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE `ad` ADD CONSTRAINT FK_77E0ED583EF319D FOREIGN KEY (condition_ad_id) REFERENCES `condition` (id)');
        $this->addSql('ALTER TABLE `ad` ADD CONSTRAINT FK_77E0ED58A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `ad` ADD CONSTRAINT FK_77E0ED588066517 FOREIGN KEY (ad_type_id) REFERENCES ad_type (id)');
        $this->addSql('ALTER TABLE `ad` ADD CONSTRAINT FK_77E0ED58F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('ALTER TABLE `ad` ADD CONSTRAINT FK_77E0ED58B15EFB97 FOREIGN KEY (recipient_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE `add`');
        $this->addSql('DROP TABLE annonce_type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `ad` DROP FOREIGN KEY FK_77E0ED588066517');
        $this->addSql('CREATE TABLE `add` (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, city_id INT DEFAULT NULL, condition_add_id INT DEFAULT NULL, user_id INT NOT NULL, annonce_type_id INT NOT NULL, sub_category_id INT NOT NULL, recipient_user_id INT DEFAULT NULL, title VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, duration INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_FD1A73E7B15EFB97 (recipient_user_id), INDEX IDX_FD1A73E7A76ED395 (user_id), INDEX IDX_FD1A73E76BF700BD (status_id), INDEX IDX_FD1A73E73F2316F3 (annonce_type_id), INDEX IDX_FD1A73E78BAC62AF (city_id), INDEX IDX_FD1A73E7F7BFE87C (sub_category_id), INDEX IDX_FD1A73E7A402D2CB (condition_add_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE annonce_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `add` ADD CONSTRAINT FK_FD1A73E73F2316F3 FOREIGN KEY (annonce_type_id) REFERENCES annonce_type (id)');
        $this->addSql('ALTER TABLE `add` ADD CONSTRAINT FK_FD1A73E76BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE `add` ADD CONSTRAINT FK_FD1A73E78BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE `add` ADD CONSTRAINT FK_FD1A73E7A402D2CB FOREIGN KEY (condition_add_id) REFERENCES `condition` (id)');
        $this->addSql('ALTER TABLE `add` ADD CONSTRAINT FK_FD1A73E7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `add` ADD CONSTRAINT FK_FD1A73E7B15EFB97 FOREIGN KEY (recipient_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `add` ADD CONSTRAINT FK_FD1A73E7F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('DROP TABLE `ad`');
        $this->addSql('DROP TABLE ad_type');
    }
}
