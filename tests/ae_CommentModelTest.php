<?php

class ae_CommentModelTest extends PHPUnit_Framework_TestCase {


	public function testConstants() {
		$this->assertTrue( mb_strlen( ae_CommentModel::TABLE ) > 0 );
		$this->assertTrue( mb_strlen( ae_CommentModel::TABLE_ID_FIELD ) > 0 );
	}


	public function testIsValidStatus() {
		$this->assertTrue( ae_CommentModel::isValidStatus( ae_CommentModel::STATUS_APPROVED ) );
		$this->assertTrue( ae_CommentModel::isValidStatus( ae_CommentModel::STATUS_SPAM ) );
		$this->assertTrue( ae_CommentModel::isValidStatus( ae_CommentModel::STATUS_TRASH ) );
		$this->assertFalse( ae_CommentModel::isValidStatus( 'bogus' ) );
		$this->assertFalse( ae_CommentModel::isValidStatus( TRUE ) );
		$this->assertFalse( ae_CommentModel::isValidStatus( NULL ) );
	}


	public function testListStatuses() {
		$statuses = ae_CommentModel::listStatuses();

		$this->assertTrue( count( $statuses ) > 0 );

		foreach( $statuses as $status ) {
			$this->assertTrue( ae_CommentModel::isValidStatus( $status ) );
		}
	}


	public function testLoadFromData() {
		$data = array(
			'co_id' => 4,
			'co_ip' => '127.0.0.1',
			'co_post' => 4,
			'co_user' => 2,
			'co_name' => 'Tester',
			'co_email' => 'test@example.com',
			'co_url' => 'http://example.com',
			'co_datetime' => '2014-10-19 23:34:00',
			'co_content' => 'lorem <strong>ipsum</strong>',
			'co_status' => ae_CommentModel::STATUS_APPROVED,
		);
		$c = new ae_CommentModel( $data );

		$this->assertTrue( $c->getId() === 4 );
		$this->assertEquals( $c->getAuthorIp(), '127.0.0.1' );
		$this->assertTrue( $c->getPostId() === 4 );
		$this->assertTrue( $c->getUserId() === 2 );
		$this->assertEquals( $c->getAuthorName(), 'Tester' );
		$this->assertEquals( $c->getAuthorEmail(), 'test@example.com' );
		$this->assertEquals( $c->getAuthorUrl(), 'http://example.com' );
		$this->assertEquals( $c->getDatetime(), '2014-10-19 23:34:00' );
		$this->assertEquals( $c->getContent(), 'lorem <strong>ipsum</strong>' );
		$this->assertTrue( $c->getStatus() === ae_CommentModel::STATUS_APPROVED );
	}


	public function testSetAuthorEmail() {
		$c = new ae_CommentModel();

		$c->setAuthorEmail( 'test@example.com' );
		$this->assertEquals( $c->getAuthorEmail(), 'test@example.com' );

		$c->setAuthorEmail( '' );
		$this->assertTrue( $c->getAuthorEmail() === '' );

		$this->setExpectedException( 'Exception' );
		$c->setAuthorEmail( 'example.com' );
	}


	public function testSetAuthorIp() {
		$c = new ae_CommentModel();

		$c->setAuthorIp( '127.0.0.1' );
		$this->assertEquals( $c->getAuthorIp(), '127.0.0.1' );

		$c->setAuthorIp( 'FE80:0000:0000:0000:0202:B3FF:FE1E:8329' );
		$this->assertEquals( $c->getAuthorIp(), 'FE80:0000:0000:0000:0202:B3FF:FE1E:8329' );

		$c->setAuthorIp( 'FE80::0202:B3FF:FE1E:8329' );
		$this->assertEquals( $c->getAuthorIp(), 'FE80::0202:B3FF:FE1E:8329' );

		$this->setExpectedException( 'Exception' );
		$c->setAuthorIp( '127.0.0.1.0' );
	}


	public function testSetAuthorName() {
		$c = new ae_CommentModel();

		$c->setAuthorName( '  Ein  Bär ' );
		$this->assertEquals( $c->getAuthorName(), 'Ein  Bär' );
	}


	public function testSetAuthorUrl() {
		$c = new ae_CommentModel();

		$c->setAuthorUrl( 'http://example.com:8080' );
		$this->assertEquals( $c->getAuthorUrl(), 'http://example.com:8080' );

		$c->setAuthorUrl( '' );
		$this->assertTrue( $c->getAuthorUrl() === '' );

		$c->setAuthorUrl( 'https://127.0.0.1' );
		$this->assertEquals( $c->getAuthorUrl(), 'https://127.0.0.1' );

		$this->setExpectedException( 'Exception' );
		$c->setAuthorUrl( 'example.com' );
	}


	public function testSetContent() {
		$c = new ae_CommentModel();

		$c->setContent( '  lorem  <strong>ipsum</strong>   ' );
		$this->assertEquals( $c->getContent(), 'lorem  <strong>ipsum</strong>' );
	}


	public function testSetDatetime() {
		$c = new ae_CommentModel();

		$c->setDatetime( '2014-10-19 23:34:00' );
		$this->assertEquals( $c->getDatetime(), '2014-10-19 23:34:00' );

		$this->setExpectedException( 'Exception' );
		$c->setDatetime( '2014-10-19' );
	}


	public function testSetId() {
		$c = new ae_CommentModel();

		$c->setId( 4 );
		$this->assertTrue( $c->getId() === 4 );

		$this->setExpectedException( 'Exception' );
		$c->setId( -1 );
	}


	public function testSetPostId() {
		$c = new ae_CommentModel();

		$c->setPostId( 4 );
		$this->assertTrue( $c->getPostId() === 4 );

		$this->setExpectedException( 'Exception' );
		$c->setPostId( -2 );
	}


	public function testSetStatus() {
		$c = new ae_CommentModel();

		$this->assertTrue( ae_CommentModel::isValidStatus( $c->getStatus() ) );

		$c->setStatus( ae_CommentModel::STATUS_APPROVED );
		$this->assertEquals( $c->getStatus(), ae_CommentModel::STATUS_APPROVED );

		$this->setExpectedException( 'Exception' );
		$c->setStatus( 'bogus' );
	}


	public function testSetUserId() {
		$c = new ae_CommentModel();

		$c->setUserId( 4 );
		$this->assertTrue( $c->getUserId() === 4 );

		$this->setExpectedException( 'Exception' );
		$c->setUserId( -1 );
	}


}
