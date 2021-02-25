<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * Visitor class for serializing the AST as HTML.
 */
class ToHtmlVisitor extends Visitor {

	/**
	 * Convert a node to an HTML string.
	 * @param Node $node
	 * @return string HTML string
	 */
	public static function toHtml( Node $node ): string {
		$visitor = new self();
		return $visitor->visit( $node );
	}

	/** @inheritDoc */
	public function visitHeading( Heading $node, ...$args ) {
		$children = implode( $this->visitNodes( $node->getChildren() ) );
		return "<h2>$children</h2>";
	}

	/** @inheritDoc */
	public function visitInclusion( Inclusion $node, ...$args ) {
		$target = $node->getTarget();
		return "<template id=\"$target\"></template>";
	}

	/** @inheritDoc */
	public function visitLink( Link $node, ...$args ) {
		$target = $node->getTarget();
		$children = implode( $this->visitNodes( $node->getChildren() ) );
		return "<a href=\"./$target\">$children</a>";
	}

	/** @inheritDoc */
	public function visitListItem( ListItem $node, ...$args ) {
		$children = implode( $this->visitNodes( $node->getChildren() ) );
		return "<li>$children</li>";
	}

	/** @inheritDoc */
	public function visitParagraph( Paragraph $node, ...$args ) {
		$children = implode( $this->visitNodes( $node->getChildren() ) );
		return "<p>$children</p>";
	}

	/** @inheritDoc */
	public function visitRoot( Root $node, ...$args ) {
		return implode( $this->visitNodes( $node->getChildren() ) );
	}

	/** @inheritDoc */
	public function visitSection( Section $node, ...$args ) {
		$result = '';
		$contextLevel = 0;
		$heading = $node->getHeading();
		if ( $heading !== null ) {
			$result .= "<section>\n";
			$result .= $this->visitHeading( $heading );
			$result .= "\n";
		}
		foreach ( $node->getChildren() as $child ) {
			$level = ( $child instanceof ListItem ) ? $child->getLevel() : 0;
			while ( $contextLevel > $level ) {
				$result .= "</ul>";
				if ( $contextLevel > 1 ) {
					$result .= "</li>";
				}
				$result .= "\n";
				$contextLevel -= 1;
			}
			while ( $level > $contextLevel ) {
				if ( $contextLevel > 0 ) {
					$result .= "<li>";
				}
				$result .= "<ul>\n";
				$contextLevel += 1;
			}
			$result .= $this->visit( $child ) . "\n";
		}
		while ( $contextLevel > 0 ) {
			$result .= "</ul>";
			if ( $contextLevel > 1 ) {
				$result .= "</li>";
			}
			$result .= "\n";
			$contextLevel -= 1;
		}
		$result .= "</section>\n";
		return $result;
	}

	/** @inheritDoc */
	public function visitText( Text $node, ...$args ) {
		return htmlentities( $node->getValue() );
	}

}
