<?php

class ae_ValidateTest extends PHPUnit_Framework_TestCase {


	public function testValidateDatetime() {
		$this->assertTrue( ae_Validate::datetime( '0000-00-00 00:00:00' ) );
		$this->assertTrue( ae_Validate::datetime( date( 'Y-m-d H:i:s' ) ) );
		$this->assertFalse( ae_Validate::datetime( '0000-00-00 00-00-00' ) );
		$this->assertFalse( ae_Validate::datetime( '00-00-00 00:00:00' ) );
		$this->assertFalse( ae_Validate::datetime( time() ) );
	}


	public function testValidateEmail() {
		$this->assertTrue( ae_Validate::emailSloppy( 'example@example.com' ) );
		$this->assertTrue( ae_Validate::emailSloppy( 'exämple_ß14@example.com' ) );
		$this->assertFalse( ae_Validate::emailSloppy( 'example.com' ) );
		$this->assertFalse( ae_Validate::emailSloppy( "exa\tmp\nle.com" ) );
		$this->assertFalse( ae_Validate::emailSloppy( 'exämpleß.com' ) );
		$this->assertFalse( ae_Validate::emailSloppy( 'example@example@example.com' ) );
		$this->assertFalse( ae_Validate::emailSloppy( TRUE ) );
	}


	public function testValidateId() {
		$this->assertTrue( ae_Validate::id( 4 ) );
		$this->assertFalse( ae_Validate::id( -90 ) );
	}


	public function testValidateInteger() {
		$this->assertTrue( ae_Validate::integer( 4 ) );
		$this->assertTrue( ae_Validate::integer( '4' ) );
		$this->assertTrue( ae_Validate::integer( -14 ) );
		$this->assertTrue( ae_Validate::integer( '-14' ) );
		$this->assertFalse( ae_Validate::integer( 4.8 ) );
		$this->assertFalse( ae_Validate::integer( '4.8' ) );
		$this->assertFalse( ae_Validate::integer( 'a' ) );
		$this->assertFalse( ae_Validate::integer( '4a' ) );
	}


	public function testValidateIp() {
		$this->assertTrue( ae_Validate::ip( '78.43.208.225' ) );
	}


	public function testValidateUrl() {
		$this->assertTrue( ae_Validate::urlSloppy( 'http://sebadorn.de' ) );
		$this->assertTrue( ae_Validate::urlSloppy( 'https://sebadorn.de' ) );
		$this->assertTrue( ae_Validate::urlSloppy( 'ftp://sebadorn.de' ) );
		$this->assertTrue( ae_Validate::urlSloppy( 'ftps://sebadorn.de' ) );
		$this->assertTrue( ae_Validate::urlSloppy( 'https://sebädörnß.de' ) );
		$this->assertTrue( ae_Validate::urlSloppy( 'https://example.com#anchor' ) );
		$this->assertTrue( ae_Validate::urlSloppy( 'https://example.com?foo=bar&lorem%20ipsum' ) );
		$this->assertFalse( ae_Validate::urlSloppy( 'http://with whitespace' ) );
		$this->assertFalse( ae_Validate::urlSloppy( 'noprotocoll.foo' ) );
		$this->assertFalse( ae_Validate::urlSloppy( NULL ) );
	}


}
