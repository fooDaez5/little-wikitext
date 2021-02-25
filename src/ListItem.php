<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The ListItem class represents a wikitext list item.
 */
class ListItem extends Node {
	/** @var int */
	protected $level;

	/**
	 * @param int $level
	 * @param array $children
	 */
	public function __construct( int $level, array $children ) {
		parent::__construct( $children );
		$this->level = $level;
	}

	/**
	 * Return the nesting level of this list item.
	 * @return int The nesting level
	 */
	public function getLevel(): int {
		return $this->level;
	}

	public function toWikitext(): string {
		$result = str_repeat( '*', $this->level ) . ' ';
		foreach ( $this->children as $child ) {
			$result .= $child->toWikitext();
		}
		return $result;
	}

	public function toHtml(): string {
		$result = "<li>";
		foreach ( $this->children as $child ) {
			$result .= $child->toHtml();
		}
		$result .= "</li>";
		return $result;
	}
}
