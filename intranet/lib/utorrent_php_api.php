<?php
class uTorrentAPI
{
    public $conf_user = "";
    public $conf_pass = "";
    public $conf_host = "";
    
    public $token = NULL;
    public $crl = NULL;
    
    function uTorrentAPI($config) {
        
    }

    function __construct($config)
    {
        $this->conf_host = $config['uTorrentURL'].":".$config['uTorrentPort'];
        $this->conf_user = $config['uTorrentUsername'];
        $this->conf_pass = $config['uTorrentPassword'];
        $this->crl = curl_init();
        $this->token = $this->get_token ();
    }
    
    function __destruct()
    {
        curl_close($this->crl);
    }
    
    public function load_data($action, $load_token = true){
        if (isset($action))
        {
            if ($load_token)
            {
                return $this->get_url_contents("http://" . $this->conf_user . ":" . $this->conf_pass . "@" . $this->conf_host . "/gui/?token=" . $this->token . $action);
            }
            else
            {
                return $this->get_url_contents("http://" . $this->conf_user . ":" . $this->conf_pass . "@" . $this->conf_host . "/gui/" . $action);
            }
        }
    }
    
    public function get_url_contents($url)
    {
            
            $curl_opts=Array(
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_FORBID_REUSE => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_COOKIEFILE => 'cookiejar.tmp',
                CURLOPT_COOKIEJAR => 'cookiejar.tmp',
                CURLOPT_CONNECTTIMEOUT => 5
            );
            curl_setopt_array($this->crl, $curl_opts);
            
            $ret = curl_exec($this->crl);
            return $ret;
    }
    
    public function get_token ()
    {
        return strip_tags($this->load_data("token.html", false));
    }
    
    public function get_torrent_list()
    {
        return json_decode($this->load_data("&list=1"),true);
    }
}       

?>