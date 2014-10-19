<?php

class ae_PermalinkTest extends PHPUnit_Framework_TestCase {


	public function testPermalink() {
		$input = 'Post "title" with Umlauten:  <em>üöäß</em> und Zahlen! 4 9!   ';
		$expected = 'post-title-with-umlauten-ueoeaess-und-zahlen-4-9-';
		$this->assertEquals( ae_Permalink::generatePermalink( $input ), $expected );
	}


	public function testPrepareTag() {
		$this->assertEquals( ae_Permalink::prepareTag( 'foo/bär 4' ), 'foo_b%C3%A4r%204' );
	}


}
