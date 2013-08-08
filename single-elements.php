<?php
/**
* Template Name: Single Elements
* Description: Single Element page that displays the Custo Field Data
*
* @package WordPress
* @subpackage Bazaarvoice Style Guide
* @since WP-Bootstrap 0.87
*
* Last Revised: August 7, 2012
*
* Created by Taylor McCaslin
*
*/
	get_header('element');
	while ( have_posts()) : the_post();
?>
<div class="row-fluid">
	<?php
		if (function_exists('bootstrapwp_breadcrumbs')) bootstrapwp_breadcrumbs();
	?>
</div><!--/.row-fluid -->
<!-- ****************** ELEMENT TITLE / DESCRIPTION ****************** -->
<div class="row-fluid">
	<?php
		//Check last modified date
		$modified_date = get_the_modified_date('Y-m-d H:i:s');
		//set values needed to change status
		$element_status = get_post_meta($post->ID, '_status', true);
		//if older than 60 days automatically change status
		if ( strtotime($modified_date) < strtotime( '-180 days' ) ) {
		  $current_post_id = get_the_ID();
		  $meta_key = '_status';
		  $meta_value = 'Review';
		  $prev_value = $element_status;
		  //update _status to "Review"
	  	update_post_meta($current_post_id, $meta_key, $meta_value, $prev_value);
  	}
		//if in development, display message
		if ( $element_status == 'In Development' ) :
	?>
	<div class="alert alert-block">
		<div>
			<h4 class="alert-heading">
				<i class="icon icon-warning-sign"></i> Draft
			</h4>
			<p>
				The Use Cases and Functionality may not be complete.
			</p>
		</div>
	</div>
	<?php
		//if in review, display message
		elseif( $element_status == 'Review' ) :
	?>
	<div class="alert alert-block alert-info">
		<div>
			<h4 class="alert-heading">
				<i class="icon icon-warning-sign"></i> Contact Owner
			</h4>
			<p>
				This element may be out of date or inactive. If you believe this element is updated and still active please contact its owner.
			</p>
		</div>
	</div>
	<?php
		//if retired, display message
		elseif( $element_status == 'Retired' ) :
	?>
	<div class="alert alert-block alert-error">
		<div>
			<h4 class="alert-heading">
				<i class="icon icon-warning-sign"></i> Retired
			</h4>
			<p>Please do not use this element.</p>
			<?php
				$post_objects = get_field('see_instead');
				if( $post_objects ):
			?>
			<p>Instead please use the following element(s):</p>
			<ul>
				<?php
					foreach( $post_objects as $post): // variable must be called $post (IMPORTANT)
					setup_postdata($post);
				?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
				<?php
					endforeach;
				?>
		  </ul>
			<?php
		  	wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly
				endif;
			?>
		</div>
	</div>
	<?php
		endif;
	?>
	<div class="row-fluid">
		<div class="span6">
			<h1>
				<?php
					the_title();
				?>
			</h1>
			<?php
				if( get_field('aka') ) {
					echo '<h3><acronym title="Also Known As">AKA</acronym>: ' . get_field('aka') . '</h3>';
				}
			?>
			<div class="well">
				<?php
					if(get_field('element_description')) {
						echo '<p>' . get_field('element_description') . '<p>';
					}
				?>
			</div> <!-- close well -->
		</div> <!-- close span6 -->
		<!-- ****************** ELEMENT DETAILS ****************** -->
		<div class="span3">
			<p>
				<h3 id="details">
					Element Details:
				</h3>
			</p>
			<div class="well">
				<h4>
					Owner:
				</h4>
				<p>
					<a href="mailto:<?= get_the_author_meta('user_email'); ?>"><?php the_author_meta('user_email'); ?></a>
				</p>
				<h4>
					Offically Updated:
				</h4>
				<p>
					<?php
						the_modified_date('F j, Y');
					?>
				</p>
				<h4>
					Element Status:
				</h4>
				<?php
					$element_status = get_post_meta($post->ID, '_status', true);
					//set values needed to change status
					if($element_status == 'In Development') {
					  echo '<span class="label label-warning">Draft</span>';
					}
					elseif($element_status == 'Live') {
					  echo '<span class="label label-success">Active</span>';
					}
					elseif($element_status == 'Review') {
					  echo '<span class="label label-success">Active</span>';
					}
					elseif($element_status == 'Retired') {
					  echo '<span class="label label-important">Retired</span>';
					}
					else {
						echo 'Contact Owner';
					}
				?>
			</div>	<!-- close well -->
		</div>  <!-- close span3-->
		<!-- ****************** SEE ALSO ****************** -->
		<div class="span3">
			<p>
				<h3 id="also">
					See Also:
				</h3>
			</p>
			<div class="well">
				<?php
					$post_objects = get_field('related_element');
					if( $post_objects ):
					echo '<h4>Related Elements</h4>';
				?>
				<ul>
					<?php
						foreach( $post_objects as $post): // variable must be called $post (IMPORTANT)
						setup_postdata($post);
					?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</li>
			    <?php
			    	endforeach;
			    ?>
			  </ul>
				<?php
					wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly
					endif;
				?>
				<br>
			</div>	<!-- close well -->
		</div>  <!-- close span3-->
	</div> <!-- close row -->
