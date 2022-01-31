<?php

if( ! class_exists( 'pfr__dppy_admin' ) ){

    class pfr__dppy_admin{

        private $options;
        private $plugin_name;
        private $plugin_basename;
        private $plugin_slug;
        private $plugin_slug_db;
        private $plugin_version;

        public function __construct( $name, $basename, $slug, $slug_db, $version ){
            $this->options          = get_option( 'pfr_delete_posts_per_year' );
            $this->plugin_name      = $name;
            $this->plugin_basename  = $basename;
            $this->plugin_slug      = $slug;
            $this->plugin_slug_db   = $slug_db;
            $this->plugin_version   = $version;

            add_action( 'admin_menu', [ $this, 'pfr__dppy__addPluginPage' ] );
            add_action( 'admin_init', [ $this, 'pfr__dppy__pageInit' ] );
            add_action( 'admin_footer_text', [ $this, 'pfr__dppy__pageFooter' ] );
            add_action( 'admin_notices', [ $this, 'pfr__dppy__showNotices' ] );
            add_filter( 'plugin_action_links_' . $this->plugin_basename, [ $this, 'pfr_add_settings_link' ] );
        }

        public function pfr__dppy__addPluginPage(){
            add_options_page(
                __( 'Settings: ' . $this->plugin_name ),
                $this->plugin_name,
                'manage_options',
                $this->plugin_slug,
                [ $this, 'pfr__dppy__pageOptions' ]
            );
        }

        public function pfr__dppy__pageOptions(){
            ?>
                <div class="wrap">
                    <h1><?php echo $this->plugin_name; ?></h1>
                    <form method="post" action="options.php">
                        <?php
                        settings_fields( $this->plugin_slug_db . '_options' );
                        do_settings_sections( $this->plugin_slug . '-admin' );
                        submit_button();
                        ?>
                    </form>
                </div>
            <?php
        }

        public function pfr__dppy__pageInit(){
            register_setting(
                $this->plugin_slug_db . '_options',
                $this->plugin_slug_db,
                [ $this, 'sanitize' ]
            );

            add_settings_section( 
                'settings_section_id_1', 
                __( 'Configuração do plugin', $this->plugin_slug ), 
                null,
                $this->plugin_slug . '-admin'
            );
            
            add_settings_field(
                'label_year',
                __( 'Label year', $this->plugin_slug ),
                [ $this, 'pfr_callback__label_year' ],
                $this->plugin_slug . '-admin',
                'settings_section_id_1'
            );
        }

        public function pfr_callback__label_year(){
            $value = isset( $this->options[ 'label_year' ] ) ? esc_attr( $this->options[ 'label_year' ] ) : '';

            ?>
            
            <input type="text" id="label_year" name="<?php echo $this->plugin_slug_db . '[label_year]' ;?>"  value="<?php echo $value; ?>" class="regular-text"><br>
            <p class="description"><?php echo __( 'Year to posts delete', $this->plugin_slug ); ?></p>
            
            <?php
        }
        

        public function sanitize( $input ){
            $new_input = [];

            if( isset( $input[ 'label_year' ] ) ){
                $new_input[ 'label_year' ] = sanitize_text_field( $input[ 'label_year' ] );
            }
            if( isset( $input[ 'parameter' ] ) ){
                $new_input[ 'parameter' ] = sanitize_text_field( $input[ 'parameter' ] );
            }

            return $new_input;
        }

        public function pfr__dppy__pageFooter(){
            return __( 'Plugin version', $this->plugin_slug ) . ': ' . $this->plugin_version;
        }
        
        public function pfr__dppy__showNotices(){

            $value = isset( $this->options[ 'label_year' ] ) ? esc_attr( $this->options[ 'label_year' ] ) : '';

            if( $value != '' ){
                $value = preg_replace("/[^0-9]/", "", $value);
            }

            if( $value == '' ){
                ?>
                <div class="error notice">
                <p><strong><?php echo $this->plugin_name; ?></strong></p>
                <p><?php echo __( 'The plugin will not work without a defined year.', PFR__WWW7_SA_SLUG ); ?></p>
                </div>
                <?php
            }else if( strlen($value) != 4 ){
                ?>
                <div class="error notice">
                <p><strong><?php echo $this->plugin_name; ?></strong></p>
                <p><?php echo __( 'Invalid year.', PFR__WWW7_SA_SLUG ); ?></p>
                </div>
                <?php
            }else if( $value > date( 'Y' ) ){
                ?>
                <div class="error notice">
                <p><strong><?php echo $this->plugin_name; ?></strong></p>
                <p><?php echo __( 'The year entered does not yet exist!', PFR__WWW7_SA_SLUG ); ?></p>
                </div>
                <?php
            }else if( $value < 2004 ){
                ?>
                <div class="error notice">
                <p><strong><?php echo $this->plugin_name; ?></strong></p>
                <p><?php echo __( 'The WordPress do not exists in the year', PFR__WWW7_SA_SLUG ); ?></p>
                </div>
                <?php
            }
            
        }

        public function pfr_add_settings_link( $links ){
            $settings_link = '<a href="options-general.php?page=' . $this->plugin_slug . '">' . __( 'Settings', $this->plugin_slug ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }
    }
}

?>