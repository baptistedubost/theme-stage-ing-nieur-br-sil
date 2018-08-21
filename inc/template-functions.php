<?php

/********************
 * Site configuration
 ********************/

 $site_name = '';
 $multilang = false;
 $config = array(
     'langs' => array('fr-fr', 'en')
 );


/****************
 * Adding support
 ****************/


// Prevent HTTP Error when uploading images to the back office
add_filter('wp_image_editors', 'change_graphic_lib');
function change_graphic_lib($array) {
    return array('WP_Image_Editor_GD', 'WP_Image_Editor_Imagick');
}

// ACF - Google Map Support
// add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
function my_acf_google_map_api( $api ){
    $api['key'] = '';
    return $api;
}

// add_filter('upload_mimes', 'my_myme_types', 1, 1);
function my_myme_types($mime_types){
    $mime_types['type'] = 'application/type'; //Adding svg extension
    return $mime_types;
}

// add_action( 'wp_head', 'insert_fb_in_head', 5 );
function insert_fb_in_head() {
    global $post;
    $site_name = 'Print in Progress';
    $defaultImg = '/images/default.jpg';
    $facebookUrl = 'https://www.facebook.com/PrintinProgress';
    // var_dump(is_singular()); 

    echo '<meta property="fb:admins" content="YOUR USER ID"/>';
    echo '<meta property="og:site_name" content="'.$site_name.'"/>';
    echo '<meta property="og:locale" content="fr_FR">';
    echo '<meta property="article:publisher" content="'.$facebookUrl.'">';

    if ( !is_singular()) {//if it is not a post or a page 
        if(is_front_page()) {

            echo '<meta property="og:title" content="'.$site_name.' | '.__('Showroom des industries créatives', 'rsw-print').'"/>';
            echo '<meta property="og:type" content="website"/>';
            echo '<meta property="og:url" content="' . get_home_url() . '"/>';
            echo '<meta property="og:description" content="'.get_bloginfo('description').'"/>';

            $homePage = new Page('home_page');
            $header = $homePage->getHeader();

            if($header != null) {
                echo '<meta property="og:image" content="' . $header['cover']['sizes']['large'] . '"/>';
                echo '<meta property="og:image:width" content="'.$header['cover']['sizes']['large-width'].'">';
                echo '<meta property="og:image:height" content="'.$header['cover']['sizes']['large-height'].'">';
            } else {
                echo '<meta property="og:image" content="' . get_uri() . '/assets/images/default.jpg"/>';
                echo '<meta property="og:image:width" content="1200">';
                echo '<meta property="og:image:height" content="630">';
            }

        } else if(is_category()) {
        }
    } else {
        if(is_page()) {
            echo '<meta property="og:title" content="' . $post->post_title . ' | '.$site_name.'"/>';
            echo '<meta property="og:type" content="article"/>';
            echo '<meta property="og:url" content="' . get_the_permalink($post->ID) . '"/>';
            echo '<meta property="og:description" content="'.get_bloginfo('description').'"/>';
    
            $page = new Post($post);
            $header = $page->getMeta('cover_picture');
            if($header === null) {
                $header = $page->getMeta('header');
            }

            if($header != null) {
                echo '<meta property="og:image" content="' . $header['sizes']['large'] . '"/>';
                echo '<meta property="og:image:width" content="'.$header['sizes']['large-width'].'">';
                echo '<meta property="og:image:height" content="'.$header['sizes']['large-height'].'">';
            } else {
                echo '<meta property="og:image" content="' . get_uri() . '/assets/images/default.jpg"/>';
                echo '<meta property="og:image:width" content="1200">';
                echo '<meta property="og:image:height" content="630">';
            }
        } else {
            echo '<meta property="og:title" content="' . $post->post_title . ' | '.$site_name.'"/>';
            echo '<meta property="og:type" content="article"/>';
            echo '<meta property="og:url" content="' . get_the_permalink($post->ID) . '"/>';
            echo '<meta property="og:description" content="'.get_bloginfo('description').'"/>';
    
            $single = new Post($post);
            $header = $single->getMeta('header')['cover'];
            if($header === null) {
                $header = $single->getMeta('header');
            }

            if($header != null) {
                echo '<meta property="og:image" content="' . $header['sizes']['large'] . '"/>';
                echo '<meta property="og:image:width" content="'.$header['sizes']['large-width'].'">';
                echo '<meta property="og:image:height" content="'.$header['sizes']['large-height'].'">';
            } else {
                echo '<meta property="og:image" content="' . get_uri() . '/assets/images/default.jpg"/>';
                echo '<meta property="og:image:width" content="1200">';
                echo '<meta property="og:image:height" content="630">';
            }
        }

    }
    echo "";
}



