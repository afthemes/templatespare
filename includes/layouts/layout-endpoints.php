<?php

if(!class_exists('AFTMLS_RestApi_Request')){

    class AFTMLS_RestApi_Reques_Controller{

        public function __construct() {
            $this->namespace = 'templatespare/v1';
            $this->query_base = 'demo-lists';
        }

        public function templatespare_register_routes() {
            

            register_rest_route(
                'templatespare/v1','single-demo-content',
                array(
                    array(
                        'methods' => \WP_REST_Server::READABLE,
                        'callback'            => array( $this, 'templatespare_get_single_demo_list_items' ),
                        'permission_callback' => function () {
                        return true;
                    },
                    ),
                )
            );
            
        }

       
        public function templatespare_get_single_demo_list_items(\WP_REST_Request $request){
            $params = $request->get_params();
            $data['singleDemo']=$this->templatespare_ajax_render_demo_lists($params['slug'],$params['s'],$params['dropdown'],$params['cat'],$params['selectedtheme']);
            
            return  $data;

        }

        function templatespare_ajax_render_demo_lists($slug,$s,$d,$cat,$theme){
            
           
            $all_demos = templatespare_templates_demo_list();
            
            $parentNode =  array();
            $searh_array = array();
            foreach($all_demos as $keys=>$value){
                    
                foreach($value['demodata'] as $key=>$filtered_data){
                    if($theme !='all' && $d !='all' && $slug=='all'){
                       
                        if($theme == $keys && $theme !='all' && in_array($d,$filtered_data['tags'])){
                           
                            $empty_array = array(
                                'free'=>$keys,
                                'premium'=>$value['premium'],
                                'slug'=>$filtered_data['slug'],
                                'title'=>$filtered_data['title'],
                                'subtitle'=>$filtered_data['subtitle'],
                                'url'=>$filtered_data['url'],
                                'tags'=>$filtered_data['tags'],
                                'parent'=>$this->templatespare_get_theme_count($keys),
                                'builder'=>isset($filtered_data['builder'])?$filtered_data['builder']:""

                            );
                            array_push($parentNode,$empty_array);
                        }
                    }
                    else if($slug != 'all'){
                       
                        if (isset($filtered_data['tags'])) {
                                if(in_array($slug,$filtered_data['tags'])){
                                    $empty_array = array(
                                        'free'=>$keys,
                                        'premium'=>$value['premium'],
                                        'slug'=>$filtered_data['slug'],
                                        'title'=>$filtered_data['title'],
                                        'subtitle'=>$filtered_data['subtitle'],
                                        'url'=>$filtered_data['url'],
                                        'tags'=>$filtered_data['tags'],
                                        'parent'=>$this->templatespare_get_theme_count($keys),
                                        'builder'=>isset($filtered_data['builder'])?$filtered_data['builder']:""

                                    );
                                    array_push($parentNode,$empty_array);
                                    
                                }
                        } 
                    }else{
                        if($d !='all' ){
                           
                           
                            if(in_array($d,$filtered_data['tags']) && ($cat !='all-cat') &&  in_array($cat,$filtered_data['tags'])){
                              
                                $empty_array = array(
                                    'free'=>$keys,
                                    'premium'=>$value['premium'],
                                    'slug'=>$filtered_data['slug'],
                                    'title'=>$filtered_data['title'],
                                    'subtitle'=>$filtered_data['subtitle'],
                                    'url'=>$filtered_data['url'],
                                    'tags'=>$filtered_data['tags'],
                                    'parent'=>$this->templatespare_get_theme_count($keys),
                                    'builder'=>isset($filtered_data['builder'])?$filtered_data['builder']:""
    
                                );
                                array_push($parentNode,$empty_array);
                                
                            
                            }else{
                               
                                if(in_array($d,$filtered_data['tags']) && ($cat =='all-cat')){
                                  
                                $empty_array = array(
                                    'free'=>$keys,
                                    'premium'=>$value['premium'],
                                    'slug'=>$filtered_data['slug'],
                                    'title'=>$filtered_data['title'],
                                    'subtitle'=>$filtered_data['subtitle'],
                                    'url'=>$filtered_data['url'],
                                    'tags'=>$filtered_data['tags'],
                                    'parent'=>$this->templatespare_get_theme_count($keys),
                                    'builder'=>isset($filtered_data['builder'])?$filtered_data['builder']:""
    
                                );
                                array_push($parentNode,$empty_array);
                                }
                            }
                              
                        }elseif($cat){
                           
                            if($cat !='all-cat'){
                                
                                if(in_array($cat,$filtered_data['tags'])){
                                    $empty_array = array(
                                        'free'=>$keys,
                                        'premium'=>$value['premium'],
                                        'slug'=>$filtered_data['slug'],
                                        'title'=>$filtered_data['title'],
                                        'subtitle'=>$filtered_data['subtitle'],
                                        'url'=>$filtered_data['url'],
                                        'tags'=>$filtered_data['tags'],
                                        'parent'=>$this->templatespare_get_theme_count($keys),
                                        'builder'=>isset($filtered_data['builder'])?$filtered_data['builder']:""
                                        
                                    );
                                    array_push($parentNode,$empty_array);
                                }
                            }else{
                                $empty_array = array(
                                    'free'=>$keys,
                                    'premium'=>$value['premium'],
                                    'slug'=>$filtered_data['slug'],
                                    'title'=>$filtered_data['title'],
                                    'subtitle'=>$filtered_data['subtitle'],
                                    'url'=>$filtered_data['url'],
                                    'tags'=>$filtered_data['tags'],
                                    'parent'=>$this->templatespare_get_theme_count($keys),
                                    'builder'=>isset($filtered_data['builder'])?$filtered_data['builder']:""
                                    
                                );
                                array_push($parentNode,$empty_array);
                            }
                        }else{
                        $empty_array = array(
                            'free'=>$keys,
                            'premium'=>$value['premium'],
                            'slug'=>$filtered_data['slug'],
                            'title'=>$filtered_data['title'],
                            'subtitle'=>$filtered_data['subtitle'],
                            'url'=>$filtered_data['url'],
                            'tags'=>$filtered_data['tags'],
                            'parent'=>$this->templatespare_get_theme_count($keys),
                            'builder'=>isset($filtered_data['builder'])?$filtered_data['builder']:""
                            
                        );
                        array_push($parentNode,$empty_array);
                    }
                    }

                    
                }
            }
            if(!empty($s))  {
                
                $filtered_array= $this->templatespare_search_for_id($s,$parentNode,$slug);
                $parentNode = array();
                if(!empty($filtered_array)){
                   
                    foreach($filtered_array as $s){
                    
                        $empty_array = array(
                            'free'=>$s['free'],
                            'premium'=>$s['premium'],
                            'slug'=>$s['slug'],
                            'title'=>$s['title'],
                            'subtitle'=>$s['subtitle'],
                            'url'=>$s['url'],
                            'tags'=>$s['tags'],
                            'parent'=>$s['parent'],
                            'builder'=>isset($s['builder'])?$s['builder']:""
                            
                        );

                     array_push($parentNode,$empty_array);
                    }
                    
                }
                
               
            }
                
           
            
             return $parentNode;
            
        }

        function templatespare_search_for_id($s, $array,$slug) {
        
            $input_text = strtolower($s);
            $new_search_array = array();
            foreach ($array as $key => $val) {
                if (str_contains($val['slug'], $input_text)) { 
                    array_push($new_search_array, $val);
                }
                    
            }
          
            if(empty($new_search_array)){
                foreach ($array as $key => $val) {
                    if (in_array($input_text,$val['tags'])) { 
                        array_push($new_search_array, $val);
                }
    
                }
             }
              
            if($slug!='all'){
                $all_demo = templatespare_templates_demo_list();
                $alldataarray = array();
                foreach($all_demo as $keys=>$value){
                    foreach($value['demodata'] as $key=>$fdata){
                        if(preg_grep("/$input_text/i", $fdata)){
                            array_push($alldataarray, $fdata);
                        }
                    }
                    $alldataarray=array(
                        'free'=>$key
                    );

                }
                $new_search_array = array_push($array,$alldataarray);
            }

            

             
             
          
            return $new_search_array;
            
         }

         public function templatespare_get_theme_count($parent){

            $all_demos = templatespare_templates_demo_list();
            $numberoftheme=count(array_values($all_demos[$parent]['demodata']));
           
            return $numberoftheme;

         }
    }


    

}