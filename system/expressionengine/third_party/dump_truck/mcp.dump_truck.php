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

/**
 * Dump Truck MCP
 *
 * @package        Dump Truck
 * @category       Module
 * @author         The Outfit, Inc
 * @link           http://fromtheoutfit.com/
 * @copyright      Copyright (c) 2012 - 2013, The Outfit, Inc.
 */
class Dump_truck_mcp
{
    private $site_id = 1;

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
     * addon index page
     *
     * @return    string
     */

    public function index()
    {
        $this->EE->load->library('table');
        $this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('dump_truck_module_name'));

        $config_obj    = $this->EE->dump_truck->get_configs();
        $config        = array();
        $members_obj   = $this->EE->member_model->get_member_groups(array('can_access_cp'));
        $member_groups = array();
        $tab_name      = $this->EE->lang->line('dump_truck_module_name');
        $header        = $this->EE->lang->line('dump_truck_module_name');
        $content       = '';

        // get the current configs and store them in array
        if ($config_obj->num_rows() > 0)
        {
            foreach ($config_obj->result() as $c)
            {
                $config[$c->member_group] = array(
                    'tab_name' => $c->tab_name,
                    'header'   => $c->header,
                    'content'  => $c->content,
                );
            }
        }

        // push the first *all* group onto the front of the array
        if (isset($config[0]))
        {
            $tab_name = $config[0]['tab_name'];
            $header   = $config[0]['header'];
            $content  = $config[0]['content'];
        }

        $group = array(
            'id'       => 0,
            'title'    => $this->EE->lang->line('dump_truck_all_groups'),
            'tab_name' => $tab_name,
            'header'   => $header,
            'content'  => $content,
        );

        array_push($member_groups, $group);

        // loop through all of the other member groups
        if ($members_obj->num_rows() > 0)
        {
            foreach ($members_obj->result() as $m)
            {
                $header   = $this->EE->lang->line('dump_truck_module_name');
                $tab_name = $this->EE->lang->line('dump_truck_module_name');
                $content  = '';

                // make sure the member group we are looping through has cp access
                if ($m->can_access_cp == 'y')
                {
                    if (isset($config[$m->group_id]))
                    {
                        $tab_name = $config[$m->group_id]['tab_name'];
                        $header   = $config[$m->group_id]['header'];
                        $content  = $config[$m->group_id]['content'];

                        // let's html entity decode anything between code blocks
                        $content = preg_replace_callback(
                            '/<code>(.*?)<\/code>/is',
                            function ($matches)
                            {
                                return '<code>' . html_entity_decode($matches[1]) . '</code>';
                            }, $content);
                    }

                    $group = array(
                        'id'       => $m->group_id,
                        'title'    => $m->group_title,
                        'tab_name' => $tab_name,
                        'header'   => $header,
                        'content'  => $content,
                    );

                    array_push($member_groups, $group);
                }
            }
        }

        $vars = array(
            'member_groups' => $member_groups,
            'action_url'    => 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=dump_truck' . AMP . 'method=config_handler',
            'form_hidden'   => NULL,
        );

        return $this->EE->load->view('/mcp/index', $vars, TRUE);
    }

    /**
     * it puts the configs in the database
     *
     * @access public
     * @return void
     */

    public function config_handler()
    {
        $errors = FALSE;
        $data   = array();

        if ($_POST)
        {
            foreach ($_POST as $k => $v)
            {
                if (substr($k, 0, 7) == 'content' && strlen(trim($this->EE->input->post($k))) > 0)
                {
                    $member_group = str_replace('content_', '', $k);
                    $tab_name     = (strlen(trim($this->EE->input->post('tab_name_' . $member_group))) > 0)
                        ? $this->EE->input->post('tab_name_' . $member_group)
                        : $this->EE->lang->line('dump_truck_module_name');
                    $header       = (strlen(trim($this->EE->input->post('header_' . $member_group))) > 0)
                        ? $this->EE->input->post('header_' . $member_group)
                        : $this->EE->lang->line('dump_truck_module_name');

                    $content = $this->EE->input->post($k);

                    // let's html entity decode anything between code blocks
                    $content = preg_replace_callback(
                        '/<code>(.*?)<\/code>/is',
                        function ($matches)
                        {
                            return '<code>' . htmlentities($matches[1]) . '</code>';
                        }, $content);

                    $config = array(
                        'site_id'      => $this->site_id,
                        'member_group' => $member_group,
                        'tab_name'     => $tab_name,
                        'header'       => $header,
                        'content'      => $content,
                    );

                    array_push($data, $config);
                }
            }
        }

        if ($errors)
        {
            $this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('dump_truck_config_failure'));
        }
        else
        {
            $this->EE->dump_truck->set_configs($data);
            $this->EE->session->set_flashdata('message_success', $this->EE->lang->line('dump_truck_config_success'));
        }

        $this->EE->functions->redirect(BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=dump_truck' . AMP . 'method=index');
    }

}
/* End of file mcp.dump_truck.php */
/* Location: ./system/expressionengine/third_party/dump_truck/mcp.dump_truck.php */
