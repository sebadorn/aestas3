<?php

class ae_ValidateTest extends PHPUnit_Framework_TestCase {


	public function testValidateId() {
		$this->assertTrue( ae_Validate::id( 4 ) );
		$this->assertFalse( ae_Validate::id( -90 ) );
	}


}
