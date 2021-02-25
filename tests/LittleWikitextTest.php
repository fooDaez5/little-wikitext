<?php

namespace Wikimedia\LittleWikitext\Tests;

use Wikimedia\LittleWikitext\LittleWikitext;
use Wikimedia\LittleWikitext\Tests\ParserTests\TestFileReader;

/**
 * The main PHPUnit entry point.
 *
 * Runs the tests from parserTests.txt, which are loaded via
 * the ::provideTestCases() method.
 *
 * @coversDefaultClass \Wikimedia\LittleWikitext\LittleWikitext
 */
class LittleWikitextTest extends \PHPUnit\Framework\TestCase {

	public function provideTestCases() {
		$tests = TestFileReader::read( __DIR__ . "/parserTests.txt" );
		foreach ( $tests as $t ) {
			$desc = $t->errorMsg( trim( $t->testName ?? '<unknown>' ) );
			yield $desc => [ $t ];
		}
	}

	/**
	 * @dataProvider provideTestCases
	 * @covers ::markup2ast
	 */
	public function testUnexpanded( $test ) {
		// Skip the test for Task 1 when running `composer initial-test`
		if (
			defined( 'PHPUNIT_INITIAL_TEST' ) &&
			strpos( $test->testName ?? '', '(task 1)' ) !== false
		) {
			$this->markTestSkipped( 'Running initial-test' );
		}

		$sections = $test->sections;
		$this->assertArrayHasKey(
			'markup', $sections, 'Test case is missing !!markup section'
		);
		$input = $sections['markup'];
		$unexpanded = $sections['html/unexpanded'] ?? $sections['html'] ?? null;
		$expanded = $sections['html/expanded'] ?? $sections['html'] ?? null;

		$ast = LittleWikitext::markup2ast( $input );
		$html = $ast->toHtml();

		// Assert validity of HTML!
		// Use ::makeValidHtml and ::assertEquals here (instead of ::isValidHtml)
		// for better diagnostics if the test fails.
		$this->assertEquals(
			ValidHtmlTest::makeValidHtml( $html ),
			$html,
			'Invalid HTML (unexpanded)'
		);

		// Check that the output matches what's expected (if that was provided)
		if ( $unexpanded !== null ) {
			$this->assertEquals(
				$unexpanded,
				$html,
				'Unexpanded HTML did not match expected'
			);
		}

		// Now convert the AST back to markup; this conversion should be
		// lossless (for unexpanded markup at least)
		$markup = $ast->toMarkup();
		$this->assertEquals(
			$input,
			$markup,
			'AST did not cleanly round-trip back to markup'
		);

		// Expand inclusions and check that the result matches what's
		// expected.
		$newAst = $ast; // XXX invoke your own code to make $newAst
		$newHtml = $newAst->toHtml();
		$this->assertEquals(
			ValidHtmlTest::makeValidHtml( $newHtml ),
			$newHtml,
			'Invalid HTML (expanded)'
		);
		$expanded = null; // XXX remove this line to enable tests
		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( $expanded !== null ) {
			$this->assertEquals(
				$expanded,
				$newHtml,
				'Expanded HTML did not match expected'
			);
		}
	}
}
