<?php

/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['staff_list']   = '{title_legend},name,headline,type;{config_legend},staff_archives;{redirect_legend},jumpTo;{protected_legend:hide},protected;{template_legend:hide},staff_template,customTpl;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['staff_reader'] = '{title_legend},name,headline,type;{config_legend},staff_archives,overviewPage,customLabel;{protected_legend:hide},protected;{template_legend:hide},staff_template,customTpl;{expert_legend:hide},guests,cssID,space';

// $GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader']   = '{title_legend},name,headline,type;{config_legend},news_archives,overviewPage,customLabel;{template_legend:hide},news_metaFields,news_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';


// Add fields to tl_module
$GLOBALS['TL_DCA']['tl_module']['fields']['staff_archives'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_archives'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_stafflist', 'getStaffArchives'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['staff_template'] = array
(
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

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Contao\BackendUser', 'User');
	}

	/**
	 * Get all news archives and return them as array
	 *
	 * @return array
	 */
	public function getStaffArchives()
	{
		if (!$this->User->isAdmin && !\is_array($this->User->news))
		{
			return array();
		}

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT id, title FROM tl_staff_division ORDER BY title");

		while ($objArchives->next())
		{
			if ($this->User->hasAccess($objArchives->id, 'news'))
			{
				$arrArchives[$objArchives->id] = $objArchives->title;
			}
		}

		return $arrArchives;
	}


}