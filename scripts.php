<?php

return [
    
    /*
     * Installation hook
     *
     */
    'install'   => function ($app) {
        $util = $app['db']->getUtility();
        if ($util->tableExists('@eventlist_event') === false) {
            $util->createTable('@eventlist_event', function ($table) {
    
                $table->addColumn('id', 'integer', [
                    'unsigned'      => true,
                    'length'        => 10,
                    'autoincrement' => true,
                ]);
    
                $table->addColumn('category_id', 'integer', [
                    'length'  => 10,
                    'default' => 0,
                ]);
    
                $table->addColumn('status', 'smallint');
                $table->addColumn('slug', 'string', ['length' => 255]);
                $table->addColumn('title', 'string', ['length' => 255]);
                $table->addColumn('content', 'text');
                $table->addColumn('price', 'float', [
                    'unsigned' => true,
                    'length'   => 10,
                    'notnull'  => false,
                ]);
                $table->addColumn('performer', 'json_array',
                    ['notnull' => false]);
                $table->addColumn('data', 'json_array', ['notnull' => false]);
                $table->addColumn('date', 'datetime', ['notnull' => false]);
                $table->addColumn('modified', 'datetime');
                $table->setPrimaryKey(['id']);
                $table->addUniqueIndex(['slug'], '@EVENT_SLUG');
            });
        }
        if ($util->tableExists('@eventlist_category') === false) {
            $util->createTable('@eventlist_category', function ($table) {
                $table->addColumn('id', 'integer', [
                    'unsigned'      => true,
                    'length'        => 10,
                    'autoincrement' => true,
                ]);
                $table->addColumn('status', 'smallint');
                $table->addColumn('slug', 'string', ['length' => 255]);
                $table->addColumn('title', 'string', ['length' => 255]);
                $table->addColumn('date', 'datetime', ['notnull' => false]);
                $table->addColumn('modified', 'datetime');
                $table->setPrimaryKey(['id']);
            });
        }
    },
    
    /*
     * Enable hook
     *
     */
    'enable'    => function ($app) {
    },
    
    /*
     * Uninstall hook
     *
     */
    'uninstall' => function ($app) {
        // remove the tables
        $util = $app['db']->getUtility();
        if ($util->tableExists('@eventlist_event')) {
            $util->dropTable('@eventlist_event');
        }
        if ($util->tableExists('@eventlist_category')) {
            $util->dropTable('@eventlist_category');
        }
        
        // remove the config
        $app['config']->remove('spqr/eventlist');
    },
    
    /*
     * Runs all updates that are newer than the current version.
     *
     */
    'updates'   => [],

];