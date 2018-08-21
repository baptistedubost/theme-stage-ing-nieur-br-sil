<?php

/**
 * Category Class to retrieve a category 
 * 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Category class
*/

class Category {
    var $post;
    var $ID;
    var $post_title;
    var $post_name;
    var $post_content;
    var $post_type;
    var $term_id;
    var $name;
    var $slug;
    var $category_parent;
    var $taxonomy;

    function __construct($post) {
        $this->post = $post;
        $this->ID = $post->term_id;
        $this->term_id = $post->term_id;
        $this->post_title = $post->name;
        $this->name = $post->name;
        $this->post_name = $post->slug;
        $this->slug = $post->slug;
        $this->post_content = $post->description;
        $this->category_parent = $post->category_parent;
        $this->taxonomy = $post->taxonomy;
    }

    /**
     * @args {String} : post_title, post_name ...
     */
    function get($arg) {
        return $this->post->$arg;
    }

    function getMeta($metaArg, $isSingle = true) {
        return get_field($metaArg, $this->taxonomy.'_'.$this->ID); 
    }
    
    function getPermalink() {
        return get_permalink($this->ID);
    }
}