<?php

namespace CMS\Model;

use CMS\Entity\Page as PageEntity;
use Utilities\Service\Random;
use Zend\File\Transfer\Adapter\Http;
use Utilities\Form\FormButtons;
use Utilities\Service\Status;
use CMS\Service\PageTypes;

/**
 * Page Model
 * 
 * Handles Page Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package cms
 * @subpackage model
 */
class Page
{

    const UPLOAD_PATH = 'public/upload/pageContents/';

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Utilities\Service\Random;

     */
    protected $random;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
        $this->random = new Random();
    }

    /**
     * Prepare logs
     * 
     * @access public
     * @param array $logs
     * @return array logs prepared for display
     */
    public function prepareHistory($logs)
    {
        $dummyPage = new PageEntity();
        foreach ($logs as &$log) {
            foreach ($log['data'] as $dataKey => &$dataValue) {
                if ($dataKey == "body") {
                    $dummyPage->body = $dataValue;
                    $dataValue = $dummyPage->getBody();
                }
                if ($dataKey == "bodyAr") {
                    $dummyPage->bodyAr = $dataValue;
                    $dataValue = $dummyPage->getBodyAr();
                }
            }
        }
        return $logs;
    }

    public function uploadImage($fileData)
    {

        $upload = new Http();
        $upload->setDestination(self::UPLOAD_PATH);
        try {

            // upload received file(s)
            $upload->receive();
        } catch (\Exception $e) {
            // return $uploadResult;
        }
        //This method will return the real file name of a transferred file.
        $name = $upload->getFileName($fileData['upload']['name']);
        //This method will return extension of the transferred file
        $extention = pathinfo($name, PATHINFO_EXTENSION);
        //get random new name
        $newName = $this->random->getRandomUniqueName();
        $newFullName = self::UPLOAD_PATH . $newName . '.' . $extention;
        // rename
        rename($name, $newFullName);
        $uploadResult = $newFullName;

        return $uploadResult;
    }

    public function listImages()
    {
        $images = scandir(self::UPLOAD_PATH);
        // Note : we need to unset first 3 items which (1,2) -> Parents or dir
        // 3 -> .gitignore File which is hidden
        unset($images[0]);
        unset($images[1]);
        unset($images[2]);
        $new = array();
        foreach ($images as $image) {
            array_push($new, $image);
        }
        return $new;
    }

    /**
     * Save page
     * 
     * @access public
     * @param CMS\Entity\Page $page
     * @param array $data ,default is empty array
     * @param bool $editFlag ,default is false
     */
    public function save($page, $data = array(), $editFlag = false)
    {
        if (array_key_exists(FormButtons::SAVE_AND_PUBLISH_BUTTON, $data)) {
            $page->setStatus(Status::STATUS_ACTIVE);
        }
        elseif (array_key_exists(FormButtons::UNPUBLISH_BUTTON, $data)) {
            $page->setStatus(Status::STATUS_INACTIVE);
        }
        elseif (array_key_exists(FormButtons::SAVE_BUTTON, $data) && $editFlag === false) {
            $page->setStatus(Status::STATUS_INACTIVE);
        }
        if ($editFlag === true) {
            $data = array();
        }
        $this->query->setEntity("CMS\Entity\Page")->save($page, $data);
    }
    
    /**
     * Set page form required fields
     * 
     * @access public
     * @param Zend\Form\FormInterface $form
     * @param array $data
     * @param bool $editFlag ,default is false
     */
    public function setFormRequiredFields($form, $data, $editFlag = false)
    {
        $inputFilter = $form->getInputFilter();
            // type is not press release
            if ($data['type'] != PageTypes::PRESS_RELEASE_TYPE ) {
                // Change required flag to false for press release fields
                $category = $inputFilter->get('category');
                $category->setRequired(false);
                $summary = $inputFilter->get('summary');
                $summary->setRequired(false);
                $summaryAr = $inputFilter->get('summaryAr');
                $summaryAr->setRequired(false);
                $author = $inputFilter->get('author');
                $author->setRequired(false);
                $picture = $inputFilter->get('picture');
                $picture->setRequired(false);
            }
            // file not updated
            if ($editFlag === true && isset($data['picture']['name']) && empty($data['picture']['name'])) {
                // Change required flag to false for any previously uploaded files
                $picture = $inputFilter->get('picture');
                $picture->setRequired(false);
            }
    }

}
