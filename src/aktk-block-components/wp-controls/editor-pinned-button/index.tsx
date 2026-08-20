/**
 * WordPress Dependencies
 */
import { Fill } from '@wordpress/components';

/**
 * Aktk Dependencies
 */
import Button from '@aktk/block-components/wp-controls/button';

interface EditorPinnedButtonProps {
	label: string;
	icon: React.ReactNode;
	onClick: () => void;
}

/**
 * エディターヘッダーの固定領域にボタンを表示する.
 *
 * @param props 固定ボタンに渡すprops.
 */
export default function EditorPinnedButton(
	props: EditorPinnedButtonProps
): React.ReactElement {
	const { label, icon, onClick } = props;

	return (
		<Fill name="PinnedItems/core">
			<Button label={label} size="compact" onClick={onClick}>
				{icon}
			</Button>
		</Fill>
	);
}
