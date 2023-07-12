<?php



namespace GeorgPreissl\Staff;


class ElementStaff extends \ContentElement
{
    protected $strTemplate = 'ce_staff';

	public function generate()
	{
        $objEmployee = StaffEmployeeModel::findByPk($this->staff_employee);

		if ($objEmployee !== null)
		{
            $this->employee = $objEmployee;
            if (TL_MODE == 'BE')
            {
            return $objEmployee->forename . ' ' .$objEmployee->surname;
            }
            return parent::generate();
		}
	}



    protected function compile()
    {
		if ($this->employee)
		{
            $objStaff = new ModuleStaffList($this->employee);
            $objStaff->staff_template = $this->staff_template ? :'staff_short';
            $objStaff->imgSize = $this->size;
            if($this->staff_jumpto){

                $objStaff->jumpTo = $this->staff_jumpto;
            }
			$this->Template->html = $objStaff->parseEmployee($this->employee);
            
		}        
    }
}