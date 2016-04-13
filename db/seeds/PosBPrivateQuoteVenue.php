<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;
use Courses\Entity\PrivateQuoteVenue;

class PosBPrivateQuoteVenue extends AbstractSeed
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
        $venues = array(
            array(
                'name' => PrivateQuoteVenue::VENUE_CUSTOMER_PREMISES,
            ),
            array(
                'name' => PrivateQuoteVenue::VENUE_COMPANY_PREMISES,
            ),
            array(
                'name' => PrivateQuoteVenue::VENUE_OTHER_PREMISES,
            ),
        );
        $this->insert('private_quote_venue', $venues);
    }

}
