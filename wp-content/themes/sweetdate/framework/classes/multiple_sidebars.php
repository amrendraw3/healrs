<?php
/*
Plugin Name: Sidebar Generator
Plugin URI: http://www.getson.info
Description: This plugin generates as many sidebars as you need. Then allows you to place them on any page you wish. Version 1.1 now supports themes with multiple sidebars. 
Version: 1.1.1
Author: Kyle Getson
Author URI: http://www.kylegetson.com
Copyright (C) 2009 Kyle Robert Getson
*/

/*
Copyright (C) 2009 Kyle Robert Getson, kylegetson.com and getson.info

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! class_exists( 'sidebar_generator' ) ) {
	class sidebar_generator {

		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_menu', array( 'sidebar_generator', 'admin_menu' ) );
			add_action( 'admin_print_scripts', array( 'sidebar_generator', 'admin_print_scripts' ) );

			//edit posts/pages
			add_action( 'edit_form_advanced', array( 'sidebar_generator', 'edit_form' ) );
			add_action( 'edit_page_form', array( 'sidebar_generator', 'edit_form' ) );

			//save posts/pages
			add_action( 'edit_post', array( 'sidebar_generator', 'save_form' ) );
			add_action( 'publish_post', array( 'sidebar_generator', 'save_form' ) );
			add_action( 'save_post', array( 'sidebar_generator', 'save_form' ) );
			add_action( 'edit_page_form', array( 'sidebar_generator', 'save_form' ) );

		}

		public static function init() {

			// Register AJAX hooks
			if ( current_user_can( 'manage_options' ) ) {
				add_action( 'wp_ajax_add_sidebar', array( 'sidebar_generator', 'add_sidebar' ) );
				add_action( 'wp_ajax_remove_sidebar', array( 'sidebar_generator', 'remove_sidebar' ) );
			}

			//go through each sidebar and register it
			$sidebars = sidebar_generator::get_sidebars();

			global $wp_registered_sidebars;

			if ( is_array( $sidebars ) ) {
				foreach ( $sidebars as $sidebar ) {
					$sidebar_class = sanitize_title( $sidebar );
					$i             = count( $wp_registered_sidebars ) + 1;

					register_sidebar( array(
						'name'          => $sidebar,
						'id'            => 'sidebar-' . $i,
						'before_widget' => '<div id="%1$s" class="widgets ' . $sidebar_class . ' clearfix %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => apply_filters( 'sq_ms_before_title', '<h5>' ),
						'after_title'   => apply_filters( 'sq_ms_after_title', '</h5>' ),
					) );
				}
			}
		}

		public static function admin_print_scripts() {
			wp_print_scripts( array( 'sack' ) );
			?>
			<script>
				function add_sidebar(sidebar_name) {

					var mysack = new sack("<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>");

					mysack.execute = 1;
					mysack.method = 'POST';
					mysack.setVar("action", "add_sidebar");
					mysack.setVar("sidebar_name", sidebar_name);
					mysack.setVar("sidebar_generator_nonce", "<?php echo wp_create_nonce( 'add_sidebar' )?>");
					mysack.encVar("cookie", document.cookie, false);
					mysack.onError = function () {
						alert('Ajax error. Cannot add sidebar')
					};
					mysack.runAJAX();
					return true;
				}

				function remove_sidebar(sidebar_name, num) {

					var mysack = new sack("<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>");

					mysack.execute = 1;
					mysack.method = 'POST';
					mysack.setVar("action", "remove_sidebar");
					mysack.setVar("sidebar_name", sidebar_name);
					mysack.setVar("sidebar_generator_nonce", "<?php echo wp_create_nonce( 'remove_sidebar' )?>");
					mysack.setVar("row_number", num);
					mysack.encVar("cookie", document.cookie, false);
					mysack.onError = function () {
						alert('Ajax error. Cannot add sidebar')
					};
					mysack.runAJAX();
					//alert('hi!:::'+sidebar_name);
					return true;
				}
			</script>
			<?php
		}

		public static function add_sidebar() {
			check_admin_referer( 'add_sidebar', 'sidebar_generator_nonce' );

			$sidebars = sidebar_generator::get_sidebars();
			$name     = str_replace( array( "\n", "\r", "\t" ), '', $_POST['sidebar_name'] );
			//$id       = sidebar_generator::name_to_class( $name );
			$id       = sanitize_title( $name );
			if ( isset( $sidebars[ $id ] ) ) {
				die( "alert('Sidebar already exists, please use a different name.')" );
			}

			$sidebars[ $id ] = $name;
			sidebar_generator::update_sidebars( $sidebars );

			$js = "
			var tbl = document.getElementById('sbg_table');
			var lastRow = tbl.rows.length;
			// if there's no header row in the table, then iteration = lastRow + 1
			var iteration = lastRow;
			var row = tbl.insertRow(lastRow);
			
			// left cell
			var cellLeft = row.insertCell(0);
			var textNode = document.createTextNode('" . esc_js( $name ) . "');
			cellLeft.appendChild(textNode);
			
			//middle cell
			var cellLeft = row.insertCell(1);
			var textNode = document.createTextNode('" . esc_js( $id ) . "');
			cellLeft.appendChild(textNode);
			
			//var cellLeft = row.insertCell(2);
			//var textNode = document.createTextNode('[<a href=\'javascript:void(0);\' onclick=\'return remove_sidebar_link($name);\'>Remove</a>]');
			//cellLeft.appendChild(textNode)
			
			var cellLeft = row.insertCell(2);
			removeLink = document.createElement('a');
      		linkText = document.createTextNode('remove');
			removeLink.setAttribute('onclick', 'remove_sidebar_link(\'" . esc_js( $name ) . "\')');
			removeLink.setAttribute('href', '#');
        
      		removeLink.appendChild(linkText);
      		cellLeft.appendChild(removeLink);

			
		";


			die( "$js" );
		}

		public static function remove_sidebar() {
			check_admin_referer( 'remove_sidebar', 'sidebar_generator_nonce' );

			$sidebars = sidebar_generator::get_sidebars();
			$name     = str_replace( array( "\n", "\r", "\t" ), '', $_POST['sidebar_name'] );
			$id       = sanitize_title( $name );
			if ( ! isset( $sidebars[ $id ] ) ) {
				$id = sidebar_generator::name_to_class( $name );

				if ( ! isset( $sidebars[ $id ] ) ) {
					die( "alert('Sidebar does not exist.')" );
				}
			}
			$row_number = (int) $_POST['row_number'];
			unset( $sidebars[ $id ] );
			sidebar_generator::update_sidebars( $sidebars );
			$js = "var tbl = document.getElementById('sbg_table');tbl.deleteRow($row_number)";

			die( $js );
		}

		public static function admin_menu() {
			add_theme_page( 'Sidebars', 'Sidebars', 'manage_options', 'multiple_sidebars', array(
				'sidebar_generator',
				'admin_page',
			) );

		}

		public static function admin_page() {
			?>
			<script>
				function remove_sidebar_link(name, num) {
					answer = confirm("Are you sure you want to remove " + name + "?\nThis will remove any widgets you have assigned to this sidebar.");
					if (answer) {
						remove_sidebar(name, num);
					} else {
						return false;
					}
				}
				function add_sidebar_link() {
					var sidebar_name = prompt("Sidebar Name:", "");
					add_sidebar(sidebar_name);
				}
			</script>
			<div class="wrap">
				<h2>Sidebars</h2>
				<br/>
				<table class="widefat page" id="sbg_table" style="width:600px;">
					<tr>
						<th>Sidebar Name</th>
						<th>CSS class</th>
						<th>Remove</th>
					</tr>
					<?php
					$sidebars = self::get_sidebars();
					if ( is_array( $sidebars ) && ! empty( $sidebars ) ) {
						$cnt = 0;
						foreach ( $sidebars as $sidebar ) {
							$alt = ( $cnt % 2 == 0 ? 'alternate' : '' );
							?>
							<tr class="<?php echo esc_attr( $alt ) ?>">
								<td><?php echo esc_html( $sidebar ); ?></td>
								<td><?php echo sanitize_title( $sidebar ); ?></td>
								<td><a href="javascript:void(0);"
								       onclick="return remove_sidebar_link('<?php echo esc_attr( $sidebar ); ?>',<?php echo (int) ( $cnt + 1 ); ?>);"
								       title="Remove this sidebar">remove</a></td>
							</tr>
							<?php
							$cnt ++;
						}
					} else {
						?>
						<tr>
							<td colspan="3">No Sidebars defined</td>
						</tr>
						<?php
					}
					?>
				</table>
				<br/><br/>

				<div class="add_sidebar">
					<a href="javascript:void(0);" onclick="return add_sidebar_link()" title="Add a sidebar"
					   class="button-primary">+ Add New Sidebar</a>

				</div>

			</div>
			<?php
		}

		/**
		 * for saving the pages/post
		 * @param integer $post_id
		 * @return void
		 */
		public static function save_form( $post_id ) {

			if ( isset( $_POST['sbg_edit'] ) && ! empty( $_POST['sbg_edit'] ) ) {
				delete_post_meta( $post_id, 'sbg_selected_sidebar' );
				delete_post_meta( $post_id, 'sbg_selected_sidebar_replacement' );
				add_post_meta( $post_id, 'sbg_selected_sidebar', $_POST['sidebar_generator'] );
				add_post_meta( $post_id, 'sbg_selected_sidebar_replacement', $_POST['sidebar_generator_replacement'] );
			}
		}

		public static function edit_form() {
			global $post;
			$post_id = $post;
			if ( is_object( $post_id ) ) {
				$post_id = $post_id->ID;
			}
			$selected_sidebar = get_post_meta( $post_id, 'sbg_selected_sidebar', true );
			if ( ! is_array( $selected_sidebar ) ) {
				$tmp                 = $selected_sidebar;
				$selected_sidebar    = array();
				$selected_sidebar[0] = $tmp;
			}
			$selected_sidebar_replacement = get_post_meta( $post_id, 'sbg_selected_sidebar_replacement', true );
			if ( ! is_array( $selected_sidebar_replacement ) ) {
				$tmp                             = $selected_sidebar_replacement;
				$selected_sidebar_replacement    = array();
				$selected_sidebar_replacement[0] = $tmp;
			}
			?>

			<div id='sbg-sortables' class='meta-box-sortables'>
				<div id="sbg_box" class="postbox ">
					<div class="handlediv" title="Click to toggle"><br/></div>
					<h3 class='hndle'><span>Sidebar</span></h3>

					<div class="inside">
						<div class="sbg_container">
							<input name="sbg_edit" type="hidden" value="sbg_edit"/>

							<p>Please select the sidebar you would like to display on this page. <strong>Note:</strong>
								You must first create the sidebar under Appearance > Sidebars.
							</p>
							<ul>
								<?php
								global $wp_registered_sidebars;
								for ( $i = 0; $i < 1; $i ++ ) { ?>
									<li>
										<select name="sidebar_generator[<?php echo $i ?>]" style="display: none;">
											<option value="0"<?php if ( $selected_sidebar[ $i ] == '' ) {
												echo " selected";
											} ?>>WP Default Sidebar
											</option>
											<?php
											$sidebars = $wp_registered_sidebars;// sidebar_generator::get_sidebars();
											if ( is_array( $sidebars ) && ! empty( $sidebars ) ) {
												foreach ( $sidebars as $sidebar ) {
													if ( $selected_sidebar[ $i ] == $sidebar['name'] ) {
														echo "<option value='" . esc_attr( $sidebar['name'] ) . "' selected>" . esc_html( $sidebar['name'] ) . "</option>\n";
													} else {
														echo "<option value='" . esc_attr( $sidebar['name'] ) . "'>" . esc_html( $sidebar['name'] ) . "</option>\n";
													}
												}
											}
											?>
										</select>
										<select name="sidebar_generator_replacement[<?php echo $i ?>]">
											<option value="0"<?php if ( '' == $selected_sidebar_replacement[ $i ] ) {
												echo ' selected';
											} ?>>None
											</option>
											<?php

											$sidebar_replacements = $wp_registered_sidebars;//sidebar_generator::get_sidebars();
											if ( is_array( $sidebar_replacements ) && ! empty( $sidebar_replacements ) ) {
												foreach ( $sidebar_replacements as $sidebar ) {
													if ( $selected_sidebar_replacement[ $i ] == $sidebar['name'] ) {
														echo "<option value='{$sidebar['name']}' selected>{$sidebar['name']}</option>\n";
													} else {
														echo "<option value='{$sidebar['name']}'>{$sidebar['name']}</option>\n";
													}
												}
											}
											?>
										</select>

									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<?php
		}

		/**
		 * called by the action get_sidebar. this is what places this into the theme
		 * @param string $name
		 */
		public static function get_sidebar( $name = '0' ) {

			if ( ! is_singular() ) {
				if ( '0' != $name ) {
					dynamic_sidebar( $name );
				} else {
					dynamic_sidebar();
				}

				return;//do not do anything
			}

			global $wp_query;
			$post = $wp_query->get_queried_object();
			if ( ! $post ) {
				dynamic_sidebar();

				return;
			}

			if (is_home() && get_option( 'page_for_posts' )) {
				$post_id = get_option( 'page_for_posts' );
			} else {
				$post_id = $post->ID;
			}

			if ( ! is_object( $post ) ) {

				if ( function_exists( 'kleo_bp_get_page_id' ) && bp_is_blog_page() && kleo_bp_get_page_id() ) {
					$post_id = kleo_bp_get_page_id();
				} else {
					if ( '0' != $name ) {
						dynamic_sidebar( $name );
					} else {
						dynamic_sidebar();
					}

					return;
				}
			}

			$selected_sidebar             = get_post_meta( $post_id, 'sbg_selected_sidebar', true );
			$selected_sidebar_replacement = get_post_meta( $post_id, 'sbg_selected_sidebar_replacement', true );
			$did_sidebar                  = false;
			//this page uses a generated sidebar


			//var_dump( $selected_sidebar_replacement );exit;

			if ( ! empty( $selected_sidebar_replacement ) && ( isset( $selected_sidebar_replacement ) && '0' !== $selected_sidebar_replacement[0] ) ) {

				if ( function_exists( 'is_woocommerce' ) ) {
					if ( is_woocommerce() ) {
						$shop_sidebar = 'shop-1';

						if ( $name == $shop_sidebar ) {
							$selected_sidebar = array( $shop_sidebar );
						}
					}
				}
			}

			if ( '' != $selected_sidebar && '0' != $selected_sidebar ) {
				echo '';
				if ( is_array( $selected_sidebar ) && ! empty( $selected_sidebar ) ) {
					for ( $i = 0; $i < sizeof( $selected_sidebar ); $i ++ ) {

						if ( '0' == $name && '0' == $selected_sidebar[ $i ] && '0' == $selected_sidebar_replacement[ $i ] ) {
							//echo "\n\n<!-- [called $name selected {$selected_sidebar[$i]} replacement {$selected_sidebar_replacement[$i]}] -->";
							dynamic_sidebar();//default behavior
							$did_sidebar = true;
							break;
						} elseif ( '0' == $name && '0' == $selected_sidebar[ $i ] ) {
							//we are replacing the default sidebar with something
							//echo "\n\n<!-- [called $name selected {$selected_sidebar[$i]} replacement {$selected_sidebar_replacement[$i]}] -->";
							dynamic_sidebar( $selected_sidebar_replacement[ $i ] );//default behavior
							$did_sidebar = true;
							break;
						} elseif ( $selected_sidebar[ $i ] == $name ) {
							//we are replacing this $name
							//echo "\n\n<!-- [called $name selected {$selected_sidebar[$i]} replacement {$selected_sidebar_replacement[$i]}] -->";
							$did_sidebar = true;
							dynamic_sidebar( $selected_sidebar_replacement[ $i ] );//default behavior
							break;
						}
						//echo "<!-- called=$name selected={$selected_sidebar[$i]} replacement={$selected_sidebar_replacement[$i]} -->\n";
					}
				}
				if ( true == $did_sidebar ) {
					echo '';

					return;
				}
				//go through without finding any replacements, lets just send them what they asked for
				if ( '0' != $name ) {
					dynamic_sidebar( $name );
				} else {
					dynamic_sidebar();
				}
				echo '';

				return;
			} else {
				if ( '0' != $name ) {
					dynamic_sidebar( $name );
				} else {
					dynamic_sidebar();
				}
			}
		}

		/**
		 * replaces array of sidebar names
		 */
		public static function update_sidebars( $sidebar_array ) {
			$sidebars = update_option( 'sbg_sidebars', $sidebar_array );
		}

		/**
		 * gets the generated sidebars
		 */
		public static function get_sidebars() {
			$sidebars = get_option( 'sbg_sidebars' );

			return $sidebars;
		}

		public static function name_to_class( $name ) {
			$class = str_replace( array(
				' ',
				',',
				'.',
				'"',
				"'",
				'/',
				"\\",
				'+',
				'=',
				')',
				'(',
				'*',
				'&',
				'^',
				'%',
				'$',
				'#',
				'@',
				'!',
				'~',
				'`',
				'<',
				'>',
				'?',
				'[',
				']',
				'{',
				'}',
				'|',
				':',
			), '', $name );

			return $class;
		}

	}
}
$sbg = new sidebar_generator;

function generated_dynamic_sidebar( $name = '0' ) {
	sidebar_generator::get_sidebar( $name );

	return true;
}
