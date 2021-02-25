<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Text class represents plaintext.
 */
class Text extends LeafNode {
	/** @var string */
	protected $value;

	/**
	 * @param string $value
	 */
	public function __construct( string $value ) {
		parent::__construct();
		$this->value = $value;
	}

	/** @return string */
	public function getValue() {
		return $this->value;
	}

	/** @inheritDoc */
	public function accept( Visitor $visitor, ...$args ) {
		return $visitor->visitText( $this, ...$args );
	}
}
