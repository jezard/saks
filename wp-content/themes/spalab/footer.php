            <?php if( !is_page_template( 'tpl-fullwidth.php' ) ): ?>
            		</div><!-- **Container - End** -->
    		<?php endif;?>
         </div><!-- **Main - End** -->
    
    		<?php if(dttheme_option('general', 'show-newsletter') and dttheme_option('general', 'newsletter-listid') != ""): ?>        
            <!-- **Newsletter**  -->
            <div id="newsletter" class="newsletter-form">
              <div class="container">
                <h2><?php _e('Subscribe to Newsletter','dt_themes');?></h2>
                <form method="post" name="frmsubnewsletter">
                    <input name="btm_mc_emailid" type="email" placeholder="<?php _e('Enter Your Mail id','dt_themes');?>" required="" />
                    <input type="hidden" name="btm_mc_listid" value="<?php echo dttheme_option("general", "newsletter-listid"); ?>" />                       
                    <button name="submit" class="dt-sc-button large" ><?php _e('Subscribe','dt_themes');?></button>
                </form>	<?php 
                
                #AFTER SUBMITTING FORM...
                if( isset($_REQUEST['btm_mc_emailid']) ):
                
                    require_once(IAMD_FW."theme_widgets/mailchimp/MCAPI.class.php");
                    $mcapi = new MCAPI( dttheme_option('general','mailchimp-key') );
                    
                    $merge_vars = Array( 'FNAME' =>$_REQUEST['btm_mc_name'], 'EMAIL' => $_REQUEST['btm_mc_emailid'] );
                    $list_id = $_REQUEST['btm_mc_listid'];
        
                    if($mcapi->listSubscribe($list_id, $_REQUEST['btm_mc_emailid'], $merge_vars ) ):
                        // It worked!   
                      $msg = '<span style="color:green;">'.__('Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.', 'dt_themes').'</span>';
                    else:
                        // An error ocurred, return error message   
                        $msg = '<span style="color:red;"><b>'.__('Error:', 'dt_themes').'</b>&nbsp; ' . $mcapi->errorMessage.'</span>';
                    endif;
                    
                    #PRINTING RESULT...
                    if ( isset($msg) ) echo '<span class="zn_mailchimp_result">'.$msg.'</span>';				
                endif; ?>
              </div>
            </div><!-- **Newsletter - End** -->
		<?php endif; ?>            
 
     
<?php $dttheme_options = get_option(IAMD_THEME_SETTINGS);
$dttheme_general = $dttheme_options['general'];?>
<!-- **Footer** -->
<footer id="footer"><?php
		  $show_footer_logo =  dttheme_option ('general','show-footer-logo');
		  if( !is_null($show_footer_logo) ) :?>
			  <div class="footer-logo"><?php
				  $flogo = dttheme_option('general','footer-logo-url');
				  $flogo = !empty($flogo) ? $flogo : IAMD_BASE_URL."images/footer-top-logo.png";
				  
				  $retina_url = dttheme_option('general','retina-footer-logo-url');
				  $retina_url = !empty($retina_url) ? $retina_url : IAMD_BASE_URL."images/footer-top-logo@2x.png";
				  
				  $width = dttheme_option('general','retina-footer-logo-width');
				  $width = !empty($width) ? $width."px;" : "66px";
				  
				  $height = dttheme_option('general','retina-footer-logo-height');
				  $height = !empty($height) ? $height."px;" : "33px";?>
				  <img class="normal_logo" src="<?php echo $flogo;?>" alt="<?php _e('Footer Logo','dt_themes');?>" title="<?php _e('Footer Logo','dt_themes');?>">
				  <img class="retina_logo" src="<?php echo $retina_url;?>" alt="<?php echo dttheme_blog_title();?>"
					   title="<?php echo dttheme_blog_title(); ?>" style="width:<?php echo $width;?>; height:<?php echo $height;?>;"/>
			  </div><?php
		  endif; ?>
                      
<?php if(!empty($dttheme_general['show-footer'])): ?>
		<div class="container"><?php
        	echo do_shortcode('[dt_sc_hr_invisible]');
			echo do_shortcode('[dt_sc_clear]');
            echo '<div class="ico-border"> <i class="ico-bg flower"></i> </div>';
			echo do_shortcode('[dt_sc_hr_invisible]');
			echo do_shortcode('[dt_sc_clear]'); ?>
		<?php dttheme_show_footer_widgetarea($dttheme_general['footer-columns']);?></div>
<?php endif; ?>

       <div class="copyright gradient-bg"> 
        <div class="container">
            	<?php global $dt_allowed_html_tags; ?>
				<?php if( !empty($dttheme_general['show-copyrighttext']) ): 
							echo "<div class='copyright-content'>";
							  echo wp_kses(stripslashes($dttheme_general['copyright-text']), $dt_allowed_html_tags );
							echo "</div>";			  
					  endif; ?>
                      					  
						<div class="footer-menu">
					<?php  if (function_exists('wp_nav_menu')) :
								$footerMenu = wp_nav_menu(array('theme_location'=>'footer_menu','menu_id'=>'','menu_class'=>'footer-menu','echo'=>false,'container'=>false,'depth' => 1, 'fallback_cb'=>'dttheme_default_navigation'));
                    		endif;
                    		if(!empty($footerMenu))	echo $footerMenu;  ?>
						</div>
			
		</div>
       </div>
</footer><!-- **Footer - End** -->
	</div><!-- **Inner Wrapper - End** -->
    
    
</div><!-- **Wrapper - End** -->

<?php	if (is_singular() AND comments_open())
			wp_enqueue_script( 'comment-reply');

		if(dttheme_option('integration', 'enable-body-code') != '') 
			echo "<script type='text/javascript'>".wp_kses(stripslashes(dttheme_option('integration', 'body-code')), $dt_allowed_html_tags )."</script>";
		wp_footer(); ?>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-577e0b676678a726"></script></body>
</html>
<?php 
// $trace =  debug_backtrace ();
// print_r($trace);
 ?>