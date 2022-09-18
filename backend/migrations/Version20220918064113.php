<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220918064113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE addresses (id INT AUTO_INCREMENT NOT NULL, house_number INT NOT NULL, addition VARCHAR(2) DEFAULT NULL, postal_code VARCHAR(6) NOT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, UNIQUE INDEX address_unique (postal_code, house_number, addition), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allowance (id INT AUTO_INCREMENT NOT NULL, resident_id INT NOT NULL, status VARCHAR(255) NOT NULL, budget DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_66C848838012C5B0 (resident_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, allowance_id INT NOT NULL, budget DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expired_at DATE NOT NULL, INDEX IDX_84ACBE48282DEF00 (allowance_id), UNIQUE INDEX decision_unique (allowance_id, expired_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resident (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, parcel_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_1D03DA06F5B7AF75 (address_id), INDEX IDX_1D03DA06465E670C (parcel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ride (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, taxi_id INT NOT NULL, location_id INT NOT NULL, destination_id INT NOT NULL, distance DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9B3D7CD0BDEE7539 (decision_id), INDEX IDX_9B3D7CD0506FF81C (taxi_id), INDEX IDX_9B3D7CD064D218E (location_id), INDEX IDX_9B3D7CD0816C6140 (destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxi (id INT AUTO_INCREMENT NOT NULL, parcel_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5F8463C2465E670C (parcel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allowance ADD CONSTRAINT FK_66C848838012C5B0 FOREIGN KEY (resident_id) REFERENCES resident (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48282DEF00 FOREIGN KEY (allowance_id) REFERENCES allowance (id)');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA06F5B7AF75 FOREIGN KEY (address_id) REFERENCES addresses (id)');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA06465E670C FOREIGN KEY (parcel_id) REFERENCES parcel (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0506FF81C FOREIGN KEY (taxi_id) REFERENCES taxi (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD064D218E FOREIGN KEY (location_id) REFERENCES addresses (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0816C6140 FOREIGN KEY (destination_id) REFERENCES addresses (id)');
        $this->addSql('ALTER TABLE taxi ADD CONSTRAINT FK_5F8463C2465E670C FOREIGN KEY (parcel_id) REFERENCES parcel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allowance DROP FOREIGN KEY FK_66C848838012C5B0');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48282DEF00');
        $this->addSql('ALTER TABLE resident DROP FOREIGN KEY FK_1D03DA06F5B7AF75');
        $this->addSql('ALTER TABLE resident DROP FOREIGN KEY FK_1D03DA06465E670C');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0BDEE7539');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0506FF81C');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD064D218E');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0816C6140');
        $this->addSql('ALTER TABLE taxi DROP FOREIGN KEY FK_5F8463C2465E670C');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE allowance');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('DROP TABLE resident');
        $this->addSql('DROP TABLE ride');
        $this->addSql('DROP TABLE taxi');
    }
}
