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

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

// config
require_once PATH_THIRD . 'dump_truck/config' . EXT;

/**
 * Dump Truck Model
 *
 * @package        Dump Truck
 * @category       model
 * @author         The Outfit, Inc
 * @link           http://fromtheoutfit.com/
 * @copyright      Copyright (c) 2012 - 2013, The Outfit, Inc.
 */

class Dump_truck_model
{

    public function __construct()
    {
        // ee super object
        $this->EE      =& get_instance();
        $this->site_id = $this->EE->config->item('site_id');

        // comment out the following line to enable caching
        $this->EE->db->cache_off();
    }

    /**
     * returns an object of config options for the current site id
     *
     * @access public
     * @return object
     */

    public function get_configs()
    {
        $this->EE->db->where('site_id', $this->site_id);
        return $this->EE->db->get('dump_truck_config');
    }

    /**
     * Deletes existing configs for the current site id and then sets new options
     *
     * @access public
     * @param array $data
     * @return void
     */

    public function set_configs($data)
    {
        // delete the current configs for this site id
        $this->EE->db->where('site_id', $this->site_id);
        $this->EE->db->delete('dump_truck_config');

        if (sizeof($data) > 0)
        {
            $this->EE->db->insert_batch('dump_truck_config', $data);
        }
    }

    /**
     * Returns the accessory settings for the current user based on group id
     *
     * @access public
     * @param int $member_group
     * @return object
     */

    public function get_config($member_group = 0)
    {
        $where_in = array();

        // create an array that includes both the *all* member group (0) and the current member group
        if (!$member_group)
        {
            $member_group = $this->EE->session->userdata['group_id'];
        }

        array_push($where_in, (int)$member_group);
        array_push($where_in, 0);

        $this->EE->db->select('tab_name, header, content');
        $this->EE->db->where_in('member_group', $where_in);
        $this->EE->db->order_by('id', 'desc');

        return $this->EE->db->get('dump_truck_config', 1);

    }

}

/* End of file dump_truck_model.php */
/* Location: ./system/expressionengine/third_party/dump_truck/models/dump_truck_model.php */