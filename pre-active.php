<?php

class BDTT_ACTIVATION {
    
    public function __construct($file) {
        register_activation_hook($file,array($this,'bdtt_preactive'));
    }

    public function bdtt_preactive() {

        global $baseUrl;

        foreach($baseUrl as $slug => $base) {
            if (!bdttPostExist($slug)) {
                $id = wp_insert_post([
                    'post_title' => $base['name'],
                    'post_name' => $slug,
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_author' => 1
                ]);
                if ($id) update_post_meta($id, '_wp_page_template', $base['template']);
            }
        }
    }
}