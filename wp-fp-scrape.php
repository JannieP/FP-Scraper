<?php
  /*
   Plugin Name: WP FocalPrice Scraper
   Plugin URI: http://c0nan.net
   Description: A wordpress pluggin that will scrape focalprice listings and supply the result
   Version: 1.0
   Author: c0nan
   Author URI: http://c0nan.net
   */
  
   define('FPS_VERSION', '1.0');
   define('FPS_DB', '1');
   define('FPS_URI', 'http://c0nan.net');
   
   if (!defined('PLUGINDIR')) {
         define('PLUGINDIR', 'wp-content/plugins');
   }

  define('FPS_FILE', trailingslashit(ABSPATH . PLUGINDIR) . 'wp-fp-scrape/wp-fp-scrape.php');
  include_once trailingslashit(ABSPATH . PLUGINDIR).'wp-fp-scrape/panel/support.php';

  function fps_install(){
      $fps_install = new wp-fp-scrape;
      foreach ($fps_install->options as $option) {
          add_option('fps_' . $option, $fps_install->$option);
      }
      update_option('fps_message', 'Activated:');
  }
  
  register_activation_hook(__FILE__, 'fps_install');

  function tb_deactivate() {
  //  if (get_option('fps_keep') != '1'){
  //    $fps_install = new wp-fp-scrape;
  //    foreach ($fps_install->options as $option) {
  //      delete_option('fps_'.$option);
  //    }
  //  }
  }
  //register_deactivation_hook(__FILE__, 'fps_deactivate');
  
  function fps_menu_items(){
      if (current_user_can('manage_options')) {
          add_menu_page('WP FocalPrice Scraper', 'WP FocalPrice Scraper', 10, 'wp-fp-scrape', 'fps_options_form');
          add_submenu_page('wp-fp-scrape','WP FPS Options','WP FPS Options', 10, 'wp-fp-scrape', 'fps_options_form');
          add_submenu_page('wp-fp-scrape', 'WP FPS Level1','WP FPS Level1', 10, 'wp-fps1', 'fps_l1_form');
          add_submenu_page('wp-fp-scrape', 'WP FPS Support','WP FPS Support', 10, 'wp-support', 'fps_support');
          add_submenu_page('wp-fp-scrape', 'WP FPS Logs','WP FPS Logs', 10, 'wp-logs', 'fps_logs');
      }
  }
  add_action('admin_menu', 'fps_menu_items');
  
  function fps_plugin_action_links($links, $file){
      $plugin_file = basename(__FILE__);
      if (basename($file) == $plugin_file) {
          $settings_link = '<a href="options-general.php?page=' . $plugin_file . '">' . __('Settings', 'wp-fp-scrape') . '</a>';
          array_unshift($links, $settings_link);
      }
      return $links;
  }
  add_filter('plugin_action_links', 'fps_plugin_action_links', 10, 2);
  
  function trim_value(&$value){
      $value = trim($value);
  }
  
  function fps_init(){
      
      global $fps;
      $fps = new wp-fp-scrape;
      $fps->get_settings();
      
      if (is_admin()) {
          
          global $wp_version;
          $update = false;
          
          if (isset($wp_version) && version_compare($wp_version, '2.5', '>=') && empty($fps->install_date)) {
              $update = true;
          }
          
          $installed_version = get_option('fps_installed_version');
          
          if ($installed_version != FPS_VERSION) {
              $update = true;
          } elseif ($update) {
              add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please update your <a href="%s">WP FocalPrice Scraper settings</a>', 'wp-fp-scrape'), admin_url('options-general.php?page=wp-fp-scrape')) . "</p></div>';"));
          }

          if (!checkversion()){
             add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>WP FocalPrice Scraper - New Release Available, Please Update</p></div>';"));
          }

      }

      if ($fps->do_csv == '1') {
            add_action('wp_footer', 'fps_csv_form');
      }
      
  }
  add_action('init', 'fps_init');

  function checkversion(){
     return false;
  }
  
  function fps_request_handler(){

      global $fps;

      if (!empty($_GET['fps_action'])) {

          switch ($_GET['fps_action']) {
              case 'fps_reset_log':
                  update_option('fps_message', '');
                  $fps->message = '';
                  wp_redirect(admin_url('options-general.php?page=wp-fp-scrape'));
                  break;
              case 'fps_run_csv':
                  fps_log('FP SCraper L1 Test');
                  $fps->fps_run_csv();
                  wp_redirect(admin_url('options-general.php?page=wp-fp-scrape'));
                  break;
          }

      }

      if (!empty($_POST['fps_action'])) {
          
          switch ($_POST['fps_action']) {
              case 'fps_update_settings':
                  if (!wp_verify_nonce($_POST['_wpnonce'], 'fps_settings')) {
                      wp_die('Oops, please try again.');
                  }
                  $fps->populate_settings();
                  $fps->update_settings();
                  $gets = '&fps_updated=true';
                  wp_redirect(admin_url('options-general.php?page=wp-fp-scrape' . $gets));
                  die();
                  break;
          }

      }
  }
  add_action('init', 'fps_request_handler', 10);
 
  function fps_options_form(){
      //fps_formhandler(0);
  }
  
  function fps_l1_form(){
      fps_formhandler("csvScrapeFormTitle","csvScrapeContent");
  }

  function fps_logs(){
      logContent();      
  }
  
  function fps_formhandler($formTitleHandler = '', $formContentHandler = ''){
      
      global $fps;
      
      if (strcmp($_GET['fps_updated'], "true") == 0) {

          print('
            <div id="message" class="updated fade">
              <p>WP FocalPrice Scraper Options Updated.</p>
            </div>
          ');

      }
      
      if (1 == 1){   //TODO - Kill this

        $titleFnc = $formTitleHandler;
        call_user_func($titleFnc);

      ?>
         <script type="text/javascript">
           
           function wpfpsConfirm(msg){
               var answer = confirm(msg);
               if (answer)
                  return true;
               else
                  return false;
           }
           
           function doCustomSubmitForm(theget){
               document.forms["fps_form1"].action=document.forms["fps_form1"].action + '?page=wp-fp-scrape' + theget;
               document.forms["fps_form1"].submit();
           }
           
         </script>
      <?php

      print('
         <form id="fps_form1" name="fps_form1" action="' . admin_url('options-general.php') . '" method="post">
            <p class="submit">
              <input type="submit" name="save_all" class="button-primary" value="Save Options" />
            </p>
            <fieldset class="options">
              <div id="dashboard-widgets-wrap">
                <div id="dashboard-widgets" class="metabox-holder">
                  <div id="postbox-container" style="width:75%; float:left;">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
      ');

      $contentFnc = $formContentHandler;
      call_user_func($contentFnc);

      print('

                    </div>
                  </div>
                </div>
              </div>
           </fieldset>
           <p class="submit">
             <input type="submit" name="save_all" class="button-primary" value="Save Options" />
           </p>
           <input type="hidden" name="fps_action" value="fps_update_settings" class="hidden" style="display: none;" />
           ' . wp_nonce_field('fps_settings', '_wpnonce', true, false) . wp_referer_field(false) . '
         </form>
      ');

      print('
       <div id="dashboard-widgets-wrap">
         <div id="dashboard-widgets" class="metabox-holder">
           <div id="postbox-container" style="width:75%; float:left;">
             <div id="side-sortables" class="meta-box-sortables ui-sortable">
      ');

      //testContent();
      //supportContent();

      print('
             </div>
           </div>
         </div>
       </div>
      ');
      }
      //siteContent();
      do_action('fps_options_form');

   }
  
   function fps_b3_form($content = ''){

      global $fps, $wp_query;
      $fps = new wp-fp-scrape;
      $fps->get_settings();

      if (1 == 1){//}($_GET['fps_l3'] == '1') || ($fps->allways == '1' && $_GET['fps_l3'] != '2')){ 
         //Excute sepperate method
         //loadwpbPOP($post);
      }

   }

   function fps_b3_testpage(){

       $_GET['fps_l3'] = '1';
       $_GET['fps_l3_t'] = '1';
       print('<div class="wpfpstp"></div>');
       fps_b3_form();

   }
  
   function fps_log($val=''){

       if (get_option('fps_keep_logs') == '1') {
           update_option('fps_message', get_option('fps_message') . '<br>' . time() .'->' . $val);
       }

   }

   function fps_db($val=''){

       global $fps;

       if ($fps->db == '1') {
           update_option('fps_message', get_option('fps_message') . '<br>'. time() .'->' . $val);
       }

   }

   function fps_e($val=''){

      fps_db('4:'.$val);
      echo'<br>->';
      
      if(is_array($val)){
         print_r($val);
      }else{
         echo $val;
      }
      
      echo'<-<br>';
      
   }
                  
   function tb_support(){
      addSupportPanel();
   }
   
   function logContent(){
         global $tb;
         print('
         <div id="dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder">
            <div id="postbox-container" style="width:75%; float:left;">
               <div id="side-sortables" class="meta-box-sortables ui-sortable">
                  <div id="tblog1" class="postbox if-js-closed" >
                  <h3 class=\'hndle\'>
                     <span>
                        Logs <span style="font-size: xx-small">(Just so you san see what happening.)</span>
                     </span>
                  </h3>
                  <div class="inside">
                     <div class="table">
                        <table class="widefat">
                        <thead>
                           <tr><th>LOG</th></tr>
                        </thead>
                        <tbody>
                           <tr><td>' . $tb->message . '</td></tr>
                           <tr><td><form id="tb_log1" name="tb_log1" action="' . admin_url('options-general.php') . '?tb_action=tb_reset_log" method="post"><p class="submit"><input type="submit" name="submit" value="' . __('Clear Logs', 'wp-barbarian') . '" /></p></form></td></tr>
                        </tbody>
                        </table>
                     </div>
                  </div>
                  </div>
               </div>
            </div>
            </div>
         </div>
         ');
      }

?>
