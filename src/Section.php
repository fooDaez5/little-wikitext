<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * The Section class represents a named or unnamed section.
 * Sections consist of an optional heading followed
 * by list items or paragraphs.
 *
 * Note that the Heading (if present) is *not* included in the array
 * returned by `::getChildren()`.
 */
class Section extends Node {
	/** @var ?Heading */
	private $heading;

	/**
	 * @param ?Heading $heading The section heading, or null for the
	 *   unnamed section
	 * @param Node[] $children The listitem or paragraph contents of the section
	 */
	public function __construct( ?Heading $heading, array $children ) {
		parent::__construct( $children );
		$this->heading = $heading;
	}

	/** @return ?Heading */
	public function getHeading(): ?Heading {
		return $this->heading;
	}

	/** @inheritDoc */
	public function accept( Visitor $visitor, ...$args ) {
		return $visitor->visitSection( $this, ...$args );
	}

	/**
	 * Return the 'simple name' of this section if one exists,
	 * otherwise, null.
	 * @see Heading::getSimpleName()
	 * @return ?string The simple name if this section has one, otherwise null
	 */
	public function getSimpleName(): ?string {
		return $this->heading !== null ? $this->heading->getSimpleName() : null;
	}
}
