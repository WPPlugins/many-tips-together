<?php
/**
 * Admin Page Class Validate
 *
 * @package ManyTipsTogether
 */
class B5F_MTT_Validate
{
	/**
	 * General for AdminBar tab
	 * 
	 * @param object $value
	 * @return object
	 */
	public static function validate_adminbar( $value )
	{
		if( isset( $value['url'] ) )
			$value['url'] = self::validate_url( $value['url'] );

		if( isset( $value['title'] ) )
			$value['title'] =
					(self::is_alphanumeric( $value['title'] )) ? $value['title'] : '';

		if( isset( $value['howdy'] ) )
			$value['howdy'] =
					(self::is_alphanumeric( $value['howdy'] )) ? $value['howdy'] : '';

		for( $i = 0; $i < 6; $i++ )
		{
			if( isset( $value["bar_{$i}_title"] ) )
				$value["bar_{$i}_title"] =
						esc_html( stripslashes( $value["bar_{$i}_title"] ) );

			if( isset( $value["bar_{$i}_url"] ) )
				$value["bar_{$i}_url"] =
						self::validate_url( $value["bar_{$i}_url"] );
		}

		return $value;
	}


	/**
	 * Especific for Post renaming settings
	 * 
	 * @param object $value
	 * @return object
	 */
	public static function validate_post_renaming( $value )
	{
		foreach( $value as $key => $val )
		{
			if( 'enabled' != $key )
				$value[$key] =
						self::is_alphanumeric( $value[$key], true ) ? $value[$key] : '';
		}

		return $value;
	}


	/**
	 * General for Post Listing tab
	 * 
	 * @param object $value
	 * @return object
	 */
	public static function validate_postlisting( $value )
	{
		if( isset( $value['width'] ) )
		{
			$val = B5F_MTT_Utils::validate_css_px_percent( $value['width'] );
			$value['width'] = $val ? $val : '';
		}

		if( isset( $value['proportion'] ) )
		{
			$val = B5F_MTT_Utils::validate_css_number( $value['proportion'] );
			$value['proportion'] = $val ? $val : '';
		}

		return $value;
	}


	/**
	 * General for Post Editing tab
	 * 
	 * @param number $value
	 * @return number
	 */
	public static function validate_postediting( $value )
	{
		$val = B5F_MTT_Utils::validate_pos_neg_number( $value );
		$value = ( false !== $val ) ? $val : '';
		return $value;
	}


	/**
	 * Used in Widgets
	 * 
	 * @param number $value
	 * @return number
	 */
	public static function validate_rss_time( $value )
	{
		$return = B5F_MTT_Utils::validate_pos_neg_number( $value );

		if( false === $return )
			return '';

		if( $return <= 0 )
			$return = '';

		return $return;
	}


	/**
	 * Used in Plugins tab
	 * 
	 * @param object $value
	 * @return object
	 */
	public static function validate_colorize_plugins( $value )
	{
		if( isset( $value['names'] ) )
			$value['names'] = 
					self::is_alphanumeric( $value['names'] )
					? $value['names'] : '';

		return $value;
	}

	/**
	 * 
	 * @param type $value
	 * @return type
	 */
	public static function validate_general_tab( $value )
	{
		if( isset( $value['time'] ) )
			$value['time'] = 
					self::validate_rss_time( $value['time'] )
					? $value['time'] : '';

		return $value;
	}

	
	/**
	 * 
	 * @param type $value
	 * @return type
	 */
	public static function validate_maintenance( $value )
	{
		if( isset( $value['title'] ) )
			$value['title'] =
					self::is_alphanumeric( $value['title'] ) 
					? esc_html( stripslashes( $value['title'] ) ) : '';

		if( isset( $value['line0'] ) )
			$value['line0'] =
					self::is_alphanumeric( $value['line0'] ) 
					? $value['line0'] : '';

		if( isset( $value['line1'] ) )
			$value['line1'] =
					self::is_alphanumeric( $value['line1'] ) 
					? $value['line1'] : '';

		if( isset( $value['line2'] ) )
			$value['line2'] =
					self::is_alphanumeric( $value['line2'] ) 
					? $value['line2'] : '';

		return $value;
	}


