<?php
/*
 * vanbanForm
 * @copyright  2009 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
require_once 'qtht/models/CoQuanModel.php';
require_once 'qtht/models/LinhVucVanBanModel.php';
require_once 'vbpq/models/capModel.php';
class vanbanForm extends Zend_Form
{
	private $_coquanModel;
	private $_linhvucvanbanModel;
	private $_capModel;
	function getCoQuans()
	{
		$this->_coquanModel=new CoQuanModel();						
		$dataResult = $this->_coquanModel->getAllCoQuan();
		$arrReturn=array();
		if(count($dataResult)>0)
		{
			foreach($dataResult as $row)
			{
				$idcoquan=$row['ID_CQ'];
				$name=$row['NAME'];						
				$arrReturn+=array($idcoquan=>$name);
			}
		}       	
		return $arrReturn;
	}
	function getLinhVucs()
	{
		$this->_linhvucvanbanModel=new LinhVucVanBanModel();				
		$dataResult = $this->_linhvucvanbanModel->getAllLinhVuc();
		$arrReturn=array();
		if(count($dataResult)>0)
		{
			foreach($dataResult as $row)
			{
				$idlinhvuc=$row['ID_LVVB'];
				$name=$row['NAME'];						
				$arrReturn+=array($idlinhvuc=>$name);
			}
		}       	
		return $arrReturn;
	}
	function getCaps()
	{
		$this->_capModel=new capModel();				
		$dataResult = $this->_capModel->getAllCap();
		$arrReturn=array();
		if(count($dataResult)>0)
		{
			foreach($dataResult as $row)
			{
				$id=$row['ID_CAP'];
				$name=$row['TEN_CAP'];						
				$arrReturn+=array($id=>$name);
			}
		}       	
		return $arrReturn;
	}
	public function __construct($options = null)
    {
    	
        parent::__construct($options);       
       
        $this->setAttribs(array(
            'name'  => 'vanbanForm', 
        	'method'=>'post',      	
        ));
        
		$mavanban = new Zend_Form_Element_Text('MAVANBAN');
        $mavanban->setRequired(true)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'512','size'=>'50','style'=>'width:50px'))
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
					'options' => array(1, 512)),					
				)
			) 
        ->setDecorators(array('ViewHelper'));
        
        
        $sokyhieu = new Zend_Form_Element_Text('SOKYHIEU');
        $sokyhieu->setRequired(true)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'512','size'=>'100','style'=>'width:100px'))
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
					'options' => array(1, 512)),					
				)
			) 
        ->setDecorators(array('ViewHelper'));
        
        $ngaybanhanh = new Zend_Form_Element_Text('NGAYBANHANH');
        $ngaybanhanh->setRequired(true)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'20','size'=>'20','style'=>'width:50px','id'=>'NGAYBANHANH'))
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
					'options' => array(1, 20)
				),				
				array
				(
					'validator' => 'Date',
					'options' => array("d/m/Y", "vi_VN")
				),					
				)
			) 
        ->setDecorators(array('ViewHelper'));
        
        $ngaycohieuluc = new Zend_Form_Element_Text('NGAYCOHIEULUC');
        $ngaycohieuluc->setRequired(false)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'20','size'=>'20','style'=>'width:50px','id'=>'NGAYCOHIEULUC'))
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
					'options' => array(1, 20)
				),
				array
				(
					'validator' => 'Date',
					'options' => array("d/m/Y", "vi_VN")
				),					
			 )
			) 
        ->setDecorators(array('ViewHelper'));
        
        $ngayhethieuluc = new Zend_Form_Element_Text('NGAYHETHIEULUC');
        $ngayhethieuluc->setRequired(false)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'20','size'=>'20','style'=>'width:50px','id'=>'NGAYHETHIEULUC'))
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
					'options' => array(1, 20)
				),
				array
				(
					'validator' => 'Date',
					'options' => array("d/m/Y", "vi_VN")
				),					
				)
			) 
        ->setDecorators(array('ViewHelper'));
        
        $vanbanhuongdan = new Zend_Form_Element_Textarea('VANBANHUONGDAN');
        $vanbanhuongdan->setRequired(false)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('cols'=>'4','rows'=>'5','style'=>'width:540px'))
        ->addFilter('StringTrim')        
        ->setDecorators(array('ViewHelper'));
        
        $trichyeu = new Zend_Form_Element_Textarea('TRICHYEU');
        $trichyeu->setRequired(true)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('cols'=>'4','rows'=>'5','style'=>'width:540px'))
        ->addFilter('StringTrim')        
        ->setDecorators(array('ViewHelper'));
        
        $nguoiky = new Zend_Form_Element_Text('NGUOIKY');
        $nguoiky->setRequired(false)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'512','size'=>'150','style'=>'width:150px'))
        ->addFilter('StringTrim')        
        ->setDecorators(array('ViewHelper'));
        
        $nguoitao = new Zend_Form_Element_Text('NGUOITAO');
        $nguoitao->setRequired(false)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'512','size'=>'40','style'=>'width:50px'))
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
					'options' => array(1, 512)),					
				)
			) 
        ->setDecorators(array('ViewHelper'));
        
        $nguoisuacuoi = new Zend_Form_Element_Text('NGUOISUACUOI');
        $nguoisuacuoi->setRequired(false)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'512','size'=>'40','style'=>'width:50px'))
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
					'options' => array(1, 512)),					
				)
			) 
        ->setDecorators(array('ViewHelper'));
        
        $nguoikiemduyet = new Zend_Form_Element_Text('NGUOIKIEMDUYET');
        $nguoikiemduyet->setRequired(false)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'512','size'=>'40','style'=>'width:50px'))
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
					'options' => array(1, 512)),					
				)
			) 
        ->setDecorators(array('ViewHelper'));
        
        $nguonvanban = new Zend_Form_Element_Text('NGUONVANBAN');
        $nguonvanban->setRequired(false)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'512','size'=>'300','style'=>'width:300px'))
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
					'options' => array(1, 512)),					
				)
			) 
        ->setDecorators(array('ViewHelper'));
        
        
        $coquanbanhanhtext = new Zend_Form_Element_Text('COQUANBANHANH_TEXT');
        $coquanbanhanhtext->setRequired(true)
        ->addFilter('StripTags')
        ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
        ->setOptions(array('maxlength'=>'500','size'=>'300','style'=>'width:300px'))
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
        
        $idcoquan = new Zend_Form_Element_Select('ID_COQUANBANHANH');
		$idcoquan->setRequired(true)
                ->addMultiOptions($this->getCoQuans())
  	            ->setOptions(array('style'=>'width:150px','class'=>'select_grey','id'=>'ID_COQUANBANHANH'))
  	            ->setOrder(0)
  	            ->addValidator("Int")
                ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
  	            ->setDecorators(array('ViewHelper'));
  	    
  	    $idcap = new Zend_Form_Element_Select('ID_CAP');
		$idcap->setRequired(true)
                ->addMultiOptions($this->getCaps())
  	            ->setOptions(array('style'=>'width:150px','class'=>'select_grey','id'=>'ID_CAP'))
  	            ->setOrder(0)
  	            ->addValidator("Int")
                ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
  	            ->setDecorators(array('ViewHelper'));
  	    
  	    $idlinhvuc = new Zend_Form_Element_Select('ID_LINHVUC');
		$idlinhvuc->setRequired(true)
                ->addMultiOptions($this->getLinhVucs())
  	            ->setOptions(array('style'=>'width:150px','class'=>'select_grey','id'=>'ID_LINHVUC'))
  	            ->setOrder(0)
  	            ->addValidator("Int")
                ->addPrefixPath('Unitech_Validate', 'Unitech/Validate/', 'validate')
  	            ->setDecorators(array('ViewHelper'));
  	            
        
		//$this->addElement($mavanban);
		$this->addElement($sokyhieu);
		$this->addElement($ngaybanhanh);
		$this->addElement($ngaycohieuluc);
		$this->addElement($coquanbanhanhtext);
		$this->addElement($ngayhethieuluc);
		$this->addElement($vanbanhuongdan);
		$this->addElement($nguoiky);
		//$this->addElement($idcoquan);
		$this->addElement($nguoitao);
		$this->addElement($nguoisuacuoi);
		$this->addElement($nguoikiemduyet);
		$this->addElement($nguonvanban);
		$this->addElement($trichyeu);
		$this->addElement($idlinhvuc);
		$this->addElement($idcap);
		$this->addElement($nguoiky);
	}
}