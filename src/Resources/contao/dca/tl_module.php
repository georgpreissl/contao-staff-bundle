<?php

// Add palettes to tl_module
$GLOBALS['TL_DCA']['tl_module']['palettes']['staff_list'] = '{title_legend},name,headline,type;'.
															'{config_legend},staff_archives,description;'.
															'{redirect_legend},jumpTo;'.
															'{protected_legend:hide},protected;'.
															'{template_legend:hide},staff_template,customTpl;'.
															'{image_legend:hide},imgSize;'.
															'{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['staff_reader'] = '{title_legend},name,headline,type;'.
															'{config_legend},staff_archives,overviewPage,customLabel;'.
															'{protected_legend:hide},protected;'.
															'{template_legend:hide},staff_template,customTpl;'.
															'{image_legend:hide},imgSize;'.
															'{expert_legend:hide},guests,cssID,space';

dump($GLOBALS['TL_DCA']['tl_module']['fields']);

// Add fields to tl_module
$GLOBALS['TL_DCA']['tl_module']['fields']['staff_archives'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['staff_archives'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_stafflist', 'getStaffDivisions'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['description'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['staff_description'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr'),
	'sql'                     => "text NULL"
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

class tl_module_stafflist extends Contao\Backend
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
	public function getStaffDivisions()
	{
		// if (!$this->User->isAdmin && !\is_array($this->User->news))
		// {
		// 	return array();
		// }

		$arrDivisions = array();
		$objDivison = $this->Database->execute("SELECT id, title FROM tl_staff_division ORDER BY title");

		while ($objDivison->next())
		{
			// if ($this->User->hasAccess($objDivison->id, 'news'))
			// {
				$arrDivisions[$objDivison->id] = $objDivison->title;
			// }
		}

		return $arrDivisions;
	}


}