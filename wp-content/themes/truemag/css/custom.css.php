<?php

//header('Content-type: text/css');

//require '../../../../wp-load.php';

/* Get Theme Options here and echo custom CSS */
// 
// for example: 
// $topmenu_visible = ot_get_option( 'topmenu_visible', 1);

//color
if($content_color = ot_get_option('content_color')){ ?>
	body,
    .video-item .item-content, .item-head h3, .item-head h3 a,
    .comment-content p, .comment-meta cite, .comment-meta cite a,
    .cactus-sub-wrap .entry-title a{
    	color: <?php echo $content_color ?>;
    }
<?php }
if($main_color_1 = ot_get_option('main_color_1')){ ?>
    .dark-div a.bgcolor1hover:hover{color: #000;}
    .dark-div button:hover,.dark-div input[type='submit']:hover,
    .dark-div .navbar-toggle:hover,
    .dark-div .topnav-light .navbar-toggle:hover,
    .player-button .next-post.same-cat a:hover,
    .player-button .prev-post.same-cat a:hover,
    .box-more.single-full-pl:hover,
    .video-series-list .btn:hover, .video-series-list .btn.series-current{
        border-color:<?php echo $main_color_1 ?>;
        background:<?php echo $main_color_1 ?>;
    }
    #slider .video_slider  .carousel-pagination a.selected,
    #slider .video_slider .ct-btn{background:<?php echo $main_color_1 ?>;}
    #slider .video_slider .carousel-pagination a:hover,
    #slider .video_slider .carousel-pagination a.selected{border-color:<?php echo $main_color_1 ?>;}
    #bottom .widget.PI_SimpleTwitterTweets ul li:hover:before,
    .dark-div a:hover,
    .dark-div ul li:before,
    .maincolor1, a.maincolor1,
    .maincolor1hover:hover,
    a.maincolor1hover:hover,
    .main-menu li.current-menu-item > a,
    .main-menu .nav > li > a:hover,
    .main-menu .nav > li:hover > a,
    #top-nav.topnav-light .main-menu li.current-menu-item > a,
    #top-nav.topnav-light .main-menu .nav > li > a:hover,
    #top-nav .dropdown-menu li>a:before,
    #top-nav .dropdown-menu>li>a:hover,
    #top-nav .dropdown-menu>li>a:focus,
    .preview-mode .video-item h3 a:hover,
    .player-button a:hover,
    .video-toolbar-item.like-dislike .status:before,
    #top-carousel.inbox-more  .bgcolor1hover:hover,
    #top-carousel.inbox-more a.bgcolor1hover:hover,
    #head-carousel #metro-carousel.is-carousel .carousel-button a:hover,
    #head-carousel #big-carousel.is-carousel .carousel-button a:hover,
    #control-stage-carousel .video-item:hover .item-head,
    #control-stage-carousel .video-item:hover .item-head h3,
    #control-stage-carousel .control-up:hover,
    #control-stage-carousel .control-down:hover,
    .mashmenu .menu li.level0:hover>a,
    .mashmenu .menu li.level0:hover .sub-channel li.hover a,
    .mashmenu .columns .list a:hover,
    .mashmenu .content-item .title a:hover,
    .headline .htitle marquee a,
    .headline .htitle marquee p,
    .headline .htitle .hicon i,
    .headline .htitle .scroll-text ul li a,
    .headline .htitle .scroll-text ul li p,
    .headline .htitle h4,
    .headline-content.col-md-9 .headline .htitle .scroll-text ul li a:hover,
    .headline .htitle .scroll-text ul li p:hover,
    .dark-div .navbar-toggle, .dark-div .topnav-light .navbar-toggle,
    .headline-content .headline .htitle .scroll-text ul li a:hover,
    .headline .htitle .scroll-text ul li p:hover,.headline .htitle .hicon .first-tex,
    .topnav-light .headline-content.col-md-6 .headline .htitle .scroll-text ul li a:hover,
    .topnav-light .headline .htitle .scroll-text ul li p:hover,
    header #top-carousel.cat-carousel .video-item h3 a:hover,
    .amazing .rating-bar.bgcolor2,
    .wpb_accordion .wpb_accordion_wrapper .wpb_accordion_header.ui-accordion-header-active a,
    .wpb_accordion .wpb_accordion_wrapper .wpb_accordion_header.ui-accordion-header-active:before,
	.pathway a:hover, .dark-div .pathway a:hover,
    .woocommerce .products .star-rating, .woocommerce-page .products .star-rating,
    .dark-div .smart-box .item-head h3 a:hover,
    .off-canvas-inner ul li a:before{
        color: <?php echo $main_color_1 ?>;
    }
    .carousel-dotted a.selected, .carousel-dotted a:hover, 
    .bgcolor1,
    .bgcolor1hover:hover,
    a.bgcolor1hover:hover,
    .rich-list li:hover .rich-list-icon,
    .video-item.marking_vd .mark_bg,
    header #top-carousel.cat-carousel .bgcolor2,
    #bottom .widget ul li:hover:before,
    .amazing .slide .ct-btn, .classy-carousel-horizon #stage-carousel .video-item .ct-btn,
    #classy-carousel #top-carousel .video-item.selected .item-thumbnail .item-head{
        background-color: <?php echo $main_color_1 ?>;
    }
    .dark-div textarea:focus,
    .dark-div input[type='text']:focus,
    .dark-div input[type='url']:focus,
    .dark-div input[type='email']:focus,
    .dark-div input[type='number']:focus,
    .dark-div input[type='password']:focus,
    .dark-div select:focus,
    .bordercolor1,
    .bordercolor1hover:hover,
    .dark-form .form-control:focus,
    .dark-form .input-group:hover .form-control,
    .dark-form .input-group:hover .form-control:focus,
    .dark-form .input-group:hover .input-group-btn .btn-default,
    #control-stage-carousel .video-item:hover,
    #control-stage-carousel .video-item.selected,
    #control-stage-carousel .control-up:hover,
    #control-stage-carousel .control-down:hover,
    #top-carousel.full-more a.maincolor1:hover,
    #top-carousel.cat-carousel .is-carousel .carousel-button a:hover,
    .wpb_accordion .wpb_accordion_section.group h3.ui-accordion-header-active,
    .wpb_accordion .wpb_accordion_wrapper .wpb_accordion_content.ui-accordion-content-active,
    #bottom .widget ul li:before,
    .cactus-video-list-content .control-up:hover, 
    .cactus-video-list-content .control-down:hover,
    .cactus-video-list-content .cactus-widget-posts .cactus-widget-posts-item.active{
        border-color: <?php echo $main_color_1 ?>;
    }
    .video-toolbar-item.like-dislike .status,
    .video-toolbar-item.like-dislike:hover .status,
    .layout-2 .submit-video .btn:hover, .layout-3 .submit-video .btn:hover{
        border-color:<?php echo $main_color_1 ?> !important;
        background:<?php echo $main_color_1 ?> !important;
    }
	.widget_revslider .tp-bullets.simplebullets.round .bullet:hover{background:<?php echo $main_color_1;?>}
    .woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale,
    .widget_revslider .tp-bullets.simplebullets.round .bullet.selected{background:<?php echo $main_color_1;?>}
	.tp-caption.tm_heading,
    .cactus-video-list-content .control-up:hover, .cactus-video-list-content .control-down:hover,
    .dark-div .cactus-video-list-content .video-listing .cactus-widget-posts .widget-posts-title a:hover{color:<?php echo $main_color_1;?>}
    .pull-right .social-icon.shopping:hover{color: <?php echo $main_color_1;?> !important;}
<?php
// calculate gradient color of button
$gradient_color = '#'.tm_color_gradient($main_color_1,array(-6,8,57));
?>
	.tp-button.tm_button{background-color: <?php echo $main_color_1;?>; background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo $main_color_1;?>), to(<?php echo $gradient_color;?>));background-image: -webkit-linear-gradient(top, <?php echo $main_color_1;?>, <?php echo $gradient_color;?>); background-image: -moz-linear-gradient(top, <?php echo $main_color_1;?>, <?php echo $gradient_color;?>); background-image: -ms-linear-gradient(top, <?php echo $main_color_1;?>, <?php echo $gradient_color;?>); background-image: -o-linear-gradient(top, <?php echo $main_color_1;?>, <?php echo $gradient_color;?>); background-image: linear-gradient(to bottom, <?php echo $main_color_1;?>, <?php echo $gradient_color;?>);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=<?php echo $main_color_1;?>,endColorstr=<?php echo $gradient_color;?>)}
