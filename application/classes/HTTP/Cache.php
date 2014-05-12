<?php defined('SYSPATH') or die('No direct script access.');

class HTTP_Cache extends Kohana_HTTP_Cache {

	public static function basic_cache_key_generator(Request $request)
	{
		$uri     = $request->uri();
		$query   = $request->query();

		return sha1($uri.'?'.http_build_query($query, NULL, '&'));
	}
}
