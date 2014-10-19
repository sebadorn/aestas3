<?php

class ae_CategoryModelTest extends PHPUnit_Framework_TestCase {


	public function testConstants() {
		$this->assertTrue( mb_strlen( ae_CategoryModel::TABLE ) > 0 );
		$this->assertTrue( mb_strlen( ae_CategoryModel::TABLE_ID_FIELD ) > 0 );
	}


	public function testIsValidStatus() {
		$this->assertTrue( ae_CategoryModel::isValidStatus( ae_CategoryModel::STATUS_AVAILABLE ) );
		$this->assertTrue( ae_CategoryModel::isValidStatus( ae_CategoryModel::STATUS_TRASH ) );
		$this->assertFalse( ae_CategoryModel::isValidStatus( 'bogus' ) );
		$this->assertFalse( ae_CategoryModel::isValidStatus( TRUE ) );
		$this->assertFalse( ae_CategoryModel::isValidStatus( NULL ) );
	}


	public function testListStatuses() {
		$statuses = ae_CategoryModel::listStatuses();

		$this->assertTrue( count( $statuses ) > 0 );

		foreach( $statuses as $status ) {
			$this->assertTrue( ae_CategoryModel::isValidStatus( $status ) );
		}
	}


	public function testLoadFromData() {
		$data = array(
			'ca_id' => 4,
			'ca_parent' => 0,
			'ca_permalink' => 'My Category!',
			'ca_status' => ae_CategoryModel::STATUS_AVAILABLE,
			'ca_title' => 'My Category'
		);
		$c = new ae_CategoryModel( $data );

		$this->assertTrue( $c->getId() === 4 );
		$this->assertTrue( $c->getParent() === 0 );
		$this->assertTrue( $c->getPermalink() === 'my-category-' );
		$this->assertTrue( $c->getStatus() === ae_CategoryModel::STATUS_AVAILABLE );
		$this->assertTrue( $c->getTitle() === 'My Category' );
	}


	public function testSetId() {
		$c = new ae_CategoryModel();
		$c->setId( 4 );
		$this->assertTrue( $c->getId() === 4 );

		$this->setExpectedException( 'Exception' );
		$c->setId( -1 );
	}


	public function testSetChildren() {
		$c = new ae_CategoryModel();
		$c->setChildren( array( 1, 2, 50 ) );
		$this->assertEquals( $c->getChildren(), array( 1, 2, 50 ) );

		$this->setExpectedException( 'Exception' );
		$c->setChildren( array( 1, 2, -4 ) );
	}


	public function testSetParent() {
		$c = new ae_CategoryModel();
		$c->setParent( 4 );
		$this->assertTrue( $c->getParent() === 4 );

		$this->setExpectedException( 'Exception' );
		$c->setParent( -1 );
	}


	public function testSetPermalink() {
		$c = new ae_CategoryModel();
		$c->setPermalink( 'category-permalink-test' );
		$this->assertEquals( $c->getPermalink(), 'category-permalink-test' );

		$input = 'Post "title" with Umlauten:  <em>üöäß</em> und Zahlen! 4 9!   ';
		$expected = 'post-title-with-umlauten-ueoeaess-und-zahlen-4-9-';
		$c->setPermalink( $input );
		$this->assertEquals( $c->getPermalink(), $expected );
	}


	public function testSetStatus() {
		$c = new ae_CategoryModel();
		$this->assertTrue( ae_CategoryModel::isValidStatus( $c->getStatus() ) );

		$c->setStatus( ae_CategoryModel::STATUS_AVAILABLE );
		$this->assertEquals( $c->getStatus(), ae_CategoryModel::STATUS_AVAILABLE );

		$this->setExpectedException( 'Exception' );
		$c->setStatus( 'bogus' );
	}


	public function testSetTitle() {
		$c = new ae_CategoryModel();
		$c->setTitle( 'my-category' );
		$this->assertEquals( $c->getTitle(), 'my-category' );

		$c->setTitle( 4 );
		$this->assertTrue( $c->getTitle() === '4' );

		$this->setExpectedException( 'Exception' );
		$c->setTitle( '' );
	}


}
