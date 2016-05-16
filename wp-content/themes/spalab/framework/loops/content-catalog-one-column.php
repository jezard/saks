<?php
	$item_meta = get_post_meta($post->ID, '_catalog_settings', true);
	$subtitle = isset( $item_meta['subtitle']) ? $item_meta['subtitle'] : NULL;
	$price = isset( $item_meta['price']) ? $item_meta['price'] : NULL;
	$link = isset( $item_meta['link']) ? $item_meta['link'] : NULL; ?>
    
	<article class="menu-list">
		<div class="menu-thumb">
			<span class="rounded">
				<?php if(has_post_thumbnail()):                                     
                        the_post_thumbnail("full");
                      else: ?>
                         <img src="http://placehold.it/600x600&text=Image" alt="<?php printf(esc_attr__('%s'),the_title_attribute('echo=0'));?>" title="<?php printf(esc_attr__('%s'),the_title_attribute('echo=0'));?>" />
                <?php endif; ?>
			</span>    
		</div>
		<div class="menu-details">
			<div class="menu-title">
				<h3><?php the_title(); ?></h3>
				<?php if(!empty($subtitle)): ?><span><?php echo esc_html($subtitle); ?></span><?php endif; ?>
				<?php if(!empty($price)): ?><a class="price dt-sc-button small" href="<?php echo esc_attr( $link ); ?>"><?php echo esc_html($price); ?></a><?php endif; ?>
			</div>
			<?php the_content(); ?>
		</div>
	</article>