<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * Very simple abstract type for an AST node.
 */
abstract class Node {
	/** @var Node[] */
	protected $children;

	/** @param Node[] $children */
	protected function __construct( array $children ) {
		$this->children = $children;
	}

	/** @return Node[] */
	final public function getChildren(): array {
		return $this->children;
	}

	abstract public function toWikitext(): string;

	abstract public function toHtml(): string;

	/**
	 * @param Node[] $children
	 * @return Node[]
	 */
	public static function trim( array $children ): array {
		$children = self::normalize( $children );
		if ( count( $children ) > 0 ) {
			$first = $children[0];
			if ( $first instanceof Text ) {
				$txt = ltrim( $first->getValue() );
				array_splice( $children, 0, 1, $txt === '' ? [] : [ new Text( $txt ) ] );
			}
		}
		if ( count( $children ) > 0 ) {
			$last = $children[count( $children ) - 1];
			if ( $last instanceof Text ) {
				$txt = rtrim( $last->getValue() );
				array_splice( $children, -1, 1, $txt === '' ? [] : [ new Text( $txt ) ] );
			}
		}
		return $children;
	}

	/**
	 * @param Node[] $children
	 * @return Node[]
	 */
	public static function normalize( array $children ): array {
		// collapse adjacent Text nodes
		$text = [];
		$result = [];
		foreach ( $children as $item ) {
			$last = count( $result ) - 1;
			if ( $item instanceof Text ) {
				if ( $item->value !== '' ) {
					$text[] = $item->value;
				}
			} else {
				if ( count( $text ) > 0 ) {
					$result[] = new Text( implode( '', $text ) );
					$text = [];
				}
				$result[] = $item;
			}
		}
		if ( count( $text ) > 0 ) {
			$result[] = new Text( implode( '', $text ) );
		}
		return $result;
	}
}
