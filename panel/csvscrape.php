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
                ' . __('FPS Scraper to CSV', 'wpfpscrape') . '<br>
                <span style="font-size: xx-small">(' . __('This is where the fun starts.', 'wpfpscrape') . ')</span>
              </span>
            </h3>
            
            <div class="inside">
              
              <h4>' . __('Focalprice Listing:', 'wpfpscrape') . '</h4>
              
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

  
?>
