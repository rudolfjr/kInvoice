<?php get_header(); ?>
	<div class="main-content" role="main">
		<?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts()) : $wp_query->the_post();  ?>
			<header class="page-header">
				<h1>
					<?php 
						_e( 'Products', 'kinvoice' );
					 ?>
				</h1>
			</header>


				<article id="post-<?php the_ID();?>" <?php post_class();?> >
					<!-- Article header -->
					<header class="entry-header">
						<?php
						// if the post has a thumbnail and it´s not password protected
						// then display the thumbnail

						if (has_post_thumbnail() && ! post_password_required() ) { ?>
							<a href="<?php the_permalink();?>" rel="bookmark"><figure class="entry-thumbnail"><?php the_post_thumbnail();?></figure></a>
						<?php }else{?>
							<a href="<?php the_permalink();?>" rel="bookmark"><figure class="entry-thumbnail"><img src="<?php echo plugins_url( 'assets/img/product.png', __FILE__ );?>" alt="<?php the_title(); ?>"></figure></a>
						<?php
						}
						?>
							<p><?php the_title();?></p>
							<?php the_content(); ?>
							<form action="#" method="post">
								<input type="hidden" name="id" value="<?php the_ID();?>">
								<input type="hidden" name="type" value="save">
								<label><?php _e( 'Quantity', 'kinvoice' ); ?></label>
								<input type="number" value="1" name="qtd">
								<input type="submit" value="<?php _e( 'Add to Invoice', 'kinvoice' ); ?>">
							</form>

					</header><!-- end entry-header -->

				</article>

		<?php endwhile;  else: {   ?>
			<?php _e( 'Nothing here...', 'kinvoice' ); ?>
		<?php } endif; ?>
	</div><!-- end main content -->

<?php get_footer(); ?>