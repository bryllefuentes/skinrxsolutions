<?php


if (!function_exists('mvvwb_dateFormatPhpToJs')) {
    function mvvwb_dateFormatPhpToJs($str)
    {
        return str_replace('s', '', str_replace('S', '', $str));
    }
}
if (!function_exists('mvvwb_dateStringToObject')) {

    function mvvwb_dateStringToObject($start, $end = false)
    {
        $timeZoneName = timezone_name_from_abbr("", get_option('gmt_offset') * HOUR_IN_SECONDS, false);
        $start = new DateTime($start, mvvwb_getTimeZone());
        if ($end) {
            $end = new DateTime($end, mvvwb_getTimeZone());
        }
        return ['start' => $start, 'end' => $end, 'duration' => ''];
    }
}
if (!function_exists('mvvwb_bookings_get_status_label')) {

    function mvvwb_bookings_get_status_label($status)
    {
        $statuses = array(
            'unpaid' => __('Unpaid', 'booking-for-woocommerce'),
            'pending-confirmation' => __('Pending Confirmation', 'booking-for-woocommerce'),
            'confirmed' => __('Confirmed', 'booking-for-woocommerce'),
            'paid' => __('Paid', 'booking-for-woocommerce'),
            'cancelled' => __('Cancelled', 'booking-for-woocommerce'),
            'complete' => __('Complete', 'booking-for-woocommerce'),
        );


        return array_key_exists($status, $statuses) ? $statuses[$status] : $status;
    }
}



function mvvwb_booking_order_requires_confirmation($order)
{
    $requires = false;

    if ($order) {
        foreach ($order->get_items() as $item) {
            if (mvvwb_booking_requires_confirmation($item['product_id'])) {
                $requires = true;
                break;
            }
        }
    }

    return $requires;
}

function mvvwb_cart_requires_confirmation()
{
    $requires = false;

    if (!empty(WC()->cart->cart_contents)) {
        foreach (WC()->cart->cart_contents as $item) {
            if (mvvwb_booking_requires_confirmation($item['product_id'])) {
                $requires = true;
                break;
            }
        }
    }

    return $requires;
}

function mvvwb_price($price)
{


    extract(array(
        'ex_tax_label' => false,
        'currency' => '',
        'decimal_separator' => wc_get_price_decimal_separator(),
        'thousand_separator' => wc_get_price_thousand_separator(),
        'decimals' => wc_get_price_decimals(),
        'price_format' => get_woocommerce_price_format()));
    if ($decimal_separator) {
        $decimal_separator = trim($decimal_separator);
        $price = str_replace($decimal_separator, '.', $price);
    }

    //$unformatted_price = $price;
    $negative = $price < 0;
    $price = floatval($negative ? $price * -1 : $price);
//
    $price = number_format($price, $decimals, $decimal_separator, $thousand_separator);
    $return = html_entity_decode(($negative ? '-' : '') . sprintf($price_format, get_woocommerce_currency_symbol($currency), $price));

    return $return;
}

function mvvwb_daysDiff($start, $end)
{
    $interval = $start->diff($end);
    if ($interval->invert) {
        return false;
    }
    if ($interval->format('%a') == 0) {
        $end->modify('+1 day');
    }
    $interval = $start->diff($end);
    return $interval->format('%a');
}

function mvvwb_booking_requires_confirmation($id)
{
    $item = New MVVWB_Booking_Item($id);

    if (
        $item->isValid
        && $item->requiresConfirmation()
    ) {
        return true;
    }

    return false;
}

function mvvwb_timezone_string($offset)
{

    $offset = (float)$offset;
    $hours = (int)$offset;
    $minutes = ($offset - $hours);

    $sign = ($offset < 0) ? '-' : '+';
    $abs_hour = abs($hours);
    $abs_mins = abs($minutes * 60);
    $tz_offset = sprintf('%s%02d:%02d', $sign, $abs_hour, $abs_mins);

    return $tz_offset;
}

function mvvwb_date($format, $datetime)
{
    // wrapper for wp_date in 5.3
    if (function_exists('wp_date')) {
        return wp_date($format, $datetime);
    }
    $dt = new DateTime('@' . $datetime);
    $offset = mvvwb_getTimeZone()->getOffset($dt);
    return date_i18n($format, $datetime + $offset);
}

function mvvwb_getTimeZone()
{
    if (function_exists('wp_timezone')) {
        return wp_timezone();
    }
    $timeZoneName = timezone_name_from_abbr("", get_option('gmt_offset') * HOUR_IN_SECONDS, false);
    return new DateTimeZone($timeZoneName);
}

if (!function_exists('mvvwb_getConfig')) {
    function mvvwb_getAllConfig($sec = false)
    {
        $default = [
            'labels' => [
                'dateStart' => 'Start Date',
                'dateEnd' => 'End Date',
                'dateRange' => 'Date Range',
                'timeStart' => 'Start Time',
                'duration' => 'Duration',
                'persons' => 'Persons',
                'quantity' => 'Quantity',
                'total' => 'Total',
                'adult' => 'Adult',
                'children' => 'Children',
                'bookingPrice' => 'Booking Price',
                'fixedCharge' => 'Fixed Charge',
                'bookingPricePersons' => 'Booking Price Persons',
                'selectedRange' => 'Selected:',
                'bookingPriceAdult' => 'Price (Adult)',
                'bookingPriceChildren' => 'Price (Children)',

            ],
            'resource' => [
                'resourcesTitle' => 'Resources',
                'resPriPerU' => 'Per Unit',
                'resPriPerP' => 'Per Person',
                'resPriPerPpU' => 'Per unit Per Person',
                'resPriOnce' => 'One Time',
            ],
            'button' => [
                'bookNow' => 'Book Now',
                'checkAvail' => 'Check Availability',
            ],
            'messages' => [
                'notAvailable' => 'Selected date range is not available',
                'invalidSelection' => 'Invalid Selection',
                'availQuantityS' => 'Only %d Quantity is available',
                'availQuantityP' => 'Only %d Quantities are available',
                'resourceNotAvailable' => 'Resource %s Not Available',
            ]
        ];
        $option = get_option('mvvwb_config', $default);
        foreach ($default as $key => $val) {
            if (!isset($option[$key])) {
                $option[$key] = $val;
                continue;
            }
            foreach ($val as $k => $v) {
                if (!isset($option[$key][$k])) {
                    $option[$key][$k] = $v;
                }
            }
        }
        if (!isset($option['conf'])) {
            $option['conf'] = [];
        }
        $option = apply_filters('mvvwb_config_filter', $option);
        if ($sec !== false) {
            return isset($option[$sec]) ? $option[$sec] : [];
        }
        return $option;
    }

}
if (!function_exists('mvvwb_getConfig')) {


    function mvvwb_getConfig($key, $default = false)
    {

        $config = mvvwb_getAllConfig();
        $keys = explode('.', $key);
        if (isset($keys[0])) {
            $config = isset($config[$keys[0]]) ? $config[$keys[0]] : $default;
        }
        if (isset($keys[1])) {
            $config = isset($config[$keys[1]]) ? $config[$keys[1]] : $default;
        }

        return $config;


    }

}
if (!function_exists('is_mvvwb_booking_product')) {


    function is_mvvwb_booking_product($product)
    {

    }
}
