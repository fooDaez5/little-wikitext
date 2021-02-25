<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Heading class represents a wikitext heading.
 */
class Heading extends Node {
	/**
	 * @param array $children
	 */
	public function __construct( array $children ) {
		parent::__construct( $children );
	}

	public function toWikitext(): string {
		$result = "== ";
		foreach ( $this->children as $child ) {
			$result .= $child->toWikitext();
		}
		$result .= " ==";
		return $result;
	}

	public function toHtml(): string {
		$result = "<h2>";
		foreach ( $this->children as $child ) {
			$result .= $child->toHtml();
		}
		$result .= "</h2>";
		return $result;
	}
}
