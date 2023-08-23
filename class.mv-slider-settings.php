<?php

if ( ! class_exists( 'MV_Slider_Settings' ) ) {
	class MV_Slider_Settings {
		public static $options;

		public function __construct() {
			self::$options = get_option( 'mv_slider_options' );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}

		public function admin_init() {

			register_setting( 'mv_slider_group', 'mv_slider_options', array( $this, 'mv_slider_validate' ) );

			add_settings_section(
				'mv_slider_main_section',
				'How does it work?',
				null,
				'mv_slider_page1'
			);

			add_settings_field(
				'mv_slider_shortcode',
				'Shortcode',
				array( $this, 'mv_slider_shortcode_callback' ),
				'mv_slider_page1',
				'mv_slider_main_section'
			);

			add_settings_section(
				'mv_slider_second_section',
				'Other Plugin Settings',
				null,
				'mv_slider_page2'
			);

			add_settings_field(
				'mv_slider_title',
				'Slider Title',
				array( $this, 'mv_slider_title_callback' ),
				'mv_slider_page2',
				'mv_slider_second_section',
				array(
					'label_for' => 'mv_slider_title'
				)
			);

			add_settings_field(
				'mv_slider_bullets',
				'Display Bullets',
				array( $this, 'mv_slider_bullets_callback' ),
				'mv_slider_page2',
				'mv_slider_second_section',
				array(
					'label_for' => 'mv_slider_bullets'
				)
			);

			add_settings_field(
				'mv_slider_style',
				'Style',
				array( $this, 'mv_slider_style_callback' ),
				'mv_slider_page2',
				'mv_slider_second_section',
				array(
					'label_for' => 'mv_slider_style',
					'items' => array(
						'style-1',
						'style-2',
					)
				)
			);
		}

		public function mv_slider_shortcode_callback() {
			?>
			<span>Use the shortcode [mv_slider] to display the slider in any post/page/widget.</span>
			<?php
		}

		public function mv_slider_title_callback() {
			?>
			<input type="text" name="mv_slider_options[mv_slider_title]" id="mv_slider_title"
				value="<?php echo isset( self::$options['mv_slider_title'] ) ? esc_html( self::$options['mv_slider_title'] ) : ''; ?>" />
			<?php
			echo var_dump( self::$options );
		}

		public function mv_slider_bullets_callback() {
			?>
			<input type="checkbox" name="mv_slider_options[mv_slider_bullets]" id="mv_slider_bullets" value="1" <?php
			if ( isset( self::$options["mv_slider_bullets"] ) ) {
				checked( "1", self::$options["mv_slider_bullets"], true );
			}
			?> />
			<label for="mv_slider_bullets">Whether to display bullets or not</label>
			<?php
		}

		public function mv_slider_style_callback( $args ) {
			?>
			<select name="mv_slider_options[mv_slider_style]" id="mv_slider_style">
				<?php foreach ( $args['items'] as $item ) { ?>
					<option value="<?php echo esc_attr( $item ); ?>" <?php isset( self::$options['mv_slider_style'] ) ? selected( esc_html( $item ), self::$options['mv_slider_style'], true ) : ''; ?>>
						<?php echo esc_html( ucfirst( $item ) ); ?>
					</option>
				<?php } ?>
				?>
			</select>
			<?php
		}

        public function mv_slider_validate( $input ) {
            $new_input = array();
            foreach ($input as $key => $value) {
                if( empty( $value ) ) {
                    add_settings_error( 'mv_slider_options', 'mv_slider_message', 'The Title field cannot be left empty.', 'error' );
                }
                $new_input[$key] = sanitize_text_field( $value );

                /*
                switch ($key) {
                    case 'mv_slider_title':
                        if( empty( $value ) ) {
                            add_settings_error( 'mv_slider_options', 'mv_slider_message', 'The Title field cannot be left empty.', 'error' );
                        }
                        $new_input[$key] = sanitize_text_field( $value );
                    break;
                    case 'mv_slider_url':
                        $new_input[$key] = esc_url_raw( $value );
                    break;
                    case 'mv_slider_int':
                        $new_input[$key] = absint( $value );
                    break;
                    default:
                        $new_input[$key] = sanitize_text_field( $value );
                    break;
                }
                */
            }

            return $new_input;
        }
	}
}