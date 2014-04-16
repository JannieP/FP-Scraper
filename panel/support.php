<?php

      function addSupportPanel(){
         print('
         <div id="dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder">
            <div id="postbox-container" style="width:75%; float:left;">
               <div id="side-sortables" class="meta-box-sortables ui-sortable">
                  <div id="fpslog1" class="postbox if-js-closed" >
                  <h3 class=\'hndle\'>
                     <span>
                        SUPPORT <span style="font-size: xx-small">(Thisis where YOU seek Help)</span>
                     </span>
                  </h3>
                  <div class="inside">
                     <div class="table">
                        <table class="widefat">
                           <tr><td><iframe src="http://c0nan.net/support/" width=100% height=600px /></td></tr>
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
