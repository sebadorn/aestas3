<?php

class ae_UserModelTest extends PHPUnit_Framework_TestCase {


	public function testConstants() {
		$this->assertTrue( mb_strlen( ae_UserModel::TABLE ) > 0 );
		$this->assertTrue( mb_strlen( ae_UserModel::TABLE_ID_FIELD ) > 0 );
	}


	public function testGetLink() {
		$u = new ae_UserModel();
		$u->setId( 2 );
		$u->setPermalink( 'the-user' );

		if( ae_Settings::isModRewriteEnabled() ) {
			$this->assertEquals( $u->getLink( 'http://example.com/' ), 'http://example.com/' . PERMALINK_BASE_USER . 'the-user' );
		}
		else {
			$this->assertEquals( $u->getLink( 'http://example.com/' ), 'http://example.com/?' . PERMALINK_GET_USER . '=2' );
		}
	}


	public function testIsValidStatus() {
		$this->assertTrue( ae_UserModel::isValidStatus( ae_UserModel::STATUS_ACTIVE ) );
		$this->assertTrue( ae_UserModel::isValidStatus( ae_UserModel::STATUS_SUSPENDED ) );
		$this->assertFalse( ae_UserModel::isValidStatus( 'bogus' ) );
		$this->assertFalse( ae_UserModel::isValidStatus( TRUE ) );
		$this->assertFalse( ae_UserModel::isValidStatus( NULL ) );
	}


	public function testListStatuses() {
		$statuses = ae_UserModel::listStatuses();

		$this->assertTrue( count( $statuses ) > 0 );

		foreach( $statuses as $status ) {
			$this->assertTrue( ae_UserModel::isValidStatus( $status ) );
		}
	}


	public function testLoadFromData() {
		$data = array(
			'u_id' => 2,
			'u_name_extern' => 'Test',
			'u_name_intern' => 'The Tester',
			'u_permalink' => 'the-tester',
			'u_pwd' => 'password hash',
			'u_status' => ae_UserModel::STATUS_ACTIVE,
		);
		$u = new ae_UserModel( $data );

		$this->assertTrue( $u->getId() === 2 );
		$this->assertEquals( $u->getNameExternal(), 'Test' );
		$this->assertEquals( $u->getNameInternal(), 'The Tester' );
		$this->assertEquals( $u->getPermalink(), 'the-tester' );
		$this->assertEquals( $u->getPasswordHash(), 'password hash' );
		$this->assertTrue( $u->getStatus() === ae_UserModel::STATUS_ACTIVE );
	}


	public function testSetId() {
		$u = new ae_UserModel();

		$u->setId( 4 );
		$this->assertTrue( $u->getId() === 4 );

		$this->setExpectedException( 'Exception' );
		$u->setId( -1 );
	}


	public function testSetNameExternal() {
		$u = new ae_UserModel();

		$u->setNameExternal( 'my-user' );
		$this->assertEquals( $u->getNameExternal(), 'my-user' );

		$u->setNameExternal( 4 );
		$this->assertTrue( $u->getNameExternal() === '4' );
	}


	public function testSetNameInternal() {
		$u = new ae_UserModel();

		$u->setNameInternal( 'my-user' );
		$this->assertEquals( $u->getNameInternal(), 'my-user' );

		$u->setNameInternal( 4 );
		$this->assertTrue( $u->getNameInternal() === '4' );

		$this->setExpectedException( 'Exception' );
		$u->setNameInternal( '' );
	}


	public function testSetPasswordHash() {
		$u = new ae_UserModel();

		$hash = ae_Security::hash( 'test pwd' );
		$u->setPasswordHash( $hash );
		$this->assertTrue( $u->getPasswordHash() === $hash );

		$u->setPasswordHash( 123456 );
		$this->assertTrue( $u->getPasswordHash() === '123456' );

		$this->setExpectedException( 'Exception' );
		$u->setPasswordHash( '' );
	}


	public function testSetPermalink() {
		$u = new ae_UserModel();
		$u->setPermalink( 'user-permalink-test' );
		$this->assertEquals( $u->getPermalink(), 'user-permalink-test' );

		$input = 'Post "title" with Umlauten:  <em>üöäß</em> und Zahlen! 4 9!   ';
		$expected = 'post-title-with-umlauten-ueoeaess-und-zahlen-4-9-';
		$u->setPermalink( $input );
		$this->assertEquals( $u->getPermalink(), $expected );
	}


	public function testSetStatus() {
		$u = new ae_UserModel();
		$this->assertTrue( ae_UserModel::isValidStatus( $u->getStatus() ) );

		$u->setStatus( ae_UserModel::STATUS_ACTIVE );
		$this->assertEquals( $u->getStatus(), ae_UserModel::STATUS_ACTIVE );

		$this->setExpectedException( 'Exception' );
		$u->setStatus( TRUE );
	}


}
