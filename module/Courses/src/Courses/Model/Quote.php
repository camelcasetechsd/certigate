<?php

namespace Courses\Model;

use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\Criteria;
use Courses\Entity\PublicQuote;
use Courses\Entity\PrivateQuote;
use Courses\Form\PublicQuoteReservationForm;
use Courses\Form\PrivateQuoteReservationForm;
use Utilities\Service\Status;

/**
 * Quote Model
 * 
 * Handles Quote common business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Translation\Service\Translator\TranslatorHandler $translationHandler
 * @property Utilities\Service\View\FormView $formView
 * @property Courses\Service\QuoteGenerator $quoteGenerator
 * @property array $quoteConfig
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
     * @var Courses\Service\QuoteGenerator
     */
    protected $quoteGenerator;

    /**
     *
     * @var array
     */
    protected $quoteConfig;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Translation\Service\Translator\TranslatorHandler $translationHandler
     * @param Utilities\Service\View\FormView $formView
     * @param Courses\Service\QuoteGenerator $quoteGenerator
     * @param array $quoteConfig
     */
    public function __construct($query, $translationHandler, $formView, $quoteGenerator, $quoteConfig)
    {
        $this->query = $query;
        $this->translationHandler = $translationHandler;
        $this->formView = $formView;
        $this->quoteGenerator = $quoteGenerator;
        $this->quoteConfig = $quoteConfig;
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
     * Cleanup cancelled quotes
     * Release reserved seats by public quotes
     * 
     * @access public
     */
    public function cleanup()
    {
        $publicQuotes = $this->getQuotes( /*$type =*/ PublicQuote::QUOTE_TYPE, /*$status =*/ Status::STATUS_INACTIVE, /*$lastModifiedDays =*/ $this->quoteConfig["expireAfterDays"] );
        $privateQuotes = $this->getQuotes( /*$type =*/ PrivateQuote::QUOTE_TYPE, /*$status =*/ Status::STATUS_INACTIVE, /*$lastModifiedDays =*/ $this->quoteConfig["expireAfterDays"] );
        foreach($publicQuotes as $publicQuote){
            $courseEvent = $publicQuote->getCourseEvent();
            $courseEvent->setStudentsNo((int)$courseEvent->getStudentsNo() + (int)$publicQuote->getSeatsNo());
            $this->query->save($courseEvent, /*$data =*/ array(), /*$flushAll =*/ false, /*$noFlush =*/ true);
        }
        $quotes = array_merge($publicQuotes, $privateQuotes);
        foreach($quotes as $quote){
            $this->query->remove($quote, /*$noFlush =*/ true);
        }
        $this->query->entityManager->flush();
    }
    
    /**
     * Get quotes
     * 
     * @access public
     * @param string $type
     * @param int $status ,default is null
     * @param int $lastModifiedDays ,default is null
     * @return array
     */
    public function getQuotes( $type, $status = null, $lastModifiedDays = null )
    {
        $entityName = "Courses\Entity\\" . $type . "Quote";
        $repository = $this->query->setEntityName($entityName)->entityRepository;
        $queryBuilder = $repository->createQueryBuilder( "pq" );
        $parameters = array();

        $queryBuilder->select( "pq" )
            ->from( $entityName, "pq" );
        if (!is_null( $status )) {
            $parameters['status'] = $status;
            $queryBuilder->andWhere( $queryBuilder->expr()->eq( 'pq.status', ":status" ) );
        }
        if (!is_null( $lastModifiedDays )) {
            $lastModifiedDate = new \DateTime("- $lastModifiedDays days");
            $parameters['lastModifiedDays'] = $lastModifiedDate;
            $queryBuilder->andWhere( $queryBuilder->expr()->lte( 'pq.modified', ":lastModifiedDays" ) );
        }
       
        if (count( $parameters ) > 0) {
            $queryBuilder->setParameters( $parameters );
        }
        return $queryBuilder->getQuery()->getResult();
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
