<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * Helper class to map section names to the Section nodes they correspond to.
 */
class SectionMap {
	/** @var array<string,Section> */
	private $map;

	/**
	 * Create a map from section names to Section nodes.
	 * @param Root $node The root node of the AST.
	 */
	public function __construct( Root $node ) {
		// Visitor class for collecting section names
		$this->map = $node->accept( new class() extends TraversalVisitor {
			/** @var array<string,Section> */
			private $map = [];

			/** @inheritDoc */
			public function visitSection( Section $node, ...$args ) {
				$name = $node->getSimpleName();
				if ( $name !== null ) {
					$this->map[$name] = $node;
				}
				// We don't need to recurse into children, because sections
				// are not nested inside sections.
			}

			/** @inheritDoc */
			public function visitRoot( Root $node, ...$args ) {
				parent::visitRoot( $node, ...$args );
				return $this->map;
			}
		} );
	}

	/**
	 * Return the section with the given name, or null if none exists.
	 * @param string $name The name of the section
	 * @return ?Section
	 */
	public function getSection( string $name ): ?Section {
		return $this->map[$name] ?? null;
	}

}
