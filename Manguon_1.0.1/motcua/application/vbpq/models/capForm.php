<?php
/*
 * capForm
 * @copyright  2009 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
class capForm extends Zend_Form
{
	public function __construct($options = null)
    {
    	
        parent::__construct($options);       
       
        $this->setAttribs(array(
            'name'  => 'capForm', 
        	'method'=>'post',      	
        ));
        
		$tencap = new Zend_Form_Element_Text('TEN_CAP');
        $tencap->setRequired(true)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'500','size'=>'50','style'=>'width:500px'))
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
		$this->addElement($tencap);
	}
}