<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425132537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE announcement (id SERIAL NOT NULL, id_user_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4DB9D91C79F37AE5 ON announcement (id_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE announcement_ue (announcement_id INT NOT NULL, ue_id INT NOT NULL, PRIMARY KEY(announcement_id, ue_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A87DC913AEA17 ON announcement_ue (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A87DC62E883B1 ON announcement_ue (ue_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id SERIAL NOT NULL, id_user_id INT NOT NULL, id_post_id INT NOT NULL, comment_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526C79F37AE5 ON comment (id_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526C9514AA5C ON comment (id_post_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE course (id SERIAL NOT NULL, id_ue_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT DEFAULT NULL, file_path TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_169E6FB98C6DC281 ON course (id_ue_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE homework (id SERIAL NOT NULL, id_ue_id INT NOT NULL, instructions TEXT NOT NULL, due_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, file_path TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8C600B4E8C6DC281 ON homework (id_ue_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE login_history (id SERIAL NOT NULL, id_user_id INT NOT NULL, login_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, activity_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_37976E3679F37AE5 ON login_history (id_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE post (id SERIAL NOT NULL, id_user_id INT NOT NULL, title VARCHAR(300) NOT NULL, content TEXT NOT NULL, post_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A8A6C8D79F37AE5 ON post (id_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE submission (id SERIAL NOT NULL, id_user_id INT NOT NULL, id_homework_id INT NOT NULL, file_path TEXT NOT NULL, grade INT DEFAULT NULL, feedback TEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DB055AF379F37AE5 ON submission (id_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DB055AF3AA111012 ON submission (id_homework_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ue (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, credits INT NOT NULL, illustration TEXT DEFAULT NULL, last_update_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ue_user (ue_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(ue_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6D91EF5462E883B1 ON ue_user (ue_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6D91EF54A76ED395 ON ue_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, id_role_id INT NOT NULL, first_name VARCHAR(150) NOT NULL, last_name VARCHAR(150) NOT NULL, email VARCHAR(300) NOT NULL, password VARCHAR(300) NOT NULL, profile_picture TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D64989E8BDC ON "user" (id_role_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement ADD CONSTRAINT FK_4DB9D91C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_ue ADD CONSTRAINT FK_4A87DC913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_ue ADD CONSTRAINT FK_4A87DC62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course ADD CONSTRAINT FK_169E6FB98C6DC281 FOREIGN KEY (id_ue_id) REFERENCES ue (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework ADD CONSTRAINT FK_8C600B4E8C6DC281 FOREIGN KEY (id_ue_id) REFERENCES ue (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE login_history ADD CONSTRAINT FK_37976E3679F37AE5 FOREIGN KEY (id_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD CONSTRAINT FK_DB055AF379F37AE5 FOREIGN KEY (id_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3AA111012 FOREIGN KEY (id_homework_id) REFERENCES homework (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ue_user ADD CONSTRAINT FK_6D91EF5462E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ue_user ADD CONSTRAINT FK_6D91EF54A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64989E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement DROP CONSTRAINT FK_4DB9D91C79F37AE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_ue DROP CONSTRAINT FK_4A87DC913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_ue DROP CONSTRAINT FK_4A87DC62E883B1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP CONSTRAINT FK_9474526C79F37AE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP CONSTRAINT FK_9474526C9514AA5C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course DROP CONSTRAINT FK_169E6FB98C6DC281
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework DROP CONSTRAINT FK_8C600B4E8C6DC281
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE login_history DROP CONSTRAINT FK_37976E3679F37AE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D79F37AE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission DROP CONSTRAINT FK_DB055AF379F37AE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission DROP CONSTRAINT FK_DB055AF3AA111012
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ue_user DROP CONSTRAINT FK_6D91EF5462E883B1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ue_user DROP CONSTRAINT FK_6D91EF54A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64989E8BDC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE announcement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE announcement_ue
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE course
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE homework
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE login_history
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE post
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE submission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ue
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ue_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
    }
}
