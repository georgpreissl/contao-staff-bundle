<?php

namespace GeorgPreissl\Staff;



/**
 * Class ModuleCdList
 *
 * Front end module "cd list".
 */
class ModuleStaffList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_stafflist';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['staff_list'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->staff_archives = \StringUtil::deserialize($this->staff_archives);

		// Return if there are no archives
		if (empty($this->staff_archives) || !\is_array($this->staff_archives))
		{
			return '';
		}


		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
// dump($this->staff_archives);
		$objEmployees = StaffEmployeeModel::findPublishedByPids($this->staff_archives);



		if ($objEmployees !== null)
		{
			$objStaff = new Staff();
			$objStaff->staff_template = $this->staff_template;
			$objStaff->imgSize = $this->imgSize;
			$objStaff->jumpTo = $this->jumpTo;
			$this->Template->description = $this->description;
			$this->Template->employees = $objStaff->parseEmployees($objEmployees);
		}

	}
}
