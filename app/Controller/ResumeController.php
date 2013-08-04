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

                $filename = str_replace(" ", "_", $options['Resume']['filename']['name']);
                $filename = str_replace("__", "_", $filename);
                $filetype = $options['Resume']['filename']['type'];
                $fileRead = "./uploads/" . $filename;

                if ($filetype == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                    $this->read_file_doc($fileRead);
                } elseif ($filetype == "application/msword") {
                    echo "testing";
// $this->parseWord($filename);
                }

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

    public function parseWord($userDoc) {
        $filename = WWW_ROOT . "uploads\\test.doc";
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open(realpath($filename));

// Extract content.
        $content = (string) $word->ActiveDocument->Content;

        echo $content;

        $word->ActiveDocument->Close(false);

        $word->Quit();
        $word = null;
        unset($word);
//unlink($TXTfilename);
    }

    public function read_file_doc($filename) {
        $zip = new ZipArchive;
        if ($zip->open($filename) === true) {
            // docx
            if (($index = $zip->locateName("word/document.xml")) !== false) {
                $data = $zip->getFromIndex($index);
                $xml = DOMDocument::loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);

                $file_content = ($xml->saveXML());

                $pattern = "/([A-Za-z0-9\.\-\_\!\#\$\%\&\'\*\+\/\=\?\^\`\{\|\}]+)\@([A-Za-z0-9.-_]+)(\.[A-Za-z]{2,5})/";
                preg_match_all($pattern, $file_content, $emails);
                echo "Emails :";
                echo "<br />";
                for ($i = 0; $i < count($emails[0]); $i++) {
                    echo $emails[0][$i] . "<br />";
                }

                echo "<hr />";
//                
                echo "Phone Numbers :";
                echo "<br />";
                preg_match("/^\+?[0-9]{3}-?([0-9]{7}|[0-9]-[0-9]{2}-[0-9]{2}-[0-9]{2}|[0-9]{3}-[0-9]{2}-[0-9]-[0-9])$/", $file_content, $phone);
                print_r($phone);

                exit;
            }
            //echo $file_content;exit;
        }
    }

}