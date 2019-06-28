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

        $arrColumns = array("$t.pid=? AND $t.published='1'");





        $arrValues = array($intPid);
        $arrOptions['order'] = "$t.sorting";

        return static::findBy($arrColumns, $arrValues, $arrOptions);
    }






	public static function findPublishedByPids($arrPids, array $arrOptions=array())
	{
		if (empty($arrPids) || !\is_array($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")");





		return static::findBy($arrColumns, null, $arrOptions);
	}










}
