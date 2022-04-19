<?php
namespace Qkly;
class JobSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    const OPTION_NAME = 'qkly_option_name';

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', [$this, 'add_plugin_page' ] );
        add_action( 'admin_init', [$this, 'page_init']);
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_menu_page(
            'Settings Admin',
            'Qkly Job Listing',
            'manage_options',
            'qkly-setting-admin',
            [ $this, 'create_admin_page' ],
            'dashicons-yes-alt'
        );
    }

    public static function getEndPoint()
    {
        $options = get_option( self::OPTION_NAME );
        $apiUrl = ( !empty( $options ) && isset( $options['api_url'] ) ) ? $options['api_url'] : "https://qkly-jobboard-production-api.azurewebsites.net/";
        return $apiUrl;
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( self::OPTION_NAME );
        ?>
        <div class="wrap">
            <?php if (isset($_GET['settings-updated'])) : ?>
                <div class="notice notice-success is-dismissible"><p><?php _e("Settings saved."); ?>.</p></div>
            <?php endif; ?>

            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'qkly_option_group' );
                do_settings_sections( 'qkly-setting-admin' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'qkly_option_group', // Option group
            self::OPTION_NAME, // Option name
            [$this, 'sanitize'] // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Qkly Job Listing Settings', // Title
            [$this, 'print_section_info'], // Callback
            'qkly-setting-admin' // Page
        );

        add_settings_field(
            'api_url', // ID
            'API Url', // Title
            [$this, 'api_url_callback'], // Callback
            'qkly-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'jobs_per_page',
            'Number of Jobs Per Page',
            [$this, 'jobs_per_page_callback'],
            'qkly-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'layout_display',
            'Grid',
            [$this,'layout_display_callback'],
            'qkly-setting-admin',
            'setting_section_id'
        );
        add_settings_field(
            'company_id',
            'Company ID',
            [$this, 'company_id_callback'],
            'qkly-setting-admin',
            'setting_section_id'
        );

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = [];
        $new_input['api_url'] = ( isset( $input['api_url'] ) &&  !empty( $input['api_url'] ) ) ?
                                esc_url_raw( $input['api_url'] ) : '';

       $new_input['jobs_per_page'] = ( isset( $input['jobs_per_page'] ) &&  !empty( $input['jobs_per_page'] ) ) ?
                                    filter_var( $input['jobs_per_page'], FILTER_VALIDATE_INT ) : 10;

        $new_input['company_id'] = ( isset( $input['company_id'] ) &&  !empty( $input['company_id'] ) ) ?
                                    sanitize_text_field( $input['company_id'] ) : "";

        $new_input['layout_display'] = ( isset( $input['layout_display'] ) &&  !empty( $input['layout_display' ] ) ) ?
                                    sanitize_text_field( $input['layout_display'] ) : "double-col";

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print "<h2>Enter your settings :</h2>
            Use [qkly_job_list] shortcode in editor for displaying the jobs list. 
";
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function api_url_callback()
    {
        $endPoint = self::getEndPoint();
        printf(
            '<input type="text" id="api_url" name="%s[api_url]" class="widefat" style="max-width: 500px;" value="%s" />',
            self::OPTION_NAME, $endPoint
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function jobs_per_page_callback()
    {
        $numberOfJobs = ( isset($this->options['jobs_per_page'])  && ( !empty($this->options['jobs_per_page']) ) ) ?
                        filter_var( $this->options['jobs_per_page'], FILTER_VALIDATE_INT ) : 10;

        printf(
            '<input type="text" id="jobs_per_page" name="%s[jobs_per_page]" size="2" value="%s" />',
            self::OPTION_NAME, $numberOfJobs
        );
    }

    public function layout_display_callback()
    {
        $layoutDisplay = ( isset( $this->options['layout_display'] ) && ( !empty( $this->options['layout_display'] ) ) ) ?
                        sanitize_text_field( $this->options['layout_display'] ) : "double-col";

        ?>
        <input type="radio" id="layout_display" name="<?php echo self::OPTION_NAME; ?>[layout_display]"
            size="2" value="single-col" <?php echo ($layoutDisplay=="single-col") ? "checked" : ""; ?> />
            <label for="single-col">Single Column</label><br>
            <input type="radio" id="layout_display" name="<?php echo self::OPTION_NAME; ?>[layout_display]"
            size="2" value="double-col" <?php echo $layoutDisplay=="double-col" ? "checked" : ""?>  />
        <label for="double-col">Double Column</label>
<?php
    }

    public function company_id_callback() {
        $companyID = ( isset( $this->options['company_id'] ) && ( !empty( $this->options['company_id'] ) ) ) ?
                    sanitize_text_field( $this->options['company_id'] ) : "";
        printf(
            '<input type="text" id="company_id" name="%s[company_id]" class="widefat" style="max-width: 500px;"  value="%s" />',
            self::OPTION_NAME, $companyID
        );
    }
}