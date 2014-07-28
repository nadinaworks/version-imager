<?php
$cache_life = 60; //caching time, in seconds
$download = false;

//config for each theme
$theme_config = array (
	'rosa' => array (
		'dest_dir' => '../demos/rosa2/assets/',
	),
	'heap' => array (
		'dest_dir' => '../demos/heap/assets/',
	),
	'border' => array (
		'dest_dir' => '../demos/border/assets/',
	),
	'bucket' => array (
		'dest_dir' => '../demos/bucket/assets/',
	),
	'lens' => array (
		'dest_dir' => '../demos/lens/assets/',
	),
	'fuse' => array (
		'dest_dir' => '../demos/fuse/assets/',
	),
	'senna' => array (
		'dest_dir' => '../demos/senna/assets/',
	),
);
if (!isset($_REQUEST['theme'])) {
    exit();
}

$url = 'http://pixelgrade.com/version-imager/version-bubble.php';

$url_segs = parse_url($url);
if (!isset($url_segs['host'])) {
    exit();
}

$here = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$bin_files = '/usr/local/bin' . DIRECTORY_SEPARATOR;
$jobs = $here . 'jobs' . DIRECTORY_SEPARATOR;
$destination = $here . $theme_config[$_REQUEST['theme']]['dest_dir'];

if (!is_dir($jobs)) {
    mkdir($jobs);
    file_put_contents($jobs . 'index.php', '<?php exit(); ?>');

}
if (!is_dir($destination)) {
    mkdir($destination);
    file_put_contents($destination . 'index.php', '<?php exit(); ?>');

}


$w = 1024;
$h = 768;
if (isset($_REQUEST['w'])) {
    $w = intval($_REQUEST['w']);
}

if (isset($_REQUEST['h'])) {
    $h = intval($_REQUEST['h']);
}

if (isset($_REQUEST['clipw'])) {
    $clipw = intval($_REQUEST['clipw']);
}

if (isset($_REQUEST['cliph'])) {
    $cliph = intval($_REQUEST['cliph']);
}

if (isset($_REQUEST['download'])) {
    $download = $_REQUEST['download'];
}

$url = strip_tags($url);
$url = str_replace(';', '', $url);
$url = str_replace('"', '', $url);
$url = str_replace('\'', '/', $url);
$url = str_replace('<?', '', $url);
$url = str_replace('<?', '', $url);
$url = str_replace('\077', ' ', $url);

$url_params = array();
if (isset($_REQUEST['date'])) {
    $url_params['date'] = urlencode($_REQUEST['date']);
}
if (isset($_REQUEST['version'])) {
    $url_params['version'] = urlencode($_REQUEST['version']);
}
if (isset($_REQUEST['content'])) {
    $url_params['content'] = urlencode($_REQUEST['content']);
}

//add the needed params to the url
$url = add_query_arg($url_params, $url);

$screen_file = sanitize_file_name($_REQUEST['theme'].'-update-'.str_replace('.','-',$_REQUEST['version'])).'.png';
$cache_job = $destination . $screen_file;


$refresh = false;
if (is_file($cache_job)) {
    $filemtime = @filemtime($cache_job); // returns FALSE if file does not exist
    if (!$filemtime or (time() - $filemtime >= $cache_life)) {
        $refresh = true;
    }
}


$url = escapeshellcmd($url);

if (!is_file($cache_job) or $refresh == true) {
    $src = "

    var page = require('webpage').create();

    page.viewportSize = { width: {$w}, height: {$h} };

    ";

    if (isset($clipw) && isset($cliph)) {
        $src .= "page.clipRect = { top: 0, left: 0, width: {$clipw}, height: {$cliph} };";
    }

    $src .= "

    page.open('{$url}', function () {
        page.render('{$screen_file}', { format: 'png', quality: 90 });
        phantom.exit();
    });


    ";

    $job_file = $jobs . $url_segs['host'] . crc32($src) . '.js';
    file_put_contents($job_file, $src);

    $exec = $bin_files . 'phantomjs ' . $job_file;
	
    $escaped_command = escapeshellcmd($exec);

    exec($escaped_command);

    if (is_file($here . $screen_file)) {
        rename($here . $screen_file, $cache_job);
    }
}


if (is_file($cache_job)) {
    if ($download != false) {
        $file = $cache_job;
        $file_name=basename($file);
        $type = 'image/png';
        header("Content-disposition: attachment; filename={$file_name}");
        header("Content-type: {$type}");
        readfile($file);
    } else {
        $file = $cache_job;
        $type = 'image/png';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }


}

