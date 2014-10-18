<?php

class ae_SecurityTest extends PHPUnit_Framework_TestCase {


	public function testArea() {
		$areas = array( 'create', 'credits', 'dashboard', 'edit', 'manage', 'media', 'settings' );

		foreach( $areas as $area ) {
			$this->assertTrue( ae_Security::isValidArea( $area ) );
		}

		$this->assertFalse( ae_Security::isValidArea( 'created' ) );
		$this->assertFalse( ae_Security::isValidArea( '' ) );
		$this->assertFalse( ae_Security::isValidArea( TRUE ) );
		$this->assertFalse( ae_Security::isValidArea( NULL ) );


		$subAreasManage = array( 'category', 'comment', 'media', 'page', 'post', 'user' );

		foreach( $subAreasManage as $area ) {
			$this->assertTrue( ae_Security::isValidSubArea( 'manage', $area ) );
		}

		$this->assertFalse( ae_Security::isValidSubArea( 'manage', 'created' ) );
		$this->assertFalse( ae_Security::isValidSubArea( 'media', 'created' ) );
		$this->assertFalse( ae_Security::isValidSubArea( 'media', '' ) );
		$this->assertFalse( ae_Security::isValidSubArea( 'media', TRUE ) );
		$this->assertFalse( ae_Security::isValidSubArea( 'media', NULL ) );
	}


	public function testHashing() {
		$this->assertNotEquals( trim( ae_Security::hash( 'lorem ipsum' ) ), '' );
		$this->assertNotEquals(
			trim( ae_Security::hash( 'lorem' ) ),
			trim( ae_Security::hash( 'ipsum' ) )
		);
		$this->assertNotEquals(
			trim( ae_Security::hash( 'lorem' ) ),
			trim( ae_Security::hash( 'lorem' ) )
		);
		$this->assertTrue( ae_Security::verify(
			'this is my test input',
			ae_Security::hash( 'this is my test input' )
		) );
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
