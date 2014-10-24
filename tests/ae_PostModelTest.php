<?php

class ae_PostModelTest extends PHPUnit_Framework_TestCase {


	public function testAddCategory() {
		$p = new ae_PostModel();

		$c1 = new ae_CategoryModel();
		$c1->setId( 1 );

		$c2 = new ae_CategoryModel();
		$c2->setId( 2 );

		$p->addCategory( $c1 );
		$p->addCategory( $c2 );

		$this->assertEquals( $p->getCategories(), array( $c1, $c2 ) );
	}


	public function testConstants() {
		$this->assertTrue( mb_strlen( ae_PostModel::TABLE ) > 0 );
		$this->assertTrue( mb_strlen( ae_PostModel::TABLE_ID_FIELD ) > 0 );
	}


	public function testGetContentSnippet() {
		$p = new ae_PostModel();

		$p->setContent( 'lorem ipsum<!--more-->dolor sit amet.' );
		$this->assertEquals( $p->getContentSnippet(), 'lorem ipsum' );

		$p->setContent( 'Once upon a time.' );
		$this->assertEquals( $p->getContentSnippet(), 'Once upon a time.' );

		$p->setContent( '<!--more-->foobar' );
		$this->assertEquals( $p->getContentSnippet(), '' );
	}


	public function testGetLink() {
		$p = new ae_PostModel();
		$p->setId( 4 );
		$p->setPermalink( 'a-post' );

		if( ae_Settings::isModRewriteEnabled() ) {
			$this->assertEquals( $p->getLink( 'http://example.com/' ), 'http://example.com/' . PERMALINK_BASE_POST . 'a-post' );
		}
		else {
			$this->assertEquals( $p->getLink( 'http://example.com/' ), 'http://example.com/?' . PERMALINK_GET_POST . '=4' );
		}
	}


	public function testHasSnippet() {
		$p = new ae_PostModel();

		$p->setContent( 'üöä<!--more-->ß' );
		$this->assertTrue( $p->hasSnippet() );

		$p->setContent( 'lorem ipsüm' );
		$this->assertFalse( $p->hasSnippet() );
	}


	public function testIsValidStatus() {
		$this->assertTrue( ae_PostModel::isValidStatus( ae_PostModel::STATUS_DRAFT ) );
		$this->assertTrue( ae_PostModel::isValidStatus( ae_PostModel::STATUS_TRASH ) );
		$this->assertFalse( ae_PostModel::isValidStatus( 'bogus' ) );
		$this->assertFalse( ae_PostModel::isValidStatus( TRUE ) );
		$this->assertFalse( ae_PostModel::isValidStatus( NULL ) );
	}


	public function testListStatuses() {
		$statuses = ae_PostModel::listStatuses();

		$this->assertTrue( count( $statuses ) > 0 );

		foreach( $statuses as $status ) {
			$this->assertTrue( ae_PostModel::isValidStatus( $status ) );
		}
	}


	public function testLoadFromData() {
		$data = array(
			'po_id' => 4,
			'categories' => array( 1, 11, 26 ),
			'po_comments' => ae_PostModel::COMMENTS_CLOSED,
			'po_content' => '<p>foobar</p>',
			'po_datetime' => '2014-10-24 09:14:03',
			'po_edit' => '2014-10-24 09:16:03',
			'po_title' => 'My Page',
			'po_permalink' => 'My Page!',
			'po_status' => ae_PostModel::STATUS_PUBLISHED,
			'po_tags' => 'zwei;kleine;Wölfe',
			'po_user' => 2
		);
		$p = new ae_PostModel( $data );

		$this->assertTrue( $p->getId() === 4 );
		$this->assertEquals( $p->getCategoryIds(), array( 1, 11, 26 ) );
		$this->assertTrue( $p->getCommentsStatus() === ae_PostModel::COMMENTS_CLOSED );
		$this->assertEquals( $p->getContent(), '<p>foobar</p>' );
		$this->assertEquals( $p->getDatetime(), '2014-10-24 09:14:03' );
		$this->assertEquals( $p->getEditDatetime(), '2014-10-24 09:16:03' );
		$this->assertEquals( $p->getTitle(), 'My Page' );
		$this->assertEquals( $p->getPermalink(), 'my-page-' );
		$this->assertTrue( $p->getStatus() === ae_PostModel::STATUS_PUBLISHED );
		$this->assertEquals( $p->getTagsString(), 'zwei;kleine;Wölfe' );
		$this->assertTrue( $p->getUserId() === 2 );
	}


