<?php

class ae_CommentfilterModelTest extends PHPUnit_Framework_TestCase {


	public function testConstants() {
		$this->assertTrue( mb_strlen( ae_CommentfilterModel::TABLE ) > 0 );
		$this->assertTrue( mb_strlen( ae_CommentfilterModel::TABLE_ID_FIELD ) > 0 );
	}


	public function testIsValidStatus() {
		$this->assertTrue( ae_CommentfilterModel::isValidStatus( ae_CommentfilterModel::STATUS_ACTIVE ) );
		$this->assertTrue( ae_CommentfilterModel::isValidStatus( ae_CommentfilterModel::STATUS_INACTIVE ) );
		$this->assertFalse( ae_CommentfilterModel::isValidStatus( 'bogus' ) );
		$this->assertFalse( ae_CommentfilterModel::isValidStatus( TRUE ) );
		$this->assertFalse( ae_CommentfilterModel::isValidStatus( NULL ) );
	}


	public function testListStatuses() {
		$statuses = ae_CommentfilterModel::listStatuses();

		$this->assertTrue( count( $statuses ) > 0 );

		foreach( $statuses as $status ) {
			$this->assertTrue( ae_CommentfilterModel::isValidStatus( $status ) );
		}
	}


	public function testLoadFromData() {
		$data = array(
			'cf_id' => 4,
			'cf_name' => 'Test filter',
			'cf_target' => ae_CommentfilterModel::TARGET_CONTENT,
			'cf_match' => '/(ends on )?test$/i',
			'cf_action' => ae_CommentfilterModel::ACTION_UNAPPROVE,
			'cf_status' => ae_CommentfilterModel::STATUS_ACTIVE,
		);
		$c = new ae_CommentfilterModel( $data );

		$this->assertTrue( $c->getId() === 4 );
		$this->assertEquals( $c->getName(), 'Test filter' );
		$this->assertTrue( $c->getMatchTarget() === ae_CommentfilterModel::TARGET_CONTENT );
		$this->assertEquals( $c->getMatchRule(), '/(ends on )?test$/i' );
		$this->assertTrue( $c->getAction() === ae_CommentfilterModel::ACTION_UNAPPROVE );
		$this->assertTrue( $c->getStatus() === ae_CommentfilterModel::STATUS_ACTIVE );
	}


	public function testSetAction() {
		$c = new ae_CommentfilterModel();

		$c->setAction( ae_CommentfilterModel::ACTION_DROP );
		$this->assertTrue( $c->getAction() === ae_CommentfilterModel::ACTION_DROP );

		$this->setExpectedException( 'Exception' );
		$c->setAction( TRUE );
	}


	public function testSetId() {
		$c = new ae_CommentfilterModel();

		$c->setId( 4 );
		$this->assertTrue( $c->getId() === 4 );

		$this->setExpectedException( 'Exception' );
		$c->setId( -1 );
	}


	public function testSetMatchRule() {
		$c = new ae_CommentfilterModel();

		$c->setMatchRule( '/^this is regex\./i' );
		$this->assertEquals( $c->getMatchRule(), '/^this is regex\./i' );

		$c->setMatchRule( ';^This too is regex\.$;' );
		$this->assertEquals( $c->getMatchRule(), ';^This too is regex\.$;' );

		$this->setExpectedException( 'Exception' );
		$c->setMatchRule( 'not regex' );
	}


	public function testSetMatchTarget() {
		$c = new ae_CommentfilterModel();

		$c->setMatchTarget( ae_CommentfilterModel::TARGET_NAME );
		$this->assertTrue( $c->getMatchTarget() === ae_CommentfilterModel::TARGET_NAME );

		$this->setExpectedException( 'Exception' );
		$c->setMatchTarget( TRUE );
	}


	public function testSetStatus() {
		$c = new ae_CommentfilterModel();

		$this->assertTrue( ae_CommentfilterModel::isValidStatus( $c->getStatus() ) );

		$c->setStatus( ae_CommentfilterModel::STATUS_ACTIVE );
		$this->assertEquals( $c->getStatus(), ae_CommentfilterModel::STATUS_ACTIVE );

		$this->setExpectedException( 'Exception' );
		$c->setStatus( 'bogus' );
	}


	public function testSetName() {
		$c = new ae_CommentfilterModel();

		$c->setName( 'my comment filter' );
		$this->assertEquals( $c->getName(), 'my comment filter' );

		$c->setName( 4 );
		$this->assertTrue( $c->getName() === '4' );
	}


}
