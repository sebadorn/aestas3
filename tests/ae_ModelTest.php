<?php

class ae_ModelTest extends PHPUnit_Framework_TestCase {


	public function testPermalink() {
		$input = 'Post "title" with Umlauten:  <em>üöäß</em> und Zahlen! 4 9!   ';
		$expected = 'post-title-with-umlauten-ueoeaess-und-zahlen-4-9-';
		$this->assertEquals( ae_Model::generatePermalink( $input ), $expected );
	}


}
