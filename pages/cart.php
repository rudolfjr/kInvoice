
	<div class="main-content col-md-8" role="main">
		<?php if( $products ) : ?>
			<header class="page-header">
				<h1>
					<?php 
						_e( 'Cart', 'kinvoice' );
					 ?>
				</h1>
			</header>

			<?php 
				foreach ($products as $post) { 
					setup_postdata( $post );
			?>
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
							<p>QTD: <?php echo $post->qtd; ?></p>

							<form action="#" method="post">
								<input type="hidden" name="product" value="<?php echo $post->ID_ITEM; ?>">
								<input type="hidden" name="cart" value="<?php echo $post->cart; ?>">
								<input type="hidden" name="type" value="delete">
								<input type="submit" value="<?php _e( 'Remove item', 'kinvoice' ); ?>">
							</form>

					</header><!-- end entry-header -->

				</article>

			<?php } ?>

				<article>
					<form action="#" method="post">
						<input type="hidden" name="type" value="ok">
						<input type="hidden" name="invoice" value="<?php echo $current_invoice[0]->ID; ?>">

						<label><?php _e( 'Your name', 'kinvoice' ); ?></label>
						<input type="text" name="name">

						<label><?php _e( 'Your telephone', 'kinvoice' ); ?></label>
						<input type="text" name="phone">

						<label><?php _e( 'Your e-mail', 'kinvoice' ); ?></label>
						<input type="text" name="email">

						<label><?php _e( 'Observation', 'kinvoice' ); ?></label>
						<textarea  name="obs"></textarea>

						<input type="submit" value="<?php _e( 'Send Invoice', 'kinvoice' ); ?>">
					</form>
				</article>



		<?php else : ?>
			<?php _e( 'Nothing here...', 'kinvoice' ); ?>
		<?php endif; ?>
	</div><!-- end main content -->

