<?php
ini_set('display_errors', '1');
require_once __DIR__.'/../vendor/autoload.php';
use andreskrey\Readability\Readability;
use andreskrey\Readability\Configuration;
use andreskrey\Readability\ParseException;

class Wikimedia{
    private static $wikimedia_api_url = "https://en.wikipedia.org/w/api.php";
    
    public static function getArticlesReadabilityByCategoryName($categoryName){
        $params = [
            "action" => "query",
            "list" => "categorymembers",
            'cmtitle' => "Category:$categoryName",
            'cmlimit' => 50,
            'format' => "json"
        ];
        
        $url = self::$wikimedia_api_url.'?'. self::paramsToString($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
   
        $result_object = json_decode($result);
        $articles = isset($result_object->query->categorymembers) ? $result_object->query->categorymembers : [];
        $articles_readability = [];
        
        if($articles){ //IF articles are found then extract each article's information to rate their content readability
            $article_names = [];
            foreach ($articles as $article){
                $article_names[] = $article->title;
            }
            $articleTitles = implode("|", $article_names);
            $articles_result = self::extractContentByArticlesTitle($articleTitles); //Get all category articles with extracted information
            $articles_extract = isset($articles_result->query->pages) ? $articles_result->query->pages : [];
            foreach ($articles_extract as $extract) {
                //@TODO get value from $extract that we want to rate and pass it to self::getContentReadability method
                $articles_readability[self::getContentReadability("Pass content to rate")] = $extract->title; //Get articles extracted information readability
            }
            ksort($articles_readability); //Sort articles by readability key
        }
    
        return $articles_readability;
    }
    
    /***
     * @TODO: clean up this method.  Need to determine what content to pull for readibility rating
     * It currently just pulls the first 5 sentences of each article 
     * @params: it takes an array of article titles that need to be queried
     */
    public static function extractContentByArticlesTitle($articleTitles){
        $params = [
            "action" => "query",
            "prop" => "extracts",
            "exsentences" => 5,
            "titles" => urlencode($articleTitles),
            "explaintext" => true,
            "exlimit" => 1,
            'format' => "json"
        ];
        
        $url = self::$wikimedia_api_url.'?'. self::paramsToString($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($result);
    }
    
    /**
     * @TODO  Apply readability using andresrey library
     * https://github.com/andreskrey/readability.php
     * It currently just returns a random number between 1 - 500
     * @param: String that needs to be rated for readability
     */
    public static function getContentReadability($content){
        //$readability = new Readability(new Configuration());
        //$readability->parse($content);
        return mt_rand(1, 500);
    }
    
    private static function paramsToString($params){
        $params_string = '';
        foreach($params as $key=>$value){
            $params_string .= $key.'='.$value.'&';
        }
        return trim($params_string, '&');
    }
}

