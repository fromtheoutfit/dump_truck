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
 * Dump Truck UPD
 *
 * @package        Dump Truck
 * @category       Module
 * @author         The Outfit, Inc
 * @link           http://fromtheoutfit.com/
 * @copyright      Copyright (c) 2012 - 2013, The Outfit, Inc.
 */
class Dump_truck_upd
{

    public $version = DUMP_TRUCK_VERSION;

    public function __construct()
    {
        // Make a local reference to the ExpressionEngine super object
        $this->EE =& get_instance();
    }

    /**
     * install
     *
     * @access      public
     * @return      boolean
     */

    public function install()
    {
        $this->EE->load->dbforge();

        // module
        $data = array(
            'module_name'        => DUMP_TRUCK_UID,
            'module_version'     => $this->version,
            'has_cp_backend'     => 'y',
            'has_publish_fields' => 'n'
        );

        $this->EE->db->insert('modules', $data);

        // Create the dump truck config table
        $fields = array(
            'id'           => array('type'           => 'int',
                                    'constraint'     => '10',
                                    'unsigned'       => TRUE,
                                    'auto_increment' => TRUE),
            'site_id'      => array('type' => 'int', 'constraint' => '10'),
            'member_group' => array('type' => 'int', 'constraint' => '10'),
            'tab_name'       => array('type' => 'varchar', 'constraint' => '255'),
            'header'       => array('type' => 'varchar', 'constraint' => '255'),
            'content'      => array('type' => 'text')
        );

        $this->EE->dbforge->add_field($fields);
        $this->EE->dbforge->add_key('id', TRUE);
        $this->EE->dbforge->create_table('dump_truck_config', TRUE);
        unset($fields);

        return TRUE;
    }

    /**
     * uninstall
     *
     * @access      public
     * @return      boolean
     */

    public function uninstall()
    {
        $this->EE->load->dbforge();

        $this->EE->db->select('module_id');
        $query = $this->EE->db->get_where('modules', array('module_name' => DUMP_TRUCK_UID));

        $this->EE->db->where('module_id', $query->row('module_id'));
        $this->EE->db->delete('module_member_groups');

        $this->EE->db->where('module_name', DUMP_TRUCK_UID);
        $this->EE->db->delete('modules');

        $this->EE->db->where('class', DUMP_TRUCK_UID);
        $this->EE->db->delete('actions');

        $this->EE->dbforge->drop_table('dump_truck_config');

        return TRUE;

    }

    /**
     * update
     *
     * @access      public
     * @param string $current
     * @return      boolean
     */

    public function update($current = '')
    {
        $this->EE->load->dbforge();
        return TRUE;
    }
}
/* End of file upd.dump_truck.php */
/* Location: ./system/expressionengine/third_party/dump_truck/upd.dump_truck.php */