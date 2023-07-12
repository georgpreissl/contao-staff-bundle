<?php

declare(strict_types=1);

namespace GeorgPreissl\Staff;

use Contao\Model;

/**
 * Reads and writes departments.
 */
class StaffDepartmentModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_staff_department';
}