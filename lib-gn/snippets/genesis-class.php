<?php

class GenesisPost {
	// class référence
	// à dupliquer

	var $image;

	public function image_thumb($thumbnail) {
		$this->image = get_the_post_thumbnail( $post_id, $thumbnail );
	}

	public function content($thumbnail) {
		$this->image_thumb($thumbnail);
		?>
		<article <?php post_class(); ?> itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">
			<header class="entry-header">
				<h1 class="entry-title" itemprop="headline">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h1>
				<?php genesis_post_info(); ?>
			</header>
			<div class="entry-content" itemprop="text">
				<a href="<?php the_permalink(); ?>">
					<?php echo $this->image;?>
				</a>
				<p><?php the_excerpt(); ?></p>
			</div>
		</article>
	<?php }


	public function __construct($thumbnail = "thumbnail") {
		global $post;
		$this->content($thumbnail);
	}


}
