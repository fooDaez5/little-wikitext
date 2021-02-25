<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Paragraph class represents a wikitext paragraph.
 *
 * Paragraph contains Inclusion, Link, and/or Text nodes as children.
 */
class Paragraph extends Node {
	/**
	 * @param array $children
	 */
	public function __construct( array $children ) {
		parent::__construct( $children );
	}

	/** @inheritDoc */
	public function accept( Visitor $visitor, ...$args ) {
		return $visitor->visitParagraph( $this, ...$args );
	}
}
