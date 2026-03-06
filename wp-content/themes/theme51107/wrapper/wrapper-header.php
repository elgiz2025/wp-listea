<?php /* Wrapper Name: Header */ ?>
<div class="row">
	<div class="span12 full-top-shop" data-motopress-type="static" data-motopress-static-file="static/static-shop-nav.php">
		<?php dynamic_sidebar( 'cart-holder' ); ?>
		<?php get_template_part("static/static-shop-nav"); ?>
	</div>
</div>

<div class="row">
	<div class="span12 full-top-nav">
		<div class="row">
			<div class="span3" data-motopress-type="static" data-motopress-static-file="static/static-logo.php">
				<?php get_template_part("static/static-logo"); ?>
			</div>
			<div class="span6" data-motopress-type="static" data-motopress-static-file="static/static-nav.php">
				<?php get_template_part("static/static-nav"); ?>
			</div>
			<div class="span3" data-motopress-type="static" data-motopress-static-file="static/static-search.php">
				<?php get_template_part("static/static-search"); ?>
			</div>
		</div>
	</div>
</div>