<?php

/**
 * @link       http://jasonackerman.com
 * @since      1.0.0
 *
 * @package    Ot_Latest_Tweets
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

add_shortcode('latest-tweets', 'ms_latest_tweets');

function ms_latest_tweets( $atts ) {
    extract( shortcode_atts( array (
        'screen_name' => '',
        'count' => 1
    ), $atts ) );
    
    ob_start();

    $token = get_option( 'twitter_token', false );
    $token_secret = get_option( 'twitter_token_secret', false );
    $consumer_key = get_option( 'consumer_key', false );
    $consumer_secret = get_option( 'consumer_secret', false );

    $host = 'api.twitter.com';
    $method = 'GET';
    // This is the endpoint you are hitting
    $path = '/1.1/statuses/user_timeline.json'; // api call path

    if ($screen_name) :

        $query = array( // query parameters
            'screen_name' => $screen_name,
            'count' => $count
        );

    else : 

        die('Error: You must include a screen name.');

    endif;

    $oauth = array(
        'oauth_consumer_key' => $consumer_key,
        'oauth_token' => $token,
        'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
        'oauth_timestamp' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_version' => '1.0'
    );

    $oauth = array_map("rawurlencode", $oauth); // must be encoded before sorting
    $query = array_map("rawurlencode", $query);

    $arr = array_merge($oauth, $query); // combine the values THEN sort

    asort($arr); // secondary sort (value)
    ksort($arr); // primary sort (key)

    // http_build_query automatically encodes, but our parameters
    // are already encoded, and must be by this point, so we undo
    // the encoding step
    $querystring = urldecode(http_build_query($arr, '', '&'));

    $url = "https://$host$path";

    // mash everything together for the text to hash
    $base_string = $method."&".rawurlencode($url)."&".rawurlencode($querystring);

    // same with the key
    $key = rawurlencode($consumer_secret)."&".rawurlencode($token_secret);

    // generate the hash
    $signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));

    // this time we're using a normal GET query, and we're only encoding the query params
    // (without the oauth params)
    $url .= "?".http_build_query($query);
    $url=str_replace("&amp;","&",$url); //Patch by @Frewuill

    $oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
    ksort($oauth); // probably not necessary, but twitter's demo does it

    // also not necessary, but twitter's demo does this too
    function add_quotes($str) { return '"'.$str.'"'; }
    $oauth = array_map("add_quotes", $oauth);

    // this is the full value of the Authorization line
    $auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));

    // if you're doing post, you need to skip the GET building above
    // and instead supply query parameters to CURLOPT_POSTFIELDS
    $options = array( CURLOPT_HTTPHEADER => array("Authorization: $auth"),
                      //CURLOPT_POSTFIELDS => $postfields,
                      CURLOPT_HEADER => false,
                      CURLOPT_URL => $url,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_SSL_VERIFYPEER => false);

    // do our business
    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);

    // Gets response and decodes to a PHP array
    $twitter_data = json_decode($json, true);
    
    echo '<div class="tweet-title"><h3>Latest Tweets</h3></div>';

    foreach ($twitter_data as $tweet) {

        // API Docs for this endpoint are here: https://dev.twitter.com/rest/reference/get/statuses/user_timeline
    
        // This replaces the URLs within the tweet that are plain text with actual links
        $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
        $text = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $tweet['text']);
        // Strips the timestamp off the end of the tweet
        $date = substr($tweet['created_at'], 0, -10);

        // Now display the tweet
        echo '<p><span class="tweet-text">' . $text . '</span><br /><span class="tweet-date">' . $date . '</span></p>';

    }

    echo '<p><a class="tweet-link" target="_blank" href="http://twitter.com/'.$screen_name.'" class="-arrow-after">Follow @'.$screen_name.'</a></p>';
    
    // Return the results of the shortcode
    $tweets = ob_get_clean();
    return $tweets;

}