<?php

namespace GeorgPreissl\Staff;


abstract class ModuleStaff extends \Module
{


	protected function parseEmployees($objEmployees)
	{

		foreach ($objEmployees as $objEmployee)
		{
			$arrEmployees[] = $this->parseEmployee($objEmployee);
		}

		return $arrEmployees;
	}

	protected function parseEmployee($objEmployee)
	{



		$objTemplate = new \FrontendTemplate($this->staff_template ?: 'staff_simple');
		$objTemplate->setData($objEmployee->row());
		

		// $objTemplate->class = $strClass;
		// $objTemplate->name = $objEmployee->forename;
		// $objTemplate->subHeadline = $objArticle->subheadline;
		// $objTemplate->hasSubHeadline = $objArticle->subheadline ? true : false;
		if($this->jumpTo){
			$objPage = \PageModel::findByPk($this->jumpTo);
			$params = (\Config::get('useAutoItem') ? '/' : '/items/') . ($objEmployee->alias ?: $objEmployee->id);
			$objTemplate->link = \StringUtil::ampersand($objPage->getFrontendUrl($params));
		
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


		// dump($this->jumpTo);

				// if (($objTarget = $objEmployee->getRelated('jumpTo')) instanceof \PageModel)
				// {
				// dump("jo");
				// 	/** @var PageModel $objTarget */
				// 	self::$arrUrlCache[$strCacheKey] = StringUtil::ampersand($blnAbsolute ? $objTarget->getAbsoluteUrl() : $objTarget->getFrontendUrl());
				// }

		// $objTemplate->linkHeadline = $this->generateLink($objEmployee->headline, $objArticle, $blnAddArchive);

		// $objTemplate->more = $this->generateLink($GLOBALS['TL_LANG']['MSC']['more'], $objArticle, $blnAddArchive, true);
		// $objTemplate->link = News::generateNewsUrl($objArticle, $blnAddArchive);
		// $objTemplate->archive = $objArticle->getRelated('pid');
		// $objTemplate->count = $intCount; // see #5708
		// $objTemplate->text = '';
		// $objTemplate->hasText = false;
		// $objTemplate->hasTeaser = false;
		// $objTemplate->hasReader = true;

		return $objTemplate->parse(); 

	}
}
