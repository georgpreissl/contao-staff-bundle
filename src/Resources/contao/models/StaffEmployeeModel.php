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
	 * Find a published news item from one or more news archives by its ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return NewsModel|null The model or null if there are no news
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		if (empty($arrPids) || !\is_array($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = !preg_match('/^[1-9]\d*$/', $varId) ? array("BINARY $t.alias=?") : array("$t.id=?");
		$arrColumns[] = "$t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")";

		if (!static::isPreviewMode($arrOptions))
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
		}

		return static::findOneBy($arrColumns, $varId, $arrOptions);
	}










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
