<?php

/**
 * Newsletter Class to process a registration to mailster
 * 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Newsletter class
 */
class MailsterNewsletter {

private $newsletterListIDs = array();
private $newsletterForm = array();

function __construct($newsletterListIDs, $newsletterForm) {
    $this->newsletterListIDs = $newsletterListIDs;
    $this->newsletterForm = $newsletterForm;
}

public function addNewsletterID($listName, $listID) {
    if($this->newsletterListIDs[$listName] == null) {
        $this->newsletterListIDs[$listName] = $listID;
    }
}
public function isValid($data) {
    foreach ($this->newsletterForm as $key => $value) {
        if(empty($data[$value])) {
            return false;
        }
    }
    return true;
}

public function getReturn() {
    return $this->returnData;
}

private function setReturn($code) {
    $this->returnData["code"] = $code;
    
    switch($code) {
        case 200:
            $this->returnData['message'] = __('Email envoyé avec succès.', 'rsw-print');
            break;
        case 404:
            $this->returnData['message'] = __('Veuillez remplir tous les champs.', 'rsw-print');
            break;
        case 500:
            $this->returnData['message'] = __('Une erreur est survenue.', 'rsw-print');
            break;
        case 501:
            $this->returnData['message'] = __('Une erreur est survenue. Cela peut être dû à une pièce jointe trop volumineuse.', 'rsw-print');
            
            break;
        case 600:
            $this->returnData['message'] = __('Você foi registrado a nossa newsletter com sucesso.', 'rsw-print');
            // $this->returnData['message'] = __('Vous avez été correctement inscrit à la newsletter.', 'rsw-print');
            break;
        case 601:
            $this->returnData['message'] = __('Desculpa aconteceu um error, por favor tanta novamente mais tarde.', 'rsw-print');
            // $this->returnData['message'] = __('Veuillez essayer de vous inscrire à la newsletter ultérieurement.', 'rsw-print');
            break;
        default:
            $this->returnData["code"] = 500;
            $this->returnData['message'] = __('Une erreur est survenue.', 'rsw-print');
            break;
    }
}

/**
 * $referer: where the subscription is coming from ? (contact form, newsletter form, ...)
 */
public function newsletterSubscribe($userData, $subscribeFrom, $isSubscribing = false, $newsletterList = 'default') {
    
    if(function_exists('mailster')) {
    // var_dump($newsletterList);
    if($this->isValid($userData)) {
        
        $overwrite = $isSubscribing;
        
        $userData['status'] = $isSubscribing ? 1 : 2;
        $userData['referer'] = $subscribeFrom;

        // var_dump( $userData['status']);
        
        $listID = $this->newsletterListIDs[$newsletterList];
        // var_dump( $listID);
            $subscriber_id = mailster('subscribers')->add($userData, $overwrite);

            if(!is_wp_error($subscriber_id)){
                if($isSubscribing) {
                    mailster('subscribers')->assign_lists($subscriber_id, array($listID));
                    $this->setReturn(600);
                }
            } else {
                if($isSubscribing) {
                    mailster('subscribers')->assign_lists($subscriber_id, array($listID));
                    $this->setReturn(601);
                }
            }
            if($isSubscribing) {
                return $this->getReturn();
            }
        }
    }
}
}