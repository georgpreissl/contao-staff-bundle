<?php

namespace GeorgPreissl\Staff;



/**
 * Class ModuleStaffReader
 *
 * Front end module "staff reader".
 */
class ModuleStaffReader extends \Module
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

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['cd_reader'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if there are no items
		if (!\Input::get('items'))
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
		// printf('<pre>%s</pre>', print_r(\Input::get('items'),true));
	    $objEmployee = \StaffEmployeeModel::findByPk(\Input::get('items'));
	    // printf('<pre>%s</pre>', print_r($objEmployee->id,true));
	    $objDivision = \StaffDivisionModel::findByPk($objEmployee->pid);


		// Display a 404 page if the CD was not found
		if ($objEmployee === null)
		{
			global $objPage;
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($objPage->id);
		}
		$objPhoto = \FilesModel::findByPk($objEmployee->photo);

		// Add photo 
		if ($objPhoto !== null)
		{
			$this->Template->photo = \Image::getHtml(\Image::get($objPhoto->path, '100', '100', 'center_center'));
		}
		$this->Template->name = $objEmployee->name_prefix . $objEmployee->forename . " " . $objEmployee->surname . $objEmployee->name_appendix;
		$this->Template->forename = $objEmployee->forename;
		$this->Template->surname = $objEmployee->surname;
		$this->Template->position = $objEmployee->position;
		$this->Template->division = $objDivision->title;
		$this->Template->year_of_birth = $objEmployee->year_of_birth;

 
		$this->Template->year_of_birthLabel = $GLOBALS['TL_LANG']['MSC']['year_of_birth'];
		$this->Template->divisionLabel = $GLOBALS['TL_LANG']['MSC']['division'];
		$this->Template->durationLabel = $GLOBALS['TL_LANG']['MSC']['song_duration'];
	}
}
