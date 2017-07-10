<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://allurewebsolutions.com
 * @since      1.0.0
 *
 * @package    WP_Post_Modal
 * @subpackage WP_Post_Modal/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Post_Modal
 * @subpackage WP_Post_Modal/admin
 * @author     Allure Web Solutions <info@allurewebsolutions.com>
 */
class WP_Post_Modal_Admin
{

    /**
     * The options name to be used in this plugin
     *
     * @since    1.0.0
     * @access    private
     * @var    string $option_name Option name of this plugin
     */
    private $option_name = 'wp_post_modal';

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in WP_Post_Modal_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WP_Post_Modal_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-post-modal-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in WP_Post_Modal_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WP_Post_Modal_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-post-modal-admin.js', array('jquery'), $this->version, false);

    }


    /**
     * Add an options page under the Settings submenu
     *
     * @since  1.0.0
     */
    public function add_options_page()
    {

        $this->plugin_screen_hook_suffix = add_options_page(
            __('WP Post Popup Settings', 'wp-post-modal'),
            __('WP Post Popup', 'wp-post-modal'),
            'manage_options',
            $this->plugin_name,
            array($this, 'display_options_page')
        );

    }

    /**
     * Register all related settings of this plugin
     *
     * @since  1.0.0
     */
    public function register_setting()
    {
        add_settings_section(
            $this->option_name . '_general',
            __('General', 'wp-post-modal'),
            array($this, $this->option_name . '_general_cb'),
            $this->plugin_name
        );

        // Activate styling
        add_settings_field(
            $this->option_name . '_styling',
            __('Activate basic styling', 'wp-post-modal'),
            array($this, $this->option_name . '_styling_cb'),
            $this->plugin_name,
            $this->option_name . '_general',
            array('label_for' => $this->option_name . '_styling')
        );

        add_settings_section(
            $this->option_name . '_styling_example',
            __('Styling Example', 'wp-post-modal'),
            array($this, $this->option_name . '_styling_example_cb'),
            $this->plugin_name
        );

        register_setting($this->plugin_name, $this->option_name . '_switcher', array($this, $this->option_name . '_sanitize_switcher'));
        register_setting($this->plugin_name, $this->option_name . '_styling', array($this, $this->option_name . '_sanitize_styling'));
    }

    /**
     * Render the checkbox for styling
     *
     * @since  1.0.0
     */
    public function wp_post_modal_styling_cb()
    {
        $styling = get_option($this->option_name . '_styling');
        ?>
        <fieldset>
            <label>
                <input type="checkbox" name="<?php echo $this->option_name . '_styling' ?>"
                       id="<?php echo $this->option_name . '_styling' ?>"
                       value="styling" <?php checked($styling, 'styling'); ?>
            </label>
        </fieldset>
        <?php
    }


    /**
     * Render the text for the general section
     *
     * @since  1.0.0
     */
    public function wp_post_modal_general_cb()
    {
        echo '<p>' . __('A few options for the WP Post Popup plugin.', 'wp-post-modal') . '</p>';
    }

    /**
     * Render the image for the styling example
     *
     * @since  1.0.0
     */
    public function wp_post_modal_styling_example_cb()
    {
        echo '<img src="' . plugins_url() . '/wp-post-modal/admin/images/modal-styling-example.jpg" width="300px" />';
    }

    /**
     * Render the options page for plugin
     *
     * @since  1.0.0
     */
    public function display_options_page()
    {
        include_once 'partials/wp-post-modal-admin-display.php';
    }

    /**
     * Add settings link to plugin page
     *
     * @param $links
     * @return mixed
     */
    public function plugin_add_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=wp-post-modal">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

    /**
     * Add Visual Editor button for popup links
     *
     * @param $buttons
     * @return mixed
     */
    public function register_custom_mce_buttons($buttons)
    {
        //register buttons with their id.
        array_push($buttons, 'modal_link');
        return $buttons;
    }

    public function enqueue_custom_mce_scripts($plugin_array)
    {
        //enqueue TinyMCE plugin script with its ID.
        $plugin_array['wp_post_modal'] = plugin_dir_url(__FILE__) . "js/mce-button.js";
        return $plugin_array;
    }
}
