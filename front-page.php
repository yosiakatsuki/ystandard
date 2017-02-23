<?php
$show_on_front = get_option('show_on_front');

if('page' == $show_on_front){
	get_template_part( 'page', '' );
} else {
	get_template_part( 'home', '' );
}

?>