<?php

class Application_Form_Course extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

		$name = new Zend_Form_Element_Text('name');
		$name->setRequired();
		$name->setLabel('Course Name');
		$name->addValidator(new Zend_Validate_Db_NoRecordExists(
	    array(
	        'table' => 'course',
	        'field' => 'name'
	   		 )
		));

//		$date = new Zend_Form_Element_Date('date');
//                Zend_Form_El
//		$date->setRequired();
//		$date->setLabel('Date');

// 		$date = new Zend_Form_Element_Date('date');
// //                Zend_Form_El
// //		$date->setRequired();
// 		$date->setLabel('Date');
//		$date->addValidator(new Zend_Validate_Db_NoRecordExists(
//	    array(
//	        'table' => 'course',
//	        'field' => 'date'
//	   		 )
//		));

		$name->setAttrib('class', 'form-control');
		$cat_id = new Zend_Form_Element_Select('cat_id');
	 	$id = new Zend_Form_Element_Hidden('id');
		// $content = new Zend_Form_Element_Textarea('content');
		$cat_id->setLabel('Select Category');
		// $content->addValidator(new Zend_Validate_StringLength(array('min'=>10, 'max'=>250)));
		// $content->setAttrib('class', 'form-control');
		
                
                $summary = new Zend_Form_Element_Textarea('summary');
                $summary->setLabel('Summary');
                $summary->setAttrib('class','span6');
                
                $image = new Zend_Form_Element_File('image');
                $image->setLabel('Upload an image:');
                $image->setDestination('/var/www/html/zendSLMS/public/images/courses');
                $image->addValidator('Count', false, 1);
                $image->addValidator('Extension', false, 'jpg,png,gif');
        
                $submit = new Zend_Form_Element_Submit('Submit');
		$submit->setAttrib('class', 'btn btn-primary');
                
		$this->addElements(array($name,$summary,$image, $cat_id,$submit));


    }


}

