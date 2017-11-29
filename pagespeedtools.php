<?php
namespace app\core\google;
defined("APPPATH") OR die("Access denied");     

/**
 * This class capture json data from Pagespeed Insights. Capture image preview from url and save it.
 *
 * @package Pagespeed Insights from Google
 * @author José Luis Rojo Sánchez <jose@artegrafico.net>
 * @copyright 2017
 * @version 1.0
 * @link https//www.artegrafico.net
 * @since 1.0 alpha
 * @lincense http://www.php.net/license/3_01.txt PHP License 3.01 * 
 */

Class PageSpeedTools {    

    /**
     * @var string
     */
    protected $_pageSpeedKey = "yourapikey";    
    /**
     * @var string
     */
    protected $_dir =  "../";
    protected $_directory; 
    /**
     * @var string
     */
    protected $_extension = ".jpg";    
    /**
     * @var string
     */
    protected $_readingUrl;
            
    public function __construct(){
    }  
    
    /**
     * @access public
     * @param string $campaign_id
     * @param string $dir    
     * @return void 
     */
    public function imagePreview($id) {
        
        $this->_id = $id;   
        $this->_readingUrl = "https://www.artegrafico.net/en/"; // url to capture
        $this->_dir = "captures/";             
              
        $_img = $this->_dir.$_id.$this->_extension;
        if (!file_exists($_img)) {            
            $this->captureImageFromPageSpeedTools();
        }      
        
    }
    
    /**
     * @access public
     * @return void     
     */
    public function captureImageFromPageSpeedTools(){
        
        // capture data from pagespeed
        $captureUrl = "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=".$this->_readingUrl.$this->_id."&key=".$this->_pageSpeedKey."&screenshot=true";
        $googlePagespeedData = file_get_contents($captureUrl);
        $googlePagespeedData = json_decode($googlePagespeedData, true);
        
        // get image data
        $screenshot = $googlePagespeedData['screenshot']['data'];
        $screenshot = str_replace(array('_','-'),array('/','+'),$screenshot); 
        
        $this->saveImageFromPageSpeedTools($screenshot);
        
    }
    
    /**
     * @access public
     * @param string $img image data
     * @return void
     */
    public function saveImageFromPageSpeedTools($img){
        
        $decoded = base64_decode($img);
        file_put_contents($this->_dir.$this->_id.$this->_extension, $decoded);
        
    }
    
}
