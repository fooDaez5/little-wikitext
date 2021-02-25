<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Paragraph class represents a wikitext paragraph.
 */
class Paragraph extends Node {
	/**
	 * @param array $children
	 */
	public function __construct( array $children ) {
		parent::__construct( $children );
	}

	public function toWikitext(): string {
		$result = '';
		foreach ( $this->children as $child ) {
			$result .= $child->toWikitext();
		}
		return $result;
	}

	public function toHtml(): string {
		$result = "<p>";
		foreach ( $this->children as $child ) {
			$result .= $child->toHtml();
		}
		$result .= "</p>";
		return $result;
	}
}
