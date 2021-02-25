<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Heading class represents a wikitext heading.
 *
 * Heading contains Inclusion, Link, and/or Text nodes as children.
 */
class Heading extends Node {
	/**
	 * @param array $children
	 */
	public function __construct( array $children ) {
		parent::__construct( $children );
	}

	/** @inheritDoc */
	public function accept( Visitor $visitor, ...$args ) {
		return $visitor->visitHeading( $this, ...$args );
	}

	/**
	 * Return the 'simple name' of this heading, if the heading does not
	 * contain links or inclusions; otherwise, null.
	 * @return ?string The simple name if this heading has one, otherwise null
	 */
	public function getSimpleName(): ?string {
		// This assumes the children are normalized; see Node::normalize()
		// In fact the name is also trimmed during construction via Node::trim()
		if ( count( $this->children ) !== 1 ) {
			return null;
		}
		$child = $this->children[0];
		if ( !( $child instanceof Text ) ) {
			return null;
		}
		return $child->getValue();
	}
}
