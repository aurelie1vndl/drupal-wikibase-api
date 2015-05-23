<?php

namespace Wikibase\Api\Test;

use Mediawiki\DataModel\Revision;
use Wikibase\Api\PropertyApiLookup;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\PropertyContent;

/**
 * @covers Wikibase\Api\PropertyApiLookup
 */
class PropertyApiLookupTest extends \PHPUnit_Framework_TestCase {

	public function testGetPropertyForId() {
		$property = new Property( new PropertyId( 'P42' ), null, 'string' );

		$revisionGetterMock = $this->getMockBuilder( '\Wikibase\Api\Service\RevisionGetter' )
			->disableOriginalConstructor()
			->getMock();
		$revisionGetterMock->expects( $this->once() )
			->method( 'getFromId' )
			->with( $this->equalTo( new PropertyId( 'P42' ) ) )
			->will( $this->returnValue( new Revision( new PropertyContent( $property ) ) ) );

		$propertyApiLookup = new PropertyApiLookup( $revisionGetterMock );
		$this->assertEquals(
			$property,
			$propertyApiLookup->getPropertyForId( new PropertyId( 'P42' ) )
		);
	}

	public function testGetPropertyForIdWithException() {
		$revisionGetterMock = $this->getMockBuilder( '\Wikibase\Api\Service\RevisionGetter' )
			->disableOriginalConstructor()
			->getMock();
		$revisionGetterMock->expects( $this->once() )
			->method( 'getFromId' )
			->with( $this->equalTo( new PropertyId( 'P42' ) ) )
			->will( $this->returnValue( false ) );

		$propertyApiLookup = new PropertyApiLookup( $revisionGetterMock );

		$this->setExpectedException( 'Wikibase\DataModel\Entity\PropertyNotFoundException' );
		$propertyApiLookup->getPropertyForId( new PropertyId( 'P42' ) );
	}
}
