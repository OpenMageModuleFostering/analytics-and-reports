<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('freento_aconnector/keys'))
    ->addColumn('key_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true,
        ), 'Private Key ID')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable' => false,
        ), 'Admin User ID')
    ->addColumn('private_key', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        'default' => '',
        ), 'Private Key')
    ->addForeignKey(
        $installer->getFkName('admin_user', 'user_id', 'freento_aconnector_keys','user_id'),
        'user_id',
        $installer->getTable('admin_user'), 
        'user_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, 
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Aconnector Admin Keys')
;

$installer->getConnection()->createTable($table);
$installer->endSetup();