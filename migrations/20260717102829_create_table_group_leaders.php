<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableGroupLeaders extends AbstractMigration {
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
    public function change() : void {
        $table = $this->table('group_leaders', [
            'id' => false,
            'primary_key' => ['user_id', 'group_id'],
            'collation' => 'utf8mb4_unicode_ci',
        ]);
        $table->addColumn('user_id', 'uuid')
            ->addColumn('group_id', 'uuid')
            ->addColumn('role', 'string', ['limit' => 50])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('user_id', 'users', 'id')
            ->addForeignKey('group_id', 'groups', 'id')
            ->create();
    }
}
