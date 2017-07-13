<?php
/**
 * The search form template
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
 
$search_placeholder = Tube_Theme::$tube_labels -> get_search_placeholder( );
?>

<form class="tube-search-form typeahead-search-form" method="GET" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">          
  <div class="form-group">
    <input type="text" class="form-control input-lg" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" placeholder="<?php echo esc_attr( $search_placeholder ); ?>" data-provide="typeahead" data-endpoint="<?php echo esc_url( admin_url('admin-ajax.php?action=tube_typeahead&q=') ); ?>" data-callback="tube_typeahead_callback" autocomplete="off">
      <button type="submit" class="btn btn-link"><i class="fa fa-fw fa-search"></i></button>        
  </div>         
</form>