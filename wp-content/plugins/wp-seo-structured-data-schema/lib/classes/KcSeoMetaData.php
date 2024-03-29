<?php

if(!class_exists('KcSeoMetaData')):

    class KcSeoMetaData
    {
        function __construct()
        {
            add_action('admin_enqueue_scripts',	array($this, 'admin_enqueue_scripts'));
            add_action( 'save_post', array($this, 'save_KcSeo_schema_data'),10, 3);
        }

        function admin_enqueue_scripts(){
            global $pagenow, $typenow, $KcSeoWPSchema;
            // validate page
            $pt = $KcSeoWPSchema->kcSeoPostTypes();
            if( !in_array( $pagenow, array('post.php', 'post-new.php') ) )  return;
            if(!in_array($typenow, $pt)) return;

            // scripts
            wp_enqueue_script(array(
                'jquery',
                'jquery-ui-datepicker',
                'kcseo-select2-js',
                'kcseo-admin-js',
            ));

            // styles
            wp_enqueue_style(array(
                'kcseo-ui-css',
                'kcseo-select2-css',
                'kcseo-admin-css',
            ));

            add_action('admin_head', array($this,'admin_head'));
        }

        function admin_head(){
            global $KcSeoWPSchema;
            $pt = $KcSeoWPSchema->kcSeoPostTypes();
            foreach($pt as $postType){
                add_meta_box(
                    'kcseo-wordpres-seo-structured-data-schema-meta-box',
                    __('WP SEO Structured Data Schema by <a href="http://kcseopro.com/">KCSEOPro.com</a>', KCSEO_WP_SCHEMA_SLUG),
                    array($this,'meta_box_wp_schema'),
                    $postType,
                    'normal',
                    'high'
                );
            }

        }

        function meta_box_wp_schema($post){

            global $KcSeoWPSchema;
            wp_nonce_field( $KcSeoWPSchema->nonceText(), '_kcseo_nonce' );
            $schemas = new KcSeoSchemaModel;
            $html = null;
            $html .="<div class='schema-tips'>";
                $html .= "<p><span>Tip:</span> For more detailed information on how to configure this plugin, please visit: <a href='http://kcseopro.com/wordpress-seo-structured-data-schema-plugin/'>http://kcseopro.com/wordpress-seo-structured-data-schema-plugin/</a></p>";
                $html .= "<p><span>Tip:</span> Once you save these structured data schema settings, validate this page url here: <a href='https://developers.google.com/structured-data/testing-tool/'>https://developers.google.com/structured-data/testing-tool/</a></p>";
            $html .="</div>";
            $html .= "<div class='schema-holder'>";
                $html .= '<div id="meta-tab-holder" class="rt-tab-container">';
                        $htmlMenu = null;
                        $htmlCont = null;
                        $htmlMenu .= "<ul class='rt-tab-nav'>";
                        foreach($schemas->schemaTypes() as $schemaID => $schema){
                            $tabId = $KcSeoWPSchema->KcSeoPrefix.$schemaID;
                            $htmlMenu .= '<li><a href="#'.$tabId.'">'.$schema['title'].'</a></li>';
                            $htmlCont .="<div id='{$tabId}' class='rt-tab-content'>";
                                foreach($schema['fields'] as $fieldId => $data){
                                    $data['schemaId'] = $schemaID;
                                    $htmlCont .= $schemas->get_field($fieldId,$data, $post->ID);
                                }
                            $htmlCont .="</div>";
                        }
                        $htmlMenu .="</ul>";
                              $html .= $htmlMenu .$htmlCont;
                $html .= "</div>";
            $html .= "</div>";
            echo $html;
        }

        function save_KcSeo_schema_data($post_id,$post, $update){
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            global $KcSeoWPSchema;
            if (!wp_verify_nonce(@$_REQUEST['_kcseo_nonce'], $KcSeoWPSchema->nonceText())) return $post_id;

            // Check permissions
            if (!empty($_GET['post_type'])) {
                if (!current_user_can('edit_' . $_GET['post_type'], $post_id)) return $post_id;
            }
            $pt = $KcSeoWPSchema->kcSeoPostTypes();
            if (!in_array($post->post_type,$pt) ) return $post_id;

            $meta = array();
            $schemaModel = new KcSeoSchemaModel;
            foreach($schemaModel->schemaTypes() as $schemaID => $schema){
                $schemaMetaId = $KcSeoWPSchema->KcSeoPrefix.$schemaID;
                $data = array();
                foreach($schema['fields'] as $fieldId => $fieldData){
                    $value = (!empty($_REQUEST[$schemaMetaId][$fieldId]) ? sanitize_text_field($_REQUEST[$schemaMetaId][$fieldId]) : null);
                    if($value){
                        $data[$fieldId] = $value;
                    }
                }
                $meta[$schemaMetaId] = base64_encode(serialize($data));
            }

            foreach($meta as $mKey => $mValue){
                update_post_meta($post_id, $mKey, $mValue);
            }
        }

    }

endif;