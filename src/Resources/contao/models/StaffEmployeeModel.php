<?php

namespace GeorgPreissl\Staff;

use Model;


/**
 * Class StaffEmployeeModel
 *
 * Reads and writes employees.
 */
class StaffEmployeeModel extends Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_staff_employee';


    /**
     * Find the employees by parent ID
     * @param integer
     * @return \Model|null
     */
    public static function findByParent($intPid)
    {
        $t = static::$strTable;

        $arrColumns = array("$t.pid=?");
        $arrValues = array($intPid);
        $arrOptions['order'] = "$t.sorting";

        return static::findBy($arrColumns, $arrValues, $arrOptions);
    }
}
