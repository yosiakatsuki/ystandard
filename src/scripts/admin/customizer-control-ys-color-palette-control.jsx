import { __experimentalColorGradientControl as ColorGradientControl } from '@wordpress/block-editor';
import {
	Button,
	ColorIndicator,
	Dropdown,
	FlexItem,
	Popover,
	SlotFillProvider,
	__experimentalDropdownContentWrapper as DropdownContentWrapper,
	__experimentalHStack as HStack,
} from '@wordpress/components';
import { createRoot } from '@wordpress/element';
import { _x } from '@wordpress/i18n';

const PALETTE_LABELS = {
	theme: _x('Theme', 'Indicates this palette comes from the theme.'),
	default: _x('Default', 'Indicates this palette comes from WordPress.'),
	custom: _x('Custom', 'Indicates this palette is created by the user.'),
};

const translatePaletteLabels = (palette) =>
	palette.map((group) => ({
		...group,
		name: PALETTE_LABELS[group.slug] || group.name,
	}));

const LabeledColorIndicator = ({ colorValue, label }) => (
	<HStack justify="flex-start">
		<ColorIndicator
			className="block-editor-panel-color-gradient-settings__color-indicator"
			colorValue={colorValue}
		/>
		<FlexItem
			className="block-editor-panel-color-gradient-settings__color-name"
			title={label}
		>
			{label}
		</FlexItem>
	</HStack>
);

const ColorPaletteToggle = ({
	buttonLabel,
	descriptionId,
	isOpen,
	onToggle,
	settingLabelId,
	value,
}) => (
	<Button
		aria-describedby={descriptionId}
		aria-expanded={isOpen}
		aria-labelledby={settingLabelId}
		className={`block-editor-panel-color-gradient-settings__dropdown${
			isOpen ? ' is-open' : ''
		} ys-color-palette-control__toggle`}
		onClick={onToggle}
	>
		<LabeledColorIndicator colorValue={value} label={buttonLabel} />
	</Button>
);

const ColorPaletteDropdown = ({
	buttonLabel,
	descriptionId,
	enableAlpha,
	label,
	onChange,
	palette,
	settingLabelId,
	value,
}) => (
	<SlotFillProvider>
		<div className="ys-color-palette-control__item">
			<Dropdown
				className="block-editor-tools-panel-color-gradient-settings__dropdown ys-color-palette-control__dropdown"
				popoverProps={{ placement: 'bottom-start', shift: true }}
				renderToggle={({ isOpen, onToggle }) => (
					<ColorPaletteToggle
						buttonLabel={buttonLabel}
						descriptionId={descriptionId}
						isOpen={isOpen}
						onToggle={onToggle}
						settingLabelId={settingLabelId}
						value={value}
					/>
				)}
				renderContent={() => (
					<DropdownContentWrapper paddingSize="none">
						<div className="block-editor-panel-color-gradient-settings__dropdown-content">
							<ColorGradientControl
								clearable
								colorValue={value}
								colors={translatePaletteLabels(palette)}
								disableCustomColors={false}
								disableCustomGradients
								enableAlpha={enableAlpha}
								gradients={[]}
								label={label}
								onColorChange={onChange}
								showTitle={false}
							/>
						</div>
					</DropdownContentWrapper>
				)}
			/>
		</div>
		<Popover.Slot />
	</SlotFillProvider>
);

wp.customize.controlConstructor['ys-color-palette-control'] =
	wp.customize.Control.extend({
		ready: function () {
			const control = this;
			const mount = control.container
				.get(0)
				.querySelector('.ys-color-palette-control__mount');

			if (!mount) {
				return;
			}

			control.colorPaletteRoot = createRoot(mount);
			control.renderColorPalette = function () {
				control.colorPaletteRoot.render(
					<ColorPaletteDropdown
						buttonLabel={control.params.label}
						descriptionId={
							control.params.description
								? `_customize-description-${control.id}`
								: undefined
						}
						enableAlpha={Boolean(control.params.enableAlpha)}
						label={control.params.label}
						onChange={(color) => control.setting.set(color || '')}
						palette={control.params.palette || []}
						settingLabelId={
							control.params.label
								? `_customize-label-${control.id}`
								: undefined
						}
						value={control.setting()}
					/>
				);
			};

			control.setting.bind(control.renderColorPalette);
			control.renderColorPalette();
		},
	});
