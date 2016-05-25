<?php
 // src/AccueilBundle/Tests/Controller/BilletControllerTest.php

namespace AccueilBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class BilletControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array(''),
            array('/reserve/'),
            array('/famille/'),
            array('/reserve/2016-03-12/1/1'),
            array('/famille/2016-03-12/1/mansony/Gabon'),
            array('/resume/1'),
            array('/resumef/1'),
            array('/validation/1')
        );
    }
    
}
