<?php
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

//created with http://wpsettingsapi.jeroensormani.com/

add_action( 'admin_menu', 'Pakkelabel_add_admin_menu' );
add_action( 'admin_init', 'pakkelabel_settings_init' );


function Pakkelabel_add_admin_menu(  )
{
    add_submenu_page('woocommerce', __('Pakkelabels.dk', 'woocommerce-pakkelabel'), __('Pakkelabels.dk', 'woocommerce-pakkelabel'), 'manage_options', 'pakkelabel', 'pakkelabel_options_page');
}


function pakkelabel_settings_init(  ) {

    register_setting( 'PakkelabelPluginPage', 'Pakkelabel_settings' );

    add_settings_section(
        'Pakkelabel_pluginPage_section',
        __( '', 'Pakkelabel' ),
        'pakkelabel_settings_section_callback',
        'PakkelabelPluginPage'
    );

    add_settings_field(
        'Pakkelabel_text_field_0',
        __('Shipping Module key:', 'woocommerce-pakkelabels'),
        'pakkelabel_text_field_0_render',
        'PakkelabelPluginPage',
        'Pakkelabel_pluginPage_section'
    );

    add_settings_field(
        'Pakkelabel_google_api_key',
        __('Google Map API key:', 'woocommerce-pakkelabels'),
        'pakkelabel_google_api_key',
        'PakkelabelPluginPage',
        'Pakkelabel_pluginPage_section'
    );


}


function pakkelabel_text_field_0_render(  )
{
    $options = get_option( 'Pakkelabel_settings' );
    ?>
    <input type='text' name='Pakkelabel_settings[Pakkelabel_text_field_0]' value='<?php echo $options['Pakkelabel_text_field_0']; ?>'>
    <?php
}

function pakkelabel_google_api_key(  )
{
    $options = get_option( 'Pakkelabel_settings' );
    ?>
    <input type='text' name='Pakkelabel_settings[Pakkelabel_google_api_key]' value='<?php echo $options['Pakkelabel_google_api_key']; ?>'>
    <?php
}


function pakkelabel_settings_section_callback(  ) {

    echo __('Generate a shipping module key from: <a target="_blank" href="https://app.pakkelabels.dk/main/app/#/setting/api">Pakkelabels.dk</a> ', 'woocommerce-pakkelabels');
    echo __('</br>Generate a personal Google Map API key from:  <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">Google</a>', 'woocommerce-pakkelabels');

}


function pakkelabel_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <h2><?php echo __('Pakkelabels.dk shipping module', 'woocommerce-pakkelabels') ?></h2>

        <?php
        settings_fields( 'PakkelabelPluginPage' );
        do_settings_sections( 'PakkelabelPluginPage' );
        submit_button();
        ?>

    </form>
    <?php

}


?>