<?php
/**
 * The comments template
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */

// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
  die ('Please do not load this page directly. Thanks!');

if ( post_password_required() ):
  return;
endif;
      
// local var for post_id
$post_id = get_the_id();
?>

<div id="comments-wrap">
  
  <?php   
  // see if there are any comments
  if ( have_comments() ) : 
        
    // Get the comments by type (e.g. comments, pingbacks)
    $comments_by_type = separate_comments($comments);

    // see if there are any comments
    if ( ! empty($comments_by_type['comment']) ) :
          
      // get just the comments
      $post_comments = get_comments(array('type' => 'comment','post_id' => $post_id));
      
      // setup a counter
      $comments_count = 0;
      
      // get count of approved comments
      foreach ( $post_comments as $post_comment ):
      
        if ( $post_comment -> comment_approved ):
          $comments_count++;
        endif;
        
      endforeach;      
      
      // create a label
      $comments_count_label = _nx( 
        "Comment", 
        "Comments", 
        $comments_count,
        'Comments header label based on comment count', 
        'tube'
      );
      
      $add_comment_button_label = _x("Add a Comment", 'Comment form anchor button', 'tube');
      
      $older_comments_label = '<i class="fa fa-angle-left"></i>&nbsp; ' . _x('Older Comments', 'Comment pagination',  'tube');
      
      $newer_comments_label =  _x('Newer Comments', 'Comment pagination', 'tube') . ' &nbsp;<i class="fa fa-angle-right"></i>';
         
      // output the comments
      ?>
      
       
       <div class="clearfix">
         
         <h3 id="comments" class="pull-left">
          <?php echo wp_kses_post( $comments_count . ' ' . $comments_count_label ); ?>
         </h3>
            
        <?php  if ( comments_open() ) : ?>
        <small class="pull-right">
          <a href="#respond" class="add-a-comment-link btn btn-primary scroll-to-hash"><?php echo $add_comment_button_label; ?></a>
        </small>         
        <?php  endif; ?>
          
       </div>
      
      <hr class="highlight" />
      
      <ol class="commentlist">
        <?php 
        global $tube_theme;
        
        $comments_list_args = array(
          'type' => 'comment',
          'callback' => array( $tube_theme::$tube_comments, 'custom_comments_list' )
        );
       // custom_comments_list
        wp_list_comments( $comments_list_args );
        ?>
      </ol>
    <?php endif; ?>
    
    <?php 
    
    // PINGS
    if ( ! empty($comments_by_type['pings']) ) :
    
      // get just the pings
      $post_pingbacks = get_comments(array('type' => 'pings','post_id' => $post_id));
      
      // get number of pingbacks
      $pingbacks_count = count( $post_pingbacks );
      
      // create a label
      $pingbacks_count_label = _n( 'Pingback', 'Pingbacks', $pingbacks_count, 'tube' );
      
      // output the pings
      ?>

      <h3 id="pings">
        <?php echo wp_kses_post( $pingbacks_count . ' ' . $pingbacks_count_label ); ?>
      </h3>
      
      <hr class="highlight" />
      
      <ol class="pinglist">
        <?php         
        $pings_list_args = array(
          'type' => 'pings',
          'callback' => array( $tube_theme::$tube_comments, 'custom_comments_list' )
        );
        wp_list_comments( $pings_list_args ); 
        ?>
      </ol>
    <?php endif; ?>
    
    
        
    <?php if ( ! empty($comments_by_type['comment']) || ! empty($comments_by_type['pings']) ) : ?>
      <nav class="pagination-wrap pagination-prevnext" id="comment-nav">
        <ul class="list-inline no-bottom">
            <li class="previous"><?php previous_comments_link( $older_comments_label ) ?></li>
            <li class="next"><?php next_comments_link($newer_comments_label ) ?></li>
        </ul>
      </nav>
    <?php endif; ?>
    
  <?php
  
  else : 
  
    // this is displayed if there are no comments so far    
    if ( comments_open() ) : 
      
      // if comments are open, but there are no comments.
      do_action( 'tube_comments_open_but_empty' );      
     
    else : 
    
      // comments are closed    
      do_action( 'tube_comments_closed' );

     endif;

  endif; 
  
  // COMMENT FORM
  
  // make sure comments are open
  if ( comments_open() ) :
    
      Tube_Theme::$tube_comments -> custom_comment_form( );
    
  endif; 
  ?>
  
</div><!-- #comments-wrap -->
