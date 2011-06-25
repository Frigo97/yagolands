<?php

require_once dirname ( __FILE__ ) . '/../../classes/Yagolands.php';
require_once dirname ( __FILE__ ) . '/../../classes/Model.php';

class ModelTest extends PHPUnit_Framework_TestCase {

    protected $object;

    protected function setUp () {
        $this->object = new Model;

    }

    protected function tearDown () {
        
    }

    public function testCount () {
        $obj = new Model ( 'capelli', true );
        $this->assertEquals ( 'select count(*) num from capelli', $obj->count () );

    }

    public function testFind () {
        $obj = new Model ( 'capelli', true );
        $this->assertEquals ( 'select * from capelli', $obj->find () );

    }

    public function testOrderBy () {
        $obj = new Model ( 'persone', true );
        $this->assertEquals ( 'select * from persone order by id desc', $obj->findOrderBy ( array (
                    'id' => 'desc'
                ) ) );
        $this->assertEquals ( 'select * from persone where pasta = "spaghetti" order by id desc', $obj->findOrderBy ( array (
                    'id' => 'desc'
                        ), array (
                    'pasta' => 'spaghetti'
                ) ) );

    }

    public function testCountwhere () {
        $this->markTestIncomplete (
                'This test has not been implemented yet.'
        );

    }

    public function testFindAll () {
        $this->markTestIncomplete (
                'This test has not been implemented yet.'
        );

    }

    public function testUpdate () {
        $this->markTestIncomplete (
                'This test has not been implemented yet.'
        );

    }

    public function testCreate () {
        $this->markTestIncomplete (
                'This test has not been implemented yet.'
        );

    }

    public function testDelete () {
        $this->markTestIncomplete (
                'This test has not been implemented yet.'
        );

    }

    public function testTruncate () {
        $this->markTestIncomplete (
                'This test has not been implemented yet.'
        );

    }
    
    public function testLastInsertId () {
        $this->markTestIncomplete (
                'This test has not been implemented yet.'
        );

    }

}