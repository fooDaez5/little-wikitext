<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * Base class for the Abstract Syntax Tree (AST): a very simple
 * abstract type for a tree node.
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

	/** @return ?Node */
	final public function getFirstChild(): ?Node {
		$n = count( $this->children );
		return $n > 0 ? $this->children[0] : null;
	}

	/** @return ?Node */
	final public function getLastChild(): ?Node {
		$n = count( $this->children );
		return $n > 0 ? $this->children[$n - 1] : null;
	}

	/**
	 * Helper function to generate markup from this AST node.
	 * @return string a markup string
	 */
	final public function toMarkup(): string {
		return ToMarkupVisitor::toMarkup( $this );
	}

	/**
	 * Helper function to generate HTML from this AST node.
	 * @return string an HTML string
	 */
	final public function toHtml(): string {
		return ToHtmlVisitor::toHtml( $this );
	}

	/**
	 * Visitor pattern.
	 * @param Visitor $visitor
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function accept( Visitor $visitor, ...$args );

	/**
	 * Trim whitespace from the left and right sides of the list of children.
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
	 * Collapse adjacent or empty text nodes.
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
