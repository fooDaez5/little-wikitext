<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Root class represents a top-level forest of nodes.
 */
class Root extends Node {
	/**
	 * @param Node[] $children
	 */
	public function __construct( array $children ) {
		parent::__construct( $children );
	}

	public function toWikitext(): string {
		$result = [];
		foreach ( $this->children as $child ) {
			$result[] = $child->toWikitext() . "\n";
		}
		return implode( '', $result );
	}

	public function toHtml(): string {
		$result = '';
		$contextLevel = 0;
		$inSection = false;
		foreach ( $this->getChildren() as $child ) {
			$level = ( $child instanceof ListItem ) ? $child->getLevel() : 0;
			while ( $contextLevel > $level ) {
				$result .= "</ul>";
				if ( $contextLevel > 1 ) {
					$result .= "</li>";
				}
				$result .= "\n";
				$contextLevel -= 1;
			}
			if ( $child instanceof Heading ) {
				if ( $inSection ) {
					$result .= "</section>\n";
				}
				$result .= "<section>\n";
				$inSection = true;
			}
			while ( $level > $contextLevel ) {
				if ( $contextLevel > 0 ) {
					$result .= "<li>";
				}
				$result .= "<ul>\n";
				$contextLevel += 1;
			}
			$result .= $child->toHtml() . "\n";
		}
		while ( $contextLevel > 0 ) {
			$result .= "</ul>";
			if ( $contextLevel > 1 ) {
				$result .= "</li>";
			}
			$result .= "\n";
			$contextLevel -= 1;
		}
		if ( $inSection ) {
			$result .= "</section>\n";
		}
		return $result;
	}
}
