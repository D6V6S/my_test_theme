<article id="post-<?php the_ID(); ?>" <?php post_class('blog_post'); ?>>
    <h4> <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a> </h4>

    <div class="blog_category">
        <ul>
            <li> <?php the_category(', ')?></li>
        </ul>
    </div>

    <div class="blog_text">
        <ul>
            <li> | </li>
            <li> <?php esc_html_e('Post By :', 'mytheme');?> <?php the_author_posts_link()?> </li>
            <li> | </li>
            <li> <?php esc_html_e('On :', 'mytheme');?> <?php the_time('j F Y')?> </li>
        </ul>
    </div>

    <div class="blog_post_img">
        <a href="<?php the_permalink(); ?>"> <?php the_post_thumbnail()?> </a>
    </div>

    <?php the_excerpt()?>
    
    <a href="<?php the_permalink(); ?>"> <?php esc_html_e('Continue reading', 'mytheme');?>
        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
    </a>
</article>