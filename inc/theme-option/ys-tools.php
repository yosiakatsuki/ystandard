<?php
	if ( ! current_user_can( 'manage_options' ) )
		{
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
?>

<div class="wrap">
<h2>便利ツール</h2>
<div id="poststuff">

	<div class="postbox">
		<h2>yStandard情報</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">バージョン情報</th>
					<td>
						<div>
							<span style="display:inline-block;width:70px;">yStandard</span>：<?php echo ys_util_get_theme_version( true ); ?>
						</div>
						<?php if( get_template() != get_stylesheet() ): ?>
							<div>
								<span style="display:inline-block;width:70px;">子テーマ</span>：<?php echo ys_util_get_theme_version(); ?>
							</div>
						<?php endif; ?>
					</td>
				</tr>
			</table>
		</div>
	</div>

</div><!-- /#poststuff -->
</div><!-- /.warp -->