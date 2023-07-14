<?php

namespace GeorgPreissl\Staff;




class ModuleStaffList extends ModuleStaff
{


	protected $strTemplate = 'mod_stafflist';


	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['stafflist'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		// $objStaff = new Staff();
		$this->staff_archives = $this->sortOutProtected(\StringUtil::deserialize($this->staff_archives));
		// dump($this->staffFilterDepartments);
		// $this->staff_departments = \StringUtil::deserialize($this->staff_departments);

		// $this->staff_archives = \StringUtil::deserialize($this->staff_archives);

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


		// Determine sorting
		$t = StaffEmployeeModel::getTable();
		$order = '';

		switch ($this->staff_order)
		{
			case 'order_sorting_asc':
				$order .= "$t.sorting";
				break;

			case 'order_sorting_desc':
				$order .= "$t.sorting DESC";
				break;

			case 'order_surname_asc':
				$order .= "$t.surname";
				break;

			case 'order_surname_desc':
				$order .= "$t.surname DESC";
				break;
				
			case 'order_entrydate_asc':
				$order .= "$t.entryDate";
				break;
				
			case 'order_entrydate_desc':
				$order .= "$t.entryDate DESC";
				break;

			case 'order_random':
				$order .= "RAND()";
				break;

			default:
				$order .= "$t.sorting ASC";
		}

		// $objEmployees = StaffEmployeeModel::findPublishedByPids($this->staff_archives,array('order'=>$order,'departments'=>$this->staff_departments));
		$objEmployees = StaffEmployeeModel::findPublishedByPids($this->staff_archives,array('order'=>$order));


		

		if ($objEmployees !== null)
		{
			// dump($this->staff_departments);
			$this->Template->description = $this->staff_description;
			$this->Template->employees = $this->parseEmployees($objEmployees);
		}

	}





}
