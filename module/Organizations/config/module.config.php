<?php

namespace Organizations;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../' . APPLICATION_THEMES . CURRENT_THEME . 'modules',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Organizations\Model\Organization' => 'Organizations\Model\OrganizationFactory',
            'Organizations\Model\OrganizationUser' => 'Organizations\Model\OrganizationUserFactory',
            'Organizations\Model\OrganizationMeta' => 'Organizations\Model\OrganizationMetaFactory',
        ),
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
            'Organizations\Controller\Organizations' => 'Organizations\Controller\OrganizationsController',
            'Organizations\Controller\OrganizationUsers' => 'Organizations\Controller\OrganizationUsersController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'organizationUsers' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organization-users[/:action]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\OrganizationUsers',
                        'action' => 'index'
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                )
            ),
            'organizationUsersList' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organization-users/:organizationId',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\OrganizationUsers',
                        'action' => 'index',
                    ),
                    'constraints' => array(
                        'organizationId' => '[0-9]+',
                    ),
                )
            ),
            'organizationUsersNew' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organization-users/new/:organizationId',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\OrganizationUsers',
                        'action' => 'new',
                    ),
                    'constraints' => array(
                        'organizationId' => '[0-9]+',
                    ),
                )
            ),
            'organizationUsersEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organization-users/edit/:organizationId',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\OrganizationUsers',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'organizationUsersDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organization-users/delete/:organizationId',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\OrganizationUsers',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'organizationsList' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'index'
                    ),
                )
            ),
            'organizationsPending' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/pending/:id',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'pending',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'organizationsApproval' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/approve/:id',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'approve',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'organizationsDisapproval' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/disapprove/:id',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'disapprove',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'organizationsDownload' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/download/:id/:type[/:notApproved]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'download',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                        'type' => '[a-zA-Z]+',
                        'notApproved' => 'true',
                    ),
                )
            ),
            'org_type' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/type',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'type'
                    ),
                )
            ),
            'list_atp_orgs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/atps',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'atps'
                    ),
                )
            ),
            'list_atc_orgs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/atcs',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'atcs'
                    ),
                )
            ),
            'list_distributor_orgs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/distributors',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'distributors'
                    ),
                )
            ),
            'list_reseller_orgs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/resellers',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'resellers'
                    ),
                )
            ),
            'more' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/more[/:id]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'more'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    ),
                )
            ),
            'new_org' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/new[/:v1[/:v2[/:v3[/:v4]]]]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'new'
                    ),
                    'constraints' => array(
                        'v1' => '[0-9]*',
                        'v2' => '[0-9]*',
                        'v3' => '[0-9]*',
                        'v4' => '[0-9]*'
                    ),
                )
            ),
            'edit_org' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/edit[/:id]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'edit'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    )
                )
            ),
            'delete_org' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/delete[/:id]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'delete'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    )
                )
            ),
            'saveState' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/savestate',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'saveState'
                    )
                )
            ),
            'myOrganizations' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/myorganizations',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'myOrganizations'
                    )
                )
            ),
            'renew' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/renew[/:organizationId[/:metaId]]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'renew'
                    ),
                    'constraints' => array(
                        'organizationId' => '[0-9]*',
                        'metaId' => '[0-9]*'
                    )
                )
            )
        )
    ),
    // for cron tabs to update Expiration Flag status 
    'console' => array(
        'router' => array(
            'routes' => array(
                'updateExpirationFlag' => array(
                    'options' => array(
                        'route' => 'updateExpirationFlag [--verbose|-v] ',
                        'defaults' => array(
                            'controller' => 'Organizations\Controller\Organizations',
                            'action' => 'updateExpirationFlag'
                        )
                    )
                )
            )
        )
    ),
    'atcSkippedParams' => array(
        'atpLicenseNo',
        'atpLicenseExpiration',
        'atpLicenseAttachment',
        'atpWireTransferAttachment',
        'classesNo',
        'pcsNo_class',
        'trainingManager_id',
        'atpPrivacyStatement'
    ),
    'atpSkippedParams' => array(
        'atcLicenseNo',
        'atcLicenseExpiration',
        'atcLicenseAttachment',
        'atcWireTransferAttachment',
        'labsNo',
        'pcsNo_lab',
        'internetSpeed_lab',
        'operatingSystem',
        'operatingSystemLang',
        'officeVersion',
        'officeLang',
        'testCenterAdmin_id',
        'atcPrivacyStatement'
    ),
    'atcEditSkippedParams' => array(
        'atcLicenseNo',
        'atcLicenseExpiration',
        'atcLicenseAttachment',
        'atcWireTransferAttachment',
    ),
    'atpEditSkippedParams' => array(
        'atpLicenseNo',
        'atpLicenseExpiration',
        'atpLicenseAttachment',
        'atpWireTransferAttachment',
    ),
    'AtcRenewalFields' => array(
        'atcLicenseNo',
        'atcLicenseExpiration',
        'atcLicenseAttachment',
        'atcWireTransferAttachment'
    ),
    'AtpRenewalFields' => array(
        'atpLicenseNo',
        'atpLicenseExpiration',
        'atpLicenseAttachment',
        'atpWireTransferAttachment',
    )
);
