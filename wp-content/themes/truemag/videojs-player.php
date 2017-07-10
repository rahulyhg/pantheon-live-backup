<link type="text/css" rel="stylesheet" href="https://vjs.zencdn.net/4.5.1/video-js.css" />
<?php 
global $url;
global $auto_play;
if(strpos($url, 'youtube.com') !== false){
?>
<script src="<?php echo get_template_directory_uri() . '/inc/videojs/youtube.js' ?>" ></script>
<video id="vid1" src="" class="video-js vjs-default-skin" controls preload="auto" width="640" height="360" data-setup='{ "techOrder": ["youtube"], "src": "<?php echo $url;?>" <?php if($auto_play=='1'){?>, "autoplay": true <?php }?>}'></video>
<?php 
}else
if (strpos($url, 'vimeo.com') !== false){?>
<video id="vid1" muted="muted" src="" class="video-js vjs-default-skin" controls preload="auto" width="640" height="360" data-setup='{ "techOrder": ["vimeo"], "src": "<?php echo $url;?>", "loop": true <?php if($auto_play=='1'){?>, "autoplay": true <?php }?> }'></video>
<script src="<?php echo get_template_directory_uri() . '/inc/videojs/vjs.vimeo.js' ?>" ></script>
<?php }else
if (strpos($url, 'dailymotion.com') !== false){?>
<script src="<?php echo get_template_directory_uri() . '/inc/videojs/vjs.dailymotion.js' ?>" ></script>
<script>
        videojs.options.flash.swf = "<?php echo get_template_directory_uri() . '/inc/videojs/video-js.swf' ?>";
</script>
<video id="vid1" class="video-js vjs-default-skin" controls preload="auto" width="750" height="506"
       data-setup='{ "techOrder": ["dailymotion"], "dmControls" : "1", "src": "<?php echo $url;?>"}'></video>
<?php }?>

