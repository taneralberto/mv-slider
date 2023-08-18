<?php
/**
 * @context file
 * @group MV_Slider_Metabox
 * @tag metabox
 * @step 3
 *
 * Se crean los campos que tendrá el Metabox y se escapa los valores que vengan de
 * la base de datos.
 *
 * @ref https://developer.wordpress.org/apis/security/escaping/
 */

$meta = get_post_meta( $post->ID );
$link_text = get_post_meta( $post->ID, 'mv_slider_link_text', true );
$link_url = get_post_meta( $post->ID, 'mv_slider_link_url', true );
?>

<table class="form-table mv-slider-metabox">
    <?php
    /**
     * @group MV_Slider_Nonce
     * @tag nonce
     * @Step 1
     *
     * Se crea un campo oculto para almacenar el nonce.
     */
    ?>
    <input type="hidden" name="mv_slider_nonce" value="<?php echo wp_create_nonce( 'mv_slider_nonce' ); ?>">

    <tr>
        <th>
            <label for="mv_slider_link_text">Link Text</label>
        </th>
        <td>
            <input
                type="text"
                name="mv_slider_link_text"
                id="mv_slider_link_text"
                class="regular-text link-text"
                value="<?php echo isset( $link_text ) ? esc_attr( $link_text ) : ''; ?>"
                required
            >
        </td>
    </tr>
    <tr>
        <th>
            <label for="mv_slider_link_url">Link URL</label>
        </th>
        <td>
            <input
                type="url"
                name="mv_slider_link_url"
                id="mv_slider_link_url"
                class="regular-text link-url"
                value="<?php echo isset( $link_url ) ? esc_attr( $link_url ) : ''; ?>"
                required
            >
        </td>
    </tr>
</table>