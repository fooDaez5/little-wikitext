<?php

namespace Wikimedia\LittleWikitext\Tests;

use RemexHtml\HTMLData;
use RemexHtml\Serializer\HtmlFormatter;
use RemexHtml\Serializer\Serializer;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;

/**
 * This is a helper class to test HTML validity.
 *
 * There are a number of different ways you could define "valid HTML"
 * but this class implements the simplest one: if you parse it, then
 * re-serialize it, do you get the same thing you started with?
 *
 * You are welcome to make your implementation adhere to a stricter
 * definition of validity if you like, but this is a reasonable
 * starting point.
 */
class ValidHtmlTest extends \PHPUnit\Framework\TestCase {

	/**
	 * Parse and re-serialize an HTML string, using the WHATWG
	 * standard parsing and fragment serialization algorithms.
	 * @param string $input An HTML string
	 * @return string A 'cleaned up' HTML string
	 */
	public static function makeValidHtml( string $input ): string {
		$errorList = [];
		$errorCallback = function ( $text, $pos ) use ( &$errorList ) {
			$errorList[] = "\n<!-- $text -->";
		};
		$formatter = new class( [] ) extends HtmlFormatter {
			public function startDocument( $fragmentNamespace, $fragmentName ) {
				/* Suppress doctype */
			}
		};
		$serializer = new Serializer( $formatter, $errorCallback );
		$treeBuilder = new TreeBuilder( $serializer, [
		] );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher, $input, [
		] );
		$tokenizer->execute( [
			'fragmentNamespace' => HTMLData::NS_HTML,
			'fragmentName' => 'body',
		] );
		return $serializer->getResult() . implode( '', $errorList );
	}

	/**
	 * Use ::makeValidHtml to perform a simple validity test on the given
	 * HTML: it is valid if it is unchanged by parsing followed by
	 * serialization.  See the test cases below for some limitations
	 * of this test, but it's a reasonable starting point which should
	 * catch obvious nesting errors, etc.
	 *
	 * @param string $input An HTML string to test
	 * @return bool true if the HTML string is identical after parsing and
	 *   reserialization
	 */
	public static function isValidHtml( string $input ): bool {
		return $input === self::makeValidHtml( $input );
	}

	// Test cases for HTML validity

	/**
	 * HTML strings that are "valid" and should pass.
	 */
	public function provideGoodHtml() {
		return [
			[ '<p>Testing testing</p>' ],
			[ '<p>Foo</p>\n<ul><li>Item</li></ul>' ],
			[ '<ul><li>Item 1</li><li><ul><li>Item 2</li></ul></li></ul>' ],
			// Always use double quotes for attributes
			[ '<div class="foo"></div>' ],
			// Encode &quot; in attributes, and &lt; and &gt; outside of tags
			[ '<div class="<&quot;>">&lt;"&gt;</div>' ],
			// Note that our definition of "valid HTML" is pretty weak
			// These next violate the HTML content model, but pass our
			// simple parse-and-reserialize definition of validity.
			// You can feel free to hold your output to a higher standard. :)
			[ '<li>Bare list</li>' ],
			[ '<ul><li>Item 1</li><div>This does not belong</div></ul>' ],
		];
	}

	/**
	 * HTML strings that are "invalid".
	 */
	public function provideBadHtml() {
		return [
			// Unclosed tags
			[ '<p>' ],
			// Bad nesting
			[ '<p><p></p></p>' ],
			[ '<p><div></div></p>' ],
			// Note that we are a bit simple-minded about quoting style;
			// only double-quoted attributes are valid
			[ '<div class=foo></div>' ],
			[ "<div class='foo'></div>" ],
		];
	}

	/**
	 * @dataProvider provideGoodHtml
	 * @covers Wikimedia\LittleWikitext\Tests\ValidHtmlTest::makeValidHtml
	 */
	public function testGoodHtml( $input ) {
		// Use ::assertEquals() here for better diagnostics if the
		// test fails.
		$this->assertEquals( self::makeValidHtml( $input ), $input );
	}

	/**
	 * @dataProvider provideBadHtml
	 * @covers Wikimedia\LittleWikitext\Tests\ValidHtmlTest::isValidHtml
	 */
	public function testBadHtml( $input ) {
		$this->assertFalse( self::isValidHtml( $input ) );
	}

}
