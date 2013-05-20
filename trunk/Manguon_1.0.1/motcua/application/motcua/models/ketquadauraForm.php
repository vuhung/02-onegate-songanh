<?php
/*
 * ketquadauraForm
 * @copyright  2009 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
class ketquadauraForm extends Zend_Form
{
	/**
	 * Instance of specific form...
	 *
	 * @param  $options
	 */
	public function __construct($options = null)
    {
        parent::__construct($options);       
       
        $this->setAttribs(array(
            'name'  => 'ketquadauraForm', 
        	'method'=>'post',      	
        ));       
       	
        $tenThuTuc = new Zend_Form_Element_Text('TENKETQUA');
        $tenThuTuc->setRequired(true)
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
       
		$chuThich= new Zend_Form_Element_Textarea('CHUTHICH');
        $chuThich ->setOptions(array('cols'=>'60','rows'=>'4'))
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->addFilter('StringTrim')   
		->setRequired(false)     
        ->setDecorators(array('ViewHelper'));
		       
   		$checkactive = new Zend_Form_Element_CheckBox('ACTIVE');
		$checkactive->setCheckedValue('1')
		->setUncheckedValue('0')		
		->setValue(1)
		->setDecorators(array('ViewHelper'));

        $this->addElement($tenThuTuc);
        $this->addElement($chuThich);
		$this->addElement($checkactive);
		
		
	}
}