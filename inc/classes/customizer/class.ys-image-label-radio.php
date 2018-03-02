<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
if( class_exists( 'WP_Customize_Control' ) ) {
	class YS_Customize_Image_Label_Radio_Control extends WP_Customize_Control {
		public $type = 'image-label-radio';
		public function render_content() {
			$input_id = '_customize-input-' . $this->id;
			$description_id = '_customize-description-' . $this->id;
			$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
			$allowed_html = array(
												'img' => array(
																	'src' => array(),
																	'alt' => array(),
																	'width' => array(),
																	'height' => array(),
																)
											);
			if ( empty( $this->choices ) ) {
				return;
			}

			$name = '_customize-radio-' . $this->id;
			?>
			<div class="customizeer__image-label-radio">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description ; ?></span>
				<?php endif; ?>
				<div class="customizeer__image-label-radio-list">
					<?php foreach ( $this->choices as $value => $label ) : ?>
						<span class="customize-inside-control-row">
							<input
								id="<?php echo esc_attr( $input_id . '-radio-' . $value ); ?>"
								type="radio"
								<?php echo $describedby_attr; ?>
								value="<?php echo esc_attr( $value ); ?>"
								name="<?php echo esc_attr( $name ); ?>"
								<?php $this->link(); ?>
								<?php checked( $this->value(), $value ); ?>
								/>
								<?php /*
							<label for="<?php echo esc_attr( $input_id . '-radio-' . $value ); ?>"><?php echo esc_html( $label ); ?></label>
							*/ ?>
							<label for="<?php echo esc_attr( $input_id . '-radio-' . $value ); ?>"><?php echo wp_kses( $label, $allowed_html ); ?></label>
						</span>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}
	}
}