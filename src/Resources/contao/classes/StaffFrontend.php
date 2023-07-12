<?php

namespace GeorgPreissl\Staff;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Provide methods regarding news archives.
 */
class StaffFrontend extends \Frontend
{
	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();

	/**
	 * Page cache array
	 * @var array
	 */
	private static $arrPageCache = array();



	/**
	 * Return the schema.org data from a employee
	 *
	 * @param StaffEmployeeModel $objEmployee
	 *
	 * @return array
	 */
	public static function getSchemaOrgData(StaffEmployeeModel $objEmployee): array
	{
		$htmlDecoder = \System::getContainer()->get('contao.string.html_decoder');

		$jsonLd = array(
			'@type' => 'Person',
			'identifier' => '#/schema/person/' . $objEmployee->id,
			// 'url' => self::generateNewsUrl($objEmployee),
			// 'headline' => $htmlDecoder->inputEncodedToPlainText($objEmployee->surname),
			'givenName' => $htmlDecoder->inputEncodedToPlainText($objEmployee->forename),
			'familyName' => $htmlDecoder->inputEncodedToPlainText($objEmployee->surname),
			'jobTitle' => $htmlDecoder->inputEncodedToPlainText($objEmployee->position),
			'birthDate' => $objEmployee->year_of_birth
		);

		if ($objEmployee->description)
		{
			$jsonLd['description'] = $htmlDecoder->htmlToPlainText($objEmployee->description);
		}



		return $jsonLd;
	}

}

