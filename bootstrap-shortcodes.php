<?php
/*
Plugin Name: Bootstrap 3 Shortcodes
Plugin URI: http://wp-snippets.com/freebies/bootstrap-shortcodes or https://github.com/filipstefansson/bootstrap-shortcodes
Description: The plugin adds a shortcodes for all Bootstrap elements.
Version: 3.0.3.5
Author: Filip Stefansson, Simon Yeldon, and Michael W. Delaney
Author URI: 
License: GPL2
*/

/*  Copyright 2012  Filipstefansson  (email : filip.stefansson@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* ============================================================= */

require_once(dirname(__FILE__) . '/includes/defaults.php');
require_once(dirname(__FILE__) . '/includes/functions.php');
require_once(dirname(__FILE__) . '/includes/actions-filters.php');

function bootstrap_shortcodes_scripts()  { 

  // Bootstrap tooltip js
  wp_enqueue_script( 'bootstrap-shortcodes-tooltip', BS_SHORTCODES_URL . 'js/bootstrap-shortcodes-tooltip.js', array( 'jquery' ), false, true );
  
  // Bootstrap popover js
  wp_enqueue_script( 'bootstrap-shortcodes-popover', BS_SHORTCODES_URL . 'js/bootstrap-shortcodes-popover.js', array( 'jquery' ), false, true );

  // Bootstrap scrollspy js
  wp_enqueue_script( 'bootstrap-shortcodes-scrollspy', BS_SHORTCODES_URL . 'js/bootstrap-shortcodes-scrollspy.js', array( 'jquery' ), false, true );

}
add_action( 'wp_enqueue_scripts', 'bootstrap_shortcodes_scripts', 9999 ); // Register this fxn and allow Wordpress to call it automatcally in the header

// Begin Shortcodes
class BoostrapShortcodes {

  function __construct() {
    add_action( 'init', array( $this, 'add_shortcodes' ) );
  }

