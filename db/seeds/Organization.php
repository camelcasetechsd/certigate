<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;
use \Users\Entity\User as User;
use \Users\Entity\Role;

class Organization extends AbstractSeed
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

        // dummy user to use his id ad foreign key in orgs
        $normalUser = array(
                "firstName" => $faker->firstName,
                "middleName" => $faker->name,
                "lastName" => $faker->lastName,
                "country" => $faker->countryCode,
                "language" => $faker->languageCode,
                "username" => "testuser",
                "password" => User::hashPassword("testuser"),
                "mobile" => $faker->phoneNumber,
                "addressOne" => $faker->address,
                "addressTwo" => $faker->address,
                "city" => $faker->city,
                "zipCode" => $faker->postcode,
                "phone" => $faker->phoneNumber,
                "nationality" => $faker->countryCode,
                "identificationType" => $faker->word,
                "identificationNumber" => $faker->numberBetween(/*$min =*/ 999999),
                "identificationExpiryDate" => $faker->dateTimeBetween(/*$startDate =*/ '+2 years', /*$endDate =*/ '+20 years')->format('Y-m-d H:i:s'),
                "email" => $faker->freeEmail,
                "securityQuestion" => $faker->sentence,
                "securityAnswer" => $faker->sentence,
                "dateOfBirth" => date('Y-m-d H:i:s'),
                "photo" => '/upload/images/userdefault.png',
                "privacyStatement" => true,
                "studentStatement" => false,
                "proctorStatement" => false,
                "instructorStatement" => false,
                "testCenterAdministratorStatement" => false,
                "trainingManagerStatement" => false,
                "status" => true
            );
        $this->insert('user', $normalUser);
        $normalUserId = $this->getAdapter()->getConnection()->lastInsertId();
        
        
        
        $atp[] = array(
            'commercialName' => $faker->userName,
            'commercialNameAr' => $faker->userName,
            'status' => true,
            'type' => 2,
            'ownerName' => $faker->userName,
            'ownerNameAr' => $faker->userName,
            'ownerNationalId' => $faker->randomNumber(),
            'longtitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
            'CRNo' => $faker->randomNumber(),
            'CRExpiration' => date('Y-m-d H:i:s'),
            'CRAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'phone3' => $faker->phoneNumber,
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
            'atpLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'classesNo' => $faker->randomDigitNotNull,
            'pcsNo_class' => $faker->randomDigitNotNull,
            //atcData should be null
            'atcLicenseNo' => null,
            'atcLicenseExpiration' => null,
            'atcLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'labsNo' => null,
            'pcsNo_lab' => null,
            'operatingSystem' => null,
            'operatingSystemLang' => null,
            'internetSpeed_lab' => null,
            'officeLang' => null,
            'officeVersion' => null,
            'focalContactPerson_id' => $normalUserId
        );

        $this->insert('organization', $atp);
        $atc[] = array(
            'commercialName' => $faker->userName,
            'commercialNameAr' => $faker->userName,
            'status' => true,
            'type' => 1,
            'ownerName' => $faker->userName,
            'ownerNameAr' => $faker->userName,
            'ownerNationalId' => $faker->randomNumber(),
            'longtitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
            'CRNo' => $faker->randomNumber(),
            'CRExpiration' => date('Y-m-d H:i:s'),
            'CRAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'phone3' => $faker->phoneNumber,
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
            'atpLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'classesNo' => null,
            'pcsNo_class' => null,
            //atcData should be null
            'atcLicenseNo' => $faker->randomNumber(),
            'atcLicenseExpiration' => date('Y-m-d H:i:s'),
            'atcLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'labsNo' => $faker->randomDigitNotNull,
            'pcsNo_lab' => $faker->randomDigitNotNull,
            'operatingSystem' => $faker->biasedNumberBetween(0, 5),
            'operatingSystemLang' => $faker->biasedNumberBetween(0, 5),
            'internetSpeed_lab' => $faker->randomNumber(),
            'officeLang' => $faker->biasedNumberBetween(0, 5),
            'officeVersion' => $faker->biasedNumberBetween(0, 5),
            'focalContactPerson_id' => $normalUserId
        );

        $this->insert('organization', $atc);


        $both[] = array(
            'commercialName' => $faker->userName,
            'commercialNameAr' => $faker->userName,
            'status' => true,
            'type' => 3,
            'ownerName' => $faker->userName,
            'ownerNameAr' => $faker->userName,
            'ownerNationalId' => $faker->randomNumber(),
            'longtitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
            'CRNo' => $faker->randomNumber(),
            'CRExpiration' => date('Y-m-d H:i:s'),
            'CRAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'phone3' => $faker->phoneNumber,
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
            'atpLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'classesNo' => $faker->randomDigitNotNull,
            'pcsNo_class' => $faker->randomDigitNotNull,
            //atcData should be null
            'atcLicenseNo' => $faker->randomNumber(),
            'atcLicenseExpiration' => date('Y-m-d H:i:s'),
            'atcLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'labsNo' => $faker->randomDigitNotNull,
            'pcsNo_lab' => $faker->randomDigitNotNull,
            'operatingSystem' => $faker->biasedNumberBetween(0, 5),
            'operatingSystemLang' => $faker->biasedNumberBetween(0, 5),
            'internetSpeed_lab' => $faker->randomNumber(),
            'officeLang' => $faker->biasedNumberBetween(0, 5),
            'officeVersion' => $faker->biasedNumberBetween(0, 5),
            'focalContactPerson_id' => $normalUserId
        );

        $this->insert('organization', $both);
    }

}
