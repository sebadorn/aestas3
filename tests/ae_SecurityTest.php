<?php

class ae_SecurityTest extends PHPUnit_Framework_TestCase {


	public function setUp() {
		$_SERVER['HTTP_USER_AGENT'] = '';
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

		ae_Security::init();
	}


	public function testHashing() {
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

		$this->assertTrue( ae_Security::verify(
			'this is my test input',
			ae_Security::hash( 'this is my test input', 'some salt' )
		) );
		$this->assertFalse( ae_Security::verify(
			'this is my test input',
			ae_Security::hash( 'this is wrong', 'more salt' )
		) );

		$this->setExpectedException( 'Exception' );
		ae_Security::hash( 'empty salt exception', '' );
	}


	public function testMisc() {
		$this->assertFalse( ae_Security::getCurrentUserId() );
		$this->assertNotEquals( trim( ae_Security::getSessionVerify() ), '' );
		$this->assertFalse( ae_Security::isLoggedIn() );
	}


	public function testSanitizing() {
		$before = '';
		$after = '';
		$this->assertEquals( ae_Security::sanitizeHTML( $before ), $after );

		$before = '<strong>test</strong>';
		$after = '<strong>test</strong>';
		$this->assertEquals( ae_Security::sanitizeHTML( $before ), $after );

		$before = '<b>lorem</b> <strong>ipsum dolor</strong> sit <em>amet</em>';
		$after = '&lt;b&gt;lorem&lt;/b&gt; <strong>ipsum dolor</strong> sit <em>amet</em>';
		$this->assertEquals( ae_Security::sanitizeHTML( $before ), $after );

		$before = 'I am <iframe src="http://evil" />! <script>Oooh!</script>';
		$after = 'I am &lt;iframe src="http://evil" /&gt;! &lt;script&gt;Oooh!&lt;/script&gt;';
		$this->assertEquals( ae_Security::sanitizeHTML( $before ), $after );
	}


}
