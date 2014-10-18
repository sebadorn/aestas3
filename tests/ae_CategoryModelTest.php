<?php

class ae_CategoryModelTest extends PHPUnit_Framework_TestCase {


	public function testSetter() {
		$c = new ae_CategoryModel();

		$this->setExpectedException( 'Exception' );
		$c->setId( -1 );
	}


}
