<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * Visitor class for transforming the AST.
 *
 * This is a double-dispatch visitor pattern, which just means that
 * Visitor::visit() calls Node::accept(), and the specific subclass
 * of Node implements its ::accept to dispatch back to the appropriate
 * Node-specific method of Visitor.
 */
abstract class Visitor {

	/**
	 * Visit a node.  Dispatches to the appropriate specific visitor method
	 * below.
	 *
	 * @param Node $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	public function visit( Node $node, ...$args ) {
		return $node->accept( $this, ...$args );
	}

	/**
	 * Visit each element in an array of nodes.  If the visitor returns
	 * an array, the array is flattened into the result array.
	 * @param Node[] $nodes
	 * @param mixed ...$args
	 * @return array
	 */
	protected function visitNodes( array $nodes, ...$args ): array {
		$result = [];
		foreach ( $nodes as $child ) {
			$visited = $this->visit( $child, ...$args );
			if ( is_array( $visited ) ) {
				array_push( $result, ...$visited );
			} else {
				$result[] = $visited;
			}
		}
		return $result;
	}

	/**
	 * Visit a heading node.
	 * @param Heading $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function visitHeading( Heading $node, ...$args );

	/**
	 * Visit an inclusion node.
	 * @param Inclusion $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function visitInclusion( Inclusion $node, ...$args );

	/**
	 * Visit a link node.
	 * @param Link $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function visitLink( Link $node, ...$args );

	/**
	 * Visit a list item node.
	 * @param ListItem $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function visitListItem( ListItem $node, ...$args );

	/**
	 * Visit a paragraph node.
	 * @param Paragraph $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function visitParagraph( Paragraph $node, ...$args );

	/**
	 * Visit a root node.
	 * @param Root $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function visitRoot( Root $node, ...$args );

	/**
	 * Visit a section node.
	 * @param Section $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function visitSection( Section $node, ...$args );

	/**
	 * Visit a text node.
	 * @param Text $node
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function visitText( Text $node, ...$args );

}
