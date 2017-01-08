<?php

/**
 * This class displays results
 */

class Template {
   
    function getTable(array $data, $title, $emptyMessage = ''){
        
        echo '<div class="table-responsive">';
        echo '<h3 style="text-align: center">'.$title.' ';
        echo '</h3>';
        
        if (count($data) > 0){

            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>';
            echo implode('</th><th>', array_keys(current($data)));
            echo '</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($data as $row){
                echo'<tr>';
                echo'<td>';
                echo implode('</td><td>', $row);
                echo'</td>';
                echo'</tr>';
            }

            echo '</tbody>';
            echo '</table>';   

        } else {
           echo '<h3 style="text-align: center">'.$emptyMessage.'</h3>';
        }
        echo '</div>';
    }
    
    // credits: http://stackoverflow.com/questions/4746079/how-to-create-a-html-table-from-a-php-array
}
