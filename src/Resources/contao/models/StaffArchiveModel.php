<?php

namespace GeorgPreissl\Staff;

use Model;

/**
 * Class StaffArchiveModel
 *
 * Reads and writes divisions.
 */
class StaffArchiveModel extends Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_staff_archive';






	public static function findPublishedByIds($arrPids, array $arrOptions=array())
	{
		if (empty($arrPids) || !\is_array($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.id IN(" . implode(',', array_map('\intval', $arrPids)) . ")");





		return static::findBy($arrColumns, null, $arrOptions);
	}



}
