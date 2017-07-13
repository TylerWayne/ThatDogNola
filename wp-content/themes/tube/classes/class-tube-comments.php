<?php
/**
 * Tube_Comments
 * 
 * Various functions to improve display and functionality of comments
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Comments {
  
  public static $instance;  
  
  public static function init() {
    
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Comments();
      
      return self::$instance;
      
  }


  // Constructor

  function __construct() {
    
    // enquescripts
    add_action( 'comment_form_before', array( $this, 'enque_comment_reply_js' ) );  
    
    // add class to comment reply
    add_filter('comment_reply_link', array( $this, 'custom_comment_reply_link_class' ) );
    
    // add class to next comments link
    add_filter('next_comments_link_attributes', array( $this, 'custom_prevnext_comments_link_class' ) );
    
    // add class to previous comments link
    add_filter('previous_comments_link_attributes', array( $this, 'custom_prevnext_comments_link_class' ) );
        
    // set a filter to move the comment field to the bottom
    add_filter( 'comment_form_fields', array( $this, 'move_comment_field_to_bottom' ) );
    
  }


  // enque comment reply JS 
  function enque_comment_reply_js() {
    
    // check conditions for including the JS
    if ( is_singular() && comments_open() && get_option('thread_comments') ):
      
      wp_enqueue_script( 'comment-reply' );
      
    endif;
    
  } 
  
  // customize the class for the comment reply link
  // via http://wordpress.stackexchange.com/a/99616
  
  function custom_comment_reply_link_class($link){
    
      $link = str_replace("class='comment-reply-link", "class='comment-reply-link btn btn-sm btn-default", $link);
      
      return $link;
  }
  
  
  // customize the class for the previous / next comments page link
  // via https://css-tricks.com/snippets/wordpress/add-class-to-links-generated-by-next_posts_link-and-previous_posts_link/#comment-1587903
  function custom_prevnext_comments_link_class( $class ) {
        
    // TODO: Move classes to an array, add filter, and sanitize classes      
    
    return 'class="btn btn-sm btn-default"';
      
  }
  
  
  // move the comment field to the bottom of the comment form
  // via http://wordpress.stackexchange.com/a/218324
  
  function move_comment_field_to_bottom( $fields ) {
    
    $comment_field = $fields['comment'];
    
    unset( $fields['comment'] );
    
    $fields['comment'] = $comment_field;
    
    return $fields;
      
  }

  
  // custom comments list
  function get_custom_comments_list($comment, $args, $depth) {
    
    // set the default args for the reply link  
    $default_reply_link_args = array(
      'depth' => $depth, 
      'max_depth' => $args['max_depth'], 
      'before' => '<div class="comment-links">', 
      'after' =>'</div>'
    );
    
    // merge an passed in arguments
    $reply_link_args = array_merge( $args, $default_reply_link_args );  
    
    // determine if avatars should be shown  
    $show_avatars = get_option( 'show_avatars' ) && ( $comment -> comment_type == '' );
    
    // start an output buffer
    ob_start();
    
    ?>
     
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?> >
      
      <article class="row clearfix" itemprop="comment" itemscope itemtype="http://schema.org/Comment">      
        
        <?php if ( $show_avatars ) : ?>
          <div class="col-xs-2 col-lg-1 col-thumbnail">
              <?php echo get_avatar( $comment, '150' ); ?>
          </div>            
          <div class="col-xs-10 col-lg-11 comment-content">
        <?php else: ?>             
          <div class="col-xs-12 comment-content">
        <?php endif; ?>
          
        <div class="col col-comment-meta-and-text">
                        
          <h5 class="comment-meta-author">
            <span itemprop="author">
              <?php printf('%s', get_comment_author_link()) ?><br />
            </span>
          </h5> 
              
            <p class="small text-muted comment-meta-date" itemprop="datePublished" content="<?php comment_date('Y-m-j' ); ?>">
              <time datetime="<?php echo comment_time('Y-m-j'); ?>"> <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" class="scroll-to-hash text-muted"><?php comment_date('M j, Y' ); ?> at <?php comment_time(); ?></a></time> <?php edit_comment_link(); ?>
            </p>

          <?php if ($comment->comment_approved == '0') : ?>
            <div class="alert alert-danger comment-moderation">
              <p class="text-danger small">
                  <?php _e('This comment is awaiting moderation.','tube') ?>    
              </p>
            </div>
          <?php else: ?>             
          <?php endif; ?>
                    
          <div class="comment-text">

              <?php comment_text() ?>            
            
          </div>  
          
          <?php comment_reply_link( $reply_link_args ); ?> 
           
        </div><!-- .col-comment-meta-and-text -->
        
      </div><!-- .comment-content -->            
      
    </article><!-- .comment -->
    
    <?php
    // NOTE: Closing </li> is added by wordpress
    
    // grab the output and return
    $output = ob_get_clean();
    
    return $output;
    
  } 
    


  // function to draw the custom comments list
  function custom_comments_list($comment, $args, $depth){
                    
    // get the comments list
    $comments_list = self :: get_custom_comments_list( $comment, $args, $depth );
    
    // show the comments list (already escaped)
    echo $comments_list;
    
  }
    

  
  // COMMENT FORM

  // get the custom comment form
  function get_custom_comment_form( ){

    global $user_identity;
    
    // get the current commenter
    $commenter = wp_get_current_commenter();
    
    // check if email is required
    $req = get_option( 'require_name_email' );
    
    // custom aria CSS stuff
    $aria_req = ( $req ? " aria-required='true'" : '' );
  
    
    $must_login_text = sprintf( 
      _x( 'You must be <a href="%s">logged in</a> to post a comment.', 'Comment form: login required link and message', 'tube' ),
      esc_url( wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) )
    );
    
    $already_logged_in_text = sprintf(
      _x( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s">Log out?</a>', 'Comment form: already logged in message and logout link', 'tube' ),
      esc_url( admin_url( 'profile.php' ) ),
      $user_identity,
      esc_url( wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) )
    );

    
    $required_text = sprintf( 
      _x('Required fields are marked %s', 'Comment form: required fields help text, %s is replaced with *', 'tube'), 
      '<span class="required">*</span>' 
    ); 
    
    
    $comment_notes_before =  _x( 'Your email address will not be published.', 'Comment form: notes before comment field', 'tube' ) ; 
        
    
    $comment_notes_after =  sprintf(
      _x( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'Comment form: notes after comment field', 'tube' ),
      ' <code>' . allowed_tags() . '</code>'
    ); 
        
        
    // setup the form fields
    $fields =  array(
  
      'author' =>
        '<p class="comment-form-author"><label for="author">' . _x( 'Name', 'Comment form: name field label', 'tube' ) .
        ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
        '<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
        '" size="30"' . $aria_req . ' /></p>',
    
      'email' =>
        '<p class="comment-form-email"><label for="email">' . _x( 'Email', 'Comment form: email field label', 'tube' ) .
        ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
        '<input class="form-control" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" size="30"' . $aria_req . ' /></p>',
    
      'url' =>
        '<p class="comment-form-url"><label for="url">' . _x( 'Website', 'Comment form: website field label', 'tube' ) . '</label>' .
        '<input class="form-control" id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
        '" size="30" /></p>',
    );
  
    $args = array(
      'id_form'           => 'commentform',
      'class_form'      => 'comment-form',
      'id_submit'         => 'submit',
      'class_submit'      => 'submit btn btn-primary',
      'name_submit'       => 'submit',
      'title_reply'       => _x( 'Add a Comment', 'Comment form: reply form title', 'tube' ),
      'title_reply_to'    => _x( 'Reply to %s', 'Comment form: reply to form title', 'tube' ),
      'cancel_reply_link' => _x( 'Cancel Reply', 'Comment form: cancel reply button  text', 'tube' ),
      'label_submit'      => _x( 'Post Comment', 'Comment form: submit comment button label', 'tube' ),
      'format'            => 'xhtml',
    
      'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'Comment form: comment field label', 'tube' ) . ' <span class="required">*</span></label><textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
        '</textarea></p>',
    
      'must_log_in' => '<p class="small must-log-in">' . $must_login_text . '</p>',
    
      'logged_in_as' => '<p class="small logged-in-as">' . $already_logged_in_text . '</p>',
        
      'comment_notes_before' => '<p class="comment-notes">' .$comment_notes_before . ( $req ? $required_text : '' ) .
        '</p>',
    
      'comment_notes_after' => '<p class="form-allowed-tags">' . $comment_notes_after . '</p>',
        
      'fields' => $fields,
    
    );
      
    // display the comment form
    comment_form($args); 
  
  }


  // draw the custom comment form
  function custom_comment_form( ){
                    
    // get the comment form
    $comment_form = self :: get_custom_comment_form();
    
    // echo out the comments form
    echo wp_kses_post( $comment_form );
    
  }
    
    
    
    
}