	/**
	 * Used internally to validate URLs
	 * 
	 * @param string $uri
	 * @param type $numb Allows # or not
	 * @return string
	 */
	public static function validate_url( $uri, $numb = true )
	{
		$url = B5F_MTT_Utils::check_url( $uri, $numb );
		if( !$url )
			$uri = '';

		return $uri;
	}


	/**
	 * Used internally to validate simple string input
	 * Allows ,?!. charachters
	 * 
	 * Matching for Unicode
	 * \P{M}\p{M}*+ 
	 * @param string $val
	 * @return boolean
	 */
	public static function is_alphanumeric( $val, $simple = false )
	{
		$preg =
				$simple 
				? "/^([a-zA-Z0-9\s\P{M}\p{M}*+])+$/i" 
				: "/^([a-zA-Z0-9\s\P{M}\p{M}*+,!?.])+$/i";

		return (bool) preg_match( $preg, $val );
	}

	/**
	 * 
	 * @param string $value
	 * @return string
	 */
	public static function validate_loginout_url( $value )
	{
		// false doesn't allow #
		$url = B5F_MTT_Utils::check_url( $value['url'], false );
		if( !$url )
			$value['url'] = '';
		return $value;
	}

	/**
	 * 
	 * @param string $value
	 * @return string
	 */
	public static function validate_url_condition( $value )
	{

		if( isset( $value['url'] ) )
		{
			$url = B5F_MTT_Utils::check_url( $value['url'], true );
			if( !$url )
				$value['url'] = '';
		}
		return $value;
	}

	
	/**
	 * 
	 * @param string $value
	 * @return string
	 */
	public static function validate_simple_full_url( $value )
	{
		$url = B5F_MTT_Utils::check_url( $value, false );
		if( !$url )
			$value = '';
		return $value;
	}


	/**
	 * Validates Numbers and CSS Numbers (stripping px and %)
	 * 
	 * @param type $value
	 * @return type
	 */
	public static function validate_number( $value )
	{
		$num = B5F_MTT_Utils::validate_css_number( $value );
		$value = ( false === $num ) ? '' : $num;
		return $value;
	}


	/**
	 * Validates Numbers and CSS Numbers (stripping px and %)
	 * 
	 * @param type $value
	 * @return type
	 */
	public static function validate_css_num_value( $value )
	{
		$num = B5F_MTT_Utils::validate_css_px_percent( $value );
		if( !$num )
			$value = '';
		else
			$value = $num;

		return $value;
	}

	
	/**
	 * 
	 * @param type $value
	 * @return type
	 */
	public static function validate_thumb_column( $value )
	{
		$prop = B5F_MTT_Utils::validate_css_number( $value['proportion'] );
		if( !$prop )
			$value['proportion'] = '';
		else
			$value['proportion'] = $prop; // cleaned value

		$width = B5F_MTT_Utils::validate_css_px_percent( $value['width'] );
		if( !$width )
			$value['width'] = '';
		else
			$value['width'] = $width; // cleaned value

		return $value;
	}


	/**
	 * No html allowed
	 * 
	 * @param type $value
	 */
	public static function strip_html( $value )
	{
		return strip_tags( $value );
	}
	
	
	/**
	 * Used for all text fields that allow Html
	 * Add field name in the $checks array
	 * 
	 * @param type $value
	 * @return type
	 */
	public static function validate_html_text( $value )
	{
		if( is_array( $value ) )
		{
			$checks = array( 
				'text', 'left', 'right', 'title', 
				'content', 'line0', 'line1',
				'line2', 
			);

			foreach( $checks as $c )
			{
				if( isset( $value[$c] ) )
					$value[$c] = esc_html( stripslashes( $value[$c] ) );
			}
			if( isset( $value['msg_login'] ) )
				$value['msg_login'] = esc_html( stripslashes( self::strip_html( $value['msg_login'] ) ) );
		}
		else
		{
			if( !empty( $value ) )
				$value = esc_html( stripslashes( $value ) );
		}

		return $value;
	}
}
