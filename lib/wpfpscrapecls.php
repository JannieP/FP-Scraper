<?php 

class wpfpscrape  {

      function wpfpscrape(){
      
          $this->options = array(
            'install_date', 
            'do_csv', 
            'message', 
            'keep_logs', 
            'simulate', 
            'db',
            'csv_path'
          );
          
          $this->install_date = '';
          $this->useragent = 'WP FPS';
          $this->useragent_url = 'http://c0nan.net';
          $this->message = '';

          $this->keep_logs = '1';
          $this->simulate = '0';

          $this->db = '0';
          $this->csv_path = '';

          $this->version = TB_VERSION;

      }

      function upgrade(){
      
          if (get_option('fps_installed_version') == '1.0') {
      
              $this->doupgrade();
      
          }
      
      }    

      function doupgrade(){
      
          if (get_option('fps_oauth_validated') != '1') {
      
              tb_log('Upgrading');
              //update_option('fps_counter', 1);
              //delete_option('fps_counter');
              //update_option('fps_installed_version', '2.0');
              tb_log('Upgraded');
              return(true);
      
         }
      
      }
      
      function get_settings(){
      
          foreach ($this->options as $option) {
      
              $value = get_option('fps_' . $option);
      
              if (isset($value)) {
                  $this->$option = $value;
              } 
      
          }
      
      }
      
      function populate_settings(){
          
          foreach ($this->options as $option){
          
              if ($option != 'message') {
          
                  $value = stripslashes($_POST['fps_' . $option]);
          
                  if (isset($_POST['fps_' . $option])) {
                      $this->$option = $value;
                  }
          
              }
          
          }
          
      }
      
      function update_settings(){
          
          if (current_user_can('manage_options')) {
              
              foreach ($this->options as $option) {
                  if ($option != 'message') {
                      update_option('fps_' . $option, $this->$option);
                  }
              }
              
              if (empty($this->install_date)) {
                  update_option('fps_install_date', current_time('mysql'));
              }
              
              $this->upgrade();
              update_option('fps_installed_version', TB_VERSION);
              update_option('fps_db', TB_DB);

          }

      }
      
      function fps_run_csv(){
        
      }
  }


?>
