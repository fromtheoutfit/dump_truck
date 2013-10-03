<?php
/*
 __    __                                   __       ___     __
/\ \__/\ \                                 /\ \__  /'___\ __/\ \__
\ \ ,_\ \ \___      __         ___   __  __\ \ ,_\/\ \__//\_\ \ ,_\
 \ \ \/\ \  _ `\  /'__`\      / __`\/\ \/\ \\ \ \/\ \ ,__\/\ \ \ \/
  \ \ \_\ \ \ \ \/\  __/     /\ \L\ \ \ \_\ \\ \ \_\ \ \_/\ \ \ \ \_
   \ \__\\ \_\ \_\ \____\    \ \____/\ \____/ \ \__\\ \_\  \ \_\ \__\
    \/__/ \/_/\/_/\/____/     \/___/  \/___/   \/__/ \/_/   \/_/\/__/
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

// Include some goods
require_once PATH_THIRD . 'dump_truck/config' . EXT;

/**
 * Dump Truck
 *
 * @package        Dump Truck
 * @category       Module
 * @author         The Outfit, Inc
 * @link           http://fromtheoutfit.com/
 * @copyright      Copyright (c) 2012 - 2013, The Outfit, Inc.
 */
class Dump_truck
{
    private $version = DUMP_TRUCK_VERSION;

    public function __construct()
    {
        // ee super object
        $this->EE      =& get_instance();
        $this->site_id = $this->EE->config->item('site_id');
    }
}

/* End of file mod.dump_truck.php */
/* Location: ./system/expressionengine/third_party/dump_truck/mod.dump_truck.php */