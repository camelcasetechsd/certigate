<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;
use \Users\Entity\Role;
use Utilities\Service\Time;

class PosDAOrganization extends AbstractSeed
{

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $typeAtcId = $this->fetchRow('select id from organization_type where title = "ATC"')['id'];
        $typeAtpId = $this->fetchRow('select id from organization_type where title = "ATP"')['id'];
        $typeDistId = $this->fetchRow('select id from organization_type where title = "Distributor"')['id'];
        $typeResellerId = $this->fetchRow('select id from organization_type where title = "Re-Seller"')['id'];
        //training manager user
        $TMUserId = $this->serviceManager->get('wrapperQuery')->findOneBy('Users\Entity\User', array(
                    'username' => 'tmuser'
                ))->getId();

        $TCAUserId = $this->serviceManager->get('wrapperQuery')->findOneBy('Users\Entity\User', array(
                    'username' => 'tcauser'
                ))->getId();

        $normalUserId = $this->serviceManager->get('wrapperQuery')->findOneBy('Users\Entity\User', array(
                    'username' => 'user'
                ))->getId();


        $TMRoleId = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\Role", array(
            "name" => Role::TRAINING_MANAGER_ROLE
        ))->getId();

        $TCARoleId = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\Role", array(
            "name" => Role::TEST_CENTER_ADMIN_ROLE
        ))->getId();




        $atp[] = array(
            'commercialName' => 'atpDummy',
            'commercialNameAr' => $faker->userName,
            'status' => true,
            'ownerName' => $faker->userName,
            'ownerNameAr' => $faker->userName,
            'ownerNationalId' => $faker->randomNumber(),
            'longtitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
            'CRNo' => $faker->randomNumber(),
            'CRExpiration' => date('Y-m-d H:i:s'),
            'CRExpirationHj' => date('Y-m-d H:i:s'),
            'CRAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'phone1' => '555-555-5555',
            'phone2' => '555-555-5555',
            'phone3' => '555-555-5555',
            'fax' => $faker->randomNumber(),
            'website' => $faker->url,
            'email' => $faker->email,
            'addressLine1' => $faker->address,
            'addressLine1Ar' => $faker->address,
            'addressLine2' => $faker->address,
            'addressLine2Ar' => $faker->address,
            'city' => $faker->city,
            'cityAr' => $faker->city,
            'zipCode' => $faker->randomNumber(),
            //AtpData
            'atpLicenseNo' => $faker->randomNumber(),
            'atpLicenseExpiration' => date('Y-m-d H:i:s'),
            'atpLicenseExpirationHj' => date('Y-m-d H:i:s'),
            'atpLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'atpWireTransferAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'classesNo' => $faker->randomDigitNotNull,
            'pcsNo_class' => $faker->randomDigitNotNull,
            //atcData should be null
            'atcLicenseNo' => null,
            'atcLicenseExpiration' => null,
            'atcLicenseExpirationHj' => null,
            'atcLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'atcWireTransferAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'labsNo' => null,
            'pcsNo_lab' => null,
            'operatingSystem' => null,
            'operatingSystemLang' => null,
            'internetSpeed_lab' => null,
            'officeLang' => null,
            'officeVersion' => null,
            'focalContactPerson_id' => $normalUserId,
            'creatorId' => $TMUserId
        );
        $this->insert('organization', $atp);
        $atpId = $this->getAdapter()->getConnection()->lastInsertId();

        //adding training manager to atp
        $orgUser1 [] = array(
            'role_id' => $TMRoleId,
            'org_id' => $atpId,
            'user_id' => $TMUserId
        );
        $this->insert('organization_user', $orgUser1);

        $atpMeta [] = array(
            'type_id' => $typeAtpId,
            'org_id' => $atpId,
            'expirationDate' => date('Y-m-d H:i:s'),
            'expirationDateHj' => date('Y-m-d H:i:s'),
            'expirationFlag' => 0,
        );
        $this->insert('organization_meta', $atpMeta);

        $atc[] = array(
            'commercialName' => 'atcDummy',
            'commercialNameAr' => $faker->userName,
            'status' => true,
            'ownerName' => $faker->userName,
            'ownerNameAr' => $faker->userName,
            'ownerNationalId' => $faker->randomNumber(),
            'longtitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
            'CRNo' => $faker->randomNumber(),
            'CRExpiration' => date('Y-m-d H:i:s'),
            'CRExpirationHj' => date('Y-m-d H:i:s'),
            'CRAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'phone1' => '555-555-5555',
            'phone2' => '555-555-5555',
            'phone3' => '555-555-5555',
            'fax' => $faker->randomNumber(),
            'website' => $faker->url,
            'email' => $faker->email,
            'zipCode' => $faker->randomNumber(),
            'addressLine1' => $faker->address,
            'addressLine1Ar' => $faker->address,
            'addressLine2' => $faker->address,
            'addressLine2Ar' => $faker->address,
            'city' => $faker->city,
            'cityAr' => $faker->city,
            //AtpData
            'atpLicenseNo' => null,
            'atpLicenseExpiration' => null,
            'atpLicenseExpirationHj' => null,
            'atpLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'atpWireTransferAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'classesNo' => null,
            'pcsNo_class' => null,
            //atcData should be null
            'atcLicenseNo' => $faker->randomNumber(),
            'atcLicenseExpiration' => date('Y-m-d H:i:s'),
            'atcLicenseExpirationHj' => date('Y-m-d H:i:s'),
            'atcLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'atcWireTransferAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'labsNo' => $faker->randomDigitNotNull,
            'pcsNo_lab' => $faker->randomDigitNotNull,
            'operatingSystem' => $faker->biasedNumberBetween(0, 5),
            'operatingSystemLang' => $faker->biasedNumberBetween(0, 5),
            'internetSpeed_lab' => $faker->randomNumber(),
            'officeLang' => $faker->biasedNumberBetween(0, 5),
            'officeVersion' => $faker->biasedNumberBetween(0, 5),
            'focalContactPerson_id' => $normalUserId,
            'creatorId' => $TCAUserId
        );

        $this->insert('organization', $atc);
        $atcId = $this->getAdapter()->getConnection()->lastInsertId();

        //adding training manager to atp
        $orgUser2 [] = array(
            'role_id' => $TCARoleId,
            'org_id' => $atcId,
            'user_id' => $TCAUserId
        );
        $this->insert('organization_user', $orgUser2);

        $atcMeta [] = array(
            'type_id' => $typeAtcId,
            'org_id' => $atcId,
            'expirationDate' => date('Y-m-d H:i:s'),
            'expirationDateHj' => date('Y-m-d H:i:s'),
            'expirationFlag' => 0,
        );
        $this->insert('organization_meta', $atcMeta);


        $both[] = array(
            'commercialName' => 'bothDummy',
            'commercialNameAr' => $faker->userName,
            'status' => true,
            'ownerName' => $faker->userName,
            'ownerNameAr' => $faker->userName,
            'ownerNationalId' => $faker->randomNumber(),
            'longtitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
            'CRNo' => $faker->randomNumber(),
            'CRExpiration' => date('Y-m-d H:i:s'),
            'CRExpirationHj' => date('Y-m-d H:i:s'),
            'CRAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'phone1' => '555-555-5555',
            'phone2' => '555-555-5555',
            'phone3' => '555-555-5555',
            'fax' => $faker->randomNumber(),
            'zipCode' => $faker->randomNumber(),
            'website' => $faker->url,
            'email' => $faker->email,
            'addressLine1' => $faker->address,
            'addressLine1Ar' => $faker->address,
            'addressLine2' => $faker->address,
            'addressLine2Ar' => $faker->address,
            'city' => $faker->city,
            'cityAr' => $faker->city,
            //AtpData
            'atpLicenseNo' => $faker->randomNumber(),
            'atpLicenseExpiration' => date('Y-m-d H:i:s'),
            'atpLicenseExpirationHj' => date('Y-m-d H:i:s'),
            'atpLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'atpWireTransferAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'classesNo' => $faker->randomDigitNotNull,
            'pcsNo_class' => $faker->randomDigitNotNull,
            //atcData should be null
            'atcLicenseNo' => $faker->randomNumber(),
            'atcLicenseExpiration' => date('Y-m-d H:i:s'),
            'atcLicenseExpirationHj' => date('Y-m-d H:i:s'),
            'atcLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'atcWireTransferAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'labsNo' => $faker->randomDigitNotNull,
            'pcsNo_lab' => $faker->randomDigitNotNull,
            'operatingSystem' => $faker->biasedNumberBetween(0, 5),
            'operatingSystemLang' => $faker->biasedNumberBetween(0, 5),
            'internetSpeed_lab' => $faker->randomNumber(),
            'officeLang' => $faker->biasedNumberBetween(0, 5),
            'officeVersion' => $faker->biasedNumberBetween(0, 5),
            'focalContactPerson_id' => $normalUserId,
            'creatorId' => $TMUserId
        );

        $this->insert('organization', $both);
        $bothId = $this->getAdapter()->getConnection()->lastInsertId();


        //adding test center admin to atc part
        $orgUser3 [] = array(
            'role_id' => $TCARoleId,
            'org_id' => $bothId,
            'user_id' => $TCAUserId
        );
        $this->insert('organization_user', $orgUser3);


        //adding training manager to atp part
        $orgUser4 [] = array(
            'role_id' => $TCARoleId,
            'org_id' => $bothId,
            'user_id' => $TMUserId
        );
        $this->insert('organization_user', $orgUser4);




        $bothMeta1 [] = array(
            'type_id' => $typeAtpId,
            'org_id' => $bothId,
            'expirationDate' => date('Y-m-d H:i:s'),
            'expirationDateHj' => date('Y-m-d H:i:s'),
            'expirationFlag' => 0,
        );
        $this->insert('organization_meta', $bothMeta1);

        $bothMeta2 [] = array(
            'type_id' => $typeAtcId,
            'org_id' => $bothId,
            'expirationDate' => date('Y-m-d H:i:s'),
            'expirationDateHj' => date('Y-m-d H:i:s'),
            'expirationFlag' => 0,
        );
        $this->insert('organization_meta', $bothMeta2);
    }
}
