<?php
class GitHubShortCode {
    public static function parse($arguments, $content=null, $parser=null) {
        if(!array_key_exists('repo', $arguments) || empty($arguments['repo']) || strpos($arguments['repo'], '/')<=0) {
            return '<p><i>GitHub repository undefined</i></p>';
        }
        
        //Get Config
        $config=Config::inst()->forClass('GitHubShortCode');
        
        
        $obj=new ViewableData();
        
        //Add the Respository Setting
        $obj->Repository=$arguments['repo'];
        
        //Add Layout
        if(array_key_exists('layout', $arguments) && ($arguments['layout']=='inline' || $arguments['layout']=='stacked')) {
            $obj->Layout=$arguments['layout'];
        }else {
            $obj->Layout='inline';
        }
        
        //Add the button config
        if(array_key_exists('show', $arguments) && ($arguments['show']=='both' || $arguments['show']=='stars' || $arguments['show']=='forks')) {
            $obj->ShowButton=$arguments['show'];
        }else {
            $obj->ShowButton='both';
        }
        
        
        //Retrieve Stats
        SS_Cache::set_cache_lifetime('GitHubShortCode', $config->CacheTime);
        
        $cacheKey=md5('GitHubShortCode_'.$arguments['repo']);
        $cache=SS_Cache::factory('GitHubShortCode');
        $cachedData=$cache->load($cacheKey);
        if($cachedData==null) {
            $response=self::getFromAPI($arguments['repo'], $config);
            
            //Verify a 200, if not say the repo errored out and cache false
            if(empty($response) || $response===false || !property_exists($response, 'watchers') || !property_exists($response, 'forks')) {
                $cachedData=array('stars'=>'N/A', 'forks'=>'N/A');
            }else {
                if($config->UseShortHandNumbers==true) {
                    $stargazers=self::shortHandNumber($response->stargazers_count);
                    $forks=self::shortHandNumber($response->forks);
                }else {
                    $stargazers=number_format($response->stargazers_count);
                    $forks=number_format($response->forks);
                }
                
                $cachedData=array('stargazers'=>$stargazers, 'forks'=>$forks);
            }
            
            //Cache response to file system
            $cache->save(serialize($cachedData), $cacheKey);
        }else {
            $cachedData=unserialize($cachedData);
        }
        
        
        $obj->Stargazers=$cachedData['stargazers'];
        $obj->Forks=$cachedData['forks'];
        
        
        
        //Init ss viewer and render
        Requirements::css(GITHUBSHORTCODE_BASE.'/css/GitHubButtons.css');
        
        $ssViewer=new SSViewer('GitHubButtons');
        return $ssViewer->process($obj);
    }
    
    /**
     * Loads the data from the github api
     * @param {string} $url URL to load
     * @param {Config_ForClass} $config Configuration object for this short code
     * @return {stdObject} Returns the JSON Response from the GitHub API
     * 
     * @see http://developer.github.com/v3/repos/#get
     */
    final protected static function getFromAPI($repo, Config_ForClass $config) {
        if(function_exists('curl_init') && $ch=curl_init()) {
            curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/'.$repo);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            
            //Configure Headers
            $headers=array("Content-type: application/json", "User-Agent: curl");
            if($config->UseBasicAuth) {
                if(empty($config->Username) || empty($config->Password)) {
                    user_error('Basic Auth enabled for GitHubShortCode but the username and password are not set', E_USER_ERROR);
                }
                
                $encoded=base64_encode($config->Username.':'.$config->Password);
                $headers[]="Authorization: Basic $encoded";
            }
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            
            $contents=json_decode(curl_exec($ch));
            curl_close($ch);
            
            return $contents;
        }else {
            user_error('CURL is not available', E_USER_ERROR);
        }
    }
    
    /**
     * Gets the short hand of the given number so 1000 becomes 1k, 2000 becomes 2k, and 1000000 becomes 1m etc
     * @param {int} $number Number to convert
     * @return {string} Short hand of the given number
     */
    protected static function shortHandNumber($number) {
	    if($number>=1000000000) {
	        return round($number/1000000000, 1).'B';
	    }else if($number>=1000000) {
	        return round($number/1000000, 1).'M';
	    }else if($number>=1000) {
	        return round($number/1000, 1).'K';
	    }
	    
	    return $number;
	}
}
?>