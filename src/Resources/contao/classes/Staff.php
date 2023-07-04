<?php

namespace GeorgPreissl\Staff;




class Staff
{


	public function parseEmployees($objEmployees)
	{

		foreach ($objEmployees as $objEmployee)
		{
			$arrEmployees[] = $this->parseEmployee($objEmployee);
		}

		return $arrEmployees;
	}

	public function parseEmployee($objEmployee)
	{

		$objTemplate = new \FrontendTemplate($this->staff_template ?: 'staff_simple');
		$objTemplate->setData($objEmployee->row());

		if(isset($this->jumpTo))
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


		return $objTemplate->parse(); 

	}
}
