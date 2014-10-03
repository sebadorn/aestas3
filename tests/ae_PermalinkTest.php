<?php

class ae_PermalinkTest extends PHPUnit_Framework_TestCase {


	public function testPermalink() {
		$input = 'Post "title" with Umlauten:  <em>üöäß</em> und Zahlen! 4 9!   ';
		$expected = 'post-title-with-umlauten-ueoeaess-und-zahlen-4-9-';
		$this->assertEquals( ae_Permalink::generatePermalink( $input ), $expected );
	}


}
