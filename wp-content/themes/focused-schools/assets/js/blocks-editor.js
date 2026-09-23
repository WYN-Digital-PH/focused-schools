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
 *
 * Field types: 'text' and 'textarea' edit a single attribute; 'image' edits a
 * trio (<name>Id / <name>Url / <name>Alt) through the media library, falling
 * back to the theme's bundled photo when nothing is chosen.
 */
( function ( blocks, element, blockEditor, components, serverSideRender, i18n ) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var Button = components.Button;

	/**
	 * Media picker bound to a <name>Id / <name>Url / <name>Alt attribute trio.
	 *
	 * @param {Object} props Block props.
	 * @param {Object} field Field definition.
	 * @return {Object} Control element.
	 */
	function imageControl( props, field ) {
		var name = field.name;
		var currentId = props.attributes[ name + 'Id' ] || 0;
		var currentUrl = props.attributes[ name + 'Url' ] || '';

		function set( values ) {
			props.setAttributes( values );
		}

		return el(
			'div',
			{ key: name, style: { marginBottom: '24px' } },
			el( 'p', { style: { fontWeight: 600, marginBottom: '8px' } }, field.label ),
			currentUrl
				? el( 'img', {
					src: currentUrl,
					alt: '',
					style: { width: '100%', height: 'auto', marginBottom: '8px', borderRadius: '4px' }
				} )
				: el( 'p', { style: { opacity: 0.7, marginBottom: '8px' } }, __( 'Using the theme default.', 'focused-schools' ) ),
			el(
				MediaUploadCheck,
				null,
				el( MediaUpload, {
					allowedTypes: [ 'image' ],
					value: currentId,
					onSelect: function ( media ) {
						var next = {};
						next[ name + 'Id' ] = media.id;
						next[ name + 'Url' ] = media.url;

						if ( media.alt ) {
							next[ name + 'Alt' ] = media.alt;
						}

						set( next );
					},
					render: function ( open ) {
						return el(
							Button,
							{ variant: 'secondary', onClick: open.open },
							currentUrl ? __( 'Replace image', 'focused-schools' ) : __( 'Choose image', 'focused-schools' )
						);
					}
				} )
			),
			currentId
				? el(
					Button,
					{
						variant: 'tertiary',
						isDestructive: true,
						style: { marginLeft: '8px' },
						onClick: function () {
							var next = {};
							next[ name + 'Id' ] = 0;
							next[ name + 'Url' ] = '';
							set( next );
						}
					},
					__( 'Use theme default', 'focused-schools' )
				)
				: null,
			el( TextControl, {
				label: __( 'Alt text', 'focused-schools' ),
				help: __( 'Describe the work shown, not the brand. Leave empty to use the default.', 'focused-schools' ),
				value: props.attributes[ name + 'Alt' ] || '',
				onChange: function ( value ) {
					var next = {};
					next[ name + 'Alt' ] = value;
					set( next );
				}
			} )
		);
	}

	/**
	 * Build one control bound to a block attribute.
	 *
	 * @param {Object} props Block props.
	 * @param {Object} field Field definition.
	 * @return {Object} Control element.
	 */
	function control( props, field ) {
		if ( 'image' === field.type ) {
			return imageControl( props, field );
		}

		var Control = 'textarea' === field.type ? TextareaControl : TextControl;

		return el( Control, {
			key: field.name,
			label: field.label,
			help: field.help || undefined,
			rows: 'textarea' === field.type ? 6 : undefined,
			value: props.attributes[ field.name ] || '',
			onChange: function ( value ) {
				var next = {};
				next[ field.name ] = 'number' === typeof props.attributes[ field.name ] ? parseInt( value, 10 ) || 0 : value;
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

	var URL_HELP = __( 'A path like /team/ links within this site; a full URL is used as given.', 'focused-schools' );

	registerServerBlock( 'focused-schools/home-hero', __( 'Hero', 'focused-schools' ), [
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'subheading', label: __( 'Subheading', 'focused-schools' ), type: 'textarea' },
		{ name: 'ctaLabel', label: __( 'Button label', 'focused-schools' ) },
		{ name: 'ctaUrl', label: __( 'Button URL', 'focused-schools' ), help: __( 'Defaults to the beliefs section.', 'focused-schools' ) },
		{ name: 'youtubeId', label: __( 'YouTube video ID', 'focused-schools' ), help: __( 'The 11-character ID. Drives both the muted background loop and "Watch with sound".', 'focused-schools' ) },
		{ name: 'poster', label: __( 'Poster image', 'focused-schools' ), type: 'image' }
	] );

	registerServerBlock( 'focused-schools/beliefs', __( 'Beliefs', 'focused-schools' ), [
		{ name: 'eyebrow', label: __( 'Rail label', 'focused-schools' ) },
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'body', label: __( 'Body', 'focused-schools' ), type: 'textarea', help: __( 'Blank line between paragraphs.', 'focused-schools' ) },
		{ name: 'emphasis', label: __( 'Emphasised opening phrase', 'focused-schools' ), help: __( 'Underlined as the reader scrolls. Must be how the body begins, or it renders plain.', 'focused-schools' ) },
		{ name: 'ctaLabel', label: __( 'Button label', 'focused-schools' ) },
		{ name: 'ctaUrl', label: __( 'Button URL', 'focused-schools' ), help: URL_HELP },
		{ name: 'strip1', label: __( 'Photo strip 1', 'focused-schools' ), type: 'image' },
		{ name: 'strip2', label: __( 'Photo strip 2', 'focused-schools' ), type: 'image' },
		{ name: 'strip3', label: __( 'Photo strip 3', 'focused-schools' ), type: 'image' }
	] );

	var commitmentFields = [
		{ name: 'eyebrow', label: __( 'Eyebrow', 'focused-schools' ) },
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'headingEmphasis', label: __( 'Heading, bold tail', 'focused-schools' ) },
		{ name: 'intro', label: __( 'Intro', 'focused-schools' ), type: 'textarea' },
		{ name: 'image', label: __( 'Photo', 'focused-schools' ), type: 'image' },
		{ name: 'captionKicker', label: __( 'Caption kicker', 'focused-schools' ) },
		{ name: 'captionText', label: __( 'Caption text', 'focused-schools' ) }
	];

	[ 1, 2, 3 ].forEach( function ( i ) {
		commitmentFields.push( { name: 'item' + i + 'Heading', label: __( 'Commitment ', 'focused-schools' ) + i + __( ' — heading', 'focused-schools' ) } );
		commitmentFields.push( { name: 'item' + i + 'Body', label: __( 'Commitment ', 'focused-schools' ) + i + __( ' — body', 'focused-schools' ), type: 'textarea' } );
		commitmentFields.push( { name: 'item' + i + 'CtaLabel', label: __( 'Commitment ', 'focused-schools' ) + i + __( ' — link label', 'focused-schools' ) } );
		commitmentFields.push( { name: 'item' + i + 'CtaUrl', label: __( 'Commitment ', 'focused-schools' ) + i + __( ' — link URL', 'focused-schools' ), help: URL_HELP } );
	} );

	registerServerBlock( 'focused-schools/commitments', __( 'Commitments', 'focused-schools' ), commitmentFields );

	registerServerBlock( 'focused-schools/cycle-teaser', __( 'Cycle Teaser', 'focused-schools' ), [
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'body', label: __( 'Body', 'focused-schools' ), type: 'textarea' },
		{ name: 'image', label: __( 'Portrait', 'focused-schools' ), type: 'image' }
	] );

	registerServerBlock( 'focused-schools/cycle', __( 'Cycle of Excellence', 'focused-schools' ), [
		{ name: 'eyebrow', label: __( 'Eyebrow', 'focused-schools' ) },
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'body', label: __( 'Body', 'focused-schools' ), type: 'textarea' },
		{ name: 'phase1', label: __( 'Phase 1', 'focused-schools' ) },
		{ name: 'phase2', label: __( 'Phase 2', 'focused-schools' ) },
		{ name: 'phase3', label: __( 'Phase 3', 'focused-schools' ) }
	] );

	registerServerBlock( 'focused-schools/services', __( 'Services Teaser', 'focused-schools' ), [
		{ name: 'eyebrow', label: __( 'Eyebrow', 'focused-schools' ) },
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'description', label: __( 'Description', 'focused-schools' ), type: 'textarea' },
		{ name: 'linkLabel', label: __( 'Link label', 'focused-schools' ) },
		{ name: 'linkUrl', label: __( 'Link URL', 'focused-schools' ), help: URL_HELP },
		{ name: 'image', label: __( 'Photo', 'focused-schools' ), type: 'image' },
		{ name: 'captionKicker', label: __( 'Caption kicker', 'focused-schools' ) },
		{ name: 'captionText', label: __( 'Caption text', 'focused-schools' ) },
		{ name: 'limit', label: __( 'How many services to show', 'focused-schools' ), help: __( 'They come from the Services list, newest order first.', 'focused-schools' ) }
	] );

	registerServerBlock( 'focused-schools/impact-stories', __( 'Impact Stories Band', 'focused-schools' ), [
		{ name: 'eyebrow', label: __( 'Eyebrow', 'focused-schools' ) },
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'ctaLabel', label: __( 'Button label', 'focused-schools' ) },
		{ name: 'ctaUrl', label: __( 'Button URL', 'focused-schools' ), help: URL_HELP },
		{ name: 'limit', label: __( 'How many testimonials', 'focused-schools' ) }
	] );

	registerServerBlock( 'focused-schools/proof', __( 'Impact Figures', 'focused-schools' ), [
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ), help: __( 'The three figures come from Site Settings → Impact Stats, so this page and About cannot drift.', 'focused-schools' ) }
	] );

	registerServerBlock( 'focused-schools/contact', __( 'Contact Close', 'focused-schools' ), [
		{ name: 'eyebrow', label: __( 'Eyebrow', 'focused-schools' ) },
		{ name: 'heading', label: __( 'Heading', 'focused-schools' ) },
		{ name: 'body', label: __( 'Body', 'focused-schools' ), type: 'textarea' },
		{ name: 'ctaLabel', label: __( 'Button label', 'focused-schools' ) },
		{ name: 'ctaUrl', label: __( 'Button URL', 'focused-schools' ), help: URL_HELP }
	] );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.serverSideRender,
	window.wp.i18n
);
