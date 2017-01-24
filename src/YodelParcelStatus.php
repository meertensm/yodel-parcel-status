<?php
namespace MCS;

use DateTime;
use Exception;

use PHPHtmlParser\Dom;

class YodelParcelStatus{
    
    private $awb;
    private $postcode;

    private $delivered_states = [
        'delivered'    
    ];
    
    public $events = [];
    
    const URL = 'https://www.yodel.co.uk/tracking/:awb/:postcode';
    
    public function __construct($awb, $postcode)
    {
        $this->awb = $awb;
        $this->postcode = str_replace(' ', '', $postcode);
    }
    
    public function getStatus()
    {
        $dom = new Dom();
        $dom->load(
            strtr(self::URL, [
                ':awb' => $this->awb,
                ':postcode' => $this->postcode
            ])
        );
        
        $states = $dom->find('p[class=tracking_event]');
        
        foreach ($states as $state) { 
            
            $current_state = $state->find('span[class=description]')->text;
            
            foreach ($this->delivered_states as $delivered_state) {
                if (strpos(mb_strtolower($current_state), $delivered_state) !== false) {
                    $delivered = true;
                } else {
                    $delivered = false;
                }
            } 
            
            $this->events[] = [
                'state' => $current_state,
                'delivered' => $delivered,
                'timestamp' => DateTime::createFromFormat(
                    'd/m/y H:i', $state->find('span[class=datetime]')->text
                )
            ];
        }
        
        return $this->events;
    }
}
