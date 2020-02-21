<?php
/**
 * カスタマイザーコントロール : 画像選択ラジオボタン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * カスタマイザーコントロール : 画像選択ラジオボタン
	 */
	class YS_Customize_Section_Label_Control extends WP_Customize_Control {
		/**
		 * Type
		 *
		 * @var string
		 */
		public $type = 'section-label';

		/**
		 * カスタマイザー出力
		 */
		public function render_content() {
			$input_id         = '_customize-input-' . $this->id;
			$description_id   = '_customize-description-' . $this->id;
			$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
			if ( empty( $this->label ) ) {
				return;
			}
			?>
			<div class="customize-control__section_label">
				<?php if ( ! empty( $this->label ) ) : ?>
					<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>
				<input
					id="<?php echo esc_attr( $input_id ); ?>"
					type="<?php echo esc_attr( $this->type ); ?>"
					<?php echo $describedby_attr; ?>
					<?php $this->input_attrs(); ?>
					<?php if ( ! isset( $this->input_attrs['value'] ) ) : ?>
						value="<?php echo esc_attr( $this->value() ); ?>"
					<?php endif; ?>
					<?php $this->link(); ?>
				/>
			</div>
			<?php
		}
	}
}