/*******************
 * Helpful functions
 *******************/

function get_uri() {
    return get_template_directory_uri(); 
}

function get_svg($file) {
    return file_get_contents($file);
}

function getPageLink($page) {
    return get_page_link(get_page_by_path($page)->ID);
}

function getCurrentPage() {
    if(is_home()) {
        return 'page-home';
    } else if(is_single()) {
        return 'page-single';
    } else if(is_page()) {
        return 'page-'. get_query_var('pagename');
    } else {
        return get_query_var('pagename');
    }
}

function getCurrentPageLink() {
    global $wp;
    return home_url($wp->request);
}

function truncate($string, $length, $stopanywhere = false) {
    //truncates a string to a certain char length, stopping on a word if not specified otherwise.
    if (strlen($string) > $length) {
        //limit hit!
        $string = substr($string, 0, ($length - 3));
        if ($stopanywhere) {
            //stop anywhere
            $string .= '...';
        } else {
            //stop on a word.
            $string = substr($string, 0, strrpos($string, ' ')) . '&nbsp;&hellip;';
        }
    }
    return $string;
}

function normalize ($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );

    return strtr($string, $table);
}

// Function used for the Form's Class sendEmail function
function setHtmlContentType(){
    return "text/html";
}

// TODO: Review that function !
function echoImgResponsive($image, $width, $alt = '', $class = '', $datasrc = false) {
    if($alt == '') $alt = $site_name;
    if($datasrc) {
        echo '<img  data-srcset="'.$image[sizes][thumbnail].' '.$image[sizes]['thumbnail-width'].'w,
                '.$image[sizes][medium].' '.$image[sizes]['medium-width'].'w,
                '.$image[sizes][large].' '.$image[sizes]['large-width'].'w,
                '.$image[sizes][above_desktop].' '.$image[sizes]['above_desktop-width'].'w,
                '.$image[url].' '.$image[width].'w"

            sizes="(max-width: 400px) '.$width.',
            (max-width: 769px) '.$width.',
            (max-width: 1024px) '.$width.',
            (max-width: 1200px) '.$width.',
            '.$width.'"
            data-src="'.$image[url].'" class="'.$class.'" alt="'.$alt.'">';   
    } else {
        echo '<img  srcset="'.$image[sizes][thumbnail].' '.$image[sizes]['thumbnail-width'].'w,
                '.$image[sizes][medium].' '.$image[sizes]['medium-width'].'w,
                '.$image[sizes][large].' '.$image[sizes]['large-width'].'w,
                '.$image[sizes][above_desktop].' '.$image[sizes]['above_desktop-width'].'w,
                '.$image[url].' '.$image[width].'w"

            sizes="(max-width: 400px) '.$width.',
            (max-width: 768px) '.$width.',
            (max-width: 1024px) '.$width.',
            (max-width: 1200px) '.$width.',
            '.$width.'"
            src="'.$image[url].'" class="'.$class.'" alt="'.$alt.'">';   
    }
}

function find($array, callable $callback) {
    $index = 0;
    $result = false;

    do {
        $result = $callback($array[$index]);
        $index = $index + 1;
    } while (!$result && $index < count($array));
    
    return $result ? $array[$index - 1] : false;
}


/*******************
 * Social Networks
 *******************/


function shareWhatsapp($link, $icon = 'logo_whatsapp.svg') {
    echo '<span class="whatsapp whatsapp-share" data-link="'.$link.'"><img src="'.get_uri().'/assets/icons/'.$icon.'"></span>';
}

function shareFacebook($link, $img, $width, $height, $title, $desc) {
    echo '<span class="facebook fb-share" 
            data-link="'.$link.'"
            data-img="'.$img.'"
            data-width="'.$width.'"
            data-height="'.$height.'"
            data-title="'.$title.'"
            data-desc="'.$desc.'" >
                <img src="'.get_uri().'/assets/logos/logo_facebook.svg">
        </span>';
}
