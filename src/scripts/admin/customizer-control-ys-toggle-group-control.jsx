import {
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import { createRoot } from '@wordpress/element';

const ToggleGroup = ({ choices, description, label, onChange, value }) => (
	<ToggleGroupControl
		__next40pxDefaultSize
		__nextHasNoMarginBottom
		help={description || undefined}
		isBlock
		label={label}
		onChange={onChange}
		value={value}
	>
		{Object.entries(choices).map(([optionValue, optionLabel]) => (
			<ToggleGroupControlOption
				key={optionValue}
				label={optionLabel}
				value={optionValue}
			/>
		))}
	</ToggleGroupControl>
);

wp.customize.controlConstructor['ys-toggle-group-control'] =
	wp.customize.Control.extend({
		ready: function () {
			const control = this;
			const mount = control.container
				.get(0)
				.querySelector('.ys-toggle-group-control__mount');

			// マウント先がない場合は標準コントロールの初期化を中断する.
			if (!mount) {
				return;
			}

			control.toggleGroupRoot = createRoot(mount);
			control.renderToggleGroup = function () {
				control.toggleGroupRoot.render(
					<ToggleGroup
						choices={control.params.choices || {}}
						description={control.params.description}
						label={control.params.label}
						onChange={(nextValue) =>
							control.setting.set(nextValue || '')
						}
						value={control.setting()}
					/>
				);
			};

			control.setting.bind(control.renderToggleGroup);
			control.renderToggleGroup();
		},
	});
