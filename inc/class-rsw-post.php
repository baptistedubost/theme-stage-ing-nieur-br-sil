<?php

/**
 * Post Class to retrieve a post 
 * 
 */

defined( 'ABSPATH' ) || exit;

 /**
  * Post class
  */
  class Post {
    var $post;
    var $ID;
    var $post_title;
    var $post_name;
    var $post_content;
    var $post_type;

    function __construct($post) {
        $this->post = $post;
        $this->ID = $post->ID;
        $this->post_title = $post->post_title;
        $this->post_name = $post->post_name;
        $this->post_content = $post->post_content;
        $this->post_type = $post->post_type;
    }

    /**
     * @args {String} : post_title, post_name ...
     */
    function get($arg) {
        return $this->post->$arg;
    }

    function getMeta($metaArg, $isSingle = true) {
        return get_field($metaArg, $this->ID); 
    }
    
    function getFeatureImage($size = 'medium') {
        $att = wp_get_attachment_image_src(get_post_thumbnail_id($this->ID), $size);
        if($multilang && count($config[langs]) > 0) {
            if(empty($att)) {
                foreach($config[langs] as $lang) {
                    $o_ID = icl_object_id($this->ID, $this->post_type, false, $lang);
                    $att = wp_get_attachment_image_src( get_post_thumbnail_id($o_ID), $size);
                    if(!empty($att)) {
                        break;
                    }
                }
            }
        } 
        
        return $att[0];
    }

    function getImageACF($metaArg, $size = 'medium') {
        $att = wp_get_attachment_image_src($this->getMeta($metaArg, true), $size);
        if($multilang && count($config[langs]) > 0) {
            if(empty($att)) {
                foreach($config[langs] as $lang) {                    
                    $o_ID = icl_object_id($this->ID, $this->post_type, false, $lang);
                    $att = wp_get_attachment_image_src(get_field($metaArg, $o_ID), $size);
                    if(!empty($att)) {
                        break;
                    }
                }
            }
        } 
        
        return $att[0];
    }
    function getPermalink() {
        return get_permalink($this->ID);
    }
}