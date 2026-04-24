<?php

namespace SimpleAccountingBundle\Migrations;

use App\Doctrine\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20260311143400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the partial billing entry table for SimpleAccountingBundle';
    }

    public function up(Schema $schema): void
    {
        // there was a namespace error in the migration setup, that's why we have the same code in two files
        if ($schema->hasTable('kimai2_partial_billing_entry')) {
            $this->preventEmptyMigrationWarning();
            return;
        }

        $table = $schema->createTable('kimai2_partial_billing_entry');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('project_id', 'integer', ['notnull' => true]);
        $table->addColumn('amount', 'float', ['notnull' => true]);
        $table->addColumn('created_at', 'datetime', ['notnull' => true]);
        $table->addColumn('comment', 'text', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['project_id'], 'IDX_partial_billing_project');
        $table->addForeignKeyConstraint(
            'kimai2_projects',
            ['project_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_partial_billing_project'
        );
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('kimai2_partial_billing_entry')) {
            $schema->dropTable('kimai2_partial_billing_entry');
        }
    }
}
