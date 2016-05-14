<?php

class MaterialsController extends Zend_Controller_Action
{

    private $model = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->model = new Application_Model_DbTable_Material();
        $authorization = Zend_Auth::getInstance();
		if(!$authorization->hasIdentity()) {
			$this->redirect('users/login');
		}
		else {
            $this->view->identity = $authorization->getIdentity();
		}
    }

    public function indexAction()
    {
        // list all
        $this->view->materials = $this->model->listMaterials();

    }

    public function addAction()
    {
        // action body
        $form = new Application_Form_Material();
		if($this->getRequest()->isPost()){
			if($form->isValid($this->getRequest()->getParams())){
				// $filter = new Zend_Filter_BaseName();
				echo "<pre>";$pathparts = pathinfo($form->path->getFileName());echo "</pre>";
				// $filename = $filter->filter($path);
				$filename =$pathparts['basename'];
				$ext = $pathparts['extension'];
				$vid_arr = array("flv", "mp4", "3gp", "mkv");
				$word_arr = array("doc", "docx");
				$form->path->addFilter(new Zend_Filter_File_Rename(
		              array('target' => 'file-'.time().'-'.$filename))
		           );
				// $file = $this->getRequest->getFiles();
				// var_dump($file);
				// die();
				if( $ext == "pdf"){
					$type = "pdf";
				}
				elseif (in_array($ext, $vid_arr) ) {
					$type = "video";
				}
				elseif (in_array($ext, $word_arr) ) {
					$type = "word";
				}
				else{
					$type = "ppt";
				}
				$date = new Zend_Date();
				$form_data = $form->getValues();
				$other_data = array("type" => $type, "user_id" => 1, "course_id" => 1, "time" => $date);
				$data = array_merge($form_data, $other_data);
				if ($this->model->addMaterial($data))
					$this->redirect('materials/index');			
				
			}

		}
		$this->view->form = $form;
    }

    public function singleAction()
    {
        // action body
        $id = $this->getRequest()->getParam('id');
        $this->view->material = $this->model->getMaterialById($id);
        $comment = new Application_Model_DbTable_Comment();
		$this->view->comments = $comment->getCommentsByMaterialId($id);

    }

    public function downloadAction()
    {
    	$id = $this->getParam('id');
    	$material = $this->model->getMaterialById($id);
    	$new_value = intval($material['num_downloads']);
    	$data =array( "num_downlaods" =>  ++$new_value);
    	$this->model->downloaded($id, $data);
    	$filename = $material['path'];
    	$f = implode("/", array(
	    	realpath(APPLICATION_PATH . '/../public'),
	    	"materials",$filename
		));
    	$file = file_get_contents($f);
 
		$this->getResponse()
		     ->setBody($file)
		     ->setHeader('Content-Type', 'octet/stream')
		     ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')
		     ->setHeader('Content-Length', strlen($file));
		 
		//If using Zend_Layout, we need to disable it:
		$this->_helper->layout->disableLayout();
		 
		//Disable ViewRenderer:
		$this->_helper->viewRenderer->setNoRender(true);
    }

    public function deleteAction()
    {
    	
        $id = $this->getRequest()->getParam('id');
        $mat_info = $this->model->getMaterialById($id);
        $filename = $mat_info['path'];
        $f = implode("/", array(
	    	realpath(APPLICATION_PATH . '/../public'),
	    	"materials",$filename
		));
        unlink(realpath($f));
		if($this->model->deleteMaterial($id)){
			$this->redirect('materials/index');
		}
    }

    public function editAction()
    {
        // action body
    }

    public function showAction()
    {
        // action body
        $id = $this->getRequest()->getParam('id');
        $material = $this->model->getMaterialById($id);
        if ($material['is_hidden'] == "0") 
        	$this->model->hideMaterial($id);
        else
        	$this->model->showMaterial($id);	
        $this->redirect('materials/single/id/'.$id);

    }

    public function lockAction()
    {
        // action body
        $id = $this->getRequest()->getParam('id');
        $material = $this->model->getMaterialById($id);
        if ($material['is_locked'] == "0") 
        	$this->model->lockMaterial($id);
        else
        	$this->model->unlockMaterial($id);	
        $this->redirect('materials/single/id/'.$id);
    }


}











