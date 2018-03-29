<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Initial Migration.
 */
class Version20180326200407 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema)
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rw_comment (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, article_id INT DEFAULT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EAFFB7F675F31B (author_id), INDEX IDX_EAFFB77294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rw_article (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_96A4A3BD989D9B62 (slug), INDEX IDX_96A4A3BDF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rw_article_tag (article_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_A9C3949B7294869C (article_id), INDEX IDX_A9C3949BBAD26311 (tag_id), PRIMARY KEY(article_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rw_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, bio LONGTEXT DEFAULT NULL, image LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E628724AE7927C74 (email), UNIQUE INDEX UNIQ_E628724AF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rw_user_follower (user_id INT NOT NULL, follower_id INT NOT NULL, INDEX IDX_E90DC36A76ED395 (user_id), INDEX IDX_E90DC36AC24F853 (follower_id), PRIMARY KEY(user_id, follower_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rw_user_favorite (user_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_DF835BA9A76ED395 (user_id), INDEX IDX_DF835BA97294869C (article_id), PRIMARY KEY(user_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rw_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D37C40D35E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rw_comment ADD CONSTRAINT FK_EAFFB7F675F31B FOREIGN KEY (author_id) REFERENCES rw_user (id)');
        $this->addSql('ALTER TABLE rw_comment ADD CONSTRAINT FK_EAFFB77294869C FOREIGN KEY (article_id) REFERENCES rw_article (id)');
        $this->addSql('ALTER TABLE rw_article ADD CONSTRAINT FK_96A4A3BDF675F31B FOREIGN KEY (author_id) REFERENCES rw_user (id)');
        $this->addSql('ALTER TABLE rw_article_tag ADD CONSTRAINT FK_A9C3949B7294869C FOREIGN KEY (article_id) REFERENCES rw_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rw_article_tag ADD CONSTRAINT FK_A9C3949BBAD26311 FOREIGN KEY (tag_id) REFERENCES rw_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rw_user_follower ADD CONSTRAINT FK_E90DC36A76ED395 FOREIGN KEY (user_id) REFERENCES rw_user (id)');
        $this->addSql('ALTER TABLE rw_user_follower ADD CONSTRAINT FK_E90DC36AC24F853 FOREIGN KEY (follower_id) REFERENCES rw_user (id)');
        $this->addSql('ALTER TABLE rw_user_favorite ADD CONSTRAINT FK_DF835BA9A76ED395 FOREIGN KEY (user_id) REFERENCES rw_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rw_user_favorite ADD CONSTRAINT FK_DF835BA97294869C FOREIGN KEY (article_id) REFERENCES rw_article (id) ON DELETE CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema)
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rw_comment DROP FOREIGN KEY FK_EAFFB77294869C');
        $this->addSql('ALTER TABLE rw_article_tag DROP FOREIGN KEY FK_A9C3949B7294869C');
        $this->addSql('ALTER TABLE rw_user_favorite DROP FOREIGN KEY FK_DF835BA97294869C');
        $this->addSql('ALTER TABLE rw_comment DROP FOREIGN KEY FK_EAFFB7F675F31B');
        $this->addSql('ALTER TABLE rw_article DROP FOREIGN KEY FK_96A4A3BDF675F31B');
        $this->addSql('ALTER TABLE rw_user_follower DROP FOREIGN KEY FK_E90DC36A76ED395');
        $this->addSql('ALTER TABLE rw_user_follower DROP FOREIGN KEY FK_E90DC36AC24F853');
        $this->addSql('ALTER TABLE rw_user_favorite DROP FOREIGN KEY FK_DF835BA9A76ED395');
        $this->addSql('ALTER TABLE rw_article_tag DROP FOREIGN KEY FK_A9C3949BBAD26311');
        $this->addSql('DROP TABLE rw_comment');
        $this->addSql('DROP TABLE rw_article');
        $this->addSql('DROP TABLE rw_article_tag');
        $this->addSql('DROP TABLE rw_user');
        $this->addSql('DROP TABLE rw_user_follower');
        $this->addSql('DROP TABLE rw_user_favorite');
        $this->addSql('DROP TABLE rw_tag');
    }
}
