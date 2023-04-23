<?php

require get_template_directory(). '/inc/class-mytheme-widget-recent-posts.php';
require get_template_directory(). '/inc/class-mytheme-widget-subdcribe.php';

function mytheme_register_widget() {
	register_widget('Mytheme_Widget_Recent_Posts');
	register_widget('Mytheme_Widget_Sibscibe');
}

add_action('widgets_init', 'mytheme_register_widget');


function mytheme_setup()
{
	load_theme_textdomain('mytheme', get_template_directory() . '/lang');

    add_theme_support('title-tag');

    add_theme_support('custom-logo', array(
        'height' => 31,
        'width' => 134,
        'flex-height' =>true
    ));

    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(730, 446);
	add_image_size('mtheme-resent-post', 80, 80, true);

    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption'
    ));

    //https://developer.wordpress.org/themes/functionality/post-formats/
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'gallery'
    ));

    // add_theme_support('menus');
    register_nav_menu('primary', 'Primary menu');
}

add_action('after_setup_theme', 'mytheme_setup');


/**
 * Proper way to enqueue scripts and styles.
 */
function mytheme_scripts()
{

    wp_enqueue_style('boostrap', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('animate', get_template_directory_uri() . '/css/animate.min.css');
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
    wp_enqueue_style('style-css', get_stylesheet_uri());

    wp_enqueue_script('jquery');

    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js');
    wp_enqueue_script('css3-animate-it', get_template_directory_uri() . '/js/css3-animate-it.js');
    wp_enqueue_script('jquery-easing', get_template_directory_uri() . '/js/jquery.easing.min.js');
    wp_enqueue_script('agency', get_template_directory_uri() . '/js/agency.js');
}
add_action('wp_enqueue_scripts', 'mytheme_scripts');


// https://developer.wordpress.org/reference/functions/the_excerpt/
add_filter('excerpt_more', function ($more) {
    return '';
});


// https://misha.agency/wordpress/how-to-create-breadcrumbs.html
// https://gist.github.com/kaganvmz/b75125290275faff1e72556aff3791ce

function wptuts_the_breadcrumb(){
	global $post;
	if(!is_home()){
	   echo '<li> <a href="'.site_url().'"><i class="fa fa-home" aria-hidden="true"></i>'. esc_html__('Home', 'mytheme') .'</a></li> <li> / </li> ';
		if(is_single()) { // posts
		the_category(', ');
		echo " <li> / </li> ";
		echo '<li>';
			the_title();
		echo '</li>';
		}
		elseif (is_page()) { // pages
			if ($post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach ($breadcrumbs as $crumb) echo $crumb . '<li> / </li> ';
			}
			echo the_title();
		}
		elseif (is_category()) { // category
			global $wp_query;
			$obj_cat = $wp_query->get_queried_object();
			$current_cat = $obj_cat->term_id;
			$current_cat = get_category($current_cat);
			$parent_cat = get_category($current_cat->parent);
			if ($current_cat->parent != 0)
				echo(get_category_parents($parent_cat, TRUE, ' <li> / </li> '));
			single_cat_title();
		}
		elseif (is_search()) { // search pages
			echo 'Search results "' . get_search_query() . '"';
		}
		elseif (is_tag()) { // tags
			echo single_tag_title('', false);
		}
		elseif (is_day()) { // archive (days)
			echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> <li> / </li> ';
			echo '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></li> <li> / </li> ';
			echo get_the_time('d');
		}
		elseif (is_month()) { // archive (months)
			echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> <li> / </li>';
			echo get_the_time('F');
		}
		elseif (is_year()) { // archive (years)
			echo get_the_time('Y');
		}
		elseif (is_author()) { // authors
			global $author;
			$userdata = get_userdata($author);
			echo '<li>'. esc_html__('Posted', 'mytheme') . $userdata->display_name . '</li>';
		} elseif (is_404()) { // if page not found
			echo '<li>'. esc_html__('Error 404', 'mytheme') .'</li>';
		}
	 
		if (get_query_var('paged')) // number of page
			echo ' (' . get_query_var('paged').'- page)';
	 
	} else { // home
	   $pageNum=(get_query_var('paged')) ? get_query_var('paged') : 1;
	   if($pageNum>1)
	      echo '<li><a href="'.site_url().'"><i class="fa fa-home" aria-hidden="true"></i>'. esc_html__('Home', 'mytheme') .'</a></li> <li> / </li> <li> '.$pageNum.'- page</li>';
	   else
	      echo '<li><i class="fa fa-home" aria-hidden="true"></i>'. esc_html__('Home', 'mytheme') .'</li>';
	}
}


/*
* Pagination
* https://github.com/talentedaamer/Bootstrap-wordpress-pagination
*/

function mytheme_pagination( $args = array() ) {
    
    $defaults = array(
        'range'           => 4,
        'custom_query'    => FALSE,
        'previous_string' => esc_html__( 'Previous', 'mytheme' ),
        'next_string'     => esc_html__( 'Next', 'mytheme' ),
        'before_output'   => '<div class="next_page"><ul class="page-numbers">',
        'after_output'    => '</ul></div>'
    );
    
    $args = wp_parse_args( 
        $args, 
        apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
    );
    
    $args['range'] = (int) $args['range'] - 1;
    if ( !$args['custom_query'] )
        $args['custom_query'] = @$GLOBALS['wp_query'];
    $count = (int) $args['custom_query']->max_num_pages;
    $page  = intval( get_query_var( 'paged' ) );
    $ceil  = ceil( $args['range'] / 2 );
    
    if ( $count <= 1 )
        return FALSE;
    
    if ( !$page )
        $page = 1;
    
    if ( $count > $args['range'] ) {
        if ( $page <= $args['range'] ) {
            $min = 1;
            $max = $args['range'] + 1;
        } elseif ( $page >= ($count - $ceil) ) {
            $min = $count - $args['range'];
            $max = $count;
        } elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }
    
    $echo = '';
    $previous = intval($page) - 1;
    $previous = esc_attr( get_pagenum_link($previous) );
    
    // $firstpage = esc_attr( get_pagenum_link(1) );
    // if ( $firstpage && (1 != $page) )
    //     $echo .= '<li class="previous"><a href="' . $firstpage . '" class="page-numbers">' . __( 'First', 'mytheme' ) . '</a></li>';

    if ( $previous && (1 != $page) )
        $echo .= '<li><a href="' . $previous . '" class="page-numbers" title="' . esc_html__( 'previous', 'mytheme') . '">' . $args['previous_string'] . '</a></li>';
    
    if ( !empty($min) && !empty($max) ) {
        for( $i = $min; $i <= $max; $i++ ) {
            if ($page == $i) {
                $echo .= '<li class="active"><span class="page-numbers current">' . str_pad( (int)$i, 1, '0', STR_PAD_LEFT ) . '</span></li>';
            } else {
                $echo .= sprintf( '<li><a href="%s" class="page-numbers">%2d</a></li>', esc_attr( get_pagenum_link($i) ), $i );
            }
        }
    }
    
    $next = intval($page) + 1;
    $next = esc_attr( get_pagenum_link($next) );
    if ($next && ($count != $page) )
        $echo .= '<li><a href="' . $next . '" class="page-numbers" title="' . esc_html__( 'next', 'mytheme') . '">' . $args['next_string'] . '</a></li>';
    
    // $lastpage = esc_attr( get_pagenum_link($count) );
    // if ( $lastpage ) {
    //     $echo .= '<li class="next"><a href="' . $lastpage . '" class="page-numbers" >' . esc_html__( 'Last', 'mytheme' ) . '</a></li>';
    // }

    if ( isset($echo) )
        echo $args['before_output'] . $echo . $args['after_output'];
}

// https://codex.wordpress.org/Theme_Customization_API

function mytheme_customize_register( $wp_customize ) {

    //Header settings
    $wp_customize->add_setting('header_social', array(
        'default'   => esc_html__('Share Your Favorite Mobile Apps With Your Friends', 'mytheme'),
        'transport' => 'refresh',
    ));

    //Social links settings
    $wp_customize->add_setting('facebook_social', array(
        'default'   => __('URL Fasebook', 'mytheme'),
        'transport' => 'refresh',
    ));

    $wp_customize->add_setting('twitter_social', array(
        'default'   => __('URL twitter', 'mytheme'),
        'transport' => 'refresh',
    ));

    $wp_customize->add_setting('linkedin_social', array(
        'default'   => __('URL linkedin', 'mytheme'),
        'transport' => 'refresh',
    ));

    // Copyrite
    $wp_customize->add_setting('footer_copy', array(
        'default'   => __('Copyrite text', 'mytheme'),
        'transport' => 'refresh',
    ));


    //Add section
    $wp_customize->add_section('social_section', array(
        'title'      => __('Social settings', 'mytheme'),
        'priority'   => 30,
    ));

    $wp_customize->add_section('footer_setting', array(
        'title'      => __('Footer settings', 'mytheme'),
        'priority'   => 30,
    ));

    //Header control
    $wp_customize->add_control(
        'header_social',
        array(
            'label'      => __('Social header in footer', 'mytheme'),
            'section'    => 'social_section',
            'settings'   => 'header_social',
            'type'       => 'text',
    ));

    //Social links control
    $wp_customize->add_control(
        'facebook_social',
        array(
            'label'      => __('Facebook profile url', 'mytheme'),
            'section'    => 'social_section',
            'settings'   => 'facebook_social',
            'type'       => 'text',
    ));

    $wp_customize->add_control(
        'twitter_social',
        array(
            'label'      => __('Twitter profile url', 'mytheme'),
            'section'    => 'social_section',
            'settings'   => 'twitter_social',
            'type'       => 'text',
    ));

    $wp_customize->add_control(
        'linkedin_social',
        array(
            'label'      => __('Linkedin profile url', 'mytheme'),
            'section'    => 'social_section',
            'settings'   => 'linkedin_social',
            'type'       => 'text',
    ));

    // Copyrite
    $wp_customize->add_control(
        'footer_copy',
        array(
            'label'      => __('Footer settings', 'mytheme'),
            'section'    => 'footer_setting',
            'settings'   => 'footer_copy',
            'type'       => 'textarea',
    ));

 }
 add_action('customize_register', 'mytheme_customize_register');

 /**
 * Add a sidebar.
 */
function mytheme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__('Main Sidebar', 'mytheme'),
		'id'            => 'sidebar-1',
		'description'   => esc_html__('Widgets in this area will be shown on all posts and pages.', 'mytheme'),
		'before_widget' => '<div id="%1$s" class="sidebar_wrap %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="side_bar_heading"><h6>',
		'after_title'   => '</h6></div>',
	) );
}
add_action('widgets_init', 'mytheme_widgets_init' );


