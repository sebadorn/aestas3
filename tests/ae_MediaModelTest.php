<?php

class ae_MediaModelTest extends PHPUnit_Framework_TestCase {


	public function testConstants() {
		$this->assertTrue( mb_strlen( ae_MediaModel::TABLE ) > 0 );
		$this->assertTrue( mb_strlen( ae_MediaModel::TABLE_ID_FIELD ) > 0 );
	}


	public function testGetFilePath() {
		$m = new ae_MediaModel();

		$m->setName( 'test.png' );
		$m->setDatetime( '2014-10-22 18:36:04' );

		$this->assertEquals( $m->getFilePathNoName(), '2014/10/' );
		$this->assertEquals( $m->getFilePath(), '2014/10/test.png' );
	}


	public function testIsType() {
		$m = new ae_MediaModel();

		$m->setType( 'image/png' );
		$this->assertTrue( $m->isImage() );
		$this->assertFalse( $m->isText() );
		$this->assertFalse( $m->isVideo() );

		$m->setType( 'text/plain' );
		$this->assertFalse( $m->isImage() );
		$this->assertTrue( $m->isText() );
		$this->assertFalse( $m->isVideo() );

		$m->setType( 'video/ogg' );
		$this->assertFalse( $m->isImage() );
		$this->assertFalse( $m->isText() );
		$this->assertTrue( $m->isVideo() );

		$m->setType( 'unknown' );
		$this->assertFalse( $m->isImage() );
		$this->assertFalse( $m->isText() );
		$this->assertFalse( $m->isVideo() );

		$m->setType( FALSE );
		$this->assertFalse( $m->isImage() );
		$this->assertFalse( $m->isText() );
		$this->assertFalse( $m->isVideo() );

		$m->setType( TRUE );
		$this->assertFalse( $m->isImage() );
		$this->assertFalse( $m->isText() );
		$this->assertFalse( $m->isVideo() );

		$m->setType( NULL );
		$this->assertFalse( $m->isImage() );
		$this->assertFalse( $m->isText() );
		$this->assertFalse( $m->isVideo() );
	}


	public function testIsValidStatus() {
		$this->assertTrue( ae_MediaModel::isValidStatus( ae_MediaModel::STATUS_AVAILABLE ) );
		$this->assertTrue( ae_MediaModel::isValidStatus( ae_MediaModel::STATUS_TRASH ) );
		$this->assertFalse( ae_MediaModel::isValidStatus( 'bogus' ) );
		$this->assertFalse( ae_MediaModel::isValidStatus( TRUE ) );
		$this->assertFalse( ae_MediaModel::isValidStatus( NULL ) );
	}


	public function testListStatuses() {
		$statuses = ae_MediaModel::listStatuses();

		$this->assertTrue( count( $statuses ) > 0 );

		foreach( $statuses as $status ) {
			$this->assertTrue( ae_MediaModel::isValidStatus( $status ) );
		}
	}


	public function testLoadFromData() {
		$data = array(
			'm_id' => 4,
			'm_datetime' => '2014-10-22 18:36:04',
			'm_meta' => '{"file_size":400}',
			'm_name' => 'Test media',
			'm_status' => ae_MediaModel::STATUS_AVAILABLE,
			'm_type' => 'application/zip',
			'm_user' => 1
		);
		$m = new ae_MediaModel( $data );

		$this->assertTrue( $m->getId() === 4 );
		$this->assertEquals( $m->getDatetime(), '2014-10-22 18:36:04' );
		$this->assertEquals( $m->getDatetime( 'Y-m-d H:i:s' ), '2014-10-22 18:36:04' );
		$this->assertEquals( $m->getMetaInfo(), array( 'file_size' => 400 ) );
		$this->assertEquals( $m->getName(), 'Test media' );
		$this->assertTrue( $m->getStatus() === ae_MediaModel::STATUS_AVAILABLE );
		$this->assertEquals( $m->getType(), 'application/zip' );
		$this->assertTrue( $m->getUserId() === 1 );
	}


	public function testSetDatetime() {
		$m = new ae_MediaModel();

		$m->setDatetime( '2014-10-19 23:34:00' );
		$this->assertEquals( $m->getDatetime(), '2014-10-19 23:34:00' );
		$this->assertEquals( $m->getDatetime( 'Y H:i' ), '2014 23:34' );

		$this->setExpectedException( 'Exception' );
		$m->setDatetime( '2014-10-19' );
	}


	public function testSetId() {
		$m = new ae_MediaModel();

		$m->setId( 4 );
		$this->assertTrue( $m->getId() === 4 );

		$this->setExpectedException( 'Exception' );
		$m->setId( -1 );
	}


	public function testSetMediaPath() {
		$m = new ae_MediaModel();

		$m->setMediaPath( '../some-directory/media/' );
		$this->assertEquals( $m->getMediaPath(), '../some-directory/media/' );
	}


	public function testSetMetaInfo() {
		$m = new ae_MediaModel();

		$m->setMetaInfo( '{"file_size":400,"hocus":"pocus"}' );
		$this->assertEquals( $m->getMetaInfo(), array( 'file_size' => 400, 'hocus' => 'pocus' ) );

		$m->setMetaInfo( array( 'key' => 'value', 'lorem' => 2, 'bool' => FALSE ) );
		$this->assertEquals( $m->getMetaInfo(), array( 'key' => 'value', 'lorem' => 2, 'bool' => FALSE ) );

		$this->setExpectedException( 'Exception' );
		$m->setMetaInfo( 'bogus' );
	}


	public function testSetName() {
		$m = new ae_MediaModel();

		$m->setName( 'my comment filter' );
		$this->assertEquals( $m->getName(), 'my comment filter' );

		$m->setName( 4 );
		$this->assertTrue( $m->getName() === '4' );

		$m->setName( 'should/not/be/a/path.png' );
		$this->assertEquals( $m->getName(), 'should-not-be-a-path.png' );

		$m->setName( 'should\not\\be\a\path.png' );
		$this->assertEquals( $m->getName(), 'should-not-be-a-path.png' );

		$this->setExpectedException( 'Exception' );
		$m->setName( '' );
	}


	public function testSetStatus() {
		$m = new ae_MediaModel();

		$this->assertTrue( ae_MediaModel::isValidStatus( $m->getStatus() ) );

		$m->setStatus( ae_MediaModel::STATUS_AVAILABLE );
		$this->assertEquals( $m->getStatus(), ae_MediaModel::STATUS_AVAILABLE );

		$this->setExpectedException( 'Exception' );
		$m->setStatus( 'bogus' );
	}


	public function testSetTmpName() {
		$m = new ae_MediaModel();

		$m->setTmpName( 'gh934h89jndf' );
		$this->assertEquals( $m->getTmpName(), 'gh934h89jndf.png' );
	}


	public function testSetType() {
		$m = new ae_MediaModel();

		$m->setType( 'image/png' );
		$this->assertEquals( $m->getType(), 'image/png' );
	}


	public function testSetUserId() {
		$m = new ae_MediaModel();

		$m->setUserId( 4 );
		$this->assertTrue( $m->getUserId() === 4 );

		$this->setExpectedException( 'Exception' );
		$m->setUserId( -1 );
	}


}
