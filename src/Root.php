<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Root class represents a top-level forest of nodes.  There is a single
 * root node for the AST, and the root will contain only Section nodes.
 */
class Root extends Node {
	/**
	 * @param Section[] $children
	 */
	public function __construct( array $children ) {
		parent::__construct( $children );
	}

	/** @inheritDoc */
	public function accept( Visitor $visitor, ...$args ) {
		return $visitor->visitRoot( $this, ...$args );
	}
}
