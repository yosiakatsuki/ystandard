<?php
//------------------------------------------------------------------------------
//
//	パンくずリスト
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	パンくずリスト出力
//-----------------------------------------------
if(!function_exists( 'ys_breadcrumb')) {
	function ys_breadcrumb(){

		if(!have_posts() || (!is_single() && !is_category()) ){
			return;
		}

		// カテゴリー取得
		$catlist = ys_utilities_get_cat_id_list();
		?>
		<nav id="breadcrumb" class="breadcrumb wrap">
			<ol itemscope itemtype="http://schema.org/BreadcrumbList">
				<li itemprop="itemListElement" itemscope
			itemtype="http://schema.org/ListItem">
					<a itemprop="item" href="<?php echo home_url(); ?>"><span itemprop="name"><?php ys_breadcrumb_get_the_home_link_text() ?></span><meta itemprop="position" content="1" /></a></li>
		<?php
			$breadcrumbpos = 2;
			if($catlist !== null):
				foreach($catlist as $catid):
		?>
				<li itemprop="itemListElement" itemscope
				itemtype="http://schema.org/ListItem">
					<a itemprop="item" href="<?php echo get_category_link($catid); ?>"><span itemprop="name"><?php echo get_cat_name($catid); ?></span><meta itemprop="position" content="<?php echo $breadcrumbpos; ?>" /></a></li>
		<?php
					$breadcrumbpos += 1;
				endforeach;
			endif;
		?>
			</ol>
		</nav>
		<?php
	}
}




//-----------------------------------------------
//	パンくずリストのTOPページのリンクテキスト
//-----------------------------------------------
if(!function_exists( 'ys_breadcrumb_get_the_home_link_text')) {
	function ys_breadcrumb_get_the_home_link_text() {
		echo get_bloginfo('name');
		//echo 'ホーム';
	}
}

?>