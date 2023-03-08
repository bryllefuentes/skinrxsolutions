<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
$countries = WC()->countries->get_allowed_countries();
$base = WC()->countries->get_base_country();
$zone_args = array(
    'post_type'      => 'wc_afrsm_zone',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'ID',
    'order'          => 'DESC',
);
$zone_query = new WP_Query( $zone_args );
$zone_list = $zone_query->posts;

if ( !empty($zone_list) ) {
    $zone_count = count( $zone_list );
} else {
    $zone_count = 0;
}

?>

    <div class="afrsm-section-left afrsm-pro-list-shipping-zones">
        <div class="right_button_add_zone">
            <a href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'afrsm-wc-shipping-zones',
), admin_url( 'admin.php' ) ) ) ;
?>"
               class="button-secondary"><?php 
esc_html_e( 'Go to Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
        </div>
        <div class="afrsm-pro-zone-table res-cl">
            <h2><?php 
esc_html_e( 'Add Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' );
?></h2>
        </div>
        <div class="form-wrap">
            <form id="add-zone" class="afrsm-shipping-zone" method="post">
                <table class="form-table">
                    <tr>
                        <th>
                            <label for="zone_name"><?php 
esc_html_e( 'Name', 'advanced-flat-rate-shipping-for-woocommerce' );
?></label>
                        </th>
                        <td>
                            <input type="text" name="zone_name" id="zone_name" class="input-text">
                        </td>
                    </tr>
                    <tr>
                        <th><?php 
esc_html_e( 'Zone Type', 'advanced-flat-rate-shipping-for-woocommerce' );
?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php 
esc_html_e( 'Zone Type', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                                </legend>

                                <div class="zone_type_options zone_type_countries" id="zone_type_countries">
                                    <p class="chck"><label><input type="radio" name="zone_type" value="countries"
                                                                  id="zone_type" class="input-radio" checked="checked"/>
											<?php 
esc_html_e( 'This shipping zone is based on one or more countries', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                                        </label></p>
                                    <div class="zone_type_selectbox">
                                        <select multiple="multiple" name="zone_type_countries[]"
                                                data-placeholder="<?php 
esc_attr_e( 'Choose countries&hellip;', 'advanced-flat-rate-shipping-for-woocommerce' );
?>"
                                                class="chosen-select zone_type_country_cls">
											<?php 
foreach ( $countries as $key => $val ) {
    echo  '<option value="' . esc_attr( $key ) . '">' . esc_html( $val ) . '</option>' ;
}
?>
                                        </select>
                                        <p>
                                            <button class="select_all button"><?php 
esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="select_none button"><?php 
esc_html_e( 'None', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_africa"><?php 
esc_html_e( 'Africa Country', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_antarctica"><?php 
esc_html_e( 'Antarctica Country', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_asia"><?php 
esc_html_e( 'Asia Country', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_europe"><?php 
esc_html_e( 'EU States', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_northamerica"><?php 
esc_html_e( 'North America', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_oceania"><?php 
esc_html_e( 'Oceania', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_southamerica"><?php 
esc_html_e( 'South America', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                        </p>
                                    </div>
                                </div>
                                <div class="zone_type_options zone_type_states" id="zone_type_states">
                                    <p class="chck">
                                        <label>
                                            <input type="radio" name="zone_type" value="states" id="zone_type"
                                                   class="input-radio"><?php 
esc_html_e( 'This shipping zone is based on one of more states and counties', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                                        </label>
                                    </p>
                                    <div class="zone_type_selectbox">
                                        <select multiple="multiple" name="zone_type_states[]"
                                                data-placeholder="<?php 
esc_attr_e( 'Choose states/counties&hellip;', 'advanced-flat-rate-shipping-for-woocommerce' );
?>"
                                                class="chosen-select zone_type_states_cls">
											<?php 
foreach ( $countries as $key => $val ) {
    echo  '<option value="' . esc_attr( $key ) . '">' . esc_html( $val ) . '</option>' ;
    $states = WC()->countries->get_states( $key );
    if ( !empty($states) ) {
        foreach ( $states as $state_key => $state_value ) {
            echo  '<option value="' . esc_attr( $key . ':' . $state_key ) . '">' . esc_html( $val . ' &gt; ' . $state_value ) . '</option>' ;
        }
    }
}
?>
                                        </select>
                                        <p>
                                            <button class="select_all button"><?php 
esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="select_none button"><?php 
esc_html_e( 'None', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_africa_states"><?php 
esc_html_e( 'Africa States', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_asia_states"><?php 
esc_html_e( 'Asia States', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_europe"><?php 
esc_html_e( 'EU States', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_us_states"><?php 
esc_html_e( 'US States', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                            <button class="button select_oceania_states"><?php 
esc_html_e( 'Oceania States', 'advanced-flat-rate-shipping-for-woocommerce' );
?></button>
                                        </p>
                                    </div>
                                </div>
                                <?php 
?>
                                <div class="zone_type_options zone_type_postcodes" id="zone_type_postcodes">
                                    <p class="chck"><label><input type="radio" name="zone_type" value="postcodes"
                                                                  id="zone_type"
                                                                  class="input-radio"/> <?php 
esc_html_e( 'This shipping zone is based on one of more postcodes/zips', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                                        </label></p>
                                    <div class="zone_type_selectbox">
                                        <select name="zone_type_postcodes"
                                                data-placeholder="<?php 
esc_attr_e( 'Choose countries&hellip;', 'advanced-flat-rate-shipping-for-woocommerce' );
?>"
                                                title="Country" class="chosen_select zone_type_postcode_cls">
                                            <?php 
foreach ( $countries as $key => $val ) {
    echo  '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $base, false ) . '>' . esc_html( $val ) . '</option>' ;
    $states = WC()->countries->get_states( $key );
    if ( !empty($states) ) {
        foreach ( $states as $state_key => $state_value ) {
            echo  '<option value="' . esc_attr( $key . ':' . $state_key ) . '">' . esc_html( $val . ' &gt; ' . $state_value ) . '</option>' ;
        }
    }
}
?>
                                        </select>
                                        <br>
                                        <label for="postcodes"
                                               class="postcodes"><?php 
esc_html_e( 'Postcodes', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                                            <span class="tooltip_con">
                                        <img class="help_tip" width="16"
                                             data-tip="List 1 postcode per line. Wildcards (*) and ranges (for numeric postcodes) are supported. If you have space in postcode then please add = (equal to) instead of space EX: Postcode - ES2 ABS then You can enter ES2=*"
                                             src="<?php 
echo  esc_url( WC()->plugin_url() . '/assets/images/help.png' ) ;
?>"/>
                                        <i>
                                            <?php 
esc_html_e( 'List 1 postcode per line. Wildcards (*) and ranges (for numeric postcodes) are supported. If you have space in postcode then please add = (equal to) instead of space EX: Postcode - ES2 ABS then You can enter ES2=*', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                                        </i>
                                    </span>
                                        </label>
                                        <textarea name="postcodes" id="postcodes" class="input-text large-text"
                                                  cols="25" rows="5"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button button-primary" name="add_zone"
                           value="<?php 
esc_attr_e( 'Add shipping zone', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                </p>
				<?php 
wp_nonce_field( 'woocommerce_save_zone', 'woocommerce_save_zone_nonce' );
?>
            </form>
        </div>

    </div>

<?php 
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php';