</div>
<!-- ****************** CODE ****************** -->
<h3 id="code">
	Live Preview:
</h3>
<div class="row-fluid">
	<div class="span12">
		<a type="button" class="popout" title="Open Preview Pane in Popup Window" onclick="display_popup()"><i class="icon-external-link"></i></a>
		<div class="well-preview">
			<iframe class="iframe" style="border: 0; width: 97%" scrolling="no" id="render">
				An iframe capable browser is required to view this content. Please upgrade your browser.
			</iframe>
		</div>
	</div> <!-- close span12-->
</div>
<div class="row-fluid">
	<div class="well-code" id="code-accordion">
		<div class="accordion" id="accordion2">
			<div class="accordion-heading" id='code-click'>
				<a class="accordion-toggle big collapsed" title="Collapse/Expand Code Snippets" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" style="text-decoration: none; text-align: center;">
					<span style="display: inline;"><span class="collapsed-hidden">Show</span><span class="collapsed-visible">Hide</span> Code Snippets <span class="collapsed-hidden"><i class="icon-caret-down"></i></span><span class="collapsed-visible"><i class="icon-caret-up"></i></span></span>
				</a>
			</div>
			<div id="collapseOne" class="accordion-body collapse in" style="height:auto; ">
				<div class="accordion-inner">
					<div class="row-fluid"> <!-- open row level 2 -->
						<div class="span6">
							<h3>
								HTML:
							</h3>
							<textarea id=html name=code>
								<?php
									the_field('html');
								?>
							</textarea>
						</div> <!-- close span6-->
						<div class="span6">
							<h3>
								CSS:
							</h3>
							<textarea id=css name=code>
								<?php
									the_field('css');
								?>
							</textarea>
						</div> <!-- close span6-->
					</div> <!-- close row level 2 -->
					<div class="row-fluid"> <!-- open row level 2 -->
						<div class="span12">
							<h3>
								JS:
							</h3>
							<textarea id=javascript name=code>
								<?php
									the_field('js');
								?>
							</textarea>
						</div> <!-- close span12-->
					</div> <!-- close row level 2 -->
				</div> <!-- close accordion-inner -->
			</div> <!-- close accordion-body collapse -->
		</div> <!-- close accordion -->
	</div> <!-- close well -->
</div>
<!-- ****************** DEPENDENCIES ****************** -->
<?php
	$rows = get_field( 'assets' );
 if ( $rows ):
?>
<div class="row-fluid"><!--Assets Row -->
	<div class="span12">
		<p>
			<h3 id="special">
				Code Dependencies &amp; Assets
			</h3>
		</p>
		<?php
			foreach($rows as $row):
		?>
		<div class="well">
			<div class="row-fluid"> <!-- Level 2 Column -->
				<div class="span2">
					<h4>
						<?= $row['asset_title']; ?>
					</h4>
				</div> <!-- Close span2 -->
				<div class="span6">
					<p>
						<?= $row['asset_description']; ?>
					</p>
				</div> <!-- Close span6 -->
				<div class="span4">
					<?php
						/*
						*  Get Asset meta data
						*  Return value = ID ( allows us to get more data about the image )
						*/
						$my_id = $row['asset_file'];
						$post_id = get_post($my_id, ARRAY_A);
						$authorid = $post_id['post_author'];
						$modified = $post_id['post_modified'];
					?>
					<a href="<?= $row['asset_file']; ?>" target="_blank" title="<?= $row['asset_title']; ?>">Download <?= $row['asset_title']; ?></a>
					<br>
					Added by:
					<?php
						$user_info = get_userdata($authorid);
						echo $user_info->first_name .  " " . $user_info->last_name;
					?>
					on
					<?php
						$date = date_create_from_format('Y-m-d H:i:s', $modified);
						echo date_format($date, 'F j, Y');
					?>
				</div> <!-- Close span4 -->
			</div> <!-- close Level 2 Column -->
		</div> <!-- close well -->
		<?php
			endforeach;
		?>
	</div> <!--close span12 -->
</div> <!-- close assets row -->
<?php
	endif;
?>
<!-- ****************** USE CASES ****************** -->
<?php
	$rows = get_field('use-case');
	if($rows):
