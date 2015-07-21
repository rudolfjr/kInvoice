<?php get_header(); ?>
	<div class="main-content" role="main">
		
			<header class="page-header">
				<h1>
					<?php 
						_e( 'Products', 'kinvoice' );
					 ?>
				</h1>
			</header>

		<?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts()) : $wp_query->the_post();  ?>

					<div class="container_k">
						<div class="grid_3">
							<?php
							// if the post has a thumbnail and it´s not password protected
							// then display the thumbnail

							if (has_post_thumbnail() && ! post_password_required() ) { ?>
								<figure class="entry-thumbnail"><?php the_post_thumbnail();?></figure>
							<?php }else{?>
								<figure class="entry-thumbnail"><img src="<?php echo plugins_url( 'assets/img/product.png', __FILE__ );?>" alt="<?php the_title(); ?>"></figure>
							<?php
							}
							?>
						</div><!-- 3 column -->

						<div class="grid_9">
							<h1><?php the_title();?></h1>
							<?php the_content(); ?>
							<form action="#" method="post">
								<input type="hidden" name="id" value="<?php the_ID();?>">
								<input type="hidden" name="type" value="save">
								<label><?php _e( 'Quantity', 'kinvoice' ); ?></label>
								<input type="number" value="1" name="qtd">
								<input type="submit" value="<?php _e( 'Add to Invoice', 'kinvoice' ); ?>">
							</form>
						</div><!-- 9 column -->

						<div class="clearfix"></div>
					</div><!-- container -->

		<?php endwhile;  else: {   ?>
			<?php _e( 'Nothing here...', 'kinvoice' ); ?>
		<?php } endif; ?>
	</div><!-- end main content -->

<?php get_footer(); ?>