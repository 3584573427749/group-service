<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableGroups extends AbstractMigration {
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void {
        $table = $this->table('groups', [
            'id' => false,
            'primary_key' => ['id'],
            'collation' => 'utf8mb4_unicode_ci',
        ]);
        $table->addColumn('id', 'uuid')
            ->addColumn('group_level_id', 'uuid')
            ->addColumn('name', 'string')
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('venue', 'enum', ['values' => ['Mariebad', 'Ålands Idrottscenter']])
            ->addColumn('active', 'tinyinteger', ['limit' => 1, 'default' => 1])
            ->addColumn('competitive', 'tinyinteger', ['limit' => 1, 'default' => 0])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('group_level_id', 'group_levels', 'id')
            ->create();
    }
}
