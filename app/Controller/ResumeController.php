<?php

/**
 * Resume Controller

 */
class ResumeController extends AppController {

	/**
	 * Main index action
	 */
	public function index() {
		// form posted
		if ($this->request->is('post')) {
			// create
			$this->Resume->create();

			// attempt to save
			if ($this->Resume->save($this->request->data)) {
                            $options = $this->request->data;
                        
                            $filename = str_replace(" ", "_",$options['Resume']['filename']['name']);
                            $filetype = $options['Resume']['filename']['type'];
                          
                            $YourFile = "./uploads/".$filename; 

                            exit;
                            
                            
				$this->Session->setFlash('Your resume has been submitted');
				$this->redirect(array('action' => 'index'));

			// form validation failed
			} else {
				// check if file has been uploaded, if so get the file path
				if (!empty($this->Resume->data['Resume']['filepath']) && is_string($this->Resume->data['Resume']['filepath'])) {
					$this->request->data['Resume']['filepath'] = $this->Resume->data['Resume']['filepath'];
				}
			}
		}
	}
}