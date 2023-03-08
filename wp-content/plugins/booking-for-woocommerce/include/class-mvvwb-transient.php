<?php

if (!defined('ABSPATH'))
    exit;

class MVVWB_Transient
{


    public $_version;


    function __construct()
    {


    }

    static function dailyClear()
    {
        $transients = get_option('mvvwb_transients', ['products' => [], 'general' => [],'resources' => []]);
        foreach ($transients['products'] as $p) {
            foreach ($p as $key) {
                get_transient($key);
            }
        }
        foreach ($transients['resources'] as $p) {
            foreach ($p as $key) {
                get_transient($key);
            }
        }
    }

    static function clearTransByProduct($p_id)
    {
        $transients = get_option('mvvwb_transients', ['products' => [], 'general' => [],'resources'=>[]]);
        if (isset($transients['products'][$p_id])) {
            foreach ($transients['products'][$p_id] as $i => $key) {
                if (substr($key, 0, 11) !== "mvvwb_temp_") {
                    delete_transient($key);
                    unset($transients['products'][$p_id][$i]);
                }
            }
        }
        update_option('mvvwb_transients', $transients);
    }
    static function clearTransByResource($r_id)
    {
        $transients = get_option('mvvwb_transients', ['products' => [], 'general' => [],'resources'=>[]]);
        if (isset($transients['resources'][$r_id])) {
            foreach ($transients['resources'][$r_id] as $i => $key) {
                if (substr($key, 0, 11) !== "mvvwb_temp_") {
                    delete_transient($key);
                    unset($transients['products'][$r_id][$i]);
                }
            }
        }
        update_option('mvvwb_transients', $transients);
    }
    static function setTransient($key, $data, $p_id = false, $expire = false, $isResource = false)
    {
        set_transient($key, $data, $expire);
        $transients = get_option('mvvwb_transients', ['products' => [], 'general' => [], 'resources' => []]);
        if ($p_id) {
            if ($isResource) {
                if (isset($transients['resources'][$p_id])) {
                    $transients['resources'][$p_id][] = $key;
                } else {
                    $transients['resources'][$p_id] = [$key];
                }
            } else {
                if (isset($transients['products'][$p_id])) {
                    $transients['products'][$p_id][] = $key;
                } else {
                    $transients['products'][$p_id] = [$key];
                }
            }

        } else {
            $transients['general'][] = $key;
        }
        update_option('mvvwb_transients', $transients);
    }
}
