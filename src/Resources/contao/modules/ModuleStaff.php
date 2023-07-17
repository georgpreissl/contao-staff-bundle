<?php

namespace GeorgPreissl\Staff;

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Module;


abstract class ModuleStaff extends \Module
{


	protected function sortOutProtected($arrArchives)
	{
		if (empty($arrArchives) || !is_array($arrArchives))
		{
			return $arrArchives;
		}

		$objArchive = StaffArchiveModel::findMultipleByIds($arrArchives);
		$arrArchives = array();

		if ($objArchive !== null)
		{
			$security = \System::getContainer()->get('security.helper');

			while ($objArchive->next())
			{
				if ($objArchive->protected && !$security->isGranted(ContaoCorePermissions::MEMBER_IN_GROUPS, \StringUtil::deserialize($objArchive->memberGroups, true)))
				{
					continue;
				}
				$arrArchives[] = $objArchive->id;
			}
		}

		return $arrArchives;
	}


	public function parseEmployees($objEmployees)
	{
		$arrEmployees = array();

		if($this->staffFilterDepartments)
		{
			$arrDepartmentsSetting = \StringUtil::deserialize($this->staff_departments) ? : array();
		}

		foreach ($objEmployees as $objEmployee)
		{
			$booAddEmployee = true;
			if($this->staffFilterDepartments)
			{
				$arrDepartments = \StringUtil::deserialize($objEmployee->departments) ? : array();

				if(!array_intersect($arrDepartments, $arrDepartmentsSetting))
				{
					$booAddEmployee = false;
				};
			}
			if($booAddEmployee)
			{
				$arrEmployees[] = $this->parseEmployee($objEmployee);
			}
		}

		return $arrEmployees;
	}

	public function parseEmployee($objEmployee,$strClass='')
	{

		$objTemplate = new \FrontendTemplate($this->staff_template ?: 'staff_short');
		$objTemplate->setData($objEmployee->row());

		if ($objEmployee->cssClass)
		{
			$strClass = ' ' . $objEmployee->cssClass . $strClass;
		}

		$objTemplate->class = $strClass;

		if ($objEmployee->departments)
		{
			// dump($objEmployee->departments);
			$arrDepartments = \StringUtil::deserialize($objEmployee->departments);
			$objDepartments = StaffDepartmentModel::findMultipleByIds($arrDepartments);
			$arrDepartmentsTitles = array();
			if ($objDepartments !== null)
			{
				foreach($objDepartments as $objDepartment)
				{
					$arrDepartmentsTitles[] = $objDepartment->title;
				}
			}
			$objTemplate->department = $arrDepartmentsTitles;
		}

		if(isset($this->jumpTo) && $this->jumpTo ==! 0)
		{
			$objPage = \PageModel::findByPk($this->jumpTo);
			$strParams = (\Config::get('useAutoItem') ? '/' : '/items/') . ($objEmployee->alias ?: $objEmployee->id);
			$objTemplate->link = \StringUtil::ampersand($objPage->getFrontendUrl($strParams));
		}

		$figureBuilder = \System::getContainer()
		->get('contao.image.studio')
		->createFigureBuilder()
		->from($objEmployee->singleSRC)
		->setSize($this->imgSize);

		if (null !== ($figure = $figureBuilder->buildIfResourceExists()))
		{
			$figure->applyLegacyTemplateData($objTemplate, $objEmployee->imagemargin, $objEmployee->floating);
		}	

		// schema.org information
		$objTemplate->getSchemaOrgData = static function () use ($objTemplate, $objEmployee): array
		{
			$jsonLd = StaffFrontend::getSchemaOrgData($objEmployee);

			if ($objTemplate->addImage && $objTemplate->figure)
			{
				$jsonLd['image'] = $objTemplate->figure->getSchemaOrgData();
			}

			return $jsonLd;
		};

		return $objTemplate->parse(); 

	}
}
