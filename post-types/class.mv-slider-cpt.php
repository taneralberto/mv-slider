<?php

/**
 * @context 2-lines
 * @group MV_Slider_Post_Type
 * @tag custom post type, cpt
 * @step 1
 *
 * Se valida que no exista la clase (esto se hace para no sobreescribir la
 * clase en caso que otro desarrollado la haya creado antes en la ejecución
 * del código para modificar algo) y se crea luego
 */
if ( ! class_exists( 'MV_Slider_Post_Type' ) ) {
	class MV_Slider_Post_Type {
		function __construct() {
			/**
			 * @group MV_Slider_Post_Type
			 * @tag custom post type, cpt
			 * @step 3
			 *
			 * Se pasa el método 'create_post_type' al hook 'init'
			 */
			add_action( 'init', array( $this, 'create_post_type' ) );

			/**
			 * @group MV_Slider_Metabox
			 * @tag metabox
			 * @step 4
			 *
			 * Se agrega el método 'add_meta_boxes' creado en el @step 1 al
			 * hook 'add_meta_boxes'.
			 */
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			/**
			 * @group MV_Slider_Metabox
			 * @tag metabox
			 * @step 6
			 *
			 * Se agrega el método encargado de guardar el valor
			 * de los Metabox al hook 'save_post'.
			 */
			add_action( 'save_post', array( $this, 'save_post' ) );

			/**
			 * @group MV_Slider_Columns
			 * @tag columns
			 * @step 2
			 *
			 * El nombre de este filtro es único para cada Custom Post Type, el formato es
 			 * el siguiente: manage_[cpt]_post_columns
			 */
			add_filter( 'manage_mv-slider_posts_columns', array( $this, 'mv_slider_cpt_columns' ) );

			/**
			 * @group MV_Slider_Columns
			 * @tag columns
			 * @step 4
			 *
			 * manage_[cpt]_posts_custom_columns
			 */
			add_action( 'manage_mv-slider_posts_custom_column', array( $this, 'mv_slider_custom_columns' ), 10, 2 );

			/**
			 * @group MV_Slider_Columns
			 * @tag columns
			 * @step 6
			 *
			 * Filtro para permitir ordenar asc y des. El formato del nombre es:
 			 * manage_edit-[cpt]_sortable_columns
			 */
			add_filter( 'manage_edit-mv-slider_sortable_columns', array( $this, 'mv_slider_sortable_columns' ) );
		}

		/**
		 * @group MV_Slider_Post_Type
		 * @tag custom post type, cpt
		 * @step 2
		 *
		 * Se crea un método en el cual se ejecuta la función para
		 * crear el Custom Post Type
		 *
		 * @ref https://developer.wordpress.org/reference/functions/register_post_type/
		 */
		public function create_post_type() {
			register_post_type(
				'mv-slider',
				array(
					'label' => esc_html__( 'Slider', 'mv-slider' ),
					'description' => esc_html__( 'Slider description', 'mv-slider' ),
					'labels' => array(
						'name' => esc_html__( 'Sliders', 'mv-slider' ),
						'singular_name' => esc_html__( 'Slider', 'mv-slider' )
					),
					'public' => true,
					'supports' => array( 'title', 'editor', 'thumbnail' ),
					'hierarchical' => false,
					'show_ui' => true,
					'show_in_menu' => false,
					'menu_position' => 5,
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'can_export' => true,
					'has_archive' => false,
					'exclude_from_search' => false,
					'publicly_queryable' => true,
					'show_in_rest' => true,
					'menu_icon' => 'dashicons-images-alt2',
					// 'register_meta_box_cb' => array( $this, 'add_meta_boxes' )
				)
			);
		}

		/**
		 * @group MV_Slider_Columns
		 * @tag columns
		 * @step 1
		 *
		 * Creamos el método para el filter el cual agregará las nuevas columnas.
		 */
		public function mv_slider_cpt_columns( $columns ) {
			$columns['mv_slider_link_text'] = esc_html__( 'Link Text', 'mv-slider' );
			$columns['mv_slider_link_url'] = esc_html__( 'Link URL', 'mv-slider' );
			return $columns;
		}

		/**
		 * @group MV_Slider_Columns
		 * @tag columns
		 * @step 3
		 *
		 * Se crea el método encargado de mostrar los valores de los metabox en
		 * las columnas.
		 */
		public function mv_slider_custom_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'mv_slider_link_text':
					echo esc_html( get_post_meta( $post_id, 'mv_slider_link_text', true ) );
					break;
				case 'mv_slider_link_url':
					echo esc_url( get_post_meta( $post_id, 'mv_slider_link_url', true ) );
					break;
			}
		}

		/**
		 * @group MV_Slider_Columns
		 * @tag columns
		 * @step 5
		 *
		 * Creamos el método el cual permitirá ordenar asc y des los valores de la columna
		 */
		public function mv_slider_sortable_columns( $columns ) {
			$columns['mv_slider_link_text'] = 'mv_slider_link_text';
			return $columns;
		}

		/**
		 * @group MV_Slider_Metabox
		 * @tag metabox
		 * @step 1
		 *
		 * Se crea el Metabox.
		 */
		public function add_meta_boxes() {
			add_meta_box(
				'mv_slider_meta_box',
				esc_html__( 'Link Options', 'mv-slider' ),
				array( $this, 'add_inner_meta_boxes' ),
				'mv-slider',
				'normal',
				'high'
			);
		}

		/**
		 * @group MV_Slider_Metabox
		 * @tag metabox
		 * @step 2
		 *
		 * Se crea el callback que se llama en la función
		 * 'add_meta_box' del @step 1 y se requiere el archivo donde estará
		 * la parte HTML que serán los campos.
		 */
		public function add_inner_meta_boxes( $post ) {
			require_once( MV_SLIDER_PATH . 'views/mv-slider_metabox.php' );
		}

		/**
		 * @group MV_Slider_Metabox
		 * @tag metabox
		 * @step 5
		 *
		 * Este método se encarga de validar (sanitize) y guardar el valor de
		 * los campos del Metabox en la base de datos.
		 *
		 * @ref https://developer.wordpress.org/apis/security/sanitizing/
		 */
		public function save_post( $post_id ) {
			/**
			 * @context 2-lines
			 * @group MV_Slider_Nonce
			 * @tag nonce
			 * @Step 2
			 * Verifica si existe 'mv_slider_nonce' (se definió en un input:hidden
			 * en views/mv-slider_metabox.php) y luego se verifica si coincide.
			 */
			if ( isset( $_POST['mv_slider_nonce'] ) ) {
				if ( ! wp_verify_nonce( $_POST['mv_slider_nonce'], 'mv_slider_nonce' ) ) {
					return;
				}
			}

			/**
			 * Verifica si está activo el autoguardado de WordPress, en caso
			 * de estarlo, no permite que guarde la información automáticamente,
			 * ya que esto puede implicar un problema de seguridad.
			 */
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			/**
			 * Verifica si realmente se encuentra en el Custom Post Type.
			 */
			if ( isset( $_POST['post_type'] ) && $_POST['post_type'] === 'mv-slider' ) {
				/**
				 * Verifica si el usuario tiene el permiso de editar páginas y editar posts
				 */
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			if ( isset( $_POST['action'] ) && $_POST['action'] == 'editpost' ) {
				$old_link_text = get_post_meta( $post_id, 'mv_slider_link_text', true );
				$new_link_text = $_POST['mv_slider_link_text'];
				$old_link_url = get_post_meta( $post_id, 'mv_slider_link_url', true );
				$new_link_url = $_POST['mv_slider_link_url'];

				if ( empty( $new_link_text ) ) {
					update_post_meta( $post_id, 'mv_slider_link_text', esc_html__( 'add some text here', 'mv-slider' ) );
				} else {
					update_post_meta( $post_id, 'mv_slider_link_text', sanitize_text_field( $new_link_text ), $old_link_text );
				}

				if ( empty( $new_link_url ) ) {
					update_post_meta( $post_id, 'mv_slider_link_url', '#' );
				} else {
					update_post_meta( $post_id, 'mv_slider_link_url', sanitize_text_field( $new_link_url ), $old_link_url );
				}
			}
		}
	}
}