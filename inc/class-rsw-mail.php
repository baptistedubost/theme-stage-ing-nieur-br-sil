<?php

/**
 * Mail Class to create a form and send it by email
 * 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Mail class
 */
class Mail {

private $adminMails = array();
private $adminMailsLower = array();
private $formFields = array();
private $form = array();
private $dest = array();
private $returnData = array('code' => null, 'message' => null);

function __construct($adminMails, $formFields) {
    $this->adminMails = $adminMails;
    foreach ($this->adminMails as $key => $value) {
        $this->adminMailsLower[str_replace(' ', '_', strtolower($key))] = $key;
    }
    $this->formFields = $formFields;

    $this->checkFormEntries();
}

public function checkFormEntries() {
    if(empty($formFields['name']) || empty($formFields['email']) || empty($formFields['subject']) || empty($formFields['message'])) {
        return false;
    } else {
        return true;
    }
}


public function process($form) {
    $this->form = $form;
    $isFormFilled = true;
    
    foreach ($this->formFields as $key => $value) {
        if($isFormFilled && empty($form[$value])) {
            $isFormFilled = false;
        }
    }
    
    if($isFormFilled) {
        $this->prepareName();
        $this->dest['email'] = $form['email'];
        $this->mail['subject'] = $form['subject'];
        $this->mail['subject_min'] = str_replace(' ', '_', strtolower($form['subject']));
        $this->mail['message'] = $form['message'];
        $this->mail['name'] = $form['name'];
        $this->mail['profile'] = $form['profile'];
        $this->mail['cep'] = $form['cep'];
        $this->mail['tel'] = $form['tel'];
        
        // var_dump($this->adminMails[$this->adminMailsLower[$this->mail['subject']]]);
        // var_dump($this->mail['subject'], $this->mail['name']);

        if($this->sendEmail($this->adminMails[$this->adminMailsLower[$this->mail['subject']]], $this->mail['name'], $this->dest['email'], $this->adminMailsLower[$this->mail['subject']], $this->mail['message'], true)) {
            // var_dump('ok');
            $this->setReturn(200);
        } else {
            // var_dump('pas ok');
            $this->setReturn(500);
        }
    } else {
        return $this->formFields;
    }

    return $this->getReturn();

}

private function prepareName() {
    if(!empty($form['firstname']) && !empty($form['lastname'])) {
        $this->dest['name'] == $form['firstname'].' '.$form['lastname'];
    } else if($form['name']) {
        $this->dest['name'] == $form['name'];
    }
}

public function sendEmail($to, $fromName, $fromEmail, $subject, $message, $stripTags = false, $attachments = array()) {

    // $headers = 'From: '.strip_tags($fromName).' <'.strip_tags($fromEmail).'>' . "\r\n";

    $name = strip_tags($fromName);
    $subjectFormat =  'Contato site | ' . Trim(stripslashes(strip_tags($subject))) . ' | '. $name;
    $email = strip_tags($fromEmail);
    $headers = "From: $name <$fromEmail>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $contentExtra = $message.'<br><br>';

    $contentExtra .= '<strong>Informações do formulario</strong><br>';
    $contentExtra .= '<strong>Assunto:</strong> '. Trim(stripslashes(strip_tags($subject))) .'<br>';
    $contentExtra .= '<strong>Nome:</strong> '. $name .'<br>';
    $contentExtra .= '<strong>Email:</strong> '. $fromEmail .'<br>';
    $contentExtra .= '<strong>profile:</strong> '. $this->mail['profile'] .'<br>';
    $contentExtra .= '<strong>CEP:</strong> '. $this->mail['cep'] .'<br>';
    $contentExtra .= '<strong>Telefone:</strong> '. $this->mail['tel'] .'<br>';


    if($stripTags) {
        // var_dump($to);
        // var_dump('yoyo', nl2br(trim($message)));
        // var_dump(nl2br(trim(htmlspecialchars($message))));
        add_filter( 'wp_mail_content_type','setHtmlContentType' );
        $mail = wp_mail( $to, $subjectFormat, nl2br($contentExtra), $headers, $attachments );
        remove_filter( 'wp_mail_content_type','setHtmlContentType' );
        return $mail;
        // var_dump('sending email\n', $fromEmail);
    } else {
        // var_dump($to, $subjectFormat, Trim(stripslashes(strip_tags($message))), $headers, $attachments );
        $mail = wp_mail( $to, $subjectFormat, Trim(stripslashes(strip_tags($message))),
        $headers, $attachments );

        // var_dump($mail);
        return $mail;
    }
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
            $this->returnData['message'] = __('Vous avez été correctement inscrit à la newsletter.', 'rsw-print');
            break;
        case 601:
            $this->returnData['message'] = __('Veuillez essayer de vous inscrire à la newsletter ultérieurement.', 'rsw-print');
            break;
        default:
            $this->returnData["code"] = 500;
            $this->returnData['message'] = __('Une erreur est survenue.', 'rsw-print');
            break;
    }
}
}