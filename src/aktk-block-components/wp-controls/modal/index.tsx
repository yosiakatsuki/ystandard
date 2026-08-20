/**
 * WordPress Dependencies
 */
import { Modal as WPModal } from '@wordpress/components';

/**
 * WordPressのModalを表示する.
 *
 * @param props Modalへ渡すprops.
 */
export default function Modal(props: React.ComponentProps<typeof WPModal>) {
	return <WPModal {...props} />;
}
