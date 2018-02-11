<?php
if ( ! ys_is_amp() && ( comments_open() || get_comments_number() ) ) : ?>
	<?php comments_template(); ?>
<?php endif; ?>