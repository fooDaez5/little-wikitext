<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The List class represents a wikitext list item.
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

	/** @return string */
	public function toWikitext(): string {
		$result = "[[" . $this->target . "|";
		foreach ( $this->children as $child ) {
			$result .= $child->toWikitext();
		}
		return $result . "]]";
	}

	/** @return string */
	public function toHtml(): string {
		$result = '<a href="./' . $this->target . '">';
		foreach ( $this->children as $child ) {
			$result .= $child->toHtml();
		}
		$result .= "</a>";
		return $result;
	}
}
