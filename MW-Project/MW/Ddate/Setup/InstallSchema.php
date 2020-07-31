<?php
/**
 * Mage-World
 *
 *  @category    Mage-World
 *  @package     MW
 *  @author      Mage-world Developer
 *
 *  @copyright   Copyright (c) 2018 Mage-World (https://www.mage-world.com/)
 */
namespace MW\Ddate\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'mwddate'
         */
        if (!$installer->getConnection()->isTableExists($installer->getTable('mwddate'))) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mwddate'))
                ->addColumn(
                    'ddate_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    '8',
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Ddate Id'
                )
                ->addColumn(
                    'ddate',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                    null,
                    [],
                    'Ddate'
                )
                ->addColumn(
                    'dtime',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '50',
                    ['nullable' => false],
                    'Dtime'
                )
                ->addColumn(
                    'ampm',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '1'],
                    'Am - Pm'
                )
                ->addColumn(
                    'holiday',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'holiday'
                )
                ->addColumn(
                    'ordered',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '8',
                    ['nullable' => false, 'default' => '0'],
                    'Ordered'
                )
                ->addColumn(
                    'total_ordered',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '8',
                    ['nullable' => false, 'default' => '0'],
                    'Total Ordered'
                )
                ->addColumn(
                    'dtimetext',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '50',
                    ['nullable' => false],
                    'Dtime text'
                )
                ->addIndex(
                    $installer->getIdxName('mwddate', ['ddate_id', 'ordered', 'ddate', 'dtime']),
                    ['ddate_id', 'ordered', 'ddate', 'dtime']
                )
                ->setComment('MW Ddate');

            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'mwddate_store'
         */
        if (!$installer->getConnection()->isTableExists($installer->getTable('mwddate_store'))) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mwddate_store'))
                ->addColumn(
                    'ddate_store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    '8',
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Ddate Store Id'
                )
                ->addColumn(
                    'increment_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '50',
                    [],
                    'Increment Id'
                )
                ->addColumn(
                    'ddate_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '8',
                    ['nullable' => false, 'default' => '1'],
                    'Ddate Id'
                )
                ->addColumn(
                    'ddate_comment',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'Ddate Comment'
                )
                ->addColumn(
                    'delivery_status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '8',
                    ['nullable' => false, 'default' => 0],
                    'Delivery Status'
                )
                ->addColumn(
                    'sales_order_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    '11',
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'Sales Order Id'
                )
                ->addIndex(
                    $installer->getIdxName('mwddate_store', ['ddate_store_id', 'ddate_id']),
                    ['ddate_store_id', 'ddate_id']
                )
                ->setComment('MW Ddate Store');

            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'mwdtime'
         */
        if (!$installer->getConnection()->isTableExists($installer->getTable('mwdtime'))) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mwdtime'))
                ->addColumn(
                    'dtime_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    '8',
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Dtime Id'
                )
                ->addColumn(
                    'dtime',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '50',
                    [],
                    'Dtime'
                )
                ->addColumn(
                    'is_active',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'is_active'
                )
                ->addColumn(
                    'mon',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'Monday'
                )
                ->addColumn(
                    'tue',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'Tuesday'
                )
                ->addColumn(
                    'wed',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'Wednesday'
                )
                ->addColumn(
                    'thu',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'Thursday'
                )
                ->addColumn(
                    'fri',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'Friday'
                )
                ->addColumn(
                    'sat',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'Saturday'
                )
                ->addColumn(
                    'sun',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'Sunday'
                )
                ->addColumn(
                    'specialday',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '1',
                    ['nullable' => false, 'default' => '0'],
                    'SpecialDay'
                )
                ->addColumn(
                    'maximum_booking',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    '1',
                    ['nullable' => false, 'default' => '10'],
                    'Maximum Booking'
                )
                ->addColumn(
                    'interval',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '20',
                    ['nullable' => false, 'default' => '5h-6h'],
                    'Interval'
                )
                ->addColumn(
                    'special_day',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '50',
                    ['nullable' => true, 'default' => null],
                    'Special Day'
                )
                ->addColumn(
                    'dtimesort',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '5',
                    ['unsigned' => true, 'default' => '0'],
                    'Dtime Sort'
                )
                ->addColumn(
                    'store_ids',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '',
                    ['nullable' => true, 'default' => null],
                    'Store Ids'
                )
                ->addIndex(
                    $installer->getIdxName('mwdtime', ['dtime_id', 'dtime', 'dtimesort']),
                    ['dtime_id', 'dtime', 'dtimesort']
                )
                ->setComment('MW DTime');

            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'mwdtime_store'
         */
//        if (!$installer->getConnection()->isTableExists($installer->getTable('mwdtime_store'))) {
//            $table = $installer->getConnection()
//                ->newTable($installer->getTable('mwdtime_store'))
//                ->addColumn(
//                    'dtime_id',
//                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
//                    '8',
//                    ['nullable' => false, 'default' => 1],
//                    'Dtime Id'
//                )
//                ->addColumn(
//                    'store_id',
//                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
//                    '8',
//                    ['nullable' => false, 'default' => 0],
//                    'Store Id'
//                )
//                ->addIndex(
//                    $installer->getIdxName('mwdtime_store', ['dtime_id', 'store_id']),
//                    ['dtime_id', 'store_id']
//                )
//                ->setComment('Dtime Store');
//
//            $installer->getConnection()->createTable($table);
//        }

        $installer->endSetup();
    }
}
