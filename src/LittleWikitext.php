<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * Main entry points.
 */
abstract class LittleWikitext {

	/**
	 * Convert markup language to HTML.
	 *
	 * @param string $wikitext The input markup
	 * @return string The output HTML
	 */
	public static function wt2html( string $wikitext ): string {
		$ast = self::wt2ast( $wikitext );
		return $ast->toHtml();
	}

	/**
	 * Round-trip markup language back to markup language.
	 * In theory the output should be identical to the input.
	 *
	 * @param string $wikitext The input markup
	 * @return string The output markup
	 */
	public static function wt2wt( string $wikitext ): string {
		$ast = self::wt2ast( $wikitext );
		return $ast->toWikitext();
	}

	/**
	 * Convert markup language to an Abstract Syntax Tree.
	 *
	 * @param string $wikitext The input markup
	 * @return Root An abstract syntax tree corresponding to the input
	 */
	public static function wt2ast( string $wikitext ): Root {
		return Grammar::load( $wikitext );
	}

}
