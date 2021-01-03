<?php

if ( ! defined( 'WPINC' ) ){
    die('Stop direct access');
}
if ( !class_exists( 'Rock_Content_GDPR_Front' ) ) {

    class Rock_Content_GDPR_Front {
        private $rc_gdpr_options;
        private $rc_gdpr_cookie ;
        private $rc_gdpr_slug;

        /**
         * Rock_Content_GDPR_Front constructor.
         */
        public function __construct(){
            $this->rc_gdpr_slug = RC_GDPR_NAME;
            $this->rc_gdpr_init();
            $this->rc_gdpr_get_options();
        }

        /**
         * Init
         */
        private function rc_gdpr_init(){
            $this->rc_gdpr_cookie = $this->rc_gdpr_get_cookie();
        }

        /**
         * Getter Options
         */
        private function rc_gdpr_get_options(){
            $this->rc_gdpr_options = get_option($this->rc_gdpr_slug);
        }

        /**
         * Hook call to render front page
         */
        public function rc_gdpr_hook_render(){
            add_action( 'template_redirect', array( $this, 'rc_gdpr_render_front' ), 0 );
        }

        /**
         * Render Front
         */
        public function rc_gdpr_render_front(){
            if ( $this->rc_gdpr_is_active() ) {
                ?>
                <div class="rc_gdpr_box rc_gdpr_<?php echo $this->rc_gdpr_options['theme']; ?> rc_gdpr_<?php echo $this->rc_gdpr_options['position']; ?>">
                    <div class="rc_gdpr_content">
                        <div class="rc_gdpr_text"><?php echo $this->rc_gdpr_options['message']; ?></div>
                        <button class="rc_gdpr_ok_button" onclick="rc_gdpr_dismiss()"><?php echo $this->rc_gdpr_options['button_text']; ?></button>
                    </div>
                </div>
                <?php
            }
        }

        /**
         * @return bool
         * Cookie Test
         */
        private function rc_gdpr_get_cookie(){
            if ( empty($_COOKIE['rc_gdpr_cookie']) || $_COOKIE['rc_gdpr_cookie'] != 'dismiss' ) {
                return false;
            }
            return true;
        }

        /**
         * @return bool
         * Activated function
         */
        private function rc_gdpr_is_active(){
            if (!$this->rc_gdpr_cookie && isset($this->rc_gdpr_options['active']) && $this->rc_gdpr_options['active'] == 'on'){
                return  true;
            }
            return false;
        }

    }
}