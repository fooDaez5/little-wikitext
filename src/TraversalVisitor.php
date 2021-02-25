<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * Visitor class for traversing the AST.
 *
 * This is a helper class which provides default visitor behavior to
 * make it easier to write visitors which traverse and analyze the AST.
 */
class TraversalVisitor extends Visitor {

	/** @inheritDoc */
	public function visitHeading( Heading $node, ...$args ) {
		return $this->visitNodes( $node->getChildren(), ...$args );
	}

	/** @inheritDoc */
	public function visitInclusion( Inclusion $node, ...$args ) {
		return $this->visitNodes( $node->getChildren(), ...$args );
	}

	/** @inheritDoc */
	public function visitLink( Link $node, ...$args ) {
		return $this->visitNodes( $node->getChildren(), ...$args );
	}

	/** @inheritDoc */
	public function visitListItem( ListItem $node, ...$args ) {
		return $this->visitNodes( $node->getChildren(), ...$args );
	}

	/** @inheritDoc */
	public function visitParagraph( Paragraph $node, ...$args ) {
		return $this->visitNodes( $node->getChildren(), ...$args );
	}

	/** @inheritDoc */
	public function visitRoot( Root $node, ...$args ) {
		return $this->visitNodes( $node->getChildren(), ...$args );
	}

	/** @inheritDoc */
	public function visitSection( Section $node, ...$args ) {
		$children = $node->getChildren();
		$heading = $node->getHeading();
		if ( $heading ) {
			array_unshift( $children, $heading );
		}
		return $this->visitNodes( $children, ...$args );
	}

	/** @inheritDoc */
	public function visitText( Text $node, ...$args ) {
		return $this->visitNodes( $node->getChildren(), ...$args );
	}

}
