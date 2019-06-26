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

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['cd_list'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$objDivisions = \StaffDivisionModel::findAll();

		// Return if no divisions were found
		if ($objDivisions === null)
		{
			return;
		}


		$arrDivisions = array();

		// Generate CDs
		while ($objDivisions->next())
		{



        $objEmployees = \StaffEmployeeModel::findByParent($objDivisions->id);

			// Return if there are no Employees
			if ($objEmployees === null)
			{
				return;
			}

			$count = 0;
			$arrEmployees = array();

			// Generate Employees
			while ($objEmployees->next())
			{
				// printf('<pre>%s</pre>', print_r($objEmployees,true));
				// printf('<pre>%s</pre>', print_r($objEmployees->position,true));
				$strLink = '';

				// Generate a jumpTo link
				if ($this->jumpTo > 0)
				{
					$objJump = \PageModel::findByPk($this->jumpTo);
					// printf('<pre>%s</pre>', print_r($objJump,true));
					if ($objJump !== null)
					{
						// var_dump("fuck");
						$strLink = $this->generateFrontendUrl($objJump->row(), '/items/%s');
					}
				}
				// $strLink zb.: "staff-reader/items/%s.html"

				$strPhoto = '';
				$objPhoto = \FilesModel::findByPk($objEmployees->photo);

				// Add cover image
				if ($objPhoto !== null)
				{
					$strPhoto = \Image::getHtml(\Image::get($objPhoto->path, '100', '100', 'center_center'));
				}

				$arrEmployees[] = array
				(
					'number' => ++$count,
					'name' => $objEmployees->name_prefix . $objEmployees->forename . " " . $objEmployees->surname . $objEmployees->name_appendix,
					'position' => $objEmployees->position,
					'link' => strlen($strLink) ? sprintf($strLink, $objEmployees->id) : '',
					'photo' => $strPhoto

				);
			}









			$arrDivisions[] = array
			(
				'title' => $objDivisions->title,
				'description' => $objDivisions->description,
				'employees' => $arrEmployees
			);
			// printf('<pre>%s</pre>', print_r($arrDivisions,true));
		}

		$this->Template->divisions = $arrDivisions;
	}
}
