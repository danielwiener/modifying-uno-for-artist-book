<?php 
global $gpp;
$blogcats = "";
$blogexclude = "";
 
function dw_remove_dashboard_widgets() {
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	// comment out what you want or don't want
 	global $wp_meta_boxes;

	//Right Now - Comments, Posts, Pages at a glance
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	//Recent Comments                                                                                              
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	//Incoming Links
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	//Plugins - Popular, New and Recently updated Wordpress Plugins
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);

	//Wordpress Development Blog Feed
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	//Other Wordpress News Feed
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	//Quick Press Form
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	//Recent Drafts List
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
}

// Hook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'dw_remove_dashboard_widgets' ); 

function dw_instructions_dashboard_widget_function() {
 ?>
 
	 <h4><a href="http://documentation.diyartportfolios.com/" target="_blank">Some Instructions</a></h4>
	 <p>Here are a set of instructions, that will be slowly growing. If you want something explained to you I will add it. Also please check the <a href="/wp-admin/admin.php?page=video-user-manuals/plugin.php">video manual</a></p>
	<?php //comments - do this for grants category only. And maybe separate it into another function.  
	//http://wpsnipp.com/index.php/functions-php/replace-dashboard-news-feed-with-another-rss-feed/
	echo '<div class="rss-widget">';
	     wp_widget_rss_output(array(
	          'url' => 'http://documentation.diyartportfolios.com/feed',
	          'title' => 'Helpful Documentation',
	          'items' => 10,
	          'show_summary' => 0,
	          'show_author' => 0,
	          'show_date' => 0
	     ));
	     echo '</div>';?> 
<?php

}     



// Create the function use in the action hook

function dw_add_dashboard_widgets() { 

wp_add_dashboard_widget('dw_instructions_dashboard_widget', 'Instructions', 'dw_instructions_dashboard_widget_function');
				
} 

// Hook into the 'wp_dashboard_setup' action to register our other functions

add_action('wp_dashboard_setup', 'dw_add_dashboard_widgets' );


// Unregister Sidebars
add_action( 'init', 'gpp_base_sidewinder_sidebars', 11 );

function gpp_base_sidewinder_sidebars() {
	unregister_sidebar( 'sidebar-1' );	
}

//changing the descriptions of widgets
add_filter('instructions_desc', 'gpp_base_instructions_desc_uno');
function gpp_base_instructions_desc_uno(){return "This theme uses Widgets.<br /><br />";}

$gpp = get_option('gpp_base_options');
if(isset($gpp['gpp_base_blog_cat'])) {
	$blogcats = substr($gpp['gpp_base_blog_cat'],0,-1);
	$blogexclude = str_replace(',',' and ', $blogcats);
}

	$allcategories = get_all_category_ids();
	$catarray = explode(",",$blogcats);
	$nonblogcatstemp = array_diff($allcategories,$catarray);
	$tempcats = "";
	foreach($nonblogcatstemp as $nonblogcat){
		$tempcats .= $nonblogcat.",";
	}
	$nonblogcats = str_replace(',',' and ', substr($tempcats,0,-1));

// Define Theme Options Variables
$themename = "Uno";
$sidebarshow = "false";

//config
$showsidebar = 0;
$showblog = 1;
$showwelcome = 0;
$showheadermenu = 0;
$showbackgroundmenu = 0;
$content_width = 940;
$showfooterwidgets = false;
if((isset($gpp['gpp_base_footer']) && $gpp['gpp_base_footer']==true)){
	$showfooterwidgets = true;
}
// Add Post Thumbnail Theme Support
if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 130, 200, true );
	add_image_size( '300x200', 300, 200, true );
	add_image_size( '300x400', 300, 400, true );
	add_image_size( '200x300', 200, 300, true );
	add_image_size( '175x260', 130, 200, true );
}

// remove base indexloop / footer
add_action('init','remove_gpp_base_actions');
function remove_gpp_base_actions() {
 	remove_action('gpp_base_loop_hook','gpp_base_loop_wrapper');
 	remove_action('gpp_base_sidebar_hook','gpp_base_sidebar');
 	remove_action('gpp_base_single_post_hook', 'gpp_base_single_post');	
 	remove_action('gpp_base_archive_loop_hook','gpp_base_archive_loop');
	remove_action('gpp_base_author_loop_hook', 'gpp_base_author_loop');
	remove_action('gpp_base_search_loop_hook','gpp_base_search_loop'); 	
 	remove_action('gpp_base_check_sidebar_hook', 'gpp_base_check_sidebar');
	remove_action('gpp_base_footer_credits_hook', 'gpp_base_footer_credits');

}

