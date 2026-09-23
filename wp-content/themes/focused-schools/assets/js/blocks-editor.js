/**
 * Editor UI for the homepage blocks.
 *
 * Deliberately plain ES5 — wp.element.createElement rather than JSX — so the
 * theme needs no bundler, no node_modules and no build step, per the project's
 * "no new build tools without discussion" rule.
 *
 * Every block is server-rendered, so the editor shows the real front-end markup
 * through ServerSideRender and the fields live in the sidebar. What an editor
 * sees is what the page renders, and the saved content is only the attribute
 * record — a later design change updates every page without re-saving any.
 */
( function ( blocks, element, blockEditor, components, serverSideRender, i18n ) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;

	/**
	 * Build one field control bound to a block attribute.
	 *
	 * @param {Object}   props     Block props.
	 * @param {Object}   field     Field definition.
	 * @return {Object} Control element.
	 */
	function control( props, field ) {
		var Control = 'textarea' === field.type ? TextareaControl : TextControl;

		return el( Control, {
			key: field.name,
			label: field.label,
			help: field.help || undefined,
			rows: 'textarea' === field.type ? 6 : undefined,
			value: props.attributes[ field.name ] || '',
			onChange: function ( value ) {
				var next = {};
				next[ field.name ] = value;
				props.setAttributes( next );
			}
		} );
	}

	/**
	 * Register a server-rendered block with a sidebar of plain fields.
	 *
	 * @param {string} name   Block name.
	 * @param {string} title  Panel title.
	 * @param {Array}  fields Field definitions.
	 * @return {void}
	 */
	function registerServerBlock( name, title, fields ) {
		blocks.registerBlockType( name, {
			edit: function ( props ) {
				return el(
					element.Fragment,
					null,
					el(
						InspectorControls,
						null,
						el(
							PanelBody,
							{ title: title, initialOpen: true },
							fields.map( function ( field ) {
								return control( props, field );
							} )
						)
					),
					el( 'div', { className: props.className },
						el( serverSideRender, {
							block: name,
							attributes: props.attributes
						} )
					)
				);
			},
			// Server-rendered: nothing is written into post content but the
			// attribute record itself.
			save: function () {
				return null;
			}
		} );
	}

	registerServerBlock( 'focused-schools/home-hero', __( 'Hero content', 'focused-schools' ), [
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'subheading', label: __( 'Subheading', 'focused-schools' ), type: 'textarea' },
		{ name: 'ctaLabel', label: __( 'Button label', 'focused-schools' ) },
		{ name: 'ctaUrl', label: __( 'Button URL', 'focused-schools' ), help: __( 'Defaults to the beliefs section.', 'focused-schools' ) },
		{ name: 'youtubeId', label: __( 'YouTube video ID', 'focused-schools' ), help: __( 'The 11-character ID. Drives both the muted background loop and "Watch with sound".', 'focused-schools' ) },
		{ name: 'posterUrl', label: __( 'Poster image URL', 'focused-schools' ), help: __( 'Leave empty to use the theme default.', 'focused-schools' ) }
	] );

	registerServerBlock( 'focused-schools/beliefs', __( 'Beliefs content', 'focused-schools' ), [
		{ name: 'eyebrow', label: __( 'Rail label', 'focused-schools' ) },
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'body', label: __( 'Body', 'focused-schools' ), type: 'textarea', help: __( 'Blank line between paragraphs.', 'focused-schools' ) },
		{ name: 'emphasis', label: __( 'Emphasised opening phrase', 'focused-schools' ), help: __( 'Underlined as the reader scrolls. Must be how the body begins, or it renders plain.', 'focused-schools' ) },
		{ name: 'ctaLabel', label: __( 'Button label', 'focused-schools' ) },
		{ name: 'ctaUrl', label: __( 'Button URL', 'focused-schools' ) }
	] );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.serverSideRender,
	window.wp.i18n
);