<?php
}
if($main_color_2 = ot_get_option('main_color_2')){ ?>
    #account-form div.formleft table.form-table tbody tr td input:focus, 
    #account-form div.formleft table.form-table tbody tr td select:focus,
	blockquote,
    textarea:focus,
    input[type='text']:focus,
    input[type='url']:focus,
    input[type='email']:focus,
    input[type='number']:focus,
    input[type='password']:focus,
    select:focus,
    .bordercolor2, .bordercolor2hover:hover,
    .carousel-pagination a:hover,
    .carousel-pagination a.selected,
    .wp-pagenavi .current,
    .panel-default,.panel.panel-default:hover,
    .icon-checklist .border,
    .woocommerce .woocommerce-ordering select, .woocommerce-page .woocommerce-ordering select,
    .woocommerce-checkout .login-tm input.button,
    .buddypress #buddypress .standard-form textarea:focus, .buddypress #buddypress .standard-form input[type=text]:focus, .buddypress #buddypress .standard-form input[type=text]:focus, .buddypress #buddypress .standard-form input[type=color]:focus, .buddypress #buddypress .standard-form input[type=date]:focus, .buddypress #buddypress .standard-form input[type=datetime]:focus, .buddypress #buddypress .standard-form input[type=datetime-local]:focus, .buddypress #buddypress .standard-form input[type=email]:focus, .buddypress #buddypress .standard-form input[type=month]:focus, .buddypress #buddypress .standard-form input[type=number]:focus, .buddypress #buddypress .standard-form input[type=range]:focus, .buddypress #buddypress .standard-form input[type=search]:focus, .buddypress #buddypress .standard-form input[type=tel]:focus, .buddypress #buddypress .standard-form input[type=time]:focus, .buddypress #buddypress .standard-form input[type=url]:focus, .buddypress #buddypress .standard-form input[type=week]:focus, .buddypress #buddypress .standard-form select:focus, .buddypress #buddypress .standard-form input[type=password]:focus, .buddypress #buddypress .dir-search input[type=search]:focus, .buddypress #buddypress .dir-search input[type=text]:focus,
    .channel-list .subs-button .subs-row .subs-cell > span.subscribe-counter,
    .cactus-new-feed .subs-button:not(.subscribed) .subs-row .subs-cell > span.subscribe-counter{
    	border-color:<?php echo $main_color_2 ?>;
	}
    .wp-pagenavi .current,.wp-pagenavi a:hover{border-color:<?php echo $main_color_2 ?> !important;}
    table th,
    .maincolor2, a.maincolor2, .maincolor2hover:hover, a, a:hover,
    ul li:before,
    .dark-div .maincolor2hover:hover,
    .video-item h2 a:hover, .video-item h3 a:hover,
    .video-item .item-author,
    .light-title,
    .video-toolbar-item.like-dislike .status,
    a.comment-reply-link,
    .video-toolbar-item.like-dislike .watch-action .action-like a:hover,
    .video-toolbar-item.like-dislike .watch-action .action-like a:hover:after,
    .video-toolbar-item.like-dislike .watch-action  .action-unlike a:hover,
    .video-toolbar-item.like-dislike .watch-action  .action-unlike a:hover:after,
    .top_authors_widget .tm_top_author ul li .tm_img2 a:hover,
    .tm_widget_categories ul,
    .tm_widget_categories ul li a:hover,
    #review-box h2.review-box-header,
    .review-stars .review-final-score,
    .review-stars .review-final-score h4,
    #review-box strong,.review-stars .taq-score,
    #tm_recentcomments .tm_recentcomments .info_rc a:hover,
    .widget.widget-border.widget_nav_menu .menu .menu-item a:hover,
    .widget.widget-border.widget_nav_menu .menu .menu-item ul li a:hover,
    .widget.widget-border.widget_nav_menu .menu li.current-menu-item > a,
    .widget_tm_mostlikedpostswidget ul,
    .widget_tm_mostlikedpostswidget ul li a:hover,
    .tm_widget_most_viewed_entries ul,
    .tm_widget_most_viewed_entries ul li a:hover,
    .related-title,
    .tmr-head h3,.tmr-stars,.tmr-final,
    .is-carousel.simple-carousel.testimonial .name.pos,
    .panel-default .panel-title a,.panel.panel-default .panel-heading:before,
    .boxed-icon .boxed-item .heading,.boxed-icon .boxed-item .ic_st2,
    .icon-checklist li i,
    .action-like.change-color a:after,
    .action-unlike.change-color a:after,
    .tooltipster-content .gv-title,
    .buddypress #buddypress div.item-list-tabs ul li a:hover,
    #membership-wrapper legend,
    .register-section h4,
    .standard-form h2,
    #account-form div.formleft p strong,
    .woocommerce .woocommerce-result-count, .woocommerce-page .woocommerce-result-count,
    .woocommerce .woocommerce-ordering select, .woocommerce-page .woocommerce-ordering select,
    .woocommerce .star-rating span, .woocommerce-page .star-rating span,
    .widget_product_categories .product-categories li:before,
    .widget.woocommerce ul li a:hover,
    .woocommerce .cart-collaterals .cart_totals h2, .woocommerce-page .cart-collaterals .cart_totals h2,
    .shipping_calculator h2 a,
    .woocommerce-checkout .woocommerce .woocommerce-info a:focus,
    .woocommerce-checkout .woocommerce .woocommerce-info:before,
    .woocommerce p.stars a.active, .woocommerce-page p.stars a.active,
    .woocommerce p.stars a:hover, .woocommerce-page p.stars a:hover,
    .video-toolbar-item.tm-favories .wpfp-link[href^="?wpfpaction=remove"]:before, .video-toolbar-item.tm-favories .wpfp-link:hover,
    .cactus-listing-heading h1{
    	color:<?php echo $main_color_2 ?>;
    }
    #account-form div.formleft .button-primary,
    #membership-wrapper .link .button,
    #membership-wrapper  div.topbar{background-color: <?php echo $main_color_2 ?> !important;}    
    .tooltipster-base .gv-button .quick-view,
    .bgcolor2, .bgcolor2hover:hover,
    .wp-pagenavi a:hover, .wp-pagenavi .current,
    .shortcode-asf .asf-tags .search_tags a.filtered,
    .shortcode-asf .asf-tags .search_tags a:hover,
    .carousel-pagination a.selected,
    .solid-noborder .widget-title,
    .member .member-info .member-social a.icon-social:hover,    
    .btn-playall, .btn-playall:focus,
    .subs-button .subs-row .subs-cell a,
    .header-channel .subs-button.subscribed .subs-row .subs-cell a{
        background-color:<?php echo $main_color_2 ?>;
    }
    button,
    input[type='submit'],
    .dark-div .light-div button,
    .dark-div .light-div input[type='submit'],
    .light-button,
    .multilink-btn.current-link,
    .buddypress #buddypress button, .buddypress #buddypress a.button, .buddypress #buddypress input[type=submit], .buddypress #buddypress input[type=button], .buddypress #buddypress input[type=reset], .buddypress #buddypress ul.button-nav li a, .buddypress #buddypress div.generic-button a, .buddypress #buddypress .comment-reply-link, .buddypress a.bp-title-button,
    .cactus-listing-config .load-more:hover{
        background:<?php echo $main_color_2 ?>;
        border-color:<?php echo $main_color_2 ?>;
    }
    .ct-btn{background:<?php echo $main_color_2 ?>;}
    .wp-pagenavi a, .wp-pagenavi span,
    .member .member-info .member-social a.icon-social,
    .widget.widget-border.widget_nav_menu .menu .menu-item:before{
        border-color:<?php echo $main_color_2 ?>;
        color:<?php echo $main_color_2 ?>;
    }
    .light-button,
    .cactus-listing-config .load-more,
	.cactus-listing-config .load-more:focus{
        border-color:<?php echo $main_color_2 ?>;
    }
    .advanced_trending_videos_widget .rt-article-title a, .advanced_popular_videos_widget .rt-article-title a,
    .cactus-listing-config .load-more,
	.cactus-listing-config .load-more:focus{
    	color:<?php echo $main_color_2 ?>;
    }
    .heading-shortcode .module-title * {color:<?php echo $main_color_2 ?>;}
    .compare-table-tm .compare-table-column .compare-table-row.row-first{ background-color:<?php echo $main_color_2 ?>; border:1px solid <?php echo $main_color_2 ?>;}

	.woocommerce #content div.product form.cart .button, .woocommerce div.product form.cart .button, .woocommerce-page #content div.product form.cart .button, .woocommerce-page div.product form.cart .button
    .widget_product_tag_cloud .tagcloud a:hover,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce table.shop_table thead, .woocommerce-page table.shop_table thead,
    .woocommerce-page table.cart input.checkout-button.button{background:<?php echo $main_color_2 ?> !important;}
    .woocommerce #payment #place_order, .woocommerce-page #payment #place_order{background:<?php echo $main_color_2 ?>;}
.pull-right .social-icon.shopping:hover{color: <?php echo $main_color_2 ?> !important;}
<?php
}
//fonts
if($custom_font = ot_get_option( 'custom_font')){ ?>
	@font-face
    {
    	font-family: 'Custom Font';
    	src: url('<?php echo $custom_font ?>');
    }
<?php }
$tm_text_font = ot_get_option( 'text_font', false);
$tm_text_font_family = explode(":", $tm_text_font);
$tm_text_font_family = $tm_text_font_family[0];
$tm_text_size = ot_get_option( 'text_size', '13px');
$tm_text_weight = ot_get_option( 'text_weight', '');
$tm_h1_size = ot_get_option( 'h1_size', '30px');
$tm_h1_weight = ot_get_option( 'h1_weight', '');
$tm_h2_size = ot_get_option( 'h2_size', '22px');
$tm_h2_weight = ot_get_option( 'h2_weight', '');
$tm_h3_size = ot_get_option( 'h3_size', '17.5px');
$tm_h3_weight = ot_get_option( 'h3_weight', '');
$tm_nav_size = ot_get_option( 'nav_size');
$tm_nav_weight = ot_get_option( 'nav_weight', '');