/* overwriting the base prev next links with in_same_cat to true */
add_filter('gpp_base_previous_post_link_args','gpp_base_previous_post_link_args_uno');
function gpp_base_previous_post_link_args_uno(){
	global $blogexclude, $catarray, $nonblogcats;	
	if ( in_category( $catarray ) ) {
		$catstoexclude = $nonblogcats;
	 }else {
		$catstoexclude = $blogexclude;
	 }
	 
	$args = array (	'format' 				=> '%link',
					'link'                	=> '<span class="meta-nav">&laquo;</span> %title',
					'in_same_cat'         	=> FALSE,
					'excluded_categories' 	=> $catstoexclude);
	
	return $args;
}
add_filter('gpp_base_next_post_link_args','gpp_base_next_post_link_args_uno');
function gpp_base_next_post_link_args_uno(){
	global $blogexclude, $catarray, $nonblogcats;
	if ( in_category( $catarray ) ) {
		$catstoexclude = $nonblogcats;
	 }else {
		$catstoexclude = $blogexclude;
	 }
	$args = array (	'format' 				=> '%link',
					'link'                	=> '%title <span class="meta-nav">&raquo;</span>',
					'in_same_cat'         	=> FALSE,
					'excluded_categories' 	=> $catstoexclude);
	
	return $args;
}

//bypass sidebar option
add_action('gpp_base_check_sidebar_hook', 'gpp_base_check_sidebar_uno');	
function gpp_base_check_sidebar_uno() {
	 echo "12"; 
}

add_action('gpp_base_loop_hook', 'gpp_base_loop_uno');
function gpp_base_loop_uno() { 
	global $blogexclude;
	
 	$i = 0;
 	while ( have_posts() ) : the_post() ?>
		<?php if ( !in_category($blogexclude) ): ?>
				<div class="grid_2<?php if($i%5==0){ echo " alpha";} elseif ($i%5==4){echo " omega";} ?>">
			<!-- <div class="archivecontent pad"> -->
				 <p class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('%s','gpp_base_lang'),the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></p>
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('%s','gpp_base_lang'),the_title_attribute('echo=0')); ?>"><?php gpp_base_image( array( 'width' => '130', 'height' => '200' ) ); ?></a>
			<!-- </div>  -->
		</div>
		<?php if($i%5==4){echo "<div class='clear'></div>";} ?>
		<?php $i++; ?>  
	<?php endif ?>
	<?php  endwhile; ?>	
<?php } 

/*-----------------------------------------------------------------------------------*/
/* HEADER.PHP - TITLE */
/*-----------------------------------------------------------------------------------*/
 
add_filter( 'gpp_base_doctitle', 'dw_change_base_doctitle', 10, $doctitle ); 
function dw_change_base_doctitle() {
	 $site_name = get_bloginfo('name');
	//     $separator = '|';
	//         	
	//     if ( is_single() ) {
	//       $content = single_post_title('', FALSE);
	//     } elseif ( is_home() || is_front_page() ) { 
	//       $content = get_bloginfo('description');
	//     } elseif ( is_page() ) { 
	//       $content = single_post_title('', FALSE); 
	//     } elseif ( is_search() ) { 
	//       $content = __('Search Results for:', 'gpp_base_lang'); 
	//       $content .= ' ' . esc_html(stripslashes(get_search_query()), true);
	//     } elseif ( is_category() ) {
	//       $content = __('', 'gpp_base_lang');
	//       $content .= ' ' . single_cat_title("", false);;
	//     } elseif ( is_tag() ) { 
	//       $content = __('Tag Archives:', 'gpp_base_lang');
	//       $content .= ' ' . get_query_var('tag');
	//     } elseif ( is_404() ) { 
	//       $content = __('Not Found', 'gpp_base_lang'); 
	//     } else { 
	//       $content = get_bloginfo('description');
	//     }
	// 
	//     if (get_query_var('paged')) {
	//       $content .= ' ' .$separator. ' ';
	//       $content .= 'Page';
	//       $content .= ' ';
	//       $content .= get_query_var('paged');
	//     }
	// 
	//     if($content) {
	//      
	//       $elements = array(
	//         'site_name' => $site_name,
	//         'separator' => $separator,
	//         'content' => $content
	//       );
	// 
	//     } else {
	//       $elements = array(
	//         'site_name' => $site_name
	//       );
	//     }  

    // Filters should return an array
        //$elements = apply_filters('gpp_base_doctitle', $elements); this caused a 500 error!!!!
      	

		$elements = array(
			'site_name' => $site_name
		); 
		
		//But if they don't, it won't try to implode
        if(is_array($elements)) {
                 $doctitle = implode(' ', $elements);
               } else {
                 $doctitle = $elements;
               }
		                                   
 
    $doctitle = "<title>" . $doctitle . "</title>" . "\n";
    
    echo $doctitle;
} // end gpp_base_doctitle

