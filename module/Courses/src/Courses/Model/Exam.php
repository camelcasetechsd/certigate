<?php

namespace Courses\Model;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

class Exam
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function saveBookingRequest($data, $config)
    {
        $bookObj = new \Courses\Entity\ExamBook();

        $course = $this->query->findOneBy('Courses\Entity\Course', array(
            'id' => $data['courseId']
        ));


        $atc = $this->query->findOneBy('Organizations\Entity\Organization', array(
            'id' => $data['atcId']
        ));
        // exam date
        $bookObj->setDate(new \DateTime($data['date']));
        // creation time
        $bookObj->setCreatedAt(new \DateTime());
        // number of students
        $bookObj->setStudentNum($data['studentsNo']);
        // assign request to course
        $bookObj->setCourse($course);
        // assign request to atc
        $bookObj->setAtc($atc);
        // admin pending request
        $bookObj->setAdminStatus(\Courses\Entity\ExamBook::ADMIN_PENDING);
        // tvtc nothing
        $bookObj->setTvtcStatus(Null);

        $this->query->setEntity('Courses\Entity\ExamBook')->save($bookObj);

        $tvtcMail = $config['contacts']['TVTC'];
        $admin = $config['contacts']['Admin_Email'];
        // send tvtc new mail
        $this->sendMail($bookObj, $tvtcMail, $config, true);
        // send admin new mail
        $this->sendMail($bookObj, $admin, $config);
    }

    public function listRequests()
    {
        $requests = $this->query->findAll('Courses\Entity\ExamBook');
        foreach ($requests as $req) {
            // showing tvtc status
            switch ($req->tvtcStatus) {
                case 1 :
                    $req->tvtcStatus = "Approved";
                    break;
                case 2 :
                    $req->tvtcStatus = "Declined";
                    break;
                case 3 :
                    $req->tvtcStatus = "Pending";
                    $req->isTvtcPending = 1;
                    break;
                default :
                    $req->tvtcStatus = "";
                    break;
            }
            // showing admin status
            switch ($req->adminStatus) {
                case 1 :
                    $req->adminStatus = "Approved";
                    break;
                case 2 :
                    $req->adminStatus = "Declined";

                    break;
                case 3 :
                    $req->adminStatus = "Pending";
                    $req->isAdminPending = 1;
                    break;
            }
            $req->date = date_format($req->date, 'd/m/Y');
        }

        return $requests;
    }

    public function respondeToExamRequest($response, $requestId, $tvtcResponse = null)
    {
        $request = $this->query->findOneBy('Courses\Entity\ExamBook', array(
            'id' => $requestId
        ));
        // tvtc response 
        if ($tvtcResponse != null) {
            $request->setTvtcStatus($response);
            $this->query->save($request);
        }
        // admin response
        else {
            $request->setAdminStatus($response);
            $this->query->save($request);
        }
    }

    /**
     * function meant to send 2 emails one to admin 
     * and the other to tvtc
     * @param int $request
     * @param string $to
     * @param array $config
     */
    private function sendMail($request, $to, $config, $adminMail)
    {
        $message = new Message();
        $message->addTo($to)
                ->addFrom($config['contacts']['Operations'])
                ->setSubject('Exam Request');

        // Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        $options = new SmtpOptions(array(
            'host' => 'smtp.gmail.com',
            'connection_class' => 'login',
            'connection_config' => array(
                'ssl' => 'tls',
                'username' => '',
                'password' => ''
            ),
            'port' => 587,
        ));
        // if tctv mail
        if ($adminMail != null) {
            $html = new MimePart('<h2>Exam Request</h2> <a href="' . define('ROOT_PATH', dirname(__DIR__)) . '/courses/exam/tvtc/accept/' . $request->getId() . '"> click me if you accept </a> <br>'
                    . ' <a href="' . define('ROOT_PATH', dirname(__DIR__)) . '/courses/exam/tvtc/decline/' . $request->getId() . '"> click me if you decline </a>');
        }
        // if admin mail
        else {
            $html = new MimePart('There\'s a new Exam Request .. created By' . $request->getAtc->commercialName);
        }
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($html);

        $message->setBody($body);

        $transport->setOptions($options);
        $transport->send($message);
    }

}
