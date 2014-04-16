<?php 


function csvScrapeFormTitle(){
  print('  
      <div class="wrap" id="fps_options_page">
      <div id="icon-options-general" class="icon32"><br /></div>
      <h2>Wordpress FocalPrice Scraper to CSV Options</h2>
      <span style="font-size: medium">This pluggin will scrape the listing and the each item and popolate a fully featured CSV file</span>
    ');
}

function csvScrapeContent(){

 global $fps;
 
 print('
          <div id="fps_config" class="postbox if-js-closed" >
            
            <h3 class=\'hndle\'>
              <span>
                ' . __('FPS Scraper to CSV', 'wp-fp-scrape') . '<br>
                <span style="font-size: xx-small">(' . __('This is where the fun starts.', 'wp-fp-scrape') . ')</span>
              </span>
            </h3>
            
            <div class="inside">
              
              <h4>' . __('Focalprice Listing:', 'wp-fp-scrape') . '</h4>
              
              <div class="table">
                <table class="widefat">
                  <tbody>
                    <tr>
                      <td width="30%"><label for="fps_path">Path:</label></td>
                      <td width="30%"><select id="fps_path" name="fps_path">' . esc_attr($fps->csv_path) . '</td>
                      <td><span style="font-size: xx-small">The path, excluding the domain (e.g.: /hats/abc0389376.html)</span></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      ');
 
 
}

  class wp-fp-scrape  {

      function wp-fp-scrape(){
      
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
