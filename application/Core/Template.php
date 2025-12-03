<?php 

class Template{
    private $blocks = array();
    private $cacheDir;
    
    public function __construct($template, $data){
        $this->setupCacheDir();
        $this->check_cache_dir();
        $filePath = "../application/Templates/$template";
        if(file_exists($filePath)){
            $code = file_get_contents($filePath);
            $code = $this->registerBlocks($code);
            $code = $this->loadBase($code);
            $code = $this->matchBlocks($code);
            $code = $this->parseDate($code);
            $code = $this->parseVariables($code);
            $code = $this->parsePHP($code);
            $code = $this->parseUrls($code);
            $this->create_cache_file($code, $data);
        }else{
            print "<b>Template Error: </b>  Template <i style = 'color:red'>$template</i> cannot be found";
        }
    }

    private function isLocalEnv(){
        if(ENVIRONMENT == 'DEVELOPMENT'){
            return true;
        }else{
            return false;
        }
    }

    private function setupCacheDir() {
        if ($this->isLocalEnv()) {
            $this->cacheDir = '../public/cache/'; // Local development
        } else {
            $this->cacheDir = "/tmp/"; // Cloud Run
        }
    }

    private function create_cache_file($code, $data){
        //$filePath = "../public/cache/_cache.php";
        $filePath = $this->cacheDir.'_cache.php';        
        file_put_contents($filePath, $code);
        extract($data, EXTR_SKIP);
        require_once $filePath;
    }

    private function registerBlocks($code){
        $blocksPattern = "/{% ?block (.*?) ?%}(.*?){% ?endblock? %}/is";
        preg_match_all($blocksPattern, $code, $matches, PREG_SET_ORDER);
        foreach($matches as $match){
            $this->blocks[$match[1]] = $match[2];
        }
        $code = str_replace($blocksPattern, '', $code);
        return $code;
    }

    private function loadBase($code){
        $basePattern = "/{% ?extends (\'|\")(.+)(\'|\") ?%}/";
        $baseFile = '';
        preg_match_all($basePattern, $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $match){
            $baseFile = $match[2];
        }
        if(!$baseFile){
            return $code;
        }
        $filePath = "../application/Templates/$baseFile";
        if(file_exists($filePath)){
            $code = file_get_contents($filePath);
            return $code;
        }else{
            print "<b>Template Error: </b>  Base Template $baseFile does not exist";
        }
    }

    private function matchBlocks($code){
        foreach($this->blocks as $block => $value){
            $blockPattern = "/{% block ($block) %}(.*?){% endblock %}/is";
            $code = preg_replace($blockPattern, $value, $code);
        }
        return $code;
    }

    private function check_cache_dir(){
        //$fullPath = "../public/cache/";
        $fullPath = $this->cacheDir;
        if (!is_dir($fullPath)){
            mkdir($fullPath, 0777, true);
        }
    }

    private function parseUrls($code){
        $urlPattern = "/{% url (.*?) %}/";
        preg_match_all($urlPattern, $code, $matches, PREG_SET_ORDER);
        foreach($matches as $match){
            //print_r($match);
            if (array_key_exists($match[1], ROUTES)){
                $orig_route = URL_ROOT.ROUTES[$match[1]];
                $code =  str_replace($match[0], $orig_route, $code);
            }
        }
        return $code;
    }

    private function parseVariables($code){
        $varPattern = "/{{ ?(\S+) ?}}/";
        return preg_replace($varPattern, "<?= $1; ?>", $code );
    }

    private function parseDate($code){
        return preg_replace("/{{ server_date }}/", "<?= date('Y-m-d'); ?>", $code);
    }

    private function parsePHP($code){
        $PHPPattern = "/@php(.*?)@endphp/is";
        return preg_replace($PHPPattern, "<?php $1 ?>", $code);
    }
}
?>