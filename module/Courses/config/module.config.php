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
            'courses' => 'Courses\Controller\CourseController'
        ),
        'factories' => array(
            'Courses\Model\Course' => 'Courses\Model\CourseFactory',
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
    'controllers' => array(
        'invokables' => array(
            'Courses\Controller\Course' => 'Courses\Controller\CourseController',
        ),
    ),
    'router' => array(
        'routes' => array(
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
            'coursesResourceDownload' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/download/:resource/:id/:name',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'download',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                        'resource' => '[a-zA-Z]+',
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
            // delete evaluation template created by admin
            'deleteEvTemplate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/ev-templates/delete[/:id]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'deleteEvTemplate',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            //list evaluations created by atp
            'courseEvaluations' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/evaluations[/:courseId]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'evaluations',
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
                    'route' => '/courses/evaluation/edit[/:courseId[/:evalId]]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'editEvaluation',
                    ),
                    'constraints' => array(
                        'evalId' => '[0-9]+',
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
            // delete evaluation template created by atp
            'deleteCourseEvaluation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/courses/evaluation/delete[/:id]',
                    'defaults' => array(
                        'controller' => 'Courses\Controller\Course',
                        'action' => 'deleteEvaluation',
                    ),
                    'constraints' => array(
                        'evalId' => '[0-9]+',
                        'courseId' => '[0-9]+',
                    ),
                )
            ),
        )
    ),
);
