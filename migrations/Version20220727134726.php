<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727134726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, client VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, issuer VARCHAR(255) NOT NULL, owner VARCHAR(255) NOT NULL, issue_date DATE NOT NULL, due_date DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, paid DOUBLE PRECISION DEFAULT NULL, unpaid DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, invoice_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, percent INT DEFAULT NULL, UNIQUE INDEX UNIQ_11BA68C2989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, invoice_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, jira INT DEFAULT NULL, jira_task VARCHAR(255) DEFAULT NULL, client_jira_task VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, unit INT NOT NULL, amount DOUBLE PRECISION NOT NULL, total_sum DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D34A04AD2989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoices (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoices (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C2989F1FD');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2989F1FD');
        $this->addSql('DROP TABLE invoices');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
