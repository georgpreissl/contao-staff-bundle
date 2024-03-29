<?php

namespace GeorgPreissl\Staff;



/**
 * Class ModuleStaffReader
 *
 * Front end module "staff reader".
 */
class ModuleStaffReader extends ModuleStaff
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_staffreader';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['staff_reader'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && isset($_GET['auto_item']) && \Config::get('useAutoItem'))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		// Return an empty string if "items" is not set (to combine list and reader on same page)
		if (!\Input::get('items'))
		{
			return '';
		}

		$this->staff_archives = \StringUtil::deserialize($this->staff_archives);

		if (empty($this->staff_archives) || !\is_array($this->staff_archives))
		{
			throw new InternalServerErrorException('The news reader ID ' . $this->id . ' has no archives specified.');
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->Template->articles = '';

		if ($this->overviewPage)
		{
			$this->Template->referer = \PageModel::findById($this->overviewPage)->getFrontendUrl();
			$this->Template->back = $this->customLabel ?: ($GLOBALS['TL_LANG']['MSC']['staffOverview'] ?? null);
		}
		else
		{
			trigger_deprecation('contao/news-bundle', '4.13', 'If you do not select an overview page in the news reader module, the "go back" link will no longer be shown in Contao 5.0.');

			$this->Template->referer = 'javascript:history.go(-1)';
			$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		}


		// Get the employee
		$objEmployee = StaffEmployeeModel::findPublishedByParentAndIdOrAlias(\Input::get('items'), $this->staff_archives);
// dump($objEmployee);
		if ($objEmployee === null)
		{
			// throw new \PageNotFoundException('Page not found: ' . \Environment::get('uri'));
		}

		// Set the default template
		if (!$this->staff_template)
		{
			$this->staff_template = 'staff_full';
		}
		// $objStaff = new Staff();
		// $objStaff->staff_template = $this->staff_template;
		// $objStaff->imgSize = $this->imgSize;
		$arrEmployee = $this->parseEmployee($objEmployee);
		$this->Template->employees = $arrEmployee;


	}
}
