<?php

/**
 * Page Class to retrieve a post type content  (post, page, custom_post_type, ...)
 * 
 */

defined( 'ABSPATH' ) || exit;

 /**
  * Page class
  */
class Page {
    /* Member variables */
    var $post_type;
    var $query;
    var $posts;
    var $header;

    function __construct( $postType, $postNbr = -1, $args = array()) {
        $this->post_type = $postType;
        
        if (count($args) <= 0) {
            $args = array('exclude' => '');
            $args = array('category' => '');
            $args = array('post_name' => '');
        } else {
            if($args['exclude'] === null) {
                $args['exclude'] = '';
            }
            if($args['category'] === null) {
                $args['category'] = '';
            }
            if($args['post_name'] === null) {
                $args['post_name'] = '';
            }
        }
        $this->query = array(
            'post_name' => $args['post_name'],
            'posts_per_page' => $postNbr,
            'post_type' => $this->post_type,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'category' => $args['category'], 
            'post_status' => 'publish',
            'exclude' => $args['exclude'],
            'suppress_filters' => 0
        );
        $posts = get_posts($this->query);
        $tmp = array();
        foreach ($posts as $key => $post) {
            $tmpPost = new Post($post);
            if($tmpPost->getMeta('layout') == 'bloc_content_1') {
                $this->header = $tmpPost->getMeta('bloc_content_1');
            } else {
                array_push($tmp, $tmpPost);
            }
        }
        $this->posts = $tmp;
    }

    function getPostType(){
        return $this->post_type;
    }

    function getPosts(){
        if(count($this->posts) == 1) {
            return $this->posts[0];
        } else {
            return $this->posts;
        }
    }

    /**
     * @args {String} : post title
     */
    function getBySlug($arg){
        $key = array_search($arg, array_column($this->posts, 'post_name'));
        return $this->posts[$key];
    }
    function get($arg){
        $key = array_search($arg, array_column($this->posts, 'post_title'));
        return $this->posts[$key];
    }
    function getPostByID($arg) {
        $key = array_search($arg, array_column($this->posts, 'ID'));
        return $this->posts[$key];
    }
    function getHeader() {
        return $this->header;
    }
    function getPostsCount() {
        return count($this->posts);
    }
}