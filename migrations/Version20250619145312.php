<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250619120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add quantity column to cart_product join table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cart_products ADD quantity INT NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cart_products DROP quantity');
    }
}
