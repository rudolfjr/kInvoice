	<div class="main-content col-md-8" role="main">
		<?php if( $posts_array ) : ?>
			<header class="page-header">
				<h1>
					<?php 
						_e( 'Products', 'kinvoice' );
					 ?>
				</h1>
			</header>

			<div class="container_k">
			<?php 
				foreach ($posts_array as $post) { 
					setup_postdata( $post );
			?>
						
					<div class="grid_3">
						<?php
						// if the post has a thumbnail and it´s not password protected
						// then display the thumbnail

						if (has_post_thumbnail() && ! post_password_required() ) { ?>
							<a href="<?php the_permalink();?>" rel="bookmark">
								<figure class="entry-thumbnail"><?php the_post_thumbnail();?></figure>
							</a>
						<?php }else{?>
							<a href="<?php the_permalink();?>" rel="bookmark">
								<figure class="entry-thumbnail"><img src="<?php echo plugins_url( 'assets/img/product.png', __FILE__ );?>" alt="<?php the_title(); ?>"></figure>
							</a>
						<?php
						}
						?>
						<p><?php the_title();?></p>
						<a href="<?php the_permalink(); ?>" class="kbtn"><?php _e( 'Product Information', 'kinvoice' ); ?></a>
					</div><!-- will show 3 products per line -->
			<?php } ?>
			</div><!-- container for products -->



		<?php else : ?>
			<?php _e( 'Nothing here...', 'kinvoice' ); ?>
		<?php endif; ?>
	</div><!-- end main content -->

