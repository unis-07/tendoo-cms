<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
 * Force a var to be an array.
 *
 * @param Var
 * @return Array
**/
function force_array( $array )
{
	if( is_array( $array ) )
	{
		return $array;
	}
	return array();
}

/** 
 * Output message with error tag
 *
 * @param String (error code)
 * @return String (Html result)
 * @package 3.0
**/

if(!function_exists('tendoo_error'))
{
	function tendoo_error($text)
	{
		return '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button><i style="font-size:18px;margin-right:5px;" class="fa fa-warning"></i> '.$text.'</div>';
	}
}

/** 
 * Output message with success tag
 *
 * @param String (error code)
 * @return String (Html result)
**/

if(!function_exists('tendoo_success'))
{
	function tendoo_success($text)
	{
		return '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button><i style="font-size:18px;margin-right:5px;" class="fa fa-thumbs-o-up"></i> '.$text.'</div>';
	}
}

/** 
 * Output message with warning tag
 *
 * @param String (error code)
 * @return String (Html result)
**/

if(!function_exists('tendoo_warning'))
{
	function tendoo_warning($text)
	{
		return '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button><i style="font-size:18px;margin-right:5px;" class="fa fa-warning"></i> '.$text.'</div>';
	}
}

/** 
 * Output message with Info tag
 *
 * @param String (error code)
 * @return String (Html result)
**/

if(!function_exists('tendoo_info'))
{
	function tendoo_info($text)
	{
		return '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button><i style="font-size:18px;margin-right:5px;" class="fa fa-info"></i> '.$text.'</div>';;
	}
}

/** 
 * Convert short string to long notice message
 *
 * @param String (error code)
 * @return String (Html result)
**/