function get_dispatch($url) {
    // Split the URL into its constituent parts.
    $parse = parse_url($url);

    // Remove the leading forward slash, if there is one.
    $path = ltrim($parse['path'], '/');

    // Put each element into an array.
    $elements = explode('/', $path);

    // Create a new empty array.
    $args = array();

    // Loop through each pair of elements.
    for( $i = 0; $i < count($elements); $i = $i + 2) {
        $args[$elements[$i]] = $elements[$i + 1];
    }

    return $args;
}

/**
 * Retrieve a modified URL query string.
 *
 * You can rebuild the URL and append a new query variable to the URL query by
 * using this function. You can also retrieve the full URL with query data.
 *
 * Adding a single key & value or an associative array. Setting a key value to
 * an empty string removes the key. Omitting oldquery_or_uri uses the $_SERVER
 * value. Additional values provided are expected to be encoded appropriately
 * with urlencode() or rawurlencode().
 *
 * @since 1.5.0
 *
 * @param mixed $param1 Either newkey or an associative_array
 * @param mixed $param2 Either newvalue or oldquery or uri
 * @param mixed $param3 Optional. Old query or uri
 * @return string New URL query string.
 */
function add_query_arg() {
	$ret = '';
	$args = func_get_args();
	if ( is_array( $args[0] ) ) {
		if ( count( $args ) < 2 || false === $args[1] )
			$uri = $_SERVER['REQUEST_URI'];
		else
			$uri = $args[1];
	} else {
		if ( count( $args ) < 3 || false === $args[2] )
			$uri = $_SERVER['REQUEST_URI'];
		else
			$uri = $args[2];
	}

	if ( $frag = strstr( $uri, '#' ) )
		$uri = substr( $uri, 0, -strlen( $frag ) );
	else
		$frag = '';

	if ( 0 === stripos( $uri, 'http://' ) ) {
		$protocol = 'http://';
		$uri = substr( $uri, 7 );
	} elseif ( 0 === stripos( $uri, 'https://' ) ) {
		$protocol = 'https://';
		$uri = substr( $uri, 8 );
	} else {
		$protocol = '';
	}

	if ( strpos( $uri, '?' ) !== false ) {
		list( $base, $query ) = explode( '?', $uri, 2 );
		$base .= '?';
	} elseif ( $protocol || strpos( $uri, '=' ) === false ) {
		$base = $uri . '?';
		$query = '';
	} else {
		$base = '';
		$query = $uri;
	}

	wp_parse_str( $query, $qs );
	$qs = urlencode_deep( $qs ); // this re-URL-encodes things that were already in the query string
	if ( is_array( $args[0] ) ) {
		$kayvees = $args[0];
		$qs = array_merge( $qs, $kayvees );
	} else {
		$qs[ $args[0] ] = $args[1];
	}

	foreach ( $qs as $k => $v ) {
		if ( $v === false )
			unset( $qs[$k] );
	}

	$ret = build_query( $qs );
	$ret = trim( $ret, '?' );
	$ret = preg_replace( '#=(&|$)#', '$1', $ret );
	$ret = $protocol . $base . $ret . $frag;
	$ret = rtrim( $ret, '?' );
	return $ret;
}

/**
 * Removes an item or list from the query string.
 *
 * @since 1.5.0
 *
 * @param string|array $key Query key or keys to remove.
 * @param bool $query When false uses the $_SERVER value.
 * @return string New URL query string.
 */
function remove_query_arg( $key, $query=false ) {
	if ( is_array( $key ) ) { // removing multiple keys
		foreach ( $key as $k )
			$query = add_query_arg( $k, false, $query );
		return $query;
	}
	return add_query_arg( $key, false, $query );
}

/**
 * Parses a string into variables to be stored in an array.
 *
 * Uses {@link http://www.php.net/parse_str parse_str()} and stripslashes if
 * {@link http://www.php.net/magic_quotes magic_quotes_gpc} is on.
 *
 * @since 2.2.1
 *
 * @param string $string The string to be parsed.
 * @param array $array Variables will be stored in this array.
 */
function wp_parse_str( $string, &$array ) {
	parse_str( $string, $array );
	if ( get_magic_quotes_gpc() )
		$array = stripslashes_deep( $array );
}

/**
 * Navigates through an array and removes slashes from the values.
 *
 * If an array is passed, the array_map() function causes a callback to pass the
 * value back to the function. The slashes from this value will removed.
 *
 * @since 2.0.0
 *
 * @param mixed $value The value to be stripped.
 * @return mixed Stripped value.
 */
