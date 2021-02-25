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
	 * @param string $markup The input markup
	 * @param array $options
	 * @return string The output HTML
	 */
	public static function markup2html( string $markup, array $options = [] ): string {
		$ast = self::markup2ast( $markup, $options );
		return $ast->toHtml();
	}

	/**
	 * Round-trip markup language back to markup language.
	 * In theory the output should be identical to the input.
	 *
	 * @param string $markup The input markup
	 * @param array $options
	 * @return string The output markup
	 */
	public static function markup2markup( string $markup, array $options = [] ): string {
		$ast = self::markup2ast( $markup, $options );
		return $ast->toMarkup();
	}

	/**
	 * Convert markup language to an Abstract Syntax Tree.
	 *
	 * @param string $markup The input markup
	 * @param array $options
	 * @return Root An abstract syntax tree corresponding to the input
	 */
	public static function markup2ast( string $markup, array $options = [] ): Root {
		// Ensure every line is terminated with a newline.
		$root = Grammar::load( "$markup\n" );
		// But strip the trailing blank line if one was added
		$lastSection = $root->getLastChild();
		$last = $lastSection ? $lastSection->getLastChild() : null;
		if ( $last instanceof Paragraph && $last->toMarkup() === '' ) {
			'@phan-var Section $lastSection'; // @var Section $lastSection
			$children = $root->getChildren();
			array_splice( $children, -1, 1, [
				new Section(
					$lastSection->getHeading(),
					array_slice(
						$lastSection->getChildren(),
						0, -1
					)
				)
			] );
			$root = new Root( $children );
		}
		return $root;
	}

}
