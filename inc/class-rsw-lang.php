<?php

/**
 * Lang Class to create a menu lang
 * 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lang class
*/
class Lang {

private $options;

public function __construct(array $options = array()) {
    global $langs;
    $this->options = $options;
    $langs = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
    $this->get_active_lang();
 }

/**
 * $returnType String {code, name}
 */
private function get_active_lang($returnType = 'code') {
    global $langs;
    global $activeLang;
    global $activeLangCode;
    global $activeLangName;


    foreach($langs as $lang) {
        $returnValue = [
            'code' => substr($lang["language_code"],0,2),
            'name' => substr($lang["translated_name"],0)
        ];

        if($this->options['lang_modification'] !== undefined && !empty($this->options['lang_modification']) ) {
            foreach($this->options['lang_modification'] as $key => $langName) {
                if($returnValue['name'] == $key) $returnValue['name'] = $langName;                    
            }
        }
        
        if($lang["active"]){
            $activeLang = $returnValue['code'];
            $activeLangCode = $lang["language_code"];
            $activeLangName = $returnValue['name'];
            return $returnValue[$returnType];
        }
    }
}
public function get_active_lang_code() {
    global $activeLang;
    return $activeLang;
}
public function get_active_lang_code_full() {
    global $activeLangCode;
    return $activeLangCode;
}
public function get_active_lang_name() {
    global $activeLangName;
    return $activeLangName;
}

public function get_lang_menu($returnType = 'code', $classUL = 'menu__lang', $classLI = 'menu__lang_item', $addlink = true, $activeSign = ''){
    global $langs;
    global $activeLang;
    global $activeLangCode;
    global $activeLangName;

    $response = '<ul class="'.$classUL.'">';

    if(count($langs) >= 1){
        foreach($langs as $lang){
            $returnValue = [
                'code' => substr($lang["language_code"],0,2),
                'name' => ucfirst(substr($lang["translated_name"],0))
            ];

            if($this->options['lang_modification'] !== undefined && !empty($this->options['lang_modification']) ) {
                foreach($this->options['lang_modification'] as $key => $langName) {
                    if($returnValue['name'] == $key) $returnValue['name'] = $langName;                    
                }
            }

            if($lang["active"]){
                $activeLang = substr($lang["language_code"],0,2);
                $activeLangCode = $lang["language_code"];
                $activeLangName = substr($lang["translated_name"],0);
            }
            $response .= '<li class="'.$classLI .''.($lang["active"] ? ' active':'').'">';
            $response .= ($addlink != '' ? '<a href="'.$lang["url"].'">' : '');
            $response .= ($lang["active"] ? $activeSign : '');
            $response .= $returnValue[$returnType];
            $response .= ($addlink != '' ? '</a>' : '');
            $response .='</li>';
        }
    }

    $response .= '</ul>';

    return $response;
}
public function get_lang_menu_first_active($returnType = 'code', $classUL = 'menu__lang', $classLI = 'menu__lang_item', $addlink = true, $activeSign = ''){
    global $langs;
    global $activeLang;
    global $activeLangCode;
    global $activeLangName;

    $response = '<div class="'.$classUL.'">';

    $response .= '<a href="javascript:;" class="active '.$classLI .'">';
    // $response .= ($addlink != '' ? '<a href="'.$lang["url"].'">' : '');
    $response .= $activeSign;
    if($returnType == 'code') {
        $response .= $this->get_active_lang_code();
    } else if($returnType == 'name') {
        $response .= $this->get_active_lang_name();            
    }
    // $response .= ($addlink != '' ? '</a>' : '');
    $response .='</a> <span>|</span> ';

    if(count($langs) >= 1){
        foreach($langs as $lang){
            $returnValue = [
                'code' => substr($lang["language_code"],0,2),
                'name' => ucfirst(substr($lang["translated_name"],0))
            ];

            if($this->options['lang_modification'] !== undefined && !empty($this->options['lang_modification']) ) {
                foreach($this->options['lang_modification'] as $key => $langName) {
                    if($returnValue['name'] == $key) $returnValue['name'] = $langName;                    
                }
            }

            if(!$lang["active"]){
                $response .= '<span class="'.$classLI.'">';
                $response .= ($addlink != '' ? '<a href="'.$lang["url"].'">' : '');
                $response .= $returnValue[$returnType];
                $response .= ($addlink != '' ? '</a>' : '');
                $response .='</span>';
            }
        }
    }

    $response .= '</div>';

    return $response;
}
public function echo_lang_menu(){
    echo $this->get_lang_menu();
}
}