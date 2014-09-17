<?php

class ae_ValidateTest extends PHPUnit_Framework_TestCase {


	public function testValidateDatetime() {
		$this->assertTrue( ae_Validate::datetime( '0000-00-00 00:00:00' ) );
		$this->assertTrue( ae_Validate::datetime( date( 'Y-m-d H:i:s' ) ) );
		$this->assertFalse( ae_Validate::datetime( '0000-00-00 00-00-00' ) );
		$this->assertFalse( ae_Validate::datetime( '00-00-00 00:00:00' ) );
		$this->assertFalse( ae_Validate::datetime( time() ) );
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


}
