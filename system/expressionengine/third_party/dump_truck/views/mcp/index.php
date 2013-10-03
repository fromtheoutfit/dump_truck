<p><?= lang('dump_truck_config_instructions') ?></p>

<?= form_open($action_url, '', $form_hidden); ?>
<?php
$this->table->set_template($cp_table_template);
$this->table->set_heading(
    lang('dump_truck_member_group'),
    lang('dump_truck_tab_name'),
    lang('dump_truck_header'),
    lang('dump_truck_content')
);

foreach ($member_groups as $mg)
{
    $this->table->add_row(
        $mg['title'],
        form_input('tab_name_' . $mg['id'], $mg['tab_name']),
        form_input('header_' . $mg['id'], $mg['header']),
        form_textarea('content_' . $mg['id'], $mg['content'])
    );
}

echo $this->table->generate();
?>
<div class="tableFooter">
    <div class="tableSubmit">
        <?= form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit')) ?>
    </div>
    <span class="pagination" id="filter_pagination"></span>
</div>

<?= form_close(); ?>