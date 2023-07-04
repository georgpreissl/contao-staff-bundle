<?php

// Back end modules
array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
	'staff' => array
	(
		'tables' => array('tl_staff_division', 'tl_staff_employee')
	)
));

// Front end modules
$GLOBALS['FE_MOD']['miscellaneous']['staff_list'] = 'GeorgPreissl\Staff\ModuleStaffList';
$GLOBALS['FE_MOD']['miscellaneous']['staff_reader'] = 'GeorgPreissl\Staff\ModuleStaffReader';

// Models
$GLOBALS['TL_MODELS']['tl_staff_division'] = 'GeorgPreissl\Staff\StaffDivisionModel';
$GLOBALS['TL_MODELS']['tl_staff_employee'] = 'GeorgPreissl\Staff\StaffEmployeeModel';

// Content elements
$GLOBALS['TL_CTE']['includes']['staff'] = 'GeorgPreissl\Staff\ElementStaff';
