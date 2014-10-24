<?php

class ae_PageModelTest extends PHPUnit_Framework_TestCase {


	public function testConstants() {
		$this->assertTrue( mb_strlen( ae_PageModel::TABLE ) > 0 );
		$this->assertTrue( mb_strlen( ae_PageModel::TABLE_ID_FIELD ) > 0 );
	}


	public function testGetLink() {
		$p = new ae_PageModel();
		$p->setId( 4 );
		$p->setPermalink( 'a-page' );

		if( ae_Settings::isModRewriteEnabled() ) {
			$this->assertEquals( $p->getLink( 'http://example.com/' ), 'http://example.com/' . PERMALINK_BASE_PAGE . 'a-page' );
		}
		else {
			$this->assertEquals( $p->getLink( 'http://example.com/' ), 'http://example.com/?' . PERMALINK_GET_PAGE . '=4' );
		}
	}


	public function testIsValidStatus() {
		$this->assertTrue( ae_PageModel::isValidStatus( ae_PageModel::STATUS_DRAFT ) );
		$this->assertTrue( ae_PageModel::isValidStatus( ae_PageModel::STATUS_TRASH ) );
		$this->assertFalse( ae_PageModel::isValidStatus( 'bogus' ) );
		$this->assertFalse( ae_PageModel::isValidStatus( TRUE ) );
		$this->assertFalse( ae_PageModel::isValidStatus( NULL ) );
	}


	public function testListStatuses() {
		$statuses = ae_PageModel::listStatuses();

		$this->assertTrue( count( $statuses ) > 0 );

		foreach( $statuses as $status ) {
			$this->assertTrue( ae_PageModel::isValidStatus( $status ) );
		}
	}


	public function testLoadFromData() {
		$data = array(
			'pa_id' => 4,
			'pa_comments' => ae_PageModel::COMMENTS_CLOSED,
			'pa_content' => '<p>foobar</p>',
			'pa_datetime' => '2014-10-24 09:14:03',
			'pa_edit' => '2014-10-24 09:16:03',
			'pa_title' => 'My Page',
			'pa_permalink' => 'My Page!',
			'pa_status' => ae_PageModel::STATUS_PUBLISHED,
			'pa_user' => 2
		);
		$p = new ae_PageModel( $data );

		$this->assertTrue( $p->getId() === 4 );
		$this->assertTrue( $p->getCommentsStatus() === ae_PageModel::COMMENTS_CLOSED );
		$this->assertEquals( $p->getContent(), '<p>foobar</p>' );
		$this->assertEquals( $p->getDatetime(), '2014-10-24 09:14:03' );
		$this->assertEquals( $p->getEditDatetime(), '2014-10-24 09:16:03' );
		$this->assertEquals( $p->getTitle(), 'My Page' );
		$this->assertEquals( $p->getPermalink(), 'my-page-' );
		$this->assertTrue( $p->getStatus() === ae_PageModel::STATUS_PUBLISHED );
		$this->assertTrue( $p->getUserId() === 2 );
	}


	public function testSetCommentsStatus() {
		$p = new ae_PageModel();

		$p->setCommentsStatus( ae_PageModel::COMMENTS_DISABLED );
		$this->assertTrue( $p->getCommentsStatus() === ae_PageModel::COMMENTS_DISABLED );

		$this->setExpectedException( 'Exception' );
		$p->setCommentsStatus( TRUE );
	}


	public function testSetContent() {
		$p = new ae_PageModel();

		$p->setContent( '<p>lorem <strong>ipsum</strong>!</p>' );
		$this->assertEquals( $p->getContent(), '<p>lorem <strong>ipsum</strong>!</p>' );

		$p->setContent( 4 );
		$this->assertTrue( $p->getContent() === '4' );
	}


	public function testSetDatetime() {
		$p = new ae_PageModel();

		$p->setDatetime( '2014-10-19 23:34:00' );
		$this->assertEquals( $p->getDatetime(), '2014-10-19 23:34:00' );
		$this->assertEquals( $p->getDatetime( 'Y H:i' ), '2014 23:34' );

		$this->setExpectedException( 'Exception' );
		$p->setDatetime( '2014-10-19' );
	}


	public function testSetEditDatetime() {
		$p = new ae_PageModel();

		$p->setEditDatetime( '2014-10-19 23:34:00' );
		$this->assertEquals( $p->getEditDatetime(), '2014-10-19 23:34:00' );
		$this->assertEquals( $p->getEditDatetime( 'Y H:i' ), '2014 23:34' );

		$this->setExpectedException( 'Exception' );
		$p->setEditDatetime( '2014-10-19' );
	}


	public function testSetId() {
		$p = new ae_PageModel();
		$p->setId( 4 );
		$this->assertTrue( $p->getId() === 4 );

		$this->setExpectedException( 'Exception' );
		$p->setId( -1 );
	}


	public function testSetPermalink() {
		$p = new ae_PageModel();
		$p->setPermalink( 'page-permalink-test' );
		$this->assertEquals( $p->getPermalink(), 'page-permalink-test' );

		$input = 'Post "title" with Umlauten:  <em>üöäß</em> und Zahlen! 4 9!   ';
		$expected = 'post-title-with-umlauten-ueoeaess-und-zahlen-4-9-';
		$p->setPermalink( $input );
		$this->assertEquals( $p->getPermalink(), $expected );
	}


	public function testSetStatus() {
		$p = new ae_PageModel();
		$this->assertTrue( ae_PageModel::isValidStatus( $p->getStatus() ) );

		$p->setStatus( ae_PageModel::STATUS_PUBLISHED );
		$this->assertEquals( $p->getStatus(), ae_PageModel::STATUS_PUBLISHED );

		$this->setExpectedException( 'Exception' );
		$p->setStatus( 'bogus' );
	}


	public function testSetTitle() {
		$p = new ae_PageModel();
		$p->setTitle( 'my-page' );
		$this->assertEquals( $p->getTitle(), 'my-page' );

		$p->setTitle( 4 );
		$this->assertTrue( $p->getTitle() === '4' );
	}


	public function testSetUserId() {
		$p = new ae_PageModel();

		$p->setUserId( 4 );
		$this->assertTrue( $p->getUserId() === 4 );

		$this->setExpectedException( 'Exception' );
		$p->setUserId( -1 );
	}


}