?>
<div class="row-fluid">  <!--Use Case Row -->
	<div class="span12">
		<p>
			<h3 id="use">
				Use Cases:
			</h3>
		</p>
		<?php
			foreach($rows as $row):
		?>
		<div class="well">
			<div class="row-fluid"> <!-- Level 2 Column -->
				<div class="span2">
					<h4>
						<?= $row['title']; ?>
					</h4>
				</div><!-- Close span2 -->
				<div class="span6">
					<p>
						<?= $row['description']; ?>
					</p>
				</div> <!-- Close span6 -->
				<div class="span4">
					<?php
						/*
						*  Get Image Data
						*  Return value = ID ( allows us to get more data about the image )
						*/
						$attachment_id = $row['file'];
						$size = "medium"; // (thumbnail, medium, large, full or custom size)
						$image = wp_get_attachment_image_src( $attachment_id, $size );
						$attachment = get_post( $row['file'], OBJECT );
						$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
						$image_title = $attachment->post_title;
						$caption = $attachment->post_excerpt;
						$image_description = $attachment->post_content;
					?>
					<!-- Lightbox -->
					<a data-toggle="lightbox" href="#<?= $attachment_id; ?>"><img src="<?= $image[0]; ?>"></a>
					<div class="lightbox fade" id="<?= $attachment_id; ?>" style="display: none;">
						<div class='lightbox-content'>
							<img src="<?= $image[0]; ?>">
						</div>
						<button class="close">&times;</button>
					</div>
					<!-- Close lightbox -->
					<?php
						/*
						*  Get Attachement Data
						*  Return value = ID ( allows us to get more data about the image )
						*/
						$my_id = $row['file'];
						$post_id = get_post($my_id, ARRAY_A);
						$authorid = $post_id['post_author'];
						$modified = $post_id['post_modified'];
						if($image_title) echo '<h5>' . $image_title . '</h5>';
						if($image_title) echo $image_description . '<br>';
					?>
					Source: <a href="<?= $row['url']; ?>" target="_blank" title="<?= $alt; ?>">View</a>
					<br>
					Login: <?= $row['credentials']; ?>
					<br>
					Added by:
					<?php
						$user_info = get_userdata($authorid);
						echo $user_info->first_name .  " " . $user_info->last_name;
					?>
					on
					<?php
						$date = date_create_from_format('Y-m-d H:i:s', $modified);
						echo date_format($date, 'F j, Y');
					?>
				</div> <!-- Close span4 -->
			</div> <!-- close Level 2 Column -->
		</div> <!-- close well -->
		<?php
			endforeach;
		?>
	</div> <!--close span12 -->
</div> <!-- close use case row -->
<?php
	endif;
?>
<!-- ****************** SPECIAL CASE ****************** -->
<?php
	$rows = get_field('special-use-case');
	if($rows):
?>
<div class="row-fluid">  <!--Special Case Row -->
	<div class="span12">
		<p>
			<h3 id="use">
				Special Cases:
			</h3>
		</p>
		<?php
			foreach($rows as $row):
		?>
		<div class="well">
			<div class="row-fluid"> <!-- Level 2 Column -->
				<div class="span2">
					<h4>
						<?= $row['title']; ?>
					</h4>
				</div> <!-- Close span2 -->
				<div class="span6">
					<p>
						<?= $row['description']; ?>
					</p>
				</div> <!-- Close span6 -->
				<div class="span4">
					<?php
						/*
						*  Get Image Data
						*  Return value = ID ( allows us to get more data about the image )
						*/
						$attachment_id = $row['file'];
						$size = "medium"; // (thumbnail, medium, large, full or custom size)
						$image = wp_get_attachment_image_src( $attachment_id, $size );
						$attachment = get_post( $row['file'], OBJECT );
						$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
						$image_title = $attachment->post_title;
						$caption = $attachment->post_excerpt;
						$image_description = $attachment->post_content;
					?>
					<!-- Lightbox -->
					<a data-toggle="lightbox" href="#<?= $attachment_id; ?>"><img src="<?= $image[0]; ?>"></a>
					<div class="lightbox fade" id="<?= $attachment_id; ?>" style="display: none;">
						<div class='lightbox-content'>
							<img src="<?= $image[0]; ?>">
						</div>
						<button class="close">&times;</button>
					</div>
					<!-- Close lightbox -->
					<?php
						/*
						*  Get Attachement Data
						*  Return value = ID ( allows us to get more data about the image )
						*/
						$my_id = $row['file'];
						$post_id = get_post($my_id, ARRAY_A);
						$authorid = $post_id['post_author'];
						$modified = $post_id['post_modified'];
						if($image_title) echo '<h5>' . $image_title . '</h5>';
						if($image_title) echo $image_description . '<br>';
					?>
					Source: <a href="<?= $row['url']; ?>" target="_blank" title="<?= $alt; ?>">View</a>
					<br>
					Login: <?= $row['credentials']; ?>
					<br>
					Added by:
					<?php
						$user_info = get_userdata($authorid);
						echo $user_info->first_name .  " " . $user_info->last_name;
					?>
					on
					<?php
						$date = date_create_from_format('Y-m-d H:i:s', $modified);
						echo date_format($date, 'F j, Y');
					?>
				</div> <!-- Close span4 -->
			</div> <!-- close Level 2 Column -->
		</div> <!-- close well -->
		<?php
			endforeach;
		?>
	</div> <!--close span12 -->
</div> <!-- close Special Case Row-->
<?php
	endif;
?>
<!-- ****************** COMMENTS ****************** -->
<div class="row-fluid">
	<div class="span12">
		<p>
			<h3 id="comment">
				Comments:
			</h3>
		</p>
		<div class="well">
			<?php
				comments_template();
			?>
		</div> <!-- close well -->
	</div> <!-- close span12-->
</div> <!-- close row -->
<?php
	endwhile; // end of the loop.
	get_footer('element');
?>