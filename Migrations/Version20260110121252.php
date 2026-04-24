<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SimpleAccountingBundle\Migrations;

use App\Doctrine\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * @version 1.1
 */
final class Version20260110121252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added the database table';
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
