<?php
/**
 * Mage-World
 *
 * @category    Mage-World
 * @package     MW
 * @author      Mage-world Developer
 *
 * @copyright   Copyright (c) 2018 Mage-World (https://www.mage-world.com/)
 */

namespace MW\Ddate\Model\Source\Ddate;
/**
 * Class DeliveryStatus
 * @package MW\Ddate\Model\Source\Ddate
 */
class DeliveryStatus
{
    const NOT_SHIPPED = 0;
    const SHIPPED = 1;
    const PART_OF_SHIPPED = 2;
    const CANCELED = 3;
}