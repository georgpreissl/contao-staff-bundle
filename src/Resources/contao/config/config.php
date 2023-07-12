<?php

// Back end modules
array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
	'staff' => array
	(
		'tables' => array('tl_staff_archive', 'tl_staff_employee', 'tl_staff_department')
	)
));

// Front end modules
$GLOBALS['FE_MOD']['miscellaneous']['stafflist'] = 'GeorgPreissl\Staff\ModuleStaffList';
$GLOBALS['FE_MOD']['miscellaneous']['staffreader'] = 'GeorgPreissl\Staff\ModuleStaffReader';

// Models
$GLOBALS['TL_MODELS']['tl_staff_archive'] = 'GeorgPreissl\Staff\StaffArchiveModel';
$GLOBALS['TL_MODELS']['tl_staff_employee'] = 'GeorgPreissl\Staff\StaffEmployeeModel';
$GLOBALS['TL_MODELS']['tl_staff_department'] = 'GeorgPreissl\Staff\StaffDepartmentModel';

// Content elements
$GLOBALS['TL_CTE']['includes']['staff'] = 'GeorgPreissl\Staff\ElementStaff';
