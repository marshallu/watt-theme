<?php
/**
 * Basic block for HerdPress theme
 *
 * @package marshallu/watt
 */

/**
 *  This is the callback that displays the block.
 *
 * @param   array  $block      The block settings and attributes.
 * @param   string $content    The block content (emtpy string).
 * @param   bool   $is_preview True during AJAX preview.
 */
function herdpress_basic_block( $block, $content = '', $is_preview = false ) {
	$context               = Timber::context(); // phpcs:ignore
	$context['block']      = $block;
	$context['fields']     = get_fields();
	$context['is_preview'] = $is_preview;

	if ( isset( $block['anchor'] ) ) {
		$context['anchor'] = 'id="' . $block['anchor'] . '"';
	}

	Timber::render( 'blocks/basic.twig', $context ); // phpcs:ignore
}
