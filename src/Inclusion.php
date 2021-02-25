<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Inclusion class represents a section inclusion in wikitext.
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

	/** @return string */
	public function toWikitext(): string {
		return "{{" . $this->target . "}}";
	}

	/** @return string */
	public function toHtml(): string {
		return '<template id="' . $this->target . '"></template>';
	}
}
