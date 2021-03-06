<?php
/**
 * Luxeritas WordPress Theme - free/libre wordpress platform
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @copyright Copyright (C) 2015 Thought is free.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 * @author LunaNuko
 * @link https://thk.kanzae.net/
 * @translators rakeem( http://rakeem.jp/ )
 */

if( class_exists( 'getSnsCount' ) === false ):
class getSnsCount {
	private $_ret = '!';
	private $_args = array(
		'timeout'	=> 30,
		'redirection'	=> 5,
		'httpversion'	=> '2',
		'user-agent'	=> 'Mozilla/9.9 (X11; Linux x86_64) AppleWebKit/999.99 (KHTML, like Gecko) Chrome/999.9.999.99 Safari/999.99',
		'blocking'	=> true,
		'compress'	=> true,
		'sslverify'	=> false,
	);

	public function __construct() {
	}

	public function numberUnformat( $number ) {
		return str_replace( ',', '', $number );
	}

	/* facebook count */
	public function facebookCount( $url, $app_id = '', $app_secret = '', $access_token = '' ) {
		/*
		$share = wp_remote_get( 'http://api.facebook.com/method/fql.query?format=json&query=select+total%5Fcount+from+link%5Fstat+where+url%3D%22'. urlencode($url). '%22', $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				//$this->_ret = @json_decode( $share['body'] )->shares - 0;
				$jsn = @json_decode( $share['body'], true );
				$this->_ret = isset( $jsn[0]['total_count'] ) ? $jsn[0]['total_count'] : 0;
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		*/

		// 旧 API で取得その１
		$share = wp_remote_get( 'https://graph.facebook.com/?id=' . $url . '&fields=og_object{engagement}', $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				$this->_ret = @json_decode( $share['body'] )->og_object->engagement->count;
				$this->_ret = $this->numberUnformat( $this->_ret );

				if( empty( $this->_ret ) ) {
					$id_confirm = @json_decode( $share['body'] );
					if( isset( $id_confirm->id ) ) {
						$this->_ret = 0;
					}
				}
			}
			elseif( $share['response']['code'] !== 200 ) {
				$this->_ret = '!';
			}
		}

		if( !is_numeric( $this->_ret ) ) {
			// スクレイピングで取得
			//$share = wp_remote_get( 'https://www.facebook.com/v2.11/plugins/like.php?href=' . $url . '&layout=button_count&action=like&show_faces=false&share=false&appId=' . $app_id, $this->_args );
			$share = wp_remote_get( 'https://www.facebook.com/plugins/like.php?href=' . $url . '&layout=button_count&action=like&show_faces=false&share=false&appId=' . $app_id, $this->_args );
			if( !is_wp_error( $share ) ) {
				if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
					// パターンその1
					preg_match( '/>([0-9,]+)<\/span><\/div><\/button>/imx', $share['body'], $match );
					if( isset( $match[1] ) ) {
						$this->_ret = $this->numberUnformat( $match[1] );
					}
					else {
						// パターンその2
						preg_match( '/class="pluginCountTextDisconnected">([0-9,]+)<\/span>/imx', $share['body'], $match );
						$this->_ret = isset( $match[1] ) ? $this->numberUnformat( $match[1] ) : '!';
					}
				}
				/*
				elseif( $share['response']['code'] !== 200 ) {
					if( empty( $app_id ) && empty( $app_secret ) && empty( $access_token ) ) {
						return $share['response']['message'];
					}
					else {
						$this->_ret = $share['response']['message'];
					}
				}
				*/
				else {
					$this->_ret = '!';
				}
			}
		}

		if( !is_numeric( $this->_ret ) ) {
			// 新 API で取得
			if( !empty( $app_id ) && !empty( $app_secret ) && !empty( $access_token ) ) {
				$share = wp_remote_get( 'https://graph.facebook.com/v6.0/?fields=og_object{engagement}&access_token=' . $access_token . '&id=' . $url . '', $this->_args );
				if( !is_wp_error( $share ) ) {
					if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
						$this->_ret = @json_decode( $share['body'] )->og_object->engagement->count;
						$this->_ret = $this->numberUnformat( $this->_ret );
					}
					elseif( $share['response']['code'] !== 200 ) {
						return $share['response']['message'];
					}
				}
			}
		}

		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* pinterest count */
	public function pinterestCount( $url ) {
		$share = wp_remote_get( 'https://api.pinterest.com/v1/urls/count.json?url=' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				$this->_ret = rtrim( $share['body'] , ');' ) ;
				$this->_ret = ltrim( $this->_ret , 'receiveCount(' ) ;
				$this->_ret = @json_decode( $this->_ret )->count;
				$this->_ret = $this->numberUnformat( $this->_ret ); 
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* linkedin count */
	/*
	public function linkedinCount( $url ) {
		$share = wp_remote_get( 'https://www.linkedin.com/countserv/count/share?format=json&url=' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				$this->_ret = @json_decode( $share['body'] )->count;
				$this->_ret = $this->numberUnformat( $this->_ret ); 
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}
	*/

	/* hatena count */
	public function hatenaCount( $url ) {
		$share = wp_remote_get( 'https://api.b.st-hatena.com/entry.count?url=' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && !empty( $share['body'] ) ) {
				$this->_ret = $this->numberUnformat( $share['body'] );
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
			else {
				$this->_ret = 0;
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* pocket count */
	public function pocketCount( $url ) {
		//$share = wp_remote_get( 'https://widgets.getpocket.com/v1/button?v=1&count=horizontal&url=' . $url .'&src=https', $this->_args );
		$share = wp_remote_get( 'https://widgets.getpocket.com/api/saves?url=' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 ) {
				if( is_array( $share ) ) {
					$share_cnt = @json_decode( $share['body'] );
				}
				if( !isset( $share_cnt->saves ) ) {
					$this->_ret = 0;
				}
				else {
					$this->_ret = $share_cnt->saves;
				}
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* feedly count */
	public function feedlyCount( $url ) {
		$share = wp_remote_get( 'http://cloud.feedly.com/v3/feeds/feed%2F' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				$this->_ret = @json_decode( $share['body'] )->subscribers;
				if( empty( $this->_ret ) ) {
					$this->_ret = 0;
				}
				else {
					$this->_ret = $this->numberUnformat( $this->_ret ); 
				}
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}
}
endif;