  /*--------------------------------------------------------------------------------------
    *
    * add_shortcodes
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function add_shortcodes() {

    $shortcodes = array(
      'alert', 
      'badge', 
      'breadcrumb', 
      'breadcrumb-item', 
      'button', 
      'button-group', 
      'button-toolbar', 
      'caret', 
      'carousel', 
      'carousel-item', 
      'code', 
      'collapse', 
      'collapsibles', 
      'column', 
      'container', 
      'divider', 
      'dropdown', 
      'dropdown-item', 
      'emphasis', 
      'icon', 
      'icon_white', 
      'img', 
      'jumbotron', 
      'label', 
      'lead', 
      'list-group', 
      'list-group-item', 
      'list-group-item-heading', 
      'list-group-item-text', 
      'media', 
      'media-body', 
      'media-object', 
      'modal', 
      'modal-footer',
      'nav', 
      'nav-item', 
      'page-header', 
      'panel', 
      'popover', 
      'progress', 
      'progress-bar', 
      'responsive', 
      'row', 
      'span', 
      'tab', 
      'table', 
      'table-wrap', 
      'tabs', 
      'thumbnail', 
      'tooltip', 
      'well', 
    );

    foreach ( $shortcodes as $shortcode ) {

      $function = 'bs_' . str_replace( '-', '_', $shortcode );
      add_shortcode( $shortcode, array( $this, $function ) );
      
    }

  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_button
    *
    * @author Filip Stefansson, Nicolas Jonas
    * @since 1.0
    * //DW mod added xclass var
    *-------------------------------------------------------------------------------------*/
  function bs_button( $atts, $content = null ) {

    extract( shortcode_atts( array(
      "type"     => false,
      "size"     => false,
      "block"    => false,
      "dropdown" => false,
      "link"     => '',
      "target"   => false,
      "xclass"   => false,
      "title"    => false,
      "data"     => false
    ), $atts ) );

    $class  = 'btn';
    $class .= ( $type )     ? ' btn-' . $type : ' btn-default';
    $class .= ( $size )     ? ' btn-' . $size : '';
    $class .= ( $block )    ? ' btn-block' : '';
    $class .= ( $dropdown ) ? ' dropdown-toggle' : '';
    $class .= ( $xclass )   ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes( $data );

    return sprintf( 
      '<a href="%s" class="%s"%s%s%s>%s</a>',
      esc_url( $link ),
      esc_attr( $class ),
      ( $target )     ? sprintf( ' target="%s"', esc_attr( $target ) ) : '',
      ( $title )      ? sprintf( ' title="%s"',  esc_attr( $title ) )  : '',
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );

  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_button_group
    *
    * @author M. W. Delaney
    *
    *-------------------------------------------------------------------------------------*/
  function bs_button_group( $atts, $content = null ) {
      
     extract(shortcode_atts(array(
        "size"      => false,
        "vertical"  => false,
        "justified" => false,
        "dropup"    => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'btn-group';
    $class .= ( $size )         ? ' btn-group-' . $size : '';
    $class .= ( $vertical )     ? ' btn-group-vertical' : '';
    $class .= ( $justified )    ? ' btn-group-justified' : '';
    $class .= ( $dropup )       ? ' dropup' : '';
    $class .= ( $xclass )       ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_button_toolbar
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_button_toolbar( $atts, $content = null ) {
        
     extract(shortcode_atts(array(
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'btn-toolbar';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }    
    
 /*--------------------------------------------------------------------------------------
    *
    * bs_caret
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_caret( $atts, $content = null ) {
      
     extract(shortcode_atts(array(
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'caret';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<span class="%s"%s>%s</span>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }    

 /*--------------------------------------------------------------------------------------
    *
    * bs_container
    *
    * @author Robin Wouters
    * @since 3.0.3.3
    *
    *-------------------------------------------------------------------------------------*/ 
  function bs_container( $atts, $content = null ) {
      
     extract(shortcode_atts(array(
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'container';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }    
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_dropdown
    *
    * @author M. W. Delaney
    *
    *-------------------------------------------------------------------------------------*/
  function bs_dropdown( $atts, $content = null ) {
      
     extract(shortcode_atts(array(
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'dropdown-menu';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<ul class="%s"%s role="menu">%s</ul>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_dropdown_item
    *
    * @author M. W. Delaney
    *
    *-------------------------------------------------------------------------------------*/
  function bs_dropdown_item( $atts, $content = null ) {
      
    extract(shortcode_atts(array(
        "link" => false,
        "xclass" => false,
        "data" => false
     ), $atts));

    $class  = '';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';

    $data_props = $this->parse_data_attributes($data);

    return sprintf( 
      '<li><a href="%s" class="%s"%s>%s</a></li>',
      esc_url( $link ),
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_dropdown_divider
    *
    * @author M. W. Delaney
    *
    *-------------------------------------------------------------------------------------*/
  function bs_dropdown_divider( $atts, $content = null ) {
      
    extract(shortcode_atts(array(
        "xclass" => false,
        "data" => false
     ), $atts));

    $class  = 'divider';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';

    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<li class="%s"%s>%s</li>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_nav
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_nav( $atts, $content = null ) {
      
     extract(shortcode_atts(array(
        "type"      => false,
        "stacked"   => false,
        "justified" => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'nav';
    $class .= ( $type )         ? ' nav-' . $type : ' nav-tabs';
    $class .= ( $stacked )      ? ' nav-stacked' : '';
    $class .= ( $justified )    ? ' nav-justified' : '';
    $class .= ( $xclass )       ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<ul class="%s"%s>%s</ul>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_nav_item
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_nav_item( $atts, $content = null ) {

    extract( shortcode_atts( array(
           "link"     => false,
           "active"   => false,
           "disabled" => false,
           "dropdown" => false,
           "xclass"   => false,
           "data"     => false,
         ), $atts ) );
     
         $li_classes  = '';
         $li_classes .= ( $dropdown ) ? 'dropdown' : '';
         $li_classes .= ( $active )   ? ' active' : '';
         $li_classes .= ( $disabled ) ? ' disabled' : '';
     
         $a_classes  = '';
         $a_classes .= ( $dropdown ) ? ' dropdown-toggle' : '';
         $a_classes .= ( $xclass )   ? ' ' . $xclass : '';
     
         $data_props = $this->parse_data_attributes( $data );
     
         # Wrong idea I guess ....
         #$pattern = ( $dropdown ) ? '<li%1$s><a href="%2$s"%3$s%4$s%5$s></a>%6$s</li>' : '<li%1$s><a href="%2$s"%3$s%4$s%5$s>%6$s</a></li>';
     
         //* If we have a dropdown shortcode inside the content we end the link before the dropdown shortcode, else all content goes inside the link
         $content = ( $dropdown ) ? str_replace( '[dropdown]', '</a>[dropdown]', $content ) : $content . '</a>';
     
         return sprintf(
           '<li%1$s><a href="%2$s"%3$s%4$s%5$s>%6$s</li>',
           ( ! empty( $li_classes ) ) ? sprintf( ' class="%s"', esc_attr( $li_classes ) ) : '',
           esc_html( $link ),
           ( ! empty( $a_classes ) )  ? sprintf( ' class="%s"', esc_attr( $a_classes ) )  : '',
           ( $dropdown )   ? ' data-toggle="dropdown"' : '',
           ( $data_props ) ? ' ' . $data_props : '',
           do_shortcode( $content )
         );
     
      }
          
  /*--------------------------------------------------------------------------------------
    *
    * bs_alert
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_alert($atts, $content = null) {

     extract(shortcode_atts(array(
        "type"          => false,
        "dismissable"   => false,
        "xclass"        => false,
        "data"          => false
     ), $atts));
      
    $class  = 'alert';
    $class .= ( $type )         ? ' alert-' . $type : ' alert-success';
    $class .= ( $dismissable )  ? ' alert-dismissable' : '';
    $class .= ( $xclass )       ? ' ' . $xclass : '';
      
    $dismissable = ( $dismissable ) ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s"%s>%s%s</div>',
      esc_attr( $class ),
      ( $data_props )  ? ' ' . $data_props : '',
      $dismissable,
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_progress
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_progress($atts, $content = null) {

     extract(shortcode_atts(array(
        "striped"   => false,
        "animated"  => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'progress';
    $class .= ( $striped )  ? ' progress-striped' : '';
    $class .= ( $animated )  ? ' progress-animated' : '';
    $class .= ( $xclass )       ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props )  ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_progress_bar
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_progress_bar($atts, $content = null) {

     extract(shortcode_atts(array(
        "type"      => false,
        "percent"   => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'progress-bar';
    $class .= ( $type )  ? ' progress-bar-' . $type : '';
    $class .= ( $xclass )       ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s" role="progressbar" %s%s>%s</div>',
      esc_attr( $class ),
      ( $percent )      ? ' aria-value="' . $percent . '" aria-valuemin="0" aria-valuemax="100" style="width: '. $percent . '%;"' : '',
      ( $data_props )   ? ' ' . $data_props : '',
      ( $percent )      ? '<span class="sr-only">' . $percent . ' Complete</span>' : ''

    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_code
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_code($atts, $content = null) {
     extract(shortcode_atts(array(
        "inline" => false,
        "scrollable" => false,
		"xclass" => false,
		"data" => false
     ), $atts));
	  $data_props = $this->parse_data_attributes($data);
    if($inline) {
      $return = '<code';
	  $return .= ($xclass) ? ' class="' . $xclass . '"' : '';
	  $return .= ($data_props) ? ' ' . $data_props : '';
	  $return .= '>' . $content . '</code>';
    } else {
      $return  = '<pre';
      $classes = ($scrollable) ? 'pre-scrollable': '';
	  $classes .= ($xclass) ? ' ' . $xclass : '';
	  $return .= (!empty($classes)) ? 'class="' . $classes . '"' :'';
	  $return .= ($data_props) ? ' ' . $data_props : '';
      $return .= '>' . $content . '</pre>';
    }
    return $return;
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_span
    *
    * @author Filip Stefansson
    * @since 1.0
    * @depricated Bootstrap 3 uses col-[xs|sm|md|lg]-[1-12]
    * @see bs_column
    *-------------------------------------------------------------------------------------*/
  function bs_span( $atts, $content = null ) {
    extract(shortcode_atts(array(
      "size" => 'size'
    ), $atts));

    $return = '<div class="span' . $size . '">' . do_shortcode( $content ) . '</div>';
    return $return;
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_row
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_row( $atts, $content = null ) {
      
     extract(shortcode_atts(array(
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'row';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_column
    *
    * @author Simon Yeldon
    * @since 1.0
    * @todo pull and offset
    *-------------------------------------------------------------------------------------*/
  function bs_column( $atts, $content = null ) {
      
     extract(shortcode_atts(array(
		"lg"          => false,
		"md"          => false,
		"sm"          => false,
		"xs"          => false,
		"offset_lg"   => false,
		"offset_md"   => false,
		"offset_sm"   => false,
		"offset_xs"   => false,
		"pull_lg"     => false,
		"pull_md"     => false,
		"pull_sm"     => false,
		"pull_xs"     => false,
		"push_lg"     => false,
		"push_md"     => false,
		"push_sm"     => false,
		"push_xs"     => false,
		"xclass"      => false,
        "data"        => false
     ), $atts));

    $class  = '';
    $class .= ( $lg )             ? ' col-lg-' . $lg : '';
    $class .= ( $md )             ? ' col-md-' . $md : '';
    $class .= ( $sm )             ? ' col-sm-' . $sm : '';
    $class .= ( $xs )             ? ' col-xs-' . $xs : '';
    $class .= ( $offset_lg )      ? ' col-lg-offset-' . $offset_lg : '';
    $class .= ( $offset_md )      ? ' col-md-offset-' . $offset_md : '';
    $class .= ( $offset_sm )      ? ' col-sm-offset-' . $offset_sm : '';
    $class .= ( $offset_xs )      ? ' col-xs-offset-' . $offset_xs : '';
    $class .= ( $pull_lg )        ? ' col-lg-pull-' . $pull_lg : '';
    $class .= ( $pull_md )        ? ' col-md-pull-' . $pull_md : '';
    $class .= ( $pull_sm )        ? ' col-sm-pull-' . $pull_sm : '';
    $class .= ( $pull_xs )        ? ' col-xs-pull-' . $pull_xs : '';
    $class .= ( $push_lg )        ? ' col-lg-push-' . $push_lg : '';
    $class .= ( $push_md )        ? ' col-md-push-' . $push_md : '';
    $class .= ( $push_sm )        ? ' col-sm-push-' . $push_sm : '';
    $class .= ( $push_xs )        ? ' col-xs-push-' . $push_xs : '';
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_list_group
    *
    * @author M. W. Delaney
    *
    *-------------------------------------------------------------------------------------*/
  function bs_list_group( $atts, $content = null ) {

     extract(shortcode_atts(array(
		"linked" => false,
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'list-group';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<%1$s class="%2$s"%3$s>%4$s</%1$s>',
      ( $linked ) ? 'div' : 'ul',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }      

  /*--------------------------------------------------------------------------------------
    *
    * bs_list_group_item
    *
    * @author M. W. Delaney
    *
    *-------------------------------------------------------------------------------------*/
  function bs_list_group_item( $atts, $content = null ) {

     extract(shortcode_atts(array(
		"link"    => false,
		"active"  => false,
		"xclass"  => false,
        "data"    => false
     ), $atts));

    $class  = 'list-group-item';
    $class .= ( $active )   ? ' active' : '';
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<%1$s %2$s class="%3$s"%4$s>%5$s</%1$s>',
      ( $link ) ? 'a' : 'li',
      ( $link ) ? 'href="' . esc_url( $link ) . '"' : '',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_list_group_item_heading
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_list_group_item_heading( $atts, $content = null ) {

     extract(shortcode_atts(array(
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'list-group-item-heading';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<h4 class="%s"%s>%s</h4>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_list_group_item_text
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_list_group_item_text( $atts, $content = null ) {
      
     extract(shortcode_atts(array(
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'list-group-item-text';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<p class="%s"%s>%s</p>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_breadcrumb
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_breadcrumb( $atts, $content = null ) {

     extract(shortcode_atts(array(
		"xclass" => false,
        "data"   => false
     ), $atts));

    $class  = 'breadcrumb';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';
      
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<ol class="%s"%s>%s</ol>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }  

  /*--------------------------------------------------------------------------------------
    *
    * bs_breadcrumb_item
    *
    * @author M. W. Delaney
    *
    *-------------------------------------------------------------------------------------*/
  function bs_breadcrumb_item( $atts, $content = null ) {

    extract(shortcode_atts(array(
        "link" => false,
        "xclass" => false,
        "data" => false
     ), $atts));

    $class  = '';      
    $class .= ( $xclass )   ? ' ' . $xclass : '';

    return sprintf( 
      '<li><a href="%s" class="%s"%s>%s</a></li>',
      esc_url( $link ),
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_label
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_label( $atts, $content = null ) {

     extract(shortcode_atts(array(
        "type"      => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'label';
    $class .= ( $type )     ? ' label-' . $type : ' label-default';
    $class .= ( $xclass )   ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<span class="%s"%s>%s</span>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_badge
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_badge( $atts, $content = null ) {

     extract(shortcode_atts(array(
        "right"      => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'badge';
    $class .= ( $right )    ? ' pull-right' : '';
    $class .= ( $xclass )   ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<span class="%s"%s>%s</span>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_icon
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_icon( $atts, $content = null ) {

     extract(shortcode_atts(array(
        "type"      => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'glyphicon';
    $class .= ( $type )     ? ' glyphicon-' . $type : '';
    $class .= ( $xclass )   ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<span class="%s"%s>%s</span>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * simple_table
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------
  function bs_table( $atts ) {
      extract( shortcode_atts( array(
          'cols' => 'none',
          'data' => 'none',
          'bordered' => false,
          'striped' => false,
          'hover' => false,
          'condensed' => false,
      ), $atts ) );
      $cols = explode(',',$cols);
      $data = explode(',',$data);
      $total = count($cols);
      $return  = '<table class="table ';
      $return .= ($bordered) ? 'table-bordered ' : '';
      $return .= ($striped) ? 'table-striped ' : '';
      $return .= ($hover) ? 'table-hover ' : '';
      $return .= ($condensed) ? 'table-condensed ' : '';
      $return .='"><tr>';
      foreach($cols as $col):
          $return .= '<th>'.$col.'</th>';
      endforeach;
      $output .= '</tr><tr>';
      $counter = 1;
      foreach($data as $datum):
          $return .= '<td>'.$datum.'</td>';
          if($counter%$total==0):
              $return .= '</tr>';
          endif;
          $counter++;
      endforeach;
          $return .= '</table>';
      return $return;
  }
  */

  /*--------------------------------------------------------------------------------------
    *
    * bs_table_wrap
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_table_wrap( $atts, $content = null ) {
      extract( shortcode_atts( array(
          'bordered'  => false,
          'striped'   => false,
          'hover'     => false,
          'condensed' => false,
		  'xclass'    => false,
	      'data'      => false
	  ), $atts));
    $classes  = 'table';
    $classes .= ($bordered)     ? ' table-bordered' : '';
    $classes .= ($striped)      ? ' table-striped' : '';
    $classes .= ($hover)        ? ' table-hover' : '';
    $classes .= ($condensed)    ? ' table-condensed' : '';
	$classes .= ($xclass)       ? ' ' . $xclass : '';	
    $dom = new DOMDocument;
    $dom->loadXML($content);
    $dom->documentElement->setAttribute('class', $dom->documentElement->getAttribute('class') . ' ' . $classes);
	if($data) { 
          $data = explode('|',$data);
          foreach($data as $d):
            $d = explode(',',$d);    
            $dom->documentElement->setAttribute('data-'.$d[0],trim($d[1]));
          endforeach;
    }
    $return = $dom->saveXML();
    return $return;
  }


  /*--------------------------------------------------------------------------------------
    *
    * bs_well
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    * Options:
    *   size: sm = small, lg = large
    *
    *-------------------------------------------------------------------------------------*/
    function bs_well( $atts, $content = null ) {

     extract(shortcode_atts(array(
        "size"      => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'well';
    $class .= ( $size )     ? ' well-' . $size : '';
    $class .= ( $xclass )   ? ' ' . $xclass : '';
    
    $data_props = $this->parse_data_attributes($data);
      
    return sprintf( 
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_panel
    *
    * @author M. W. Delaney
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_panel( $atts, $content = null ) {

     extract(shortcode_atts(array(
        "title"     => false,
        "heading"   => false,
        "type"      => false,
        "footer"    => false,
        "xclass"    => false,
        "data"      => false
     ), $atts));
      
    $class  = 'panel';
    $class .= ( $type )     ? ' panel-' . $type : ' panel-default';
    $class .= ( $xclass )   ? ' ' . $xclass : '';

    $data_props = $this->parse_data_attributes($data);

    $footer = ( $footer ) ? '<div class="panel-footer">' . $footer . '</div>' : '';
            
    return sprintf( 
      '<div class="%s"%s>%s<div class="panel-body">%s</div>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      ( $heading ) ? sprintf( '<div class="panel-heading">%s%s%s</div>', 
                                   ( $title ) ? '<h3 class="panel-title">' : '',
                                   esc_html( $heading ),
                                   ( $title ) ? '</h3>' : ''
                            ) : '',
      do_shortcode( $content ),
      ( $footer ) ? ' ' . $footer : ''
    );
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_tabs
    *
    * @author Filip Stefansson
    * @since 1.0
    * Modified by TwItCh twitch@designweapon.com
    * Now acts a whole nav/tab/pill shortcode solution!
    *-------------------------------------------------------------------------------------*/
  function bs_tabs( $atts, $content = null ) {

    if( isset($GLOBALS['tabs_count']) )
      $GLOBALS['tabs_count']++;
    else
      $GLOBALS['tabs_count'] = 0;

    $defaults = array(
		'xclass'	=> false,
		'data'		=> false
	);
    extract( shortcode_atts( $defaults, $atts ) );
	$data_props = $this->parse_data_attributes($data);

    // Extract the tab titles for use in the tab widget.
    preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

    $tab_titles = array();
    if( isset($matches[1]) ){ $tab_titles = $matches[1]; }

    $return = '';

    if( count($tab_titles) ){
      $return .= '<ul class="nav nav-tabs';
	  $return .= ($xclass) ? ' ' . $xclass : '';
	  $return .= '"';
	  $return .= ($data_props) ? ' ' . $data_props : '';
	  $return .= ' id="custom-tabs-'. rand(1, 100) .'">';

      $i = 0;
      foreach( $tab_titles as $tab ){
        if($i == 0)
          $return .= '<li class="active">';
        else
          $return .= '<li>';

        $return .= '<a href="#custom-tab-' . $GLOBALS['tabs_count'] . '-' . sanitize_title( $tab[0] ) . '"  data-toggle="tab">' . $tab[0] . '</a></li>';
        $i++;
      }

        $return .= '</ul>';
        $return .= '<div class="tab-content">';
        $return .= do_shortcode( $content );
        $return .= '</div>';
    } else {
      $return .= do_shortcode( $content );
    }

    return $return;
  }




  /*--------------------------------------------------------------------------------------
    *
    * bs_tab
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_tab( $atts, $content = null ) {

    if( !isset($GLOBALS['current_tabs']) ) {
      $GLOBALS['current_tabs'] = $GLOBALS['tabs_count'];
      $state = 'active';
    } else {

      if( $GLOBALS['current_tabs'] == $GLOBALS['tabs_count'] ) {
        $state = '';
      } else {
        $GLOBALS['current_tabs'] = $GLOBALS['tabs_count'];
        $state = 'active';
      }
    }

    $defaults = array(
		'title' => 'Tab',
		'xclass'	=> false,
		'data'		=> false
	);
    extract( shortcode_atts( $defaults, $atts ) );
	$data_props = $this->parse_data_attributes($data);

    $return = '<div id="custom-tab-' . $GLOBALS['tabs_count'] . '-'. sanitize_title( $title ) .'" class="tab-pane ' . $state;
	$return .= ($xclass) ? ' ' . $xclass : '';
	$return .= '"';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '>'. do_shortcode( $content ) .'</div>';
    return $return;
  }




  /*--------------------------------------------------------------------------------------
    *
    * bs_collapsibles
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_collapsibles( $atts, $content = null ) {

    if( isset($GLOBALS['collapsibles_count']) )
      $GLOBALS['collapsibles_count']++;
    else
      $GLOBALS['collapsibles_count'] = 0;

    $defaults = array(
		'xclass'	=> false,
		'data'		=> false
	);
    extract( shortcode_atts( $defaults, $atts ) );
	$data_props = $this->parse_data_attributes($data);

    // Extract the tab titles for use in the tab widget.
    preg_match_all( '/collapse title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

    $tab_titles = array();
    if( isset($matches[1]) ){ $tab_titles = $matches[1]; }

    $return = '';

    if( count($tab_titles) ){
      $return .= '<div class="panel-group';
	  $return .= ($xclass) ? ' ' . $xclass : '';
	  $return .= '"';
	  $return .= ($data_props) ? ' ' . $data_props : '';
	  $return .= ' id="accordion-' . $GLOBALS['collapsibles_count'] . '">';
      $return .= do_shortcode( $content );
      $return .= '</div>';
    } else {
      $return .= do_shortcode( $content );
    }

    return $return;
  }


  /*--------------------------------------------------------------------------------------
    *
    * bs_collapse
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_collapse( $atts, $content = null ) {

    if( !isset($GLOBALS['current_collapse']) )
      $GLOBALS['current_collapse'] = 0;
    else
      $GLOBALS['current_collapse']++;

    extract(shortcode_atts(array(
      "title" => '',
      "type" => 'default',
      "active" => false,
	  "xclass" => false,
	  "data" => false
    ), $atts));
	 $data_props = $this->parse_data_attributes($data);

    if ($active)
      $active = 'in';

    $return = '<div class="panel panel-' . $type;
	$return .= ($xclass) ? ' ' . $xclass : '';
	$return .= '"';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '><div class="panel-heading"><h3 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-' . $GLOBALS['collapsibles_count'] . '" href="#collapse_' . $GLOBALS['current_collapse'] . '_'. sanitize_title( $title ) .'">' . $title . '</a></h3></div><div id="collapse_' . $GLOBALS['current_collapse'] . '_'. sanitize_title( $title ) .'" class="panel-collapse collapse ' . $active . '"><div class="panel-body">' . do_shortcode($content) . ' </div></div></div>';
    return $return;
  }
    
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_carousel
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_carousel( $atts, $content = null ) {
    extract(shortcode_atts(array(
    "interval" => "5000",
    "pause" => false,
    "wrap" => false,
    "xclass" => false,
    "data" => false,
    ), $atts));
      
    if( isset($GLOBALS['carousel_count']) )
      $GLOBALS['carousel_count']++;
    else
      $GLOBALS['carousel_count'] = 0;
    
    $GLOBALS['carousel_active'] = true;
      
	$data_props = $this->parse_data_attributes($data);

    $i = 0;
    $indicator_count = substr_count($content,'<img');
      while($i < $indicator_count) {
        $indicators .= '<li data-target="#carousel-' . $GLOBALS['carousel_count'] . '" data-slide-to="' . $i . '"';
        $indicators .= ($i == 0) ? 'class="active"' : '';
        $indicators .= '></li>';
        $i++;
      }
    $indicators_return = '<!-- Indicators -->';
    $indicators_return .= '<ol class="carousel-indicators">';
    $indicators_return .= $indicators;
    $indicators_return .= '</ol>';

    $return = '';
    $return .= '<div id="carousel-' . $GLOBALS['carousel_count'] . '" class="carousel slide';
    $return .= ($xclass) ? ' ' . $xclass : '';
    $return .= '"';
    $return .=  ' data-ride="carousel"';
    $return .= ($interval) ? ' data-interval="' . $interval . '"' : '';
    $return .= ($pause) ? ' data-pause="' . $pause . '"' : '';
    $return .= ($wrap) ? ' data-wrap="' . $wrap . '"' : '';
    $return .= ($data_props) ? ' ' . $data_props : '';
    $return .= '>';
    $return .= $indicators_return;
    $return .= '<div class="carousel-inner">' . do_shortcode( $content ) . '</div>';
    $return .= '<!-- Controls -->';
    $return .= '<a class="left carousel-control" href="#carousel-' . $GLOBALS['carousel_count'] . '" data-slide="prev">';
    $return .= '<span class="glyphicon glyphicon-chevron-left"></span>';
    $return .= '</a>';
    $return .= '<a class="right carousel-control" href="#carousel-' . $GLOBALS['carousel_count'] . '" data-slide="next">';
    $return .= '<span class="glyphicon glyphicon-chevron-right"></span>';
    $return .= '</a>';
    $return .= '</div>';

    return $return;
  }


  /*--------------------------------------------------------------------------------------
    *
    * bs_carousel_item
    *
    * @author Filip Stefansson
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_carousel_item( $atts, $content = null ) {
    extract(shortcode_atts(array(
      "caption" => false,
	  "xclass" => false,
	  "data" => false
    ), $atts));
      $content=preg_replace('/class=".*?"/', '', $content);
      $data_props = $this->parse_data_attributes($data);
    $return = '<div class="item';
    $return .= ($GLOBALS['carousel_active']) ? ' active' : '';
    $return .= ($xclass) ? ' ' . $xclass : '';
    $return .= '"';
    $return .= ($data_props) ? ' ' . $data_props : '';
    $return .= '>' . do_shortcode($content);
    $return .= ($caption) ? '<div class="carousel-caption">' . $caption . '</div>' : '';
    $return .='</div>';
    $GLOBALS['carousel_active'] = false;
    return $return;
  }



  /*--------------------------------------------------------------------------------------
    *
    * bs_tooltip
    *
    * @author
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/

function bs_tooltip( $atts, $content = null ) {

    $defaults = array(
	   'title' => '',
	   'placement' => 'top',
	   'animation' => 'true',
	   'html' => 'false'
    );
    extract( shortcode_atts( $defaults, $atts ) );
    $classes = 'bs-tooltip';    

    $previous_value = libxml_use_internal_errors(TRUE);
    $dom = new DOMDocument;
    $dom->loadXML($content);
    libxml_clear_errors();
    libxml_use_internal_errors($previous_value);
    if(!$dom->documentElement) {
        $element = $dom->createElement('span', $content);
        $dom->appendChild($element);
    }
    $dom->documentElement->setAttribute('class', $dom->documentElement->getAttribute('class') . ' ' . $classes);
    $dom->documentElement->setAttribute('title', $title );
    if($animation) { $dom->documentElement->setAttribute('data-animation', $animation ); }
    if($placement) { $dom->documentElement->setAttribute('data-placement', $placement ); }
    if($html) { $dom->documentElement->setAttribute('data-html', $html ); }

    $return = $dom->saveXML();
    
    return $return;
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_popover
    *
    *
    *-------------------------------------------------------------------------------------*/

function bs_popover( $atts, $content = null ) {

    $defaults = array(
	   'title' => false,
       'text' => '',
	   'placement' => 'top',
	   'animation' => 'true',
	   'html' => 'false'
    );
    extract( shortcode_atts( $defaults, $atts ) );
    $classes = 'bs-popover';
    
    $previous_value = libxml_use_internal_errors(TRUE);
    $dom = new DOMDocument;
    $dom->loadXML($content);
    libxml_clear_errors();
    libxml_use_internal_errors($previous_value);
    if(!$dom->documentElement) {
        $element = $dom->createElement('span', $content);
        $dom->appendChild($element);
    }
    $dom->documentElement->setAttribute('class', $dom->documentElement->getAttribute('class') . ' ' . $classes);
    $dom->documentElement->setAttribute('data-toggle', 'popover' );
    if($title) { $dom->documentElement->setAttribute('data-original-title', $title ); }
    $dom->documentElement->setAttribute('data-content', $text );
    if($animation) { $dom->documentElement->setAttribute('data-animation', $animation ); }
    if($placement) { $dom->documentElement->setAttribute('data-placement', $placement ); }
    if($html) { $dom->documentElement->setAttribute('data-html', $html ); }

    $return = $dom->saveXML();
    
    return $return;
  }


  /*--------------------------------------------------------------------------------------
    *
    * bs_media
    *
    * @author
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
    
function bs_media( $atts, $content = null ) {
    
    $defaults = array(
	   'xclass' => false,
	   'data' =>false
    );
    extract( shortcode_atts( $defaults, $atts ) );
	 $data_props = $this->parse_data_attributes($data);
	 
    $return = '<div class="media';
	$return .= ($xclass) ? ' ' . $xclass : '';
	$return .= '"';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '>' . do_shortcode( $content ) . '</div>';
    return $return;
  }

function bs_media_object( $atts, $content = null ) {

    $defaults = array(
	   'pull' => "left",
	   'xclass' => false,
	   'data' =>false
    );
    extract( shortcode_atts( $defaults, $atts ) );
    
    $classes = "media-object";
	$classes .= ($xclass) ? ' ' . $xclass : '';
	
    $dom = new DOMDocument;
    $dom->loadXML($content);
    $dom->documentElement->setAttribute('class', $dom->documentElement->getAttribute('class') . ' ' . $classes);
	if($data) { 
          $data = explode('|',$data);
          foreach($data as $d):
            $d = explode(',',$d);    
            $dom->documentElement->setAttribute('data-'.$d[0],trim($d[1]));
          endforeach;
    }
    $return = $dom->saveXML();
    $return = '<span class="pull-'. $pull . '">' . $return . '</span>';
    return $return;
  }

function bs_media_body( $atts, $content = null ) {
    
    $defaults = array(
	   'title' => false,
	   'xclass' => false,
	   'data' =>false
    );
    extract( shortcode_atts( $defaults, $atts ) );
	 $data_props = $this->parse_data_attributes($data);
    $return .= '<div class="media-body';
	$return .= ($xclass) ? ' ' . $xclass : '';
	$return .= '"';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '>';
    $return .= ($title) ? '<h4 class="media-heading">' . $title . '</h4>' : '';
    $return .= $content . '</div>';
    return $return;
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_jumbotron
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_jumbotron( $atts, $content = null ) {
    extract(shortcode_atts(array(
      "title" => false,
	  "xclass" => false,
	  "data" => false
    ), $atts));
	 $data_props = $this->parse_data_attributes($data);
    $return .='<div class="jumbotron';
	$return .= ($xclass) ? ' ' . $xclass : '';
	$return .= '"';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '>';
    $return .= ($title) ? '<h1>' . $title . '</h1>' : '';
    $return .= do_shortcode( $content ) . '</div>';
    return $return;
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_page_header
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_page_header( $atts, $content = null ) {
	extract(shortcode_atts(array(
	  "xclass" => false,
	  "data" => false
    ), $atts));
	 $data_props = $this->parse_data_attributes($data);
    $classes = "page-header";
	$classes .= ($xclass) ? ' ' . $xclass : '';
	
    $dom = new DOMDocument;
    $dom->loadXML($content);
    $hasHeader = $dom->getElementsByTagName('h1'); 

    if($hasHeader->length == 0) {
        
        $wrapper = $dom->createElement('div');
        $dom->appendChild($wrapper);
        
        $header = $dom->createElement('h1', $content);
        $wrapper->appendChild($header);

    }
    else {
        $new_root = $dom->createElement('div');
        $new_root->appendChild($dom->documentElement);
        $dom->appendChild($new_root);
    }
    $dom->documentElement->setAttribute('class', $dom->documentElement->getAttribute('class') . ' ' . $classes);
	if($data) { 
          $data = explode('|',$data);
          foreach($data as $d):
            $d = explode(',',$d);    
            $dom->documentElement->setAttribute('data-'.$d[0],trim($d[1]));
          endforeach;
    }
    $return = $dom->saveXML();
    
    return $return;

  } 
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_lead
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_lead( $atts, $content = null ) {
	extract(shortcode_atts(array(
	  "xclass" => false,
	  "data" => false
    ), $atts));
	 $data_props = $this->parse_data_attributes($data);
    $return = '<p class="lead';
	$return .= ($xclass) ? ' ' . $xclass : '';
	$return .= '"';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '>' . do_shortcode( $content ) . '</p>';
    return $return;
  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_emphasis
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_emphasis( $atts, $content = null ) {
    extract(shortcode_atts(array(
      "type" => 'muted',
	  "xclass" => false,
	  "data" => false
    ), $atts));
	 $data_props = $this->parse_data_attributes($data);
    $return = '<span class="text-' . $type;
	$return .= ($xclass) ? ' ' . $xclass : '';
	$return .= '"';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '>' . do_shortcode( $content ) . '</span>';
    return $return;
  }
  /*--------------------------------------------------------------------------------------
    *
    * bs_img
    *
    *
    *-------------------------------------------------------------------------------------*/
function bs_img( $atts, $content = null ) {
    extract(shortcode_atts(array(
      "type" => false,
      "responsive" => false,
	  "xclass" => false,
	  "data" => false
    ), $atts));
    $classes .= ($type) ? 'img-' . $type . ' ' : '';
    $classes .= ($responsive) ? ' img-responsive' : '';
	$classes .= ($xclass) ? ' ' . $xclass : '';
    $dom = new DOMDocument;
    $dom->loadXML($content);
    foreach($dom->getElementsByTagName('img') as $image) { 
        $image->setAttribute('class', $image->getAttribute('class') . ' ' . $classes);
		if($data) { 
          $data = explode('|',$data);
          foreach($data as $d):
            $d = explode(',',$d);    
            $image->setAttribute('data-'.$d[0],trim($d[1]));
          endforeach;
		}
    } 
    $return = $dom->saveXML();
    
    return $return;

  }
    
  /*--------------------------------------------------------------------------------------
    *
    * bs_thumbnail
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_thumbnail( $atts, $content = null ) {
	extract(shortcode_atts(array(
	  "xclass" => false,
	  "data" => false
    ), $atts));
    $classes = "thumbnail";
	$classes .= ($xclass) ? ' ' . $xclass : '';
    $dom = new DOMDocument;
    $dom->loadXML($content);
    if(!$dom->documentElement) {
        $element = $dom->createElement('div', $content);
        $dom->appendChild($element);
    }
    $dom->documentElement->setAttribute('class', $dom->documentElement->getAttribute('class') . ' ' . $classes);
	if($data) { 
          $data = explode('|',$data);
          foreach($data as $d):
            $d = explode(',',$d);    
            $dom->documentElement->setAttribute('data-'.$d[0],trim($d[1]));
          endforeach;
    }
    $return = $dom->saveXML();
    
    return $return;

  }
    
    /*--------------------------------------------------------------------------------------
    *
    * bs_responsive
    *
    *
    *-------------------------------------------------------------------------------------*/
  function bs_responsive( $atts, $content = null ) {
      extract( shortcode_atts( array(
          'visible' => '',
          'hidden' => '',
		  "xclass" => false,
		  "data" => false
      ), $atts));
	  $data_props = $this->parse_data_attributes($data);
      $classes='';
      if($visible) { 
          $visible = explode(' ',$visible);
          foreach($visible as $v):
            $classes .= 'visible-'.$v.' ';
          endforeach;
      }
      if($hidden) { 
          $hidden = explode(' ',$hidden);
          foreach($hidden as $h):
            $classes .= 'hidden-'.$h.' ';
          endforeach;
      }
	  $classes .= ($xclass) ? ' ' . $xclass : '';
      $return = '<span class="' . $classes . '"';
	  $return .= ($data_props) ? ' ' . $data_props : '';
	  $return .= '>' . do_shortcode($content) . '</span>';
      return $return;

  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_modal
    *
    * @author M. W. Delaney
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_modal( $atts, $content = null ) {
    extract(shortcode_atts(array(
      "text" => '',
      "title" => '',
      "xclass" => false,
	  "data"=>false
    ), $atts));
	 $data_props = $this->parse_data_attributes($data);
    $sani_title = 'modal'. sanitize_title( $title );
    $return .='<a data-toggle="modal" href="#'. $sani_title .'"';
	$return .= ($xclass) ? ' class="' . $xclass .'"' : '';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '>'. $text .'</a>';
    $return .='<div class="modal fade" id="'. $sani_title .'" tabindex="-1" role="dialog" aria-hidden="true">';
    $return .='<div class="modal-dialog">';
    $return .='<div class="modal-content">';
    $return .='<div class="modal-header">';
    $return .='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    $return .='<h4 class="modal-title">'. $title .'</h4>';
    $return .='</div>';
    $return .='<div class="modal-body">';
    $return .= do_shortcode($content);
    $return .='</div>';
    $return .='</div><!-- /.modal-content -->';
    $return .='</div><!-- /.modal-dialog -->';
    $return .='</div><!-- /.modal -->';  
    return $return;

  }

  /*--------------------------------------------------------------------------------------
    *
    * bs_modal_footer
    *
    * @author M. W. Delaney
    * @since 1.0
    *
    *-------------------------------------------------------------------------------------*/
  function bs_modal_footer( $atts, $content = null ) {
	extract(shortcode_atts(array(
      "xclass" => false,
	  "data"=>false
    ), $atts));
	 $data_props = $this->parse_data_attributes($data);
    $return = '<div class="modal-footer';
	$return .= ($xclass) ? ' ' . $xclass : '';
	$return .= '"';
	$return .= ($data_props) ? ' ' . $data_props : '';
	$return .= '>' . do_shortcode( $content ) . '</div>';
    return $return;
  }
  
  function parse_data_attributes($data){
	if($data) { 
          $data = explode('|',$data);
          foreach($data as $d):
            $d = explode(',',$d);    
                $data_props .= 'data-'.$d[0]. '="'.trim($d[1]).'" ';
          endforeach;
      } else { $data_props = false; }
	return $data_props;
  }
    
}

new BoostrapShortcodes();