function stripslashes_deep($value) {
	if ( is_array($value) ) {
		$value = array_map('stripslashes_deep', $value);
	} elseif ( is_object($value) ) {
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) {
			$value->{$key} = stripslashes_deep( $data );
		}
	} elseif ( is_string( $value ) ) {
		$value = stripslashes($value);
	}

	return $value;
}

/**
 * Navigates through an array and encodes the values to be used in a URL.
 *
 *
 * @since 2.2.0
 *
 * @param array|string $value The array or string to be encoded.
 * @return array|string $value The encoded array (or string from the callback).
 */
function urlencode_deep($value) {
	$value = is_array($value) ? array_map('urlencode_deep', $value) : urlencode($value);
	return $value;
}

/**
 * Navigates through an array and raw encodes the values to be used in a URL.
 *
 * @since 3.4.0
 *
 * @param array|string $value The array or string to be encoded.
 * @return array|string $value The encoded array (or string from the callback).
 */
function rawurlencode_deep( $value ) {
	return is_array( $value ) ? array_map( 'rawurlencode_deep', $value ) : rawurlencode( $value );
}

/**
 * Build URL query based on an associative and, or indexed array.
 *
 * This is a convenient function for easily building url queries. It sets the
 * separator to '&' and uses _http_build_query() function.
 *
 * @see _http_build_query() Used to build the query
 * @link http://us2.php.net/manual/en/function.http-build-query.php more on what
 *		http_build_query() does.
 *
 * @since 2.3.0
 *
 * @param array $data URL-encode key/value pairs.
 * @return string URL encoded string
 */
function build_query( $data ) {
	return _http_build_query( $data, null, '&', '', false );
}

// from php.net (modified by Mark Jaquith to behave like the native PHP5 function)
function _http_build_query($data, $prefix=null, $sep=null, $key='', $urlencode=true) {
	$ret = array();

	foreach ( (array) $data as $k => $v ) {
		if ( $urlencode)
			$k = urlencode($k);
		if ( is_int($k) && $prefix != null )
			$k = $prefix.$k;
		if ( !empty($key) )
			$k = $key . '%5B' . $k . '%5D';
		if ( $v === null )
			continue;
		elseif ( $v === FALSE )
			$v = '0';

		if ( is_array($v) || is_object($v) )
			array_push($ret,_http_build_query($v, '', $sep, $k, $urlencode));
		elseif ( $urlencode )
			array_push($ret, $k.'='.urlencode($v));
		else
			array_push($ret, $k.'='.$v);
	}

	if ( null === $sep )
		$sep = ini_get('arg_separator.output');

	return implode($sep, $ret);
}

/**
 * Sanitizes a filename, replacing whitespace with dashes.
 *
 * Removes special characters that are illegal in filenames on certain
 * operating systems and special characters requiring special escaping
 * to manipulate at the command line. Replaces spaces and consecutive
 * dashes with a single dash. Trims period, dash and underscore from beginning
 * and end of filename.
 *
 * @since 2.1.0
 *
 * @param string $filename The filename to be sanitized
 * @return string The sanitized filename
 */
function sanitize_file_name( $filename ) {
	$filename_raw = $filename;
	$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));

	$filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
	$filename = str_replace($special_chars, '', $filename);
	$filename = preg_replace('/[\s-]+/', '-', $filename);
	$filename = trim($filename, '.-_');

	// Split the filename into a base and extension[s]
	$parts = explode('.', $filename);

	// Return if only one extension
	if ( count( $parts ) <= 2 ) {
		return $filename;
	}

	// Process multiple extensions
	$filename = array_shift($parts);
	$extension = array_pop($parts);
	$mimes = get_allowed_mime_types();

	/*
	 * Loop over any intermediate extensions. Postfix them with a trailing underscore
	 * if they are a 2 - 5 character long alpha string not in the extension whitelist.
	 */
	foreach ( (array) $parts as $part) {
		$filename .= '.' . $part;

		if ( preg_match("/^[a-zA-Z]{2,5}\d?$/", $part) ) {
			$allowed = false;
			foreach ( $mimes as $ext_preg => $mime_match ) {
				$ext_preg = '!^(' . $ext_preg . ')$!i';
				if ( preg_match( $ext_preg, $part ) ) {
					$allowed = true;
					break;
				}
			}
			if ( !$allowed )
				$filename .= '_';
		}
	}
	$filename .= '.' . $extension;

	return $filename;
}

 
