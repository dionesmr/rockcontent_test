<?php

if ( ! defined( 'WPINC' ) ){
    die('Stop direct access');
}

if ( !class_exists( 'Rock_Content_GDPR_Settings' ) ) {

    class Rock_Content_GDPR_Settings {
        private $rc_gdpr_name;
        private $rc_gdpr_slug;
        private $rc_gdpr_domain;
        private $rc_gdpr_options;
        private $rc_gdpr_settings;

        /**
         * Rock_Content_GDPR_Settings constructor.
         */
        public function __construct(){
            $this->rc_gdpr_name  = 'Rock Content GDPR';
            $this->rc_gdpr_slug = RC_GDPR_NAME;
            $this->rc_gdpr_domain = $this->rc_gdpr_slug;

            // Init settings
            add_action( 'admin_init', array( $this, PREFIX . 'init' ) );

            // Add Page menu
            add_action( 'admin_menu', array( $this, PREFIX . 'add_menu_page' ) );

            // Add "settings" link into plugin page
            add_filter( 'plugin_action_links_' . RC_GDPR_NAME . '/' . RC_GDPR_NAME . '.php', array( $this, PREFIX . 'add_settings_link' ) );
        }


        /**
         * Init
         */
        public function rc_gdpr_init(){
            $this->rc_gdpr_settings = $this->rc_gdpr_settings_fields();
            $this->rc_gdpr_options  = $this->rc_gdpr_get_options();
            $this->rc_gdpr_register_settings();
        }

        /**
         * Add Page menu
         */
        public function rc_gdpr_add_menu_page(){
            $page = add_options_page( $this->rc_gdpr_name, $this->rc_gdpr_name, 'manage_options', $this->rc_gdpr_slug, array( $this, 'rc_gdpr_settings_page' ) );
        }

        /**
         * Format Link
         * @param $links
         * @return mixed
         */
        public function rc_gdpr_add_settings_link($links){
            $settings_link = '<a href="options-general.php?page='.$this->rc_gdpr_slug.'">' . __( 'Settings', $this->rc_gdpr_domain ) . '</a>';
            array_push( $links, $settings_link);
            return $links;
        }

        /**
         * Format $rc_gdpr_settings variable
         * @return array
         */
        public function rc_gdpr_settings_fields() {
            $settings['current'] = array(
                'title'                 => __( 'Settings', $this->rc_gdpr_domain ),
                'description'           => __( 'Change your cookie box settings below.', $this->rc_gdpr_domain ),
                'fields'                => array(
                    array(
                        'id'            => 'active',
                        'label'         => __( 'Activated', $this->rc_gdpr_domain ),
                        'description'   => __( '', $this->rc_gdpr_domain ),
                        'type'          => 'checkbox',
                        'default'       => '',
                        'placeholder'   => ''
                    ),
                    array(
                        'id'            => 'position',
                        'label'         => __( 'Position', $this->rc_gdpr_domain ),
                        'description'   => __( 'Display message position', $this->rc_gdpr_domain ),
                        'type'          => 'select',
                        'options'       => array( 'bottom' => __('Bottom', $this->rc_gdpr_domain), 'top' => __('Top', $this->rc_gdpr_domain) ),
                        'default'       => 'top'
                    ),
                    array(
                        'id'            => 'theme',
                        'label'         => __( 'Theme', $this->rc_gdpr_domain ),
                        'description'   => __( 'Choose beetween themes', $this->rc_gdpr_domain ),
                        'type'          => 'select',
                        'options'       => array( 'ocean' => 'Ocean', 'light' => 'Light', 'forest' => 'Forest' ),
                        'default'       => 'ocean'
                    ),
                    array(
                        'id'            => 'message',
                        'label'         => __( 'Message', $this->rc_gdpr_domain ),
                        'description'   => __( 'Choose the box message', $this->rc_gdpr_domain ),
                        'type'          => 'wysiwyg',
                        'default'       => 'We use cookies to provide our services and for analytics and marketing. To find out more about our use of cookies, please see our Privacy Policy. By continuing to browse our website, you agree to our use of cookies.',
                        'placeholder'   => __( 'Standard', $this->rc_gdpr_domain ),
                    ),
                    array(
                        'id'            => 'button_text',
                        'label'         => __( 'Button', $this->rc_gdpr_domain ),
                        'description'   => __( 'Label text', $this->rc_gdpr_domain ),
                        'type'          => 'text',
                        'default'       => 'Aceito',
                        'placeholder'   => __( 'Standard', $this->rc_gdpr_domain ),
                    )
                )
            );

            return $settings;
        }

        /**
         * Getter Options
         * @return array|false|mixed|void
         */
        public function rc_gdpr_get_options() {
            $options = get_option($this->rc_gdpr_slug);
            if ( !$options && is_array( $this->rc_gdpr_settings ) ) {
                $options =  Array();
                foreach ( $this->rc_gdpr_settings as $section => $data ){
                    foreach ( $data['fields'] as $field ){
                        $options[ $field['id'] ] = $field['default'];
                    }
                }
            }
            return $options;
        }


        /**
         * Register Plugin Settings
         */
        public function rc_gdpr_register_settings(){
            if ( is_array( $this->rc_gdpr_settings ) ){
                register_setting( $this->rc_gdpr_slug, $this->rc_gdpr_slug);
                foreach ( $this->rc_gdpr_settings as $section => $data ){
                    add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->rc_gdpr_slug );
                    foreach ( $data['fields'] as $field ){
                        add_settings_field( $field['id'], $field['label'], array( $this, PREFIX . 'render' ), $this->rc_gdpr_slug, $section, array( 'field' => $field ) );
                    }
                }
            }
        }

        /**
         * Settigs Description
         * @param $section
         */
        public function settings_section($section ){
            $html = '<p> ' . $this->rc_gdpr_settings[$section['id']]['description'] . '</p>' . '<br/>';
            echo $html;
        }

        /**
         * Render fileds
         * @param $args
         */
        public function rc_gdpr_render($args ){
            $field  = $args['field'];
            $html   = '';
            $option_name = $this->rc_gdpr_slug . "[" . $field['id'] . "]";
            $data = ( isset($this->rc_gdpr_options[$field['id']]) ) ? $this->rc_gdpr_options[$field['id']] : '';

            switch ( $field['type'] ){

                case 'text':
                    $html = '<input id="' . PREFIX . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . $data . '"/>';
                    $html .= ' <label for="' . esc_attr( $field['id'] ) . '"><span class="description">' . $field['description'] . '</span></label>';
                    break;

                case 'textarea':
                    $html .= '<textarea id="' . PREFIX . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>'. "\n";
                    $html .= ' <label for="' . esc_attr( $field['id'] ) . '"><span class="description">' . $field['description'] . '</span></label>';
                    break;

                case 'wysiwyg':
                    wp_editor($data, PREFIX . esc_attr( $field['id'] ), array('textarea_rows' => 10, 'media_buttons' => false, 'textarea_name' => esc_attr( $option_name )));
                    $html .= ' <label for="' . esc_attr( $field['id'] ) . '"><span class="description">' . $field['description'] . '</span></label>';
                    break;

                case 'checkbox':
                    $checked = '';
                    if( $data && 'on' == $data ){
                        $checked = 'checked="checked"';
                    }
                    $html .= '<input id="' . PREFIX . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
                    $html .= ' <label for="' . esc_attr( $field['id'] ) . '"><span class="description">' . $field['description'] . '</span></label>';
                    break;

                case 'select':
                    $html .= '<select name="' . esc_attr( $option_name ) . '" id="' . PREFIX . esc_attr( $field['id'] ) . '">';
                    foreach( $field['options'] as $k => $v ) {
                        $selected = false;
                        if( $k == $data ) {
                            $selected = true;
                        }
                        $html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
                    }
                    $html .= '</select> ';
                    $html .= ' <label for="' . esc_attr( $field['id'] ) . '"><span class="description">' . $field['description'] . '</span></label>';
                    break;

            }

            echo $html;
        }

        /**
         * Construct all Settings Data Page
         */
        public function rc_gdpr_settings_page(){
            ?>
                <div class="wrap" id="<?php echo $this->rc_gdpr_slug; ?>">
                    <h2 class="rc_gdpr_h2"><?php _e('Rock Content GDPR Settings', $this->rc_gdpr_slug); ?></h2>
                </div>

                <form action="options.php" method="POST" class="rc_gdpr_form">
                    <?php settings_fields( $this->rc_gdpr_slug ); ?>
                    <div class="settings-container">
                        <?php do_settings_sections( $this->rc_gdpr_slug ); ?>
                    </div>
                    <?php submit_button(); ?>
                </form>
            <?php
        }

    }
}