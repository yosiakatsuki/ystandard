/* @jsxRuntime classic */
/* @jsx createElement */
import {
	Button,
	TextControl,
	TextareaControl,
	ToggleControl,
} from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useDispatch, useSelect } from '@wordpress/data';
import { createElement, Fragment, useRef } from '@wordpress/element';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { ExternalPostSettingsPanels } from './post-settings-extensions';

const settings = window.ystandardPostMetaSettings || {};

const FieldControl = ( { field, value, onChange } ) => {
	if ( 'text' === field.control ) {
		return (
			<TextControl
				__next40pxDefaultSize
				__nextHasNoMarginBottom
				label={ field.label }
				help={ field.help }
				value={ value || '' }
				onChange={ onChange }
			/>
		);
	}

	if ( 'textarea' === field.control ) {
		return (
			<TextareaControl
				__nextHasNoMarginBottom
				label={ field.label }
				help={ field.help }
				value={ value || '' }
				onChange={ onChange }
			/>
		);
	}

	return (
		<ToggleControl
			__nextHasNoMarginBottom
			label={ field.label }
			help={ field.help }
			checked={ Boolean( value ) }
			onChange={ onChange }
		/>
	);
};

export const PostMetaPanels = ( {
	postType = settings.postType,
	panels = settings.panels || {},
	fields: metaFields = settings.fields || [],
} = {} ) => {
	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	if ( ! meta ) {
		return null;
	}

	return Object.entries( panels ).map( ( [ panelName, panelTitle ] ) => {
		const fields = metaFields.filter(
			( field ) => panelName === field.panel
		);
		if ( ! fields.length ) {
			return null;
		}

		return (
			<PluginDocumentSettingPanel
				key={ panelName }
				name={ `ystandard-${ panelName }` }
				className="ystandard-post-meta-panel"
				title={ panelTitle }
			>
				<div className="ystandard-post-meta-fields">
					{ fields.map( ( field ) => (
						<FieldControl
							key={ field.key }
							field={ field }
							value={ meta[ field.key ] }
							onChange={ ( value ) =>
								setMeta( {
									...meta,
									[ field.key ]: value,
								} )
							}
						/>
					) ) }
				</div>
			</PluginDocumentSettingPanel>
		);
	} );
};

const PartsShortcodePanel = () => {
	const shortcodeInput = useRef( null );
	const { postId, postStatus } = useSelect( ( select ) => {
		const editor = select( 'core/editor' );

		return {
			postId: editor.getCurrentPostId(),
			postStatus: editor.getEditedPostAttribute( 'status' ),
		};
	}, [] );
	const { createErrorNotice, createSuccessNotice } =
		useDispatch( 'core/notices' );

	if ( 'publish' !== postStatus || ! postId ) {
		return null;
	}

	const shortcode = `[ys_parts parts_id="${ postId }"]`;
	const copyShortcode = async () => {
		let copied = false;

		if ( navigator.clipboard ) {
			try {
				await navigator.clipboard.writeText( shortcode );
				copied = true;
			} catch {
				copied = false;
			}
		}

		if ( ! copied && shortcodeInput.current ) {
			try {
				// HTTP環境などClipboard APIを使えない場合のフォールバック.
				shortcodeInput.current.focus();
				shortcodeInput.current.select();
				shortcodeInput.current.setSelectionRange( 0, shortcode.length );
				copied = document.execCommand( 'copy' );
			} catch {
				copied = false;
			}
		}

		if ( copied ) {
			createSuccessNotice(
				__( 'ショートコードをコピーしました。', 'ystandard' ),
				{
					type: 'snackbar',
				}
			);
		} else {
			createErrorNotice(
				__( 'ショートコードをコピーできませんでした。', 'ystandard' )
			);
		}
	};

	return (
		<PluginDocumentSettingPanel
			name="ystandard-parts-shortcode"
			title={ __( '[ys] ショートコード', 'ystandard' ) }
		>
			<TextControl
				ref={ shortcodeInput }
				__next40pxDefaultSize
				label={ __( 'ショートコード', 'ystandard' ) }
				value={ shortcode }
				readOnly
				onFocus={ ( event ) => event.target.select() }
			/>
			<Button variant="secondary" onClick={ copyShortcode }>
				{ __( 'ショートコードをコピー', 'ystandard' ) }
			</Button>
		</PluginDocumentSettingPanel>
	);
};

export const PostMetaPlugin = () => {
	return (
		<Fragment>
			{ settings.partsPostType === settings.postType ? (
				<PartsShortcodePanel />
			) : (
				<PostMetaPanels />
			) }
			<ExternalPostSettingsPanels
				context={ settings.postSettingsContext }
			/>
		</Fragment>
	);
};

registerPlugin( 'ystandard-post-meta', {
	render: PostMetaPlugin,
} );
