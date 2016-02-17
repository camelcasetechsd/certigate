<?php

namespace Courses;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'courses' => 'Courses\Controller\CourseController',
            'resources' => 'Courses\Controller\ResourceController',
            'exams' => 'Courses\Controller\ExamController',
            'outline' => 'Courses\Controller\OutlineController'
        ),
        'factories' => array(
            'Courses\Model\Course' => 'Courses\Model\CourseFactory',
            'Courses\Model\Resource' => 'Courses\Model\ResourceFactory',
            'Courses\Model\Outline' => 'Courses\Model\OutlineFactory',
            'Courses\Model\Exam' => 'Courses\Model\ExamFactory',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    // for cron tabs to update tvtc status 
    // from nothing to pending after 3 days
    'console' => array(
        'router' => array(
            'routes' => array(
                'list-users' => array(
                    'options' => array(
                        'route' => 'updateTvtcStatus [--verbose|-v] ',
                        'defaults' => array(
                            'controller' => 'Courses\Controller\Exam',
                            'action' => 'updateTvtcStatus'
                        )
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Courses\Controller\Course' => 'Courses\Controller\CourseController',
            'Courses\Controller\Resource' => 'Courses\Controller\ResourceController',
            'Courses\Controller\Exam' => 'Courses\Controller\ExamController',
            'Courses\Controller\Outline' => 'Courses\Controller\OutlineController',
            
        ),
    ),
    'router' => array(
        'routes' => array(
            'resources' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resources[/:action]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Resource',
                        'action' => 'index'
                    ),
                    'constraints' => array(
                        'action' => '[^(false|true)][a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                )
            ),
            'resourcesList' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resources[/:processResult]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Resource',
                        'action' => 'index'
                    ),
                    'constraints' => array(
                        'processResult' => '(false|true)'
                    ),
                )
            ),
            'resourcesListPerCourse' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resources/:courseId[/:processResult]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Resource',
                        'action' => 'index',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                        'processResult' => '(false|true)'
                    ),
                )
            ),
            'resourcesNewPerCourse' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resources/new/:courseId',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Resource',
                        'action' => 'new',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            'resourcesEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resources/edit/:courseId',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Resource',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
//            ),
//            'resourcesEditPerCourse' => array(
//                'type' => 'Zend\Mvc\Router\Http\Segment',
//                'options' => array(
//                    'route' => '/resources/edit/:id/:courseId',
//                    'defaults' => array(
//                        'controller' => 'Courses\Controller\Resource',
//                        'action' => 'edit',
//                    ),
//                    'constraints' => array(
//                        'id' => '[0-9]+',
//                        'courseId' => '[0-9]+',
//                    ),
//                )
            ),
            'resourcesDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resources/delete/:id',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Resource',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'resourcesDeletePerCourse' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resources/delete/:id/:courseId',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Resource',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            'resourcesResourceDownload' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resources/download/:id[/:latest]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Resource',
                        'action' => 'download',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                        'latest' => 'true',
                    ),
                )
            ),
            'courses' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses[/:action]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'index'
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                )
            ),
            'coursesCalendar' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/calendar',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'calendar',
                    ),
                )
            ),
            'coursesPending' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/pending/:id',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'pending',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'coursesApproval' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/approve/:id',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'approve',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'coursesInstructorCalendar' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/instructor-calendar',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'instructorCalendar',
                    ),
                )
            ),
            'coursesInstructorTraining' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/instructor-training',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'instructorTraining',
                    ),
                )
            ),
            'coursesMore' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/more/:id',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'more',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'coursesEnroll' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/enroll/:id',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'enroll',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'coursesLeave' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/leave/:id',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'leave',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'coursesEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/edit/:id',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'coursesDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/delete/:id',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            //list evaluation templates created by admin
            'EvTemplates' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/courses/ev-templates',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'evTemplates',
                    ),
                )
            ),
            // new evaluation template created by admin 
            'newEvTemplate' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/courses/ev-templates/new',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'newEvTemplate',
                    ),
                )
            ),
            // edit evaluatoin template created by admin
            'editEvTemplate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/ev-templates/edit[/:id]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'editEvTemplate',
                    ),
                    'constraints' => array(
                        'evalId' => '[0-9]+',
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            //list evaluations created by atp
            'courseEvaluations' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/evaluation[/:courseId]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'evaluation',
                    ), 'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // new evaluation template created by atp 
            'newCourseEvaluation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/evaluation/new[/:courseId]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'newEvaluation',
                    ), 'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // edit evaluatoin template created by atp
            'editCourseEvaluation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/evaluation/edit[/:courseId]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'editEvaluation',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // atc book an exam
            'examBooking' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/courses/exam/book',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Exam',
                        'action' => 'book',
                    ),
                )
            ),
            // admin list exam requests
            'examRequests' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/courses/exam/requests',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Exam',
                        'action' => 'requests',
                    ),
                )
            ),
            // admin list exam requests
            'acceptRequest' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/exam/request/accept[/:id]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Exam',
                        'action' => 'accept',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // admin list exam requests
            'declineRequest' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/exam/request/decline[/:id]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Exam',
                        'action' => 'decline',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // admin list exam requests
            'tvtcaccept' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/exam/tvtc/accept[/:id]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Exam',
                        'action' => 'tvtcAccept',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // admin list exam requests
            'tvtcdecline' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/exam/tvtc/decline[/:id]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Exam',
                        'action' => 'tvtcDecline',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // student evaluate course
            'studentEvaluation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/vote[/:courseId]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'vote',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // list Course outlines
            'courseOutlines' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/outlines[/:courseId]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Outline',
                        'action' => 'index',
                    ),
                    'constraints' => array(
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
        )
    ),
);
