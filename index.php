<?php get_header();?>

<?php get_template_part('template-parts/breadcrumbs'); ?>

<section class="post_blog_bg primary-bg">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<div class="col-md-8">
					<?php if (have_posts()):?>
						<?php while (have_posts()): the_post();
							get_template_part('template-parts/content', get_post_format());
						endwhile;?>
					<?php endif;?>

					<!-- Pagination -->
					<?php mytheme_pagination();?>

				</div>

				<!-- Sidebar  -->
				<?php get_sidebar(); ?>

			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
