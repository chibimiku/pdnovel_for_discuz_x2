<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

class adv_pdnovel
{

		public $version = '2.0';
		public $name = 'pdnovel_name';
		public $description = 'pdnovel_desc';
		public $copyright = '<a href="http://www.poudu.net" target="_blank">Poudu Inc.</a>';
		public $targets = array
		(
				0 => 'pdnovel'
		);
		public $imagesizes = array
		(
				0 => '960x60',
				1 => '765x60',
				2 => '190x60'
		);
		public $categoryvalue = array( );

		public function getsetting( )
		{
				global $_G;
				$settings = array(
						'position' => array(
								'title' => 'pdnovel_position',
								'type' => 'mradio',
								'value' => array(
										array( 1, 'pdnovel_position_1' ),
										array( 2, 'pdnovel_position_2' ),
										array( 3, 'pdnovel_position_3' ),
										array( 4, 'pdnovel_position_4' ),
										array( 5, 'pdnovel_position_5' ),
										array( 6, 'pdnovel_position_6' ),
										array( 7, 'pdnovel_position_7' ),
										array( 8, 'pdnovel_position_8' )
								),
								'default' => 1
						),
						'category' => array(
								'title' => 'pdnovel_category',
								'type' => 'mselect',
								'value' => array( )
						)
				);
				loadcache( 'pdnovelcategory' );
				$this->getcategory( 0 );
				$settings['category']['value'] = $this->categoryvalue;
				return $settings;
		}

		public function getcategory( $upid )
		{
				global $_G;
				foreach ( $_G['cache']['pdnovelcategory'] as $category )
				{
						if ( $category['upid'] == $upid )
						{
								$this->categoryvalue[] = array(
										$category['catid'],
										str_repeat( '&nbsp;', $category['level'] * 4 ).$category['catname']
								);
								$this->getcategory( $category['catid'] );
						}
				}
		}

		public function setsetting( &$advnew, &$_obfuscate_Zg8Wl64CCWkjSg�� )
		{
				global $_G;
				if ( is_array( $advnew['targets'] ) )
				{
						$advnew['targets'] = implode( "\t", $advnew['targets'] );
				}
				if ( is_array( $parameters['extra']['category'] ) && in_array( 0, $parameters['extra']['category'] ) )
				{
						$parameters['extra']['category'] = array( );
				}
		}

		public function evalcode( )
		{
				return array( 'check' => "\r\n\t\t\t\$checked = \$params[2] == \$parameter['position'] && (!\$parameter['category'] || \$parameter['category'] && in_array(\$_G['catid'], \$parameter['category']));\r\n\t\t\t", 'create' => '$adcode = $codes[$adids[array_rand($adids)]];' );
		}

}

if ( !defined( 'IN_DISCUZ' ) )
{
		exit( 'Access Denied' );
}
?>
