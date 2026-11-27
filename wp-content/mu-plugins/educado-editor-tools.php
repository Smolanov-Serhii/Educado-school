<?php
/**
 * Plugin Name: Educado Editor Tools
 * Description: Adds table editing and formatting controls to WordPress and ACF visual editors.
 */

defined( 'ABSPATH' ) || exit;

function educado_editor_table_url() {
	return plugins_url( 'educado-editor-tools/table.min.js', __FILE__ );
}

function educado_editor_tools_settings( $settings ) {
	$plugins = $settings['external_plugins'] ?? array();
	if ( is_string( $plugins ) ) {
		$plugins = json_decode( $plugins, true );
	}
	$plugins = is_array( $plugins ) ? $plugins : array();
	$plugins['table'] = $plugins['table'] ?? educado_editor_table_url();
	$settings['external_plugins'] = wp_json_encode( $plugins );

	$row = $settings['toolbar1'] ?? '';
	if ( $row ) {
		$buttons = preg_split( '/[\s,|]+/', $row, -1, PREG_SPLIT_NO_EMPTY );
		if ( ! in_array( 'table', $buttons, true ) ) {
			$settings['toolbar1'] .= ',table';
		}
	}

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'educado_editor_tools_settings', 20 );
add_filter( 'teeny_mce_before_init', 'educado_editor_tools_settings', 20 );

function educado_editor_format_buttons( $buttons ) {
	return array_values( array_unique( array_merge( $buttons, array( 'underline', 'fontsizeselect', 'backcolor', 'subscript', 'superscript' ) ) ) );
}
add_filter( 'mce_buttons_2', 'educado_editor_format_buttons', 20 );

function educado_editor_acf_toolbars( $toolbars ) {
	foreach ( $toolbars as $name => $rows ) {
		$buttons = array_merge( array(), ...array_values( $rows ) );
		$extra = array( 'table' );
		if ( 'Basic' === $name ) {
			$extra = array_merge( $extra, array( 'formatselect', 'removeformat' ) );
		}
		$toolbars[ $name ][1] = array_merge( $rows[1] ?? array(), array_diff( $extra, $buttons ) );
	}
	return $toolbars;
}
add_filter( 'acf/fields/wysiwyg/toolbars', 'educado_editor_acf_toolbars', 20 );

// wp.editor.initialize() supplies its own defaults, including for the Classic block.
add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_script( 'jquery' );
	wp_add_inline_script( 'jquery-core', sprintf(
		'jQuery(document).on("wp-before-tinymce-init", function (event, settings) {
			settings.external_plugins = settings.external_plugins || {};
			settings.external_plugins.table = settings.external_plugins.table || %s;
			if (typeof settings.toolbar1 === "string" && settings.toolbar1 && !settings.toolbar1.split(/[\s,|]+/).includes("table")) {
				settings.toolbar1 += ",table";
			}
		});',
		wp_json_encode( educado_editor_table_url() )
	) );
} );