if($tm_text_font){?>
html, body, h1, h2, h3, h4, h5, h6, p, span,
.cactus-sub-wrap .entry-title,
.cactus-widget-posts .widget-posts-title{
	font-family: '<?php echo $tm_text_font_family ?>', sans-serif;
}
<?php } ?>
h1{font-size:<?php echo $tm_h1_size ?>; <?php echo $tm_h1_weight?'font-weight:'.$tm_h1_weight:'' ?>}
h2{font-size:<?php echo $tm_h2_size ?>; <?php echo $tm_h2_weight?'font-weight:'.$tm_h2_weight:'' ?>}
h3,.cactus-widget-posts .widget-posts-title{font-size:<?php echo $tm_h3_size ?>; <?php echo $tm_h3_weight?'font-weight:'.$tm_h3_weight:'' ?>}
body,
.cactus-listing-config .load-more {font-size: <?php echo $tm_text_size ?>; <?php echo $tm_text_weight?'font-weight:'.$tm_text_weight:'' ?>}
.main-menu .nav > li > a {<?php echo $tm_nav_size?'font-size: '.$tm_nav_size:'' ?>; <?php echo $tm_nav_weight?'font-weight:'.$tm_nav_weight:'' ?>}
<?php
//mobile witdth
if($width = ot_get_option('mobile_width',false)){ ?>
@media (min-width: <?php echo $width ?>px){
    .navbar-collapse.collapse {
        display: block!important;
        height: auto!important;
        padding-bottom: 0;
        overflow: visible!important;
    }
    .main-menu .hidden-xs {
    	display: block!important;
    }
    .main-menu .visible-xs {
    	display: none!important;
    }
    .navbar-nav>li {
        float: left;
    }
    .navbar-header {
    	float: left;
    }
    .container>.navbar-header, .container>.navbar-collapse {
		margin-right: 0;
		margin-left: 0;
    }
    .navbar-toggle {
		display: none;
    }
}
@media (max-width: <?php echo $width ?>px){
#wrap.mnopen a{
	pointer-events: none;
}
#wrap.mnopen .wrap-overlay{
	position:absolute;
	top:0;
	left:0;
	right:0;
	bottom:0;
	z-index:999;
}
body.mnopen #off-canvas{
	pointer-events: auto;
	transform: translate3d(0, 0, 0);
	-moz-transform: translate3d(0, 0, 0);
	-webkit-transform: translate3d(0, 0, 0);
	overflow-scrolling: touch;
	overflow:auto;
	-webkit-overflow-scrolling: touch;
	-ms-overflow-style: -ms-autohiding-scrollbar;
	visibility: visible;
	z-index: 9999;
	transition: transform .4s ease, visibility 0s ease 0s, pointer-events 0s ease .5s;
	-webkit-transition: -webkit-transform .4s ease, visibility 0s ease 0s, pointer-events 0s ease .5s;
}
    .navbar-collapse.collapse {
        display: none!important;
    }
    .main-menu .hidden-xs {
    	display: none!important;
    }
    .main-menu .visible-xs {
    	display: block!important;
    }
    .navbar-nav>li {
        float: none;
    }
    .navbar-header {
    	float: none;
    }
    .navbar-toggle {
		display: block;
    }
    .navbar-collapse.in {
		overflow-y: auto;
    }
    .navbar-right {
    	float: left!important;
    }
    .navbar-collapse {
    	border-top: solid 1px #101010;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
		-webkit-overflow-scrolling: touch;
    }
    .logo{
        display:block;
        text-align:center;
        margin:0 40px 0 60px
    }
    #off-canvas{
        width:500px;
    }
}
@media (max-width: 767px){
#off-canvas{
	width:500px;
}
}
@media (max-width: 600px){
#off-canvas{
	width:400px;
}
}
@media (max-width: 500px){
#off-canvas{
	width:300px;
}
}
@media (max-width: 400px){
#off-canvas{
	width:250px;
}
}
<?php
}
if($page_background = ot_get_option('page_background',false)){ ?>
#body-wrap, .boxed-mode #body-wrap{
	<?php if(isset($page_background['background-color'])&&$page_background['background-color']){ ?>
	background-color:<?php echo $page_background['background-color'] ?>;
    <?php }?>
    <?php if(isset($page_background['background-image'])&&$page_background['background-image']){ ?>
	background-image:url(<?php echo $page_background['background-image'] ?>);
    <?php }?>
    <?php if(isset($page_background['background-position'])&&$page_background['background-position']){ ?>
	background-position: <?php echo $page_background['background-position'] ?>;
    <?php }?>
    <?php if(isset($page_background['background-attachment'])&&$page_background['background-attachment']){ ?>
	background-attachment:<?php echo $page_background['background-attachment'] ?>;
    <?php }?>
    <?php if(isset($page_background['background-repeat'])&&$page_background['background-repeat']){ ?>
    background-repeat:<?php echo $page_background['background-repeat'] ?>;
    <?php }?>
}
	<?php if(isset($page_background['background-attachment'])&&$page_background['background-attachment']=='fixed'){ ?>
    /*@media(min-width:768px){
        #body-wrap{
            position:fixed;
            top:0;
            bottom:0;
            left:0;
            right:0;
        }
        .admin-bar #body-wrap{
            top:32px;
        }
    }
    @media(min-width:768px) and (max-width:782px){
        .admin-bar #body-wrap{
            top:46px;
        }
        .admin-bar #off-canvas{
            top:46px;
        }
    }
    .bg-ad {
        right: 14px;
    }*/
    <?php }?>
    <?php if(isset($page_background['background-attachment'])&&$page_background['background-attachment']=='fixed'&&ot_get_option('theme_layout')!=1){ ?>
    /*#body-wrap{
        position:fixed;
        top:0;
        bottom:0;
        left:0;
        right:0;
    }
    .admin-bar #body-wrap{
        top:32px;
    }
    @media(max-width:782px){
        .admin-bar #body-wrap{
            top:46px;
        }
        .admin-bar #off-canvas{
            top:46px;
        }
    }*/
    <?php }?>
