<?php

class ae_SecurityTest extends PHPUnit_Framework_TestCase {


	public function setUp() {
		$_SERVER['HTTP_USER_AGENT'] = '';
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

		ae_Security::init();
	}


	public function testSecurity() {
		$this->assertFalse( ae_Security::getCurrentUserId() );
		$this->assertNotEquals( trim( ae_Security::getSessionVerify() ), '' );

		$this->assertNotEquals( trim( ae_Security::hash( 'lorem ipsum', 'lorem' ) ), '' );
		$this->assertEquals(
			ae_Security::hash( 'input 1', 'the salt' ),
			ae_Security::hash( 'input 1', 'the salt' )
		);
		$this->assertNotEquals(
			trim( ae_Security::hash( 'lorem', 'aaa' ) ),
			trim( ae_Security::hash( 'ipsum', 'aaa' ) )
		);

		// The salt for the Blowfish algorithm has a limit of 22 characters.
		$this->assertNotEquals(
			trim( ae_Security::hash( 'lorem', 'aaaaaaaaaaaaaaaaaaaaaa' ) ),  //   22 characters
			trim( ae_Security::hash( 'lorem', 'aaaaaaaaaaaaaaaaaaaaaaaa' ) ) // > 22 characters
		);

		$this->setExpectedException( 'Exception' );
		ae_Security::hash( 'empty salt exception', '' );
	}


}