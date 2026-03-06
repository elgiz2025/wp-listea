<?php
	$rand_id = uniqid();

	// WPML filter
	$suppress_filters = get_option('suppress_filters');

	// Get Order & Orderby Parameters
	$orderby = ( of_get_option('slider_posts_orderby') ) ? of_get_option('slider_posts_orderby') : 'date';
	$order   = ( of_get_option('slider_posts_order') ) ? of_get_option('slider_posts_order') : 'DESC';
	

	// query
	$args = array(
		'post_type'        => 'slider',
		'posts_per_page'   => -1,
		'post_status'      => 'publish',
		'orderby'          => $orderby,
		'order'            => $order,
		'suppress_filters' => $suppress_filters
		);
	$slides = get_posts($args);
	if (empty($slides)) return;
?>

<script type="text/javascript">
		jQuery(function() {
			var isparallax = true;
			if(!device.mobile() && !device.tablet()){
				isparallax = true;
			}else{
				isparallax = false;
			}

				jQuery('#parallax-slider-<?php echo $rand_id ?>').parallaxSlider({
					animateLayout: "<?php echo of_get_option( 'px_slider_effect', 'simple-fade-eff' ); ?>"
				,	parallaxEffect: isparallax
				,	duration: <?php echo of_get_option( 'px_slider_speed', 1500 ); ?>
				,	autoSwitcher: <?php echo of_get_option( 'px_slider_auto', true ); ?>
				,	autoSwitcherDelay: <?php echo of_get_option( 'px_slider_pause', 7000 ); ?>
				,	slider_navs: <?php echo of_get_option( 'px_slider_navs', true ); ?>
				,	slider_pagination: <?php echo of_get_option( 'px_slider_pags', true ); ?>
				,	parallaxInvert: false
				});
			
		});
</script>

<?php
	$resutlOutput = '<div id="parallax-slider-'.$rand_id.'" class="parallax-slider">';
		$resutlOutput.= '<ul class="baseList">';
			foreach( $slides as $k => $slide ) {
				$url                = get_post_meta($slide->ID, 'my_slider_url', true);
				$thumb_url          = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'slider-thumb');
				$sl_image_url       = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'full');
				$caption            = get_post_meta($slide->ID, 'my_slider_caption', true);

				if ( $sl_image_url[0]=='' ) {
					$sl_image_url[0] = get_stylesheet_directory_uri() . '/parallax-slider/img/no-photo.jpg';
				}

				$video_data = "";
				$slider_type = get_post_meta($slide->ID, 'parallax-slider-type-switcher', true);

				switch ($slider_type) {
				    case 'media-library-video-slide-type':

				    	$sourcesList = array(
					    	"mp4"    => get_post_meta($slide->ID, 'parallax-slider-video-src-mp4', true),
					    	"webm"   => get_post_meta($slide->ID, 'parallax-slider-video-src-webm', true),
					    	"ogv"    => get_post_meta($slide->ID, 'parallax-slider-video-src-ogv', true),
					    );

					    $sourcesUrlList = array(
					      	"mp4"     => '',
					      	"webm"   => '',
					      	"ogv"    => '',
					    );

					    $args = array(
					        'post_type' => 'attachment',
					        'post_mime_type' =>'video',
					        'post_status' => 'inherit',
					        'posts_per_page' => -1,
					    );

					    $query_videos = new WP_Query( $args );

					    if ( $query_videos->have_posts() ) {
					      	foreach ( $query_videos->posts as $item) { 
					        	$filename = wp_basename($item->guid);
					        	foreach ($sourcesList as $key => $value) {
						          	if($value == $filename){
						           		$sourcesUrlList[$key] = $item->guid;
						          	}
					        	}
					      	}
					    }

					    $video_loader = get_post_meta($slide->ID, 'parallax-slider-video-loader', true);
					    $video_mute = get_post_meta($slide->ID, 'parallax-slider-video-mute', true);

				    	if(!empty($sourcesUrlList['mp4'])) $video_data .= ' data-video-src-mp4="'.$sourcesUrlList['mp4'].'"';
				    	if(!empty($sourcesUrlList['webm'])) $video_data .= ' data-video-src-webm="'.$sourcesUrlList['webm'].'"';
				    	if(!empty($sourcesUrlList['ogv'])) $video_data .= ' data-video-src-ogv="'.$sourcesUrlList['ogv'].'"';
				    	if(!empty($video_loader)) $video_data .= ' data-video-preloader="true"';
				    	if(!empty($video_mute)) $video_data .= ' data-video-mute="true"';

				        break;

				    case 'youtube-video-slide-type':

				    	$youtube_video_id = get_post_meta($slide->ID, 'parallax-slider-youtube-video', true);
				    	$youtube_mute = get_post_meta($slide->ID, 'parallax-slider-youtube-mute', true);
				    	if(!empty($youtube_video_id)) $video_data .= ' data-video-youtube-id="'.$youtube_video_id.'"';
				    	if(!empty($youtube_mute)) $video_data .= ' data-video-mute="true"';

				        break;

				    case 'vimeo-video-slide-type':

				    	$vimeo_video_id = get_post_meta($slide->ID, 'parallax-slider-vimeo-video', true);
				    	$vimeo_mute = get_post_meta($slide->ID, 'parallax-slider-vimeo-mute', true);
				    	if(!empty($vimeo_video_id)) $video_data .= ' data-video-vimeo-id="'.$vimeo_video_id.'"';
				    	if(!empty($vimeo_mute)) $video_data .= ' data-video-mute="true"';

				        break;

				    default:

				    	break;
				}

				$resutlOutput.= '<li data-preview="'. $sl_image_url[0] .'" data-thumb-url="'.$thumb_url[0].'" data-ulr-link="'. $url .'" '.$video_data.'>';
					if ($caption) {
						$resutlOutput.= '<div class="slider_caption">';
						$resutlOutput.= stripslashes(htmlspecialchars_decode($caption));
						$resutlOutput.= '</div>';
					}
				$resutlOutput.= '</li>';
			}
		$resutlOutput.= '</ul>';
	$resutlOutput.= '</div>';

	echo $resutlOutput;
	wp_reset_postdata();
?>