if(!function_exists('fetch_error'))
{
	function fetch_notice_output($e,$extends_msg= '',$sort = FALSE)
	{
		
		if($e === TRUE)
		{
			?><style>
			.notice_sorter
			{
				border:solid 1px #999;
				color:#333;
			}
			.notice_sorter thead td
			{
				padding:2px 10px;
				text-align:center;
				background:#EEE;
				background:-moz-linear-gradient(top,#EEE,#CCC);
				border:solid 1px #999;
			}
			.notice_sorter tbody td
			{
				padding:2px 10px;
				text-align:justify;
				background:#FFF;
				border:solid 1px #999;
			}
			</style><table class="notice_sorter"><thead>
            <style>
			.notice_sorter
			{
				border:solid 1px #999;
				color:#333;
			}
			.notice_sorter thead td
			{
				padding:2px 10px;
				text-align:center;
				background:#EEE;
				background:-moz-linear-gradient(top,#EEE,#CCC);
				border:solid 1px #999;
			}
			.notice_sorter tbody td
			{
				padding:2px 10px;
				text-align:justify;
				background:#FFF;
				border:solid 1px #999;
			}
			</style>
            <tr><td>Index</td><td>Code</td><td>Description</td></tr></thead><tbody><?php    
			$index		=	1;
			foreach($__ as $k => $v)
			{
				?><tr><td><?php echo $index;?></td><td><?php echo $k;?></td><td><?php echo strip_tags($v);?></td></tr><?php
				$index++;
			}
			?></tbody></table><?php
		}
		else
		{
			if(is_string($e))
			{
				$notices		=	force_array( get_core_vars( 'tendoo_notices' ) );
				if( in_array( $e , $notices ) || array_key_exists( $e , $notices ) )
				{
					return $notices[$e];
				}
				else if(isset($notices))
				{
					if(array_key_exists($e,$notices))
					{
						return $notices[$e];
					}
					else
					{
						return tendoo_warning( __( sprintf( '"%s" is not a valid error code' , $e ) ) );
					}
				}
				else if($e != '' && strlen($e) <= 50)
				{
					return tendoo_warning( __( sprintf( '"%s" is not a valid error code' , $e ) ) );
				}
				else
				{
					return $e;
				}
			}
			return false;
		}
	}
}

/** 
 * Output message from URL
 *
 * @param String (error code)
 * @return String (Html result)
**/

if(!function_exists('fetch_notice_from_url'))
{
	function fetch_notice_from_url()
	{
		$notice = ''; 		
		if( isset( $_GET['notice'] ) )
		{
			$notice	= get_instance()->lang->line( $_GET['notice'] );
		}
		return $notice;
	}
}

/** 
 * Return true or false if numeric var is between two numbers
 *
 * @param Int( Min ), Int( Max ), $subject
 * @return Bool 
**/

if(!function_exists('between'))
{
	function between($min,$max,$var) // Site Url Plus
	{
		if($min >= $max || $min == $max)
		{
			return FALSE;
		}
		if((int)$var >= $min && (int)$var <= $max)
		{
			return TRUE;
		}
		return FALSE;
	}
}

/**
*	Returns if array key exists. If not, return false if $default is not set or return $default instead
*	@access		:	Public
*	@params		:	String (Key), $subject, $default
**/

function riake( $key , $subject, $default = false ){	
	if( is_array( $subject ) )
	{
		return array_key_exists($key, $subject) ? $subject[ $key ] : $default;
	}
	return $default;
}

/**
 * Return first index from a given Array
 * 
 * @access 	:	public
 * @param 	:	Array
 * @return 	:	Array/False
 * @note 	:	Return False if index doesn't exists or if param is not an array.
**/

function farray( $array )
{
	return riake( 0 , $array , false );
}

/**
*	set_core_vars
*	Ajoute une valeur au tableau du system
**/

function set_core_vars($key , $value , $access = 'writable')
{
	$config		=	get_instance()->config->item( 'tendoo' );	
	// Checks if var can be overwritten
	if( $var	=	riake ( $key , $config ) )
	{
		if( riake( 'access' , $var ) != 'readonly' )
		{
			$config[ $key ]	=	array(
				'access'	=>	$access,
				'var'		=>	$value
			);
			get_instance()->config->set_item( 'tendoo' , $config );
			return true;
		}
		return false;
	}
	else
	{
		$config[ $key ]		=	array(
			'access'		=>	$access,
			'var'			=>	$value
		);
		
		get_instance()->config->set_item( 'tendoo' , $config );
		return true;
	}
	return false;
}

/**
*	get_core_vars()
*	Recupère un champ sur le tableau du système.
**/

function get_core_vars($key = null)
{
	$config		=	get_instance()->config->item( 'tendoo' );	
	if( $key == null )
	{
		$core_vars	=	array();
		// valeur plus accessibilité (read_only ou writable)
		foreach( $config as $_key	=>	$vars)
		{
			$core_vars[ $_key ] =	riake( 'var' , $vars );
		}
		return $core_vars;
	}
	else
	{
		return riake( 'var' , riake( $key , $config ) );
	}
}

/**
*	push_core_vars : ajoute une nouvelle valeur à un tableau déjà existant dans le tableau du noyau
*  Deprecated
**/

function push_core_vars( $key , $var , $value = null ){
	$vars	=	get_core_vars( $key );
	if( $vars ){
		if( $value != null ){
			$vars[ $var ] =	$value;
			return set_core_vars( $key , $vars );
		} else {
			$vars[]	=	$var;
			return set_core_vars( $key , $vars );
		}
	} else { // if value is not set create an array
		$vars		=	array();
		if( $value != null ){
			$vars[ $var ] =	$value;
			return set_core_vars( $key , $vars );
		} else {
			$vars[]	=	$var;
			return set_core_vars( $key , $vars );
		}
	}
	return false;
};
/**
*	get recupère des informations sur le système.
**/
function get($key) // add to doc
{
	$instance	=	get_instance();
	switch($key)
	{
		case "str_core"		: 
			return $instance->config->item( 'version' );
		break;
		case "core_version"	:
			return (float) $instance->config->item( 'version' );
		break;
		case "core_signature"	:
			return $instance->config->item( 'core_signature' );
		break;
		case "declared-shortcuts"	:
			return get_declared_shortcuts();
		break;				
	}
}

if(!function_exists('translate')) // gt = Get Text
{
	function __( $code , $templating = null )
	{
		return translate( $code , $templating );
	}
	function _e( $code , $templating = null )
	{
		echo __( $code , $templating );
	}
	function translate( $code , $textdomain = 'tendoo-core' )
	{
		$final_lines	=	array();
		$instance		=	get_instance();
		$heavy__		=	array();
		
		if( in_array( $instance->config->item( 'language' ) , $instance->config->item( 'supported_lang' ) ) )
		{
			// Lang Recorder is only enabled while en_US lang is activated
			if( LANG_RECORDER_ENABLED == true && $textdomain = 'tendoo-core' )
			{
				if( ! file_exists( APPPATH . 'language/' . $instance->config->item( 'language' ) . '/' . $instance->config->item( 'language' ) . '.po' ) )
				{
					$lang_file	=	fopen( APPPATH . 'language/' . $instance->config->item( 'language' ) . '/' . $instance->config->item( 'language' ) . '.po' , 'a+' );
					fwrite( $lang_file , 'msgid ""' . PHP_EOL );
					fwrite( $lang_file , 'msgstr ""' . PHP_EOL );
					fwrite( $lang_file , '"Plural-Forms: nplurals=2; plural=(n != 1);\n"' . PHP_EOL );
					fwrite( $lang_file , '"Project-Id-Version: Tendoo CMS Translation\n"' . PHP_EOL );
					fwrite( $lang_file , '"Last-Translator: Translate <language@tenoo.org>\n"' . PHP_EOL );
					fwrite( $lang_file , '"POT-Creation-Date: \n"' . PHP_EOL );
					fwrite( $lang_file , '"PO-Revision-Date: \n"' . PHP_EOL );
					fwrite( $lang_file , '"Last-Translator: \n"' . PHP_EOL );
					fwrite( $lang_file , '"Language-Team: Tendoo Lang Team\n"' . PHP_EOL );
					fwrite( $lang_file , '"MIME-Version: 1.0\n"' . PHP_EOL );
					fwrite( $lang_file , '"Content-Type: text/plain; charset=UTF-8\n"' . PHP_EOL );
					fwrite( $lang_file , '"Content-Transfer-Encoding: 8bit\n"' . PHP_EOL );
					fwrite( $lang_file , '"Language: en_US\n"' . PHP_EOL );
					fwrite( $lang_file , '"X-Generator: Tendoo ' . get( 'core_id' ) . '\n"' . PHP_EOL );
					fwrite( $lang_file , '"X-Poedit-SourceCharset: UTF-8\n"' . PHP_EOL );
					fwrite( $lang_file , PHP_EOL );
					fclose( $lang_file );
				}
				
				$lang_file	=	fopen( APPPATH . 'language/' . $instance->config->item( 'language' )  . '/' . $instance->config->item( 'language' ) . '.po' , 'a+' );
				
				while ( ( $line = fgets( $lang_file ) ) !== false ) {
					if( substr( $line , 0 , 5 ) == 'msgid' )
					{
						$msgid	=	explode( '"' , $line );
						$latest	=	riake( 1 , $msgid );
					}
					if( substr( $line , 0 , 6 ) == 'msgstr' )
					{
						$msgstr	=	explode( '"' , $line );
						$heavy__[ $latest ]	=	riake( 1 , $msgstr );
					}
				}
				
				fclose( $lang_file );
				
				if( !in_array( htmlentities( $code , ENT_QUOTES ) , array_keys( $heavy__ ) ) )
				{
					$bt 		= debug_backtrace();
					$caller 	= array_shift($bt);
					
					$lang_file	=	fopen( APPPATH . 'language/' . $instance->config->item( 'language' )  . '/' . $instance->config->item( 'language' ) . '.po' , 'a+' );
					
					fwrite( $lang_file , '#: ' . $caller[ 'file' ] . ':' . $caller[ 'line' ] . PHP_EOL );
					fwrite( $lang_file , 'msgid "' . htmlentities( $code , ENT_QUOTES ) . '"' . PHP_EOL );
					fwrite( $lang_file , 'msgstr "' . htmlentities( $code , ENT_QUOTES ) . '"' . PHP_EOL );
					fwrite( $lang_file , PHP_EOL );
					fclose( $lang_file );
				}
			}
			
		}
		if( file_exists( APPPATH . 'language/' . $instance->config->item( 'language' ) . '/' . $instance->config->item( 'language' ) . '.po' ) )
		{
			$lang_file	=	fopen( APPPATH . 'language/' . $instance->config->item( 'language' ) . '/' . $instance->config->item( 'language' ) . '.po' , 'r' );
			while ( ( $line = fgets( $lang_file ) ) !== false ) {
				if( substr( $line , 0 , 5 ) == 'msgid' )
				{
					$msgid	=	explode( '"' , $line );
					$latest	=	riake( 1 , $msgid );
				}
				if( substr( $line , 0 , 6 ) == 'msgstr' )
				{
					$msgstr	=	explode( '"' , $line );
					$heavy__[ $latest ]	=	riake( 1 , $msgstr );
				}
			}
			fclose( $lang_file );
		}
		return riake( htmlentities( $code , ENT_QUOTES ) , $heavy__ , $code );
	}
}

/** 
 * Output array details
 * @access public
 * @param Array
 * @param Bool
 * @return String
**/
function print_array( $array , $return = FALSE )
{
	ob_start();
	echo '<pre>';
	print_r( $array , $return );
	echo '</pre>';
	return $return ? ob_get_clean() : null;
}

/**
 * Pagination Helper
 *
 * @access public
 * @param int
 * @param int
 * @param int
 * @param string
 * @param string url
 * @param string url
 * @return array
**/
function pagination_helper($ContentPerPage,$TotalContent,$CurrentPage,$BaseUrl,$RedirectUrl = array('error','code','page-404'))
{
	$instance	=	get_instance();
	$result		=	doPaginate($ContentPerPage,$TotalContent,$CurrentPage,$BaseUrl);
	if($result[0] == 'page-404'): redirect($RedirectUrl);endif;
	return $result;
}

function doPaginate($elpp,$ttel,$current_page,$baselink)
{
		/*// Gloabl ressources Control*/
		if(!is_finite($elpp))				: echo '<strong>$elpp</strong> is not finite'; return;
		elseif(!is_finite($current_page))	: echo '<strong>$current_page</strong> is not finite'; return;
		endif;
		
		$more	=	array();
		$ttpage = ceil($ttel / $elpp);
		if(($current_page > $ttpage || $current_page < 1) && $ttel > 0): return array(
			'start'				=>	0,
			'end'				=>	0,
			'page-404', 			// 	Deprécié
			array(),			// 	Déprécié
			'status'			=>	'page-404',
			'pagination'		=>	array(),
			'available_pages'	=>	0,
			'current_page'		=>	0
		);
		endif;
		$firstoshow = ($current_page - 1) * $elpp;
		/*// FTS*/
		if($current_page < 5):$fts = 1;
		elseif($current_page >= 5):$fts = $current_page - 4;
		endif;
		/*// LTS*/
		if(($current_page + 4) <= $ttpage):$lts = $current_page + 4;
		/*elseif($ttpage > 5):$lts = $ttpage - $current_page;*/
		else:$lts = $ttpage;
		endif;
		
		$content = null;
		for($i = $fts;$i<=$lts;$i++)
		{
			$more[]	=	array(
				'link'	=>	$baselink.'/'.$i,
				'text'	=>	$i,
				'state'	=>	((int)$i === (int)$current_page) ? "active" : "" // Fixing int type 03.11.2013
			);
		}		
		return array(
			'start'				=>	$firstoshow,
			'end'				=>	$elpp,
			'pageExists', 		// 	Deprécié
			$more,				// 	Déprécié
			'status'			=>	'pageExists',
			'pagination'		=>	$more,
			'available_pages'	=>	$ttpage,
			'current_page'		=>	$current_page
		);
		
	}
/* End of file core_helper.php */

