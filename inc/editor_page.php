<?php
/*
 * page to save global css
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('cf7style_editor_page_init')) {
    add_action('admin_menu', 'cf7style_editor_page_init');
    function cf7style_editor_page_init()
    {
        add_submenu_page(
            'edit.php?post_type=cf7_style',
            __("Settings", "contact-form-7-style"),
            __("Settings", "contact-form-7-style"),
            'manage_options',
            'cf7style-settings',
            'cf7style_settings_view'
        );
    }
}

if (!function_exists('cf7style_settings_view')) {
    function cf7style_settings_view()
    {?>
        <form method="POST" action="">
        <?php
do_settings_sections('cf7style-settings');
        submit_button(__("Save Settings", "contact-form-7-style"), 'primary', 'cf7styletracking');?>
        </form>
   <?php }
}
$initiatenewoptions = new init_sections_register_fields();

class init_sections_register_fields
{
    public function __construct()
    {
        add_filter('admin_init', array($this, 'register_new_fields'));
    }
    public function cf7style_render_checkbox($option, $args, $description, $tulip)
    {
        $tulip = $tulip ? '<div class="cf7style-tooltip" title="more info"><i class="fa fa-question-circle" aria-hidden="true">?</i><div class="cf7style-tooltip-content">' . $tulip . '<div/></div>' : '';
        return '<label><input type="checkbox" value="1" ' . checked(1, $option, false) . ' id="' . $args[0] . '[' . $args[0] . ']" name="' . $args[0] . '" />' . __($description, 'contact-form-7-style') . '</label>' . $tulip;
    }
    public function cf7style_templates_structure($args)
    {
        $html = "";
        $html .= '</tr>';
        $html .= '<tr><td colspan="2">';
        $option = get_option($args[0]);
        if ($option == '1') {
            update_option('cf7_style_add_categories', 0);
        }
        if (isset($_POST['cf7styletracking'])) {
            update_option('cf7_style_no_temps', 'show_box');
        }
        $html .= $this->cf7style_render_checkbox($option, $args, 'Install predefined templates', '<p>' . __("From here you will be able to import the Contact Form 7 Style predefined templates.", "contact-form-7-style") . '</p><p><small>' . __("This works only if  the  predefined templates are Deleted Permanently (they don't appear in", "contact-form-7-style") . ' <a href="' . admin_url('edit.php?post_status=trash&post_type=cf7_style') . '">' . __("Trash", "contact-form-7-style") . '</a> ).</small></p>');
        $html .= '</td></tr><tr><td colspan="2">';
        return $html;
    }

    public function cf7style_tooltip_structure($args)
    {
        $html = "";
        $html .= '</tr>';
        $html .= '<tr><td colspan="2">';
        $option = get_option($args[0]);
        if (isset($_POST['cf7_style_form_tooltip'])) {
            update_option('cf7_style_form_tooltip', '1');
        }
        $html .= $this->cf7style_render_checkbox($option, $args, 'Show the frontend form edit button', '');
        $html .= '</td></tr><tr><td colspan="2">';
        return $html;
    }

    public function cf7style_forcecss_structure($args)
    {
        $html = "";
        $html .= '</tr>';
        $html .= '<tr><td colspan="2">';
        $option = get_option($args[0]);
        if (isset($_POST['cf7_style_forcecss'])) {
            update_option('cf7_style_forcecss', '1');
        }
        $html .= $this->cf7style_render_checkbox($option, $args, 'Force CSS', '');
        $html .= '</td></tr><tr><td colspan="2">';
        return $html;
    }

    public function cf7style_adminbar_structure($args)
    {
        $html = "";
        $html .= '</tr>';
        $html .= '<tr><td colspan="2">';
        $option = get_option($args[0]);
        if (isset($_POST['cf7_style_adminbar'])) {
            update_option('cf7_style_adminbar', '1');

        }
        $html .= $this->cf7style_render_checkbox($option, $args, 'Add Contact Form 7 Style in the adminbar', '');
        $html .= '</td></tr><tr><td colspan="2">';
        return $html;
    }

    public function register_new_fields()
    {
        add_settings_section(
            'cf7stylesettings',
            __('Contact Form 7 Style Settings', 'contact-form-7-style'),
            array($this, 'settings_description'),
            'cf7style-settings'
        );
        /*$fields = array(
            'cf7_style_manual_style' => 'CSS',
        );*/

        $set_fields = array(
            'cf7_style_deleted' => __('Import predefined Contact Form 7 Style templates', 'contact-form-7-style'),
            'cf7_style_form_tooltip' => __('Display form edit tooltip on frontend?', 'contact-form-7-style'),
            'cf7_style_forcecss' => __('Active theme overrides your form styling?', 'contact-form-7-style'),
            'cf7_style_adminbar' => __('Display Contact Form 7 Style in admin bar?', 'contact-form-7-style'),
        );
        foreach ($set_fields as $field => $value) {
            add_settings_field(
                $field,
                $value,
                array($this, 'setting_inputs'),
                'cf7style-settings',
                'cf7stylesettings',
                array($field)
            );
            register_setting('general', $field, 'esc_attr');
        }

        foreach ($fields as $field => $value) {
            add_settings_field(
                $field,
                $value,
                //array($this, 'text_inputs'),
                //'cf7style-css-editor',
                'cf7styleeditor',
                array($field)
            );
            register_setting('general', $field, 'esc_attr');
        }
    }

    public function setting_inputs($args)
    {

        if (isset($_POST[$args[0]])) {
            update_option($args[0], 1);
            echo "<script>location.reload();</script>";
        } else {
            if (isset($_POST['cf7styletracking'])) {
                update_option($args[0], 0);
            }
            if (isset($_POST['cf7_style_form_tooltip'])) {
                update_option($args[0], 0);
            }
        }
        switch ($args[0]) {
            case 'cf7_style_deleted':
                echo $this->cf7style_templates_structure($args);
                break;
            case 'cf7_style_form_tooltip':
                echo $this->cf7style_tooltip_structure($args);
                break;
            case 'cf7_style_forcecss':
                echo $this->cf7style_forcecss_structure($args);
                break;
            case 'cf7_style_adminbar':
                echo $this->cf7style_adminbar_structure($args);
                break;
        }

    }
    public function settings_description()
    {
    }
}