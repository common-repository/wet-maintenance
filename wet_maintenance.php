<?php
/*
Plugin Name: Maintenance
Plugin URI: http://talkpress.de/blip/wet-maintenance-wordpress-plugin
Description: Puts the site into maintenance mode by sending a '503 Service Unavailable' status to all unauthenticated clients.
Author: Robert Wetzlmayr
Version: 4.4
Author URI: http://wetzlmayr.com/
License: GPL 2.0, @see http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!function_exists('wet_maintenance_header')):
    function wet_maintenance_header($status_header, $header, $text, $protocol)
    {
        if (!is_user_logged_in()) {
            return "$protocol 503 Service Unavailable";
        }
    }
endif;

if (!function_exists('wet_maintenance_content')):
    function wet_maintenance_content()
    {
        if (!is_user_logged_in()) {
            $page = <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>Service unavailable.</title>
<style>
body {
    background-color: #e0dcdc;
    color: #444;
    font-family: "Open Sans",sans-serif;
    font-size: 13px;
    line-height: 1.4em;
}
body > div {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 0 5px #888;
    margin: 3em auto;
    padding: 2em;
    text-align: center;
    width: 20%;
    min-width: 20em;
}
</style>
</head>
<body>
<div>
<h1>Service unavailable.</h1>
<p>Please check back later&hellip;</p>
</div>
</body>
</html>
EOT;
            die($page);
        }
    }
endif;

if (!function_exists('wet_maintenance_feed')):
    function wet_maintenance_feed()
    {
        if (!is_user_logged_in()) {
            die('<?xml version="1.0" encoding="UTF-8"?>' .
                '<status>Service unavailable.</status>');
        }
    }
endif;

if (!function_exists('wet_add_feed_actions')):
    function wet_add_feed_actions()
    {
        $feeds = array('rdf', 'rss', 'rss2', 'atom');
        foreach ($feeds as $feed) {
            add_action('do_feed_' . $feed, 'wet_maintenance_feed', 1, 1);
        }
    }
endif;

if (function_exists('add_filter')):
    add_filter('status_header', 'wet_maintenance_header', 10, 4);
    add_action('get_header', 'wet_maintenance_content');
    wet_add_feed_actions();
else:
// Prevent direct invocation by user agents.
    die('Get off my lawn!');
endif;
