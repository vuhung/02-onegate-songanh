<?php
/*
 * bieumauForm
 * @copyright  2009 Unitech
 * @license
 * @version
 * @link
 * @since
 * @deprecated
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
require_once 'motcua/models/LoaiModel.php';
require_once 'motcua/models/ThuTucModel.php';
class bieumauForm extends Zend_Form
{
	/**
     * Get list 'loai's
     *
     * @return array
     */
	 function optionLoais()
 	 {
 		$thutucModel = new ThuTucModel();
       	$dataResult = $thutucModel->GetAllThuTucs();
       	$counter=0;
		$arrReturn=array();
		if($dataResult!=null)
		{
			while ( $counter < count($dataResult))
			{
				if ($dataResult[$counter] > 0)
				{
					$subArray=$dataResult[$counter];
					try
					{
						$id_menu=$subArray['ID_THUTUC'];
						$name=$subArray['TENTHUTUC'];
						$arrReturn+=array($id_menu=>$name);

					}
					catch(Exception $er){ };
				}
				$counter++;
			}
		}

		return $arrReturn;
	}
	/**
	 * Instance of specific form...
	 *
	 * @param  $options
	 */
	public function __construct($options = null)
    {
        parent::__construct($options);
		$pathdir='images/bieumau/';
        $this->setAttribs(array(
            'name'  => 'loaiHsForm',
        	'method'=>'post',
        ));

        $tenbieumau = new Zend_Form_Element_Text('TEN_BIEUMAU');
        $tenbieumau->setRequired(true)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'500','size'=>'50'))
        ->addFilter('StringTrim')
        ->addValidators(
             array
             (
				array
				(
					'validator' => 'NotEmpty',
					'breakChainOnFailure' => true,
				),
				array
				(
					'validator' => 'stringLength',
					'options' => array(1, 500)),
				)
			)
        ->setDecorators(array('ViewHelper'));
       
		$comboBoxLoais = new Zend_Form_Element_Select('ID_THUTUC');	    
		$comboBoxLoais->addMultiOptions($this->optionLoais())	  
	    ->setOptions(array('style'=>'width:500px','id'=>'ID_THUTUC'))   
	    ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
	    ->setOrder(0)
		->setRequired(true)
	    ->addValidators(
             array
             (
				array
				(
					'validator' => 'existItemOnTable',
					'options' => array('ID_THUTUC','MOTCUA_THUTUC_CANCO')),
				)
			)
	    ->setDecorators(array('ViewHelper'));

   		$chuthich =new Zend_Form_Element_Textarea('CANCU');
        $chuthich->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('style'=>'width:500px','rows'=>'5'))
        ->setDecorators(array('ViewHelper'))
        ->addValidators(
             array
             (
				array
				(
					'validator' => 'stringLength',
					'options' => array(1, 1000)
				),

			 )
		);
		$require_file=true;
		if($options=="edit") $require_file=false;
		$filebieumau = new Zend_Form_Element_File('FILE_BIEUMAU');
        $filebieumau ->setDestination($pathdir)
        		->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
				->setRequired($require_file)
                ->addValidator('File_Count', false, 1)
                ->addValidator('File_Size', false, 512000)
                ->addValidator('File_NotExists',$pathdir)
                ->addValidator('File_Extension', false, 'doc,pdf')
                ->setDecorators(array('File'));

		$soluong = new Zend_Form_Element_Text('SOLUONG');
        $soluong->setRequired(false)
        ->setOptions(array('maxlength'=>'2','size'=>'2'))
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->addFilter('StringTrim')
        ->addValidator('Int')
        ->setDecorators(array('ViewHelper'));

        $this->addElement($tenbieumau);
        $this->addElement($filebieumau);
	    $this->addElement($comboBoxLoais);
		$this->addElement($chuthich);
		$this->addElement($soluong);
	}
}