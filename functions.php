<?php
/**
 * Theme functions file
 *
 * @package Louie
 * 
 */

define( 'PROLOGUE_VERSION', '1.0.0' );
define( 'PROLOGUE_THEME_URL', get_bloginfo('template_directory') );


/**
 * 加载脚本样式
 */
function Prologue_scripts() {
	wp_enqueue_style( 'index', get_stylesheet_uri(), array(), PROLOGUE_VERSION );
	wp_enqueue_script( 'jq', PROLOGUE_THEME_URL . '/assets/js/jquery.min.js', array(), PROLOGUE_VERSION, true );
	wp_enqueue_script( 'main', PROLOGUE_THEME_URL . '/assets/js/main.js', array(), PROLOGUE_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'Prologue_scripts' );


/**
 * 特色图
*/
add_theme_support( 'post-thumbnails' );


/**
 * 开启友链
*/
add_filter( 'pre_option_link_manager_enabled', '__return_true' );


/**
 * 导航
*/
register_nav_menus(array(
	'nav' => esc_html__( '导航菜单', 'Prologue' ),
));


/**
 * 头像源
*/
function gravatar_cn( $url ){ 
	$gravatar_url = array('0.gravatar.com','1.gravatar.com','2.gravatar.com');
	return str_replace( $gravatar_url, 'cn.gravatar.com', $url );
}
add_filter( 'get_avatar_url', 'gravatar_cn', 4 );


/**
 * 去除自带表情
*/
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	//add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * 去除无用元素
*/
function unregister_default_widgets() {
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Links');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Search');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Nav_Menu_Widget');
}
add_action('widgets_init', 'unregister_default_widgets', 11);
remove_action('wp_head','wp_generator');
remove_action('wp_head','wlwmanifest_link');
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'parent_post_rel_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

/**
 * 订制时间样式
 * get_time_since(strtotime($post->post_date_gmt));
 * get_time_since(strtotime($comment->comment_date_gmt), true );
*/
function get_time_since( $older_date, $comment_date = false, $text = false ) {
	$chunks = array(
		array( 24 * 60 * 60, __( ' 天前', 'Prologue' ) ),
		array( 60 * 60, __( ' 小时前', 'Prologue' ) ),
		array( 60, __( ' 分钟前', 'Prologue' ) ),
		array( 1, __( ' 秒前', 'Prologue' ) )
	);

	$newer_date = time();
	$since = abs( $newer_date - $older_date );
	if($text){
		$output = '';
	}else{
		$output = '发布于 ';
	}

	if ( $since < 30 * 24 * 60 * 60 ) {
		for ( $i = 0, $j = count( $chunks ); $i < $j; $i ++ ) {
			$seconds = $chunks[ $i ][0];
			$name    = $chunks[ $i ][1];
			if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
				break;
			}
		}
		$output .= $count . $name;
	} else {
		$output .= $comment_date ? date( 'Y-m-d H:i', $older_date ) : date( 'Y-m-d', $older_date );
	}

	return $output;
}


/**
 * 文章浏览数
*/
function set_post_views() {
    global $post;
    $post_id = intval($post->ID);
    $count_key = 'views';
    $views = get_post_custom($post_id);
    $views = intval($views['views'][0]);
    if(is_single() || is_page()) {
        if(!update_post_meta($post_id, 'views', ($views + 1))) {
            add_post_meta($post_id, 'views', 1, true);
        }
    }
}
add_action('get_header', 'set_post_views');

function get_post_views($post_id) {
    $count_key = 'views';
    $views = get_post_custom($post_id);
    $views = intval($views['views'][0]);
    $post_views = intval(post_custom('views'));
    if($views == '') {
        return 0;
    }else{
        return $views;
    }
}


/**
 * 文章缩略图
 * thumbnail (缩略图尺寸)
 * medium （中等尺寸）
 * large （大尺寸）
 * full （原始尺寸）
*/
function get_post_thumb($id, $size = 'large') {
	$get = wp_get_attachment_image_src(get_post_thumbnail_id($id), $size);
	if ($get[0]) {
		$image_url = $get[0];
	} else {
		$image_url = get_template_directory_uri() . '/images/post.jpg';
	}
	return $image_url;
}


/*
 * 友链
*/

function get_link_items() {
	$bookmarks = get_bookmarks('orderby=date&category=' .$id );
  	$output = '';
  	if ( !empty($bookmarks) ) {
		$output .= '<ul class="link-items">';
		foreach ($bookmarks as $bookmark) {
			$output .=  '<li class="link-item"><a class="button" href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" >'. $bookmark->link_name .'</a></li>';
		}
		$output .= '</ul>';
  	}
  	return $output;
}


/*
 * 类
*/
function posts_class() {
    if (is_home()) {
        $class = 'post-list';
    }elseif (is_single() || is_page()) {
        $class = 'post-content post-single';
    }elseif (is_page()) {
        $class = 'post-content post-page';
    }

    echo $class;
}


/*
 * 文章列表分页
*/
function get_next_posts_url( $label = null, $max_page = 0 ) {
    global $paged, $wp_query;
 
    if ( !$max_page )
        $max_page = $wp_query->max_num_pages;
 
    if ( !$paged )
        $paged = 1;
 
    $nextpage = intval($paged) + 1;
 
    if ( null === $label )
        $label = __( 'Next Page &raquo;' );
 
    if ( !is_single() && ( $nextpage <= $max_page ) ) {
        /**
         * Filters the anchor tag attributes for the next posts page link.
         *
         * @since 2.7.0
         *
         * @param string $attributes Attributes for the anchor tag.
         */
        $attr = apply_filters( 'next_posts_link_attributes', '' );
 
        return '<div id="pagination"><a class="button big" href="' . next_posts( $max_page, false ) . "\" $attr>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a></div>';
    }
}


/*
 * 头部
*/
function get_pages_header() {
    $before = '<div class="inner">';
    $after = '</div>';
    $bg = '<div class="post-bg" style="background-image: url('.get_post_thumb(get_the_ID()).');"></div>';
    if (is_home()) {
        $title = '<a href="'.get_bloginfo('url').'"><h1>'.get_bloginfo('name').'</h1></a>';
        $des = '<p>'.get_bloginfo('description').'</p>';
        $bg = '<video id="bg-video" autoplay="autoplay" loop="loop" muted="muted"><source src="'.get_bloginfo('template_url').'/assets/lights.mp4" type="video/mp4"></video>';
    }elseif (is_single()) {
        $title = '<h1>'.get_the_title().'</h1>';
        $des = '<p>'.get_time_since(get_post_time('U', true)).' / '.get_post_views(get_the_ID()).' 次阅读</p>';
    }elseif (is_page()) {
        $title = '<h1>'.get_the_title().'</h1>';
        $des = '';
    }elseif (is_search()) {
        $title = '<h1>关于“'.get_search_query().'”的搜索结果</h1>';
        $bg = '<video id="bg-video" autoplay="autoplay" loop="loop" muted="muted"><source src="'.get_bloginfo('template_url').'/assets/lights.mp4" type="video/mp4"></video>';
    }

    echo $before.$title.$des.$after.$bg;
}