	public function testSetCategoryIds() {
		$p = new ae_PostModel();

		$p->setCategoryIds( array( 4, 52, 921 ) );
		$this->assertEquals( $p->getCategoryIds(), array( 4, 52, 921 ) );

		$this->setExpectedException( 'Exception' );
		$p->setCategoryIds( array( 4, 52, -2 ) );
	}


	public function testSetCommentsStatus() {
		$p = new ae_PostModel();

		$p->setCommentsStatus( ae_PostModel::COMMENTS_DISABLED );
		$this->assertTrue( $p->getCommentsStatus() === ae_PostModel::COMMENTS_DISABLED );

		$this->setExpectedException( 'Exception' );
		$p->setCommentsStatus( TRUE );
	}


	public function testSetContent() {
		$p = new ae_PostModel();

		$p->setContent( '<p>lorem <strong>ipsum</strong>!</p>' );
		$this->assertEquals( $p->getContent(), '<p>lorem <strong>ipsum</strong>!</p>' );

		$p->setContent( 4 );
		$this->assertTrue( $p->getContent() === '4' );
	}


	public function testSetDatetime() {
		$p = new ae_PostModel();

		$p->setDatetime( '2014-10-19 23:34:00' );
		$this->assertEquals( $p->getDatetime(), '2014-10-19 23:34:00' );
		$this->assertEquals( $p->getDatetime( 'Y H:i' ), '2014 23:34' );

		$this->setExpectedException( 'Exception' );
		$p->setDatetime( '2014-10-19' );
	}


	public function testSetEditDatetime() {
		$p = new ae_PostModel();

		$p->setEditDatetime( '2014-10-19 23:34:00' );
		$this->assertEquals( $p->getEditDatetime(), '2014-10-19 23:34:00' );
		$this->assertEquals( $p->getEditDatetime( 'Y H:i' ), '2014 23:34' );

		$this->setExpectedException( 'Exception' );
		$p->setEditDatetime( '2014-10-19' );
	}


	public function testSetId() {
		$p = new ae_PostModel();

		$p->setId( 4 );
		$this->assertTrue( $p->getId() === 4 );

		$this->setExpectedException( 'Exception' );
		$p->setId( -1 );
	}


	public function testSetNumComments() {
		$p = new ae_PostModel();

		$p->setNumComments( '4037' );
		$this->assertTrue( $p->getNumComments() === 4037 );

		$this->setExpectedException( 'Exception' );
		$p->setNumComments( -4 );
	}


	public function testSetPermalink() {
		$p = new ae_PostModel();

		$p->setPermalink( 'page-permalink-test' );
		$this->assertEquals( $p->getPermalink(), 'page-permalink-test' );

		$input = 'Post "title" with Umlauten:  <em>üöäß</em> und Zahlen! 4 9!   ';
		$expected = 'post-title-with-umlauten-ueoeaess-und-zahlen-4-9-';
		$p->setPermalink( $input );
		$this->assertEquals( $p->getPermalink(), $expected );
	}


	public function testSetStatus() {
		$p = new ae_PostModel();

		$this->assertTrue( ae_PostModel::isValidStatus( $p->getStatus() ) );

		$p->setStatus( ae_PostModel::STATUS_PUBLISHED );
		$this->assertEquals( $p->getStatus(), ae_PostModel::STATUS_PUBLISHED );

		$this->setExpectedException( 'Exception' );
		$p->setStatus( 'bogus' );
	}


	public function testSetTags() {
		$p = new ae_PostModel();

		$p->setTags( 'zwei; kleine  ;Wölfe;' );
		$this->assertEquals( $p->getTagsString(), 'zwei;kleine;Wölfe' );
		$this->assertEquals( $p->getTags(), array( 'zwei', 'kleine', 'Wölfe' ) );

		$p->setTags( array( 'these ', '  are', 'tags !' ) );
		$this->assertEquals( $p->getTagsString(), 'these;are;tags !' );
		$this->assertEquals( $p->getTags(), array( 'these', 'are', 'tags !' ) );
	}


	public function testSetTitle() {
		$p = new ae_PostModel();

		$p->setTitle( 'my-page' );
		$this->assertEquals( $p->getTitle(), 'my-page' );

		$p->setTitle( 4 );
		$this->assertTrue( $p->getTitle() === '4' );
	}


	public function testSetUserId() {
		$p = new ae_PostModel();

		$p->setUserId( 4 );
		$this->assertTrue( $p->getUserId() === 4 );

		$this->setExpectedException( 'Exception' );
		$p->setUserId( -1 );
	}


}
