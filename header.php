<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="applicable-device" content="pc,mobile">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title><?php bloginfo('name'); ?><?php wp_title( '-', true, 'left' ); ?></title>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri();?>/images/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header id="header" class="alt">
        <?php if (!is_home()) { ?>
        <div class="gohome">
            <a href="<?php bloginfo('url'); ?>">←</a>
        </div>
        <?php } ?>
        <?php get_pages_header(); ?>
        <?php if (is_home() || is_search()) { ?>
        <div class="homeNav">
            <nav><?php wp_nav_menu( array( 'theme_location' => 'nav','menu_class'=>'subnav-ul menu-list','container'=>'ul')); ?>
            <form role="search" method="get" id="search-form" action="<?php echo home_url( '/' ); ?>">
                <div>
                    <input type="text" value="Search" name="s" id="s" onblur="if ( this.value == '' ){this.value='Search';}" onfocus = "if ( this.value == 'Search' ){this.value = '';}" />
                </div>
            </form>
            </nav>
        </div>
        <?php } ?>
    </header><!-- #header -->
    <div id="wrapper">
        <main id="primary" class="main items <?php posts_class(); ?>" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">