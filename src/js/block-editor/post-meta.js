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
import { createElement } from '@wordpress/element';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';

const settings = window.ystandardPostMetaSettings || {};

const FieldControl = ( { field, value, onChange } ) => {
	if ( 'text' === field.control ) {
		return (
			<TextControl
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
				label={ field.label }
				help={ field.help }
				value={ value || '' }
				onChange={ onChange }
			/>
		);
	}

	return (
		<ToggleControl
			label={ field.label }
			help={ field.help }
			checked={ Boolean( value ) }
			onChange={ onChange }
		/>
	);
};

const PostMetaPanels = () => {
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		settings.postType,
		'meta'
	);

	if ( ! meta ) {
		return null;
	}

	return Object.entries( settings.panels || {} ).map(
		( [ panelName, panelTitle ] ) => {
			const fields = ( settings.fields || [] ).filter(
				( field ) => panelName === field.panel
			);
			if ( ! fields.length ) {
				return null;
			}

			return (
				<PluginDocumentSettingPanel
					key={ panelName }
					name={ `ystandard-${ panelName }` }
					title={ panelTitle }
				>
					{ fields.map( ( field ) => (
						<FieldControl
							key={ field.key }
							field={ field }
							value={ meta[ field.key ] }
							onChange={ ( value ) =>
								setMeta( { ...meta, [ field.key ]: value } )
							}
						/>
					) ) }
				</PluginDocumentSettingPanel>
			);
		}
	);
};

const PartsShortcodePanel = () => {
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
		if ( ! navigator.clipboard ) {
			createErrorNotice(
				__( 'ショートコードをコピーできませんでした。', 'ystandard' )
			);
			return;
		}

		try {
			await navigator.clipboard.writeText( shortcode );
			createSuccessNotice(
				__( 'ショートコードをコピーしました。', 'ystandard' ),
				{
					type: 'snackbar',
				}
			);
		} catch {
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

const PostMetaPlugin = () => {
	if ( settings.partsPostType === settings.postType ) {
		return <PartsShortcodePanel />;
	}

	return <PostMetaPanels />;
};

registerPlugin( 'ystandard-post-meta', {
	render: PostMetaPlugin,
} );
