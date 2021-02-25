<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Inclusion class represents a section inclusion in wikitext.
 *
 * Section inclusions look like this: `{{Foo}}`.
 */
class Inclusion extends LeafNode {
	/** @var string */
	private $target;

	/**
	 * @param string $target
	 */
	public function __construct( string $target ) {
		parent::__construct();
		$this->target = $target;
	}

	/** @return string */
	public function getTarget(): string {
		return $this->target;
	}

	/** @inheritDoc */
	public function accept( Visitor $visitor, ...$args ) {
		return $visitor->visitInclusion( $this, ...$args );
	}
}
