<?php

// Add palettes to tl_module

$GLOBALS['TL_DCA']['tl_module']['palettes']['stafflist'] = '{title_legend},name,headline,type;'.
															'{config_legend},staff_archives,staff_departments,staff_description,staff_order;'.
															'{redirect_legend},jumpTo;'.
															'{protected_legend:hide},protected;'.
															'{template_legend:hide},staff_template,customTpl;'.
															'{image_legend:hide},imgSize;'.
															'{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['staffreader'] = '{title_legend},name,headline,type;'.
															'{config_legend},staff_archives,overviewPage,customLabel;'.
															'{protected_legend:hide},protected;'.
															'{template_legend:hide},staff_template,customTpl;'.
															'{image_legend:hide},imgSize;'.
															'{expert_legend:hide},guests,cssID,space';

// dump($GLOBALS['TL_DCA']['tl_module']['fields']);


// Add fields to tl_module

$GLOBALS['TL_DCA']['tl_module']['fields']['staff_archives'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['staff_archives'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_staff', 'getStaffArchives'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['staff_departments'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['staff_departments'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_staff', 'getStaffDepartments'),
	'eval'                    => array('multiple'=>true),
	'sql'                     => "blob NULL"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['staff_description'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['staff_description'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr','rte'=>'tinyMCE'),
	'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['staff_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['staff_order'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_staff', 'getSortingOptions'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) COLLATE ascii_bin NOT NULL default 'order_date_desc'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['staff_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['staff_template'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback' => static function ()
	{
		return Controller::getTemplateGroup('staff_');
	},
	'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) COLLATE ascii_bin NOT NULL default ''"
);

class tl_module_staff extends Contao\Backend
{

	// /**
	//  * Import the back end user object
	//  */
	// public function __construct()
	// {
	// 	parent::__construct();
	// 	$this->import('Contao\BackendUser', 'User');
	// }

	/**
	 * Get all divisions and return them as array
	 *
	 * @return array
	 */
	public function getStaffArchives()
	{
		// if (!$this->User->isAdmin && !\is_array($this->User->news))
		// {
		// 	return array();
		// }

		$arrArchives = array();
		$objArchive = $this->Database->execute("SELECT id, title FROM tl_staff_archive ORDER BY title");

		while ($objArchive->next())
		{
			// if ($this->User->hasAccess($objArchive->id, 'news'))
			// {
				$arrArchives[$objArchive->id] = $objArchive->title;
			// }
		}

		return $arrArchives;
	}


	public function getStaffDepartments()
	{
		// if (!$this->User->isAdmin && !\is_array($this->User->news))
		// {
		// 	return array();
		// }

		$arrDepartments = array();
		$objDepartment = $this->Database->execute("SELECT id, title FROM tl_staff_department ORDER BY title");

		while ($objDepartment->next())
		{
			// if ($this->User->hasAccess($objDepartment->id, 'news'))
			// {
				$arrDepartments[$objDepartment->id] = $objDepartment->title;
			// }
		}

		return $arrDepartments;
	}





	/**
	 * Return the sorting options
	 *
	 * @param DataContainer $dc
	 *
	 * @return array
	 */
	public function getSortingOptions(DataContainer $dc)
	{
		// if ($dc->activeRecord && $dc->activeRecord->type == 'newsmenu')
		// {
		// 	return array('order_date_asc', 'order_date_desc');
		// }

		return array('order_sorting_asc', 'order_sorting_desc','order_entrydate_asc', 'order_entrydate_desc', 'order_surname_asc', 'order_surname_desc', 'order_random');
	}	

}