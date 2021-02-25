<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Link class represents a markup hyperlink.
 *
 * The children of a Link node are the caption of the link.
 */
class Link extends Node {
	/** @var string */
	private $target;

	/**
	 * @param string $target
	 * @param Node[] $children
	 */
	public function __construct( string $target, array $children ) {
		parent::__construct( $children );
		$this->target = $target;
	}

	/** @return string */
	public function getTarget(): string {
		return $this->target;
	}

	/** @inheritDoc */
	public function accept( Visitor $visitor, ...$args ) {
		return $visitor->visitLink( $this, ...$args );
	}
}