function mytheme_widget_categories($args){
	// $walker = new Walker_Categories_Mytheme();
	// $args = array_merge($args, array('walker' => $walker));

	// return $args;
    return array_merge($args, array('walker' => new Walker_Categories_Mytheme(),));
}
add_filter('widget_categories_args', 'mytheme_widget_categories');


class Walker_Categories_Mytheme extends Walker_Category{

    /**
	 * Starts the list before the elements are added.
	 *
	 * @since 2.1.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Used to append additional content. Passed by reference.
	 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
	 * @param array  $args   Optional. An array of arguments. Will only append content if style argument
	 *                       value is 'list'. See wp_list_categories(). Default empty array.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
        parent::start_lvl( $output, $depth, $args);
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 2.1.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Used to append additional content. Passed by reference.
	 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
	 * @param array  $args   Optional. An array of arguments. Will only append content if style argument
	 *                       value is 'list'. See wp_list_categories(). Default empty array.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
        parent::end_lvl( $output, $depth, $args);
	}

	/**
	 * Starts the element output.
	 *
	 * @since 2.1.0
	 * @since 5.9.0 Renamed `$category` to `$data_object` and `$id` to `$current_object_id`
	 *              to match parent class for PHP 8 named parameter support.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string  $output            Used to append additional content (passed by reference).
	 * @param WP_Term $data_object       Category data object.
	 * @param int     $depth             Optional. Depth of category in reference to parents. Default 0.
	 * @param array   $args              Optional. An array of arguments. See wp_list_categories().
	 *                                   Default empty array.
	 * @param int     $current_object_id Optional. ID of the current category. Default 0.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		// Restores the more descriptive, specific name for use within this method.
		$category = $data_object;

		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters( 'list_cats', esc_attr( $category->name ), $category );

		// Don't generate an element if the category name is empty.
		if ( '' === $cat_name ) {
			return;
		}

		$atts         = array();
		$atts['href'] = get_term_link( $category );

		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			/**
			 * Filters the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string  $description Category description.
			 * @param WP_Term $category    Category object.
			 */
			$atts['title'] = strip_tags( apply_filters( 'category_description', $category->description, $category ) );
		}

		/**
		 * Filters the HTML attributes applied to a category list item's anchor element.
		 *
		 * @since 5.2.0
		 *
		 * @param array   $atts {
		 *     The HTML attributes applied to the list item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $href  The href attribute.
		 *     @type string $title The title attribute.
		 * }
		 * @param WP_Term $category          Term data object.
		 * @param int     $depth             Depth of category, used for padding.
		 * @param array   $args              An array of arguments.
		 * @param int     $current_object_id ID of the current category.
		 */
		$atts = apply_filters( 'category_list_link_attributes', $atts, $category, $depth, $args, $current_object_id );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ('href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$link = sprintf(
			'<i class="fa fa-folder-open-o" aria-hidden="true"></i><a%s>%s</a>',
			$attributes,
			$cat_name
		);

		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
			$link .= ' ';

			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}

			$link .= '<a href="' . esc_url( get_term_feed_link( $category, $category->taxonomy, $args['feed_type'] ) ) . '"';

			if ( empty( $args['feed'] ) ) {
				/* translators: %s: Category name. */
				$alt = ' alt="' . sprintf( esc_html__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$alt   = ' alt="' . $args['feed'] . '"';
				$name  = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}

			$link .= '>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= $name;
			} else {
				$link .= "<img src='" . esc_url( $args['feed_image'] ) . "'$alt" . ' />';
			}

            
			$link .= '</a>';
            
			if ( empty( $args['feed_image'] ) ) {
                $link .= ')';
			}
		}
        
        if ( ! empty( $args['show_count'] ) ) {
            $link .= '<span>' . number_format_i18n( $category->count ) . '</span>';
        }

		if ( 'list' === $args['style'] ) {
			$output     .= "\t<li";
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);

			if ( ! empty( $args['current_category'] ) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms(
					array(
						'taxonomy'   => $category->taxonomy,
						'include'    => $args['current_category'],
						'hide_empty' => false,
					)
				);

				foreach ( $_current_terms as $_current_term ) {
					if ( $category->term_id == $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
						$link          = str_replace( '<a', '<a aria-current="page"', $link );
					} elseif ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
					while ( $_current_term->parent ) {
						if ( $category->term_id == $_current_term->parent ) {
							$css_classes[] = 'current-cat-ancestor';
							break;
						}
						$_current_term = get_term( $_current_term->parent, $category->taxonomy );
					}
				}
			}

			/**
			 * Filters the list of CSS classes to include with each category in the list.
			 *
			 * @since 4.2.0
			 *
			 * @see wp_list_categories()
			 *
			 * @param string[] $css_classes An array of CSS classes to be applied to each list item.
			 * @param WP_Term  $category    Category data object.
			 * @param int      $depth       Depth of page, used for padding.
			 * @param array    $args        An array of wp_list_categories() arguments.
			 */
			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );
			$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';

			$output .= $css_classes;
			$output .= ">$link\n";
		} elseif ( isset( $args['separator'] ) ) {
			$output .= "\t$link" . $args['separator'] . "\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 2.1.0
	 * @since 5.9.0 Renamed `$page` to `$data_object` to match parent class for PHP 8 named parameter support.
	 *
	 * @see Walker::end_el()
	 *
	 * @param string $output      Used to append additional content (passed by reference).
	 * @param object $data_object Category data object. Not used.
	 * @param int    $depth       Optional. Depth of category. Not used.
	 * @param array  $args        Optional. An array of arguments. Only uses 'list' for whether should
	 *                            append to output. See wp_list_categories(). Default empty array.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = array() ) {
        parent::end_el( $output, $data_object, $depth, $args );
	}
}

add_filter('widget_tag_cloud_args', 'mytheme_tag_cloud');

function mytheme_tag_cloud($args, $instance ) {

	$args['smallest'] = 14;
	$args['largest'] = 14;
	$args['unit'] = 'px';
	$args['format']   = 'list';

	// var_dump($instance);
	return $args;
}

function kana_wp_tag_cloud_filter($return, $args)
{
 return '<div class="tag-detail">'.$return.'</div>';
}

add_filter('wp_tag_cloud', 'kana_wp_tag_cloud_filter', 10, 2);

// function kana_wp_tag_cloud_filter2($return, $args)
// {
// 	var_dump($args);
	
//  return $args;
// }

// add_filter('wp_tag_cloud', 'kana_wp_tag_cloud_filter2', 10, 2);