// Redirect homepage to single post page
// add_action('gpp_base_loop_hook', 'gpp_base_loop_uno');	
// function gpp_base_loop_uno() { 
// 	global $gpp, $blogcats;
// 	$exblogcats = str_replace(",",",-",$blogcats);		
// 	// grab latest post which is not blog
// 	$posts = get_posts(array('numberposts'=>1,'category'=>-$exblogcats,'post__in'=>get_option('sticky_posts')));
// 		
// 	foreach($posts as $obj) { // no need for $a - just use $result
//    	 $id =  $obj->ID;
// 	} 
// 	
// 	wp_redirect(get_permalink($id)); 
// 
// }              


// single post design
add_action('gpp_base_single_post_hook', 'gpp_base_single_post_uno');
function gpp_base_single_post_uno() {
	global $gpp, $count, $videos, $post, $blogcats;
	$videos = get_post_meta($post->ID, "video", false);

	$catarray = explode(",",$blogcats);
	
	while ( have_posts() ) : the_post() ?>
		
		<?php 
			//non blog posts
			if(!in_category($catarray)): ?>
		
		<div id="post-<?php the_ID() ?>" class="maincontent">
			
			<div class="slide">
			
			<?php if(!$videos) { ?>
			
			<?php 
			$args = array(
				'order'          => 'ASC',
				'orderby' 		 => 'menu_order',
				'post_type'      => 'attachment',
				'post_parent'    => $post->ID,
				'post_mime_type' => 'image',
				'post_status'    => null,
				'numberposts'    => -1,
				'size' 			 => 'large',
			);
			
			$attachments = get_posts($args);
			if ($attachments) {
				foreach ($attachments as $attachment) {
				$permalink = $attachment->ID;
				$img = wp_get_attachment_image_src($attachment->ID,'large');
				
			
				$width = $img['1'];
				$height = $img['2'];
				
				if ($height >= $width) {
					$resizeheight = "600";
					$class = "vertical";
				} else {
					$class= "horizontal";
					$resizeheight = '';					
				}

					echo "<div id='image-".$permalink."' class='image ".$class." clearfix'>"."\n";
					$img = gpp_base_resize( $attachment->ID, $img_url = null, '940', $resizeheight, $crop = false ) ;	
					echo "<img src='".$img['url']."'>";
					echo "<span class='imgcaption'>".$attachment->post_excerpt."</span>";
					echo "</div>"."\n";
				}
			}
			?>
			
			<?php } else {  get_template_part('video'); } ?>

	    	</div>
	    
	    	<?php if(!$videos) gpp_base_navigation_hook(); ?>
	
		</div>		
		
		<div class="meta">				
			<a href="#singlecontent" class="title"><?php the_title(); ?></a> <?php if(comments_open()) { ?>&#183;<?php } ?> <span class="comments-link"> <?php
				comments_popup_link( __( 'Leave a comment', 'gpp' ), __( '1 Comment', 'gpp' ), __( '% Comments', 'gpp' ), '', '');  ?>
			</span> <!-- &#183; --> <span><?php // gpp_base_posted_on_hook(); ?></span>			
		</div>
		<div class="imgnav">	
				
			<?php gpp_base_navigation_hook(); ?><div id="circles"><div id="indicator"></div></div>			
			
		</div>	
		<?php else: ?>
		
			<!-- single blog post -->
			<div class="singleblog">
    		<h2 class="entry-title"><?php the_title(); ?></h2>

				<div class="entry-content">
					<?php get_template_part('video'); ?>
					<?php gpp_base_content(); ?>
					<?php wp_link_pages('before=<div class="page-link">' .__('Pages:', 'gpp') . '&after=</div>') ?>
				</div>
				<div class="entry-utility">
					<p class="postmetadata alt">
						<small>
							<?php printf(__('This entry was posted on %1$s at %2$s.','gpp_base_i18n'),get_the_time(__('l, F jS, Y','gpp_base_i18n')),get_the_time());?>
							<?php _e('It is filed under','gpp_base_i18n'); ?> <?php the_category(', '); the_tags(__(' and tagged with ','gpp_base_i18n')); ?>.
							<?php printf(__('You can follow any responses to this entry through the <a href="%1$s" title="%2$s feed">%2$s</a> feed.','gpp_base_i18n'),get_post_comments_feed_link(),__('RSS 2.0','gpp_base_i18n')); ?> 
							<?php edit_post_link(__('Edit this entry','gpp_base_i18n'),'','.'); ?>
						</small>
					</p>
				</div><!-- .entry-meta -->
				<?php gpp_base_navigation_hook(); ?>
				<div class="clear"></div>
				<?php gpp_base_comments(); ?>
			</div><!-- .post -->
			
			<!-- end single blog post -->
			
			
		<?php endif; ?>
	 <?php endwhile; wp_reset_query(); 
}

add_action('gpp_base_after_single_post_hook', 'gpp_base_after_single_post_uno');	
function gpp_base_after_single_post_uno() { ?>
	<div class="clear"></div>
	<div id="singlecontent">
		<?php the_content(); ?>
	</div>

<?php } 

//add archive and author page
add_action('gpp_base_author_loop_hook', 'gpp_base_archive_loop_uno');	
add_action('gpp_base_archive_loop_hook', 'gpp_base_archive_loop_uno');	
function gpp_base_archive_loop_uno() { 
 	$i = 0;
 	while ( have_posts() ) : the_post() ?>
	<div class="grid_2<?php if($i%5==0){ echo " alpha";} elseif ($i%5==4){echo " omega";} ?>">   
			 <p class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('%s','gpp_base_lang'),the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a><p>
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('%s','gpp_base_lang'),the_title_attribute('echo=0')); ?>"><?php gpp_base_image( array( 'width' => '130', 'height' => '200' ) ); ?></a>
	</div>
	<?php if($i%5==4){echo "<div class='clear'></div>";} ?>
	<?php $i++; endwhile; ?>	
<?php }

