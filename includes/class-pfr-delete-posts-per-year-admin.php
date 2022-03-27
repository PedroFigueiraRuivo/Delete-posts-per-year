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
            add_filter( 'plugin_action_links_' . $this->plugin_basename, [ $this, 'pfr_add_settings_link' ] );
        }




        public function pfr__dppy__addPluginPage(){
            add_submenu_page(
                'tools.php',
                $this->plugin_name,
                $this->plugin_name,
                'edit_theme_options',
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
            
            add_settings_field(
                'label_limit',
                __( 'Limit', $this->plugin_slug ),
                [ $this, 'pfr_callback__label_limit' ],
                $this->plugin_slug . '-admin',
                'settings_section_id_1'
            );
            
            add_settings_field(
                'label_checked',
                __( 'Delete archives', $this->plugin_slug ),
                [ $this, 'pfr_callback__label_checked' ],
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

        public function pfr_callback__label_limit(){
            $value = isset( $this->options[ 'label_limit' ] ) ? esc_attr( $this->options[ 'label_limit' ] ) : '';

            ?>
            
            <input type="text" id="label_limit" name="<?php echo $this->plugin_slug_db . '[label_limit]' ;?>"  value="<?php echo $value; ?>" class="regular-text"><br>
            <p class="description"><?php echo __( 'limit to delete - 200 defalt ( 0 to infinit )', $this->plugin_slug ); ?></p>
            
            <?php
        }
        
        public function pfr_callback__label_checked(){
            $value = isset( $this->options[ 'label_checked' ] ) ? esc_attr( $this->options[ 'label_checked' ] ) : '';

            ?>
            <fieldset>
                <legend class="sreen-reader-text"><span><?php echo __( 'Delete archives', $this->plugin_slug ); ?></span></legend>
                <label><input type="checkbox" name="<?php echo $this->plugin_slug_db . '[label_checked]'; ?>" value="Yes" <?php echo ( $value == 'yes' ) ? 'checked="checked"' : ''; ?>></label>
            </fieldset>
            <?php 
        }
        



        public function sanitize( $input ){
            $new_input = [];

            if( isset( $input[ 'label_year' ] ) ){
                $new_input[ 'label_year' ] = sanitize_text_field( $input[ 'label_year' ] );
            }
            if( isset( $input[ 'label_limit' ] ) ){
                $new_input[ 'label_limit' ] = sanitize_text_field( $input[ 'label_limit' ] );
            }
            if( isset( $input[ 'label_checked' ] ) ){
                $new_input[ 'label_checked' ] = sanitize_text_field( $input[ 'label_checked' ] );
            }

            return $new_input;
        }




        public function pfr_add_settings_link( $links ){
            $settings_link = '<a href="options-general.php?page=' . $this->plugin_slug . '">' . __( 'Settings', $this->plugin_slug ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }
    }
}

?>