<?php }
//navigation height
if($topnav_height = ot_get_option('topnav_height',false)){
	if(ot_get_option('topnav_layout')!='layout-4'){//logo not center ?>
#top-nav .navbar {
	min-height:<?php echo $topnav_height ?>px;
}
#top-nav .navbar-header{
	height:<?php echo $topnav_height ?>px;
    line-height:<?php echo $topnav_height ?>px;
}
#top-nav .main-menu .menu-item-depth-0 > a,
#top-nav .main-menu .menu-item.level0 > a,
.main-menu .nav > li > a.account_cr {
	line-height:<?php echo $topnav_height-30 ?>px;
}
#top-nav .main-menu .navbar-nav.user_submit li a.submit-video{ line-height:<?php echo $topnav_height-22 ?>px;}
#top-nav .main-menu .navbar-nav.nav-search-box li{ line-height:<?php echo $topnav_height-10 ?>px;}
#top-nav.layout-3 .main-menu .navbar-nav.user_submit li a.submit-video{ line-height:<?php echo $topnav_height-42 ?>px;}
#top-nav.layout-3 .main-menu .navbar-nav.nav-search-box li{ line-height:<?php echo $topnav_height-30 ?>px;}
/*.layout-3 .nav-search-box{
	padding-top:<?php echo (($topnav_height-40)/2); ?>px;
}
.layout-3 .main-menu .nav > li > a.submit-video{
	padding-top:<?php echo (($topnav_height-35)/2); ?>px;
}*/
#headline.is-fixed-nav,
#headline.is-fixed-nav.layout-3{
	margin-top:<?php echo $topnav_height ?>px;
}
    <?php }else{//logo center ?>
    .layout-4 .navbar-header .logo img {
    	height:<?php echo $topnav_height-51 ?>px;
        max-height:none;
    }
    #top-nav .navbar {
        min-height:<?php echo $topnav_height-51 ?>px;
    }
    #top-nav .navbar-header{
        height:<?php echo $topnav_height-51 ?>px;
        line-height:<?php echo $topnav_height-51 ?>px;
    }
    @media(max-width:767px){
    #top-nav .navbar {
        min-height:<?php echo $topnav_height ?>px;
    }
    .layout-4 .navbar-header .logo {
    	line-height:<?php echo $topnav_height ?>px;
    }
    }
    #headline.is-fixed-nav,
    #headline.is-fixed-nav.layout-3{
        margin-top:<?php echo $topnav_height ?>px;
    }
	<?php } ?>
<?php
}
if($header_home_height = ot_get_option('header_home_height')){ ?>
#head-carousel .is-carousel {
    height: <?php echo $header_home_height ?>px;
}
#head-carousel .video-item {
	width: <?php echo $header_home_height*16/9 ?>px;
    height: <?php echo $header_home_height ?>px;
}
#head-carousel #metro-carousel .video-item .item-thumbnail img {
    width: <?php echo $header_home_height*16/9 ?>px;
}
#head-carousel #metro-carousel .video-item > .video-item {
    width: <?php echo $header_home_height*8/9 ?>px;
    height: <?php echo $header_home_height/2 ?>px;
}
#head-carousel #metro-carousel.is-carousel .carousel-button a, #head-carousel #big-carousel.is-carousel .carousel-button a {
    height: <?php echo $header_home_height ?>px;
    line-height: <?php echo $header_home_height ?>px;
}
<?php
}
if($ad_bg_width = ot_get_option('ad_bg_width')){ ?>
	.bg-ad-left, .bg-ad-right{
    	width: <?php echo $ad_bg_width ?>;
    }
<?php
}
//custom CSS
echo ot_get_option('custom_css','');