//Add Search Page
add_action('gpp_base_search_loop_hook', 'gpp_base_search_loop_uno');	
function gpp_base_search_loop_uno() { 
	$i = 0;
	while ( have_posts() ) : the_post() ?>
	<div class="grid_4<?php if($i%3==0){ echo " alpha";} elseif ($i%3==2){echo " omega";} ?>">
		<div class="archivecontent pad">
			 <h3 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s','gpp_base_lang'),the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h3>
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s','gpp_base_lang'),the_title_attribute('echo=0')); ?>"><?php gpp_base_image( array( 'width' => '300', 'height' => '400' ) ); ?></a>
			<?php gpp_base_content(); ?>
		</div>
	</div>
	<?php if($i%3==2){echo "<div class='clear'></div>";} ?>
	<?php $i++; endwhile; ?>	
<?php }

// load scroller js
add_action( 'template_redirect', 'gpp_base_load_js_uno' );
function gpp_base_load_js_uno( ) {	   
	wp_enqueue_script('scroll', get_bloginfo('stylesheet_directory').'/library/js/jquery.scrollTo-1.4.2-min.js', array('jquery'));	
}

// Add DOM ready scripts
add_action('wp_footer', 'gpp_base_load_doc_js_uno');
function gpp_base_load_doc_js_uno() {

	$doc_ready_script = '
	<script type="text/javascript">
		jQuery(document).ready(function(){	
		
			jQuery( ".maincontent" ).hide();
			jQuery( ".maincontent" ).fadeIn(2000);
			
			if(jQuery("div.slide div.video").length>0) {
				jQuery(".maincontent").height(544);
			}';	
	
	if(!is_page_template('page-blog.php')){
		$doc_ready_script .= '
				jQuery(".comments-link a, a.title").click( function(e){
					e.preventDefault();
					jQuery("#singlecontent, #commentsbox").toggle();
					jQuery(window).scrollTo(jQuery("div[id=\"singlecontent\"]"), 600);
				});
			'; 
	}	
	$doc_ready_script .= '		
			if(jQuery(".slide div").length > 1) {			
				
				jQuery(".slide div:gt(0)").hide();
				jQuery("#indicator").show();
				aindex = 0;
				jQuery(".slide div").each(function(index){
					jQuery("#indicator").append("<a href=\"#\">"+index+"</a>");
				});
				jQuery("#indicator a:first-child").addClass("active");
				
				// add class to identify first and last item
				jQuery(".slide div:first-child").addClass("first active");
				jQuery(".slide div:last-child").addClass("last");
				
				// check last post
				if(jQuery(".nav-previous").html()=="") {
					jQuery(".nav-previous").html("<a href=\"#\"></a>");
				}
				
				// right link browse older posts
				jQuery(".nav-previous a").live("click",function(e){					
					if(!jQuery(".slide div.active").hasClass("last")) {
						e.preventDefault(); 
						jQuery("#indicator a.active").removeClass().next("a").addClass("active");	 
						jQuery(".slide div:first-child").fadeOut().removeClass("active")
	         				.next("div").fadeIn().addClass("active")
	         				.end().appendTo(".slide");
	         			        			
         			} 
         			
         			//empty last post last image link         			
         			if(jQuery(".nav-previous").html()=="<a href=\"#\"></a>" && jQuery(".slide div.active").hasClass("last")) {			
	         			jQuery(".nav-previous").html("");
	         		}
	         		
         			// check first post not first image and add link
         			if(jQuery(".nav-next").html()=="") {
						jQuery(".nav-next").html("<a href=\"#\"></a>");
					}

				});
				
				// left link browse newer posts
				jQuery(".nav-next a").live("click",function(e){					
					if(!jQuery(".slide div.active").hasClass("first")) {
						e.preventDefault();
						jQuery("#indicator a.active").removeClass().prev("a").addClass("active");
						jQuery(".slide div:last-child").prependTo(".slide").addClass("active");
						jQuery(".slide div:first-child").fadeIn().next("div").fadeOut().removeClass("active");
												
					}	
					//check first post first image empty <a> link
					if(jQuery(".nav-next").html()=="<a href=\"#\"></a>" && jQuery(".slide div.image").first().hasClass("first")) {
						jQuery(".nav-next").html("");
						//alert("here");
					}
					
					// check last post not last image and add link
         			if(jQuery(".nav-previous").html()=="") {
						jQuery(".nav-previous").html("<a href=\"#\"></a>");
					}
									
				});				
				
				// clickable circle indicators nav does not work
				jQuery("#indicator a").click(function(e){
					e.preventDefault();					
				});		
				
			}
		});

	</script>';
					
	echo $doc_ready_script;

}

add_filter('childcss', 'gpp_base_custom_css_uno');

/* Load uno custom CSS for logo */
function gpp_base_custom_css_uno() { 
	global $gpp;
	$logo = $gpp['gpp_base_logo'];
	if($logo <> "") {
		list($width) = getimagesize($logo);
		echo "#masthead h1 {width: ".$width."px; margin: 0 auto 5px;}";
	}
}  

/*-----------------------------------------------------------------------------------*/
/* FOOTER - CREDITS */
/*-----------------------------------------------------------------------------------*/

function dw_gpp_base_footer_credits() {
	global $gpp, $themename;
	$affiliate = $gpp['gpp_base_affiliate_url'];
?>
	<div id="below_footer" class="grid_12 clearfix">
		<p><?php printf(__('All content &copy; %1$s by %2$s.','gpp_base_lang'),date('Y'),__(get_bloginfo('name'))); ?>
		<?php if ($affiliate != '')
			$url = $affiliate;
			else
			$url = 'http://graphpaperpress.com';
		_e('<a href="' .$url . '" title="'.$themename.' theme framework for WordPress">'.$themename.'</a> by <a href="'.$url.'" title="Graph Paper Press">Graph Paper Press</a>','gpp_base_lang'); ?>
		 </p>
	</div>
<?php }
add_action('gpp_base_footer_credits_hook', 'dw_gpp_base_footer_credits');