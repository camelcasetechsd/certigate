<?php

namespace Courses\Model;

use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\Criteria;
use Courses\Entity\PublicQuote;
use Courses\Entity\PrivateQuote;
use Courses\Form\PublicQuoteReservationForm;
use Courses\Form\PrivateQuoteReservationForm;

/**
 * Quote Model
 * 
 * Handles Quote common business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Translation\Service\Translator\TranslatorHandler $translationHandler
 * @property Utilities\Service\View\FormView $formView
 * @property Courses\Model\QuoteGenerator $quoteGenerator
 * 
 * @package courses
 * @subpackage model
 */
class Quote
{

    use \Utilities\Service\Paginator\PaginatorTrait;

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    protected $query;

    /**
     *
     * @var Translation\Service\Translator\TranslatorHandler
     */
    protected $translationHandler;

    /**
     *
     * @var Utilities\Service\View\FormView
     */
    protected $formView;

    /**
     *
     * @var Courses\Model\QuoteGenerator
     */
    protected $quoteGenerator;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Translation\Service\Translator\TranslatorHandler $translationHandler
     * @param Utilities\Service\View\FormView $formView
     * @param Courses\Model\QuoteGenerator $quoteGenerator
     */
    public function __construct($query, $translationHandler, $formView, $quoteGenerator)
    {
        $this->query = $query;
        $this->translationHandler = $translationHandler;
        $this->formView = $formView;
        $this->quoteGenerator = $quoteGenerator;
    }

    /**
     * Get courses with corresponding
     * 
     * @access public
     * @param array $courses
     * @param string $type
     * @param string $actionUrl
     * @return array courses with forms
     */
    public function prepareQuoteForms($courses, $type, $actionUrl)
    {
        $options = array("actionUrl" => $actionUrl);
        foreach ($courses as $course) {
            if ($type === PrivateQuote::QUOTE_TYPE) {
                $options["course"] = $course->getId();
                $options["translatorHandler"] = $this->translationHandler;
                $options["privateQuoteModel"] = $this->quoteGenerator->getModel($type);
                $form = new PrivateQuoteReservationForm(/* $name = */ "private_quote_reservation_" . $course->getId(), /* $options = */ $options);
                $course->form = $this->formView->getFormView($form);
            }
            else {
                foreach ($course->getCourseEvents() as $courseEvent) {
                    if ($type === PublicQuote::QUOTE_TYPE) {
                        $options["courseEvent"] = $courseEvent->getId();
                        $form = new PublicQuoteReservationForm(/* $name = */ "public_quote_reservation_" . $courseEvent->getId(), /* $options = */ $options);
                        $courseEvent->form = $this->formView->getFormView($form);
                    }
                }
            }
        }
        return $courses;
    }

    /**
     * Get translated quotes types
     * 
     * @access public
     * @return array translated quotes types
     */
    public function getTranslatedQuoteTypes()
    {
        $types = array(
            PublicQuote::QUOTE_TYPE,
            PrivateQuote::QUOTE_TYPE
        );
        return $this->translationHandler->getTranslatedArray($types);
    }

    /**
     * Filter quotes
     * 
     * @access public
     * 
     * @param bool $isAdminUser
     * @param int $userId
     * @param array $data
     */
    public function filterQuotes($isAdminUser, $userId, $data)
    {
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        if ($isAdminUser === false) {
            $criteria->andWhere($expr->eq("user", $userId));
        }
        $entityName = "Courses\Entity\PublicQuote";
        if (array_key_exists("type", $data) && !empty($data["type"])) {
            $entityName = "Courses\Entity\\" . $data["type"] . "Quote";
        }
        $this->paginator = new Paginator(new PaginatorAdapter($this->query->setEntity($entityName), $entityName));
        $this->setCriteria($criteria);
    }

}
