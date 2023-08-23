<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <?php
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'main-options';
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=mv_slider_admin&tab=main_options" class="nav-tab <?php echo $active_tab == 'main-options' ? 'nav-tab-active' : ''; ?>">Main Options</a>
        <a href="?page=mv_slider_admin&tab=additional_options" class="nav-tab <?php echo $active_tab == 'additional-options' ? 'nav-tab-active' : ''; ?>">Additional Options</a>
    </h2>
    <form action="options.php" method="post">
        <?php
        if( $active_tab == 'main_options' ) {
            /**
             * Función encargada de los nonces
             */
            settings_fields( 'mv_slider_group' );
            /**
             * Función parecida a do_action(), en la cual se anclará las opciones
             */
            do_settings_sections( 'mv_slider_page1' );
        } else {
            settings_fields( 'mv_slider_group' );
            do_settings_sections( 'mv_slider_page2' );
        }
        submit_button( 'Save Settings' );
        ?>
    </form>
</div>