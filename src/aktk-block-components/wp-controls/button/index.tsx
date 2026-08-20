import { Button as WPButton } from '@wordpress/components';

interface ButtonProps {
	children: React.ReactNode;
	className?: string;
	variant?: 'primary' | 'secondary' | 'tertiary' | 'link';
	size?: 'default' | 'compact' | 'small';
	style?: React.CSSProperties;
	isDestructive?: boolean;
	onClick: () => void;
	disabled?: boolean;
	isBusy?: boolean;
	label?: string;
}

/**
 * WordPressのButtonを共通設定付きで表示する.
 *
 * @param props Buttonへ渡すprops.
 */
export default function Button(props: ButtonProps): JSX.Element {
	// WordPressの次期標準サイズを先行して使用する.
	// @ts-ignore.
	return <WPButton {...props} __next40pxDefaultSize />;
}
