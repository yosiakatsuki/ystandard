import {
	Button,
	ColorIndicator,
	ColorPalette,
	Dropdown,
	Popover,
	SlotFillProvider,
} from '@wordpress/components';
import { createRoot } from '@wordpress/element';

const ColorPaletteDropdown = ({
	descriptionId,
	enableAlpha,
	label,
	onChange,
	palette,
	value,
}) => (
	<SlotFillProvider>
		<Dropdown
			className="ys-color-palette-control__dropdown"
			contentClassName="ys-color-palette-control__popover"
			expandOnMobile
			headerTitle={label}
			popoverProps={{ placement: 'bottom-start' }}
			renderToggle={({ isOpen, onToggle }) => (
				<Button
					aria-describedby={descriptionId}
					aria-expanded={isOpen}
					className="ys-color-palette-control__toggle"
					onClick={onToggle}
				>
					<ColorIndicator
						className={`ys-color-palette-control__indicator${
							value ? '' : ' is-empty'
						}`}
						colorValue={value || 'transparent'}
					/>
					<span className="ys-color-palette-control__label">
						{label}
					</span>
				</Button>
			)}
			renderContent={() => (
				<ColorPalette
					clearable
					colors={palette}
					enableAlpha={enableAlpha}
					onChange={onChange}
					value={value}
				/>
			)}
		/>
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
						descriptionId={
							control.params.description
								? `_customize-description-${control.id}`
								: undefined
						}
						enableAlpha={Boolean(control.params.enableAlpha)}
						label={control.params.label}
						onChange={(color) => control.setting.set(color || '')}
						palette={control.params.palette || []}
						value={control.setting()}
					/>
				);
			};

			control.setting.bind(control.renderColorPalette);
			control.renderColorPalette();
		},
	});
