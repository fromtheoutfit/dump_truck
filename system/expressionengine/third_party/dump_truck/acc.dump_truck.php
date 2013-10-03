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

// Include config file
require_once PATH_THIRD . 'dump_truck/config' . EXT;

/**
 * Dump Truck
 *
 * @package        Dump Truck
 * @author         The Outfit, Inc
 * @link           http://fromtheoutfit.com/dump_truck
 */

class Dump_truck_acc
{
    var $name = DUMP_TRUCK_NAME;
    var $id = DUMP_TRUCK_UID;
    var $version = DUMP_TRUCK_VERSION;
    var $description = 'Dump Truck allows you to quickly create an accessory for each member group.';
    var $sections = array();

    public function __construct()
    {
        // ee super object
        $this->EE      =& get_instance();
        $this->site_id = $this->EE->config->item('site_id');

        // comment out to enable caching
        $this->EE->db->cache_off();

        // load model
        $this->EE->load->model('dump_truck_model', 'dump_truck');
    }

    /**
     * Set Sections
     * Set content for the accessory
     *
     * @access  public
     * @return  void
     */

    public function set_sections()
    {
        // get the correct content for the current member
        $config = $this->EE->dump_truck->get_config();

        if ($config->num_rows() == 1)
        {
            $c = $config->row();

            // set the tab name, header and content for the accessory
            $this->name = $c->tab_name;
            $this->sections[$c->header] = $c->content;
        }

        $config->free_result();
    }

    /**
     * Update dump truck
     *
     * @access public
     * @return boolean
     */

    public function update()
    {
        return TRUE;
    }
}

/* End of file acc.dump_truck.php */
/* Location: ./system/expressionengine/third_party/dump_truck/acc.dump_truck.php */