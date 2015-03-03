<?php
//SurveyUtil_inc.php

class SurveyUtil
{
    public static function responseList($myID)
    {
        $myReturn = '';

        $sql = "SELECT DateAdded, ResponseID FROM " . PREFIX . "responses WHERE SurveyID = $myID";
        #reference images for pager
        $prev = '<img src="' . VIRTUAL_PATH . 'images/arrow_prev.gif" border="0" />';
        $next = '<img src="' . VIRTUAL_PATH . 'images/arrow_next.gif" border="0" />';

# Create instance of new 'pager' class
        $myPager = new Pager(10,'',$prev,$next,'');
        $sql = $myPager->loadSQL($sql);  #load SQL, add offset

# connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

        if(mysqli_num_rows($result) > 0)
        {#records exist - process
            $myReturn .= '<div class="surveyList">
            <table>
                <thead>
                    <tr>
                        <th>Date Added</th>
                        <th>Link</th>
                    </tr>
            </thead>
            <tbody>';
            if($myPager->showTotal()==1){$itemz = "Response";}else{$itemz = "Responses";}  //deal with plural
            $myReturn .= '<div align="center">We have ' . $myPager->showTotal() . ' ' . $itemz . '!</div>';
            while($row = mysqli_fetch_assoc($result))
            {# process each row
                $myReturn .= '<tr>';
                $myReturn .= '<td>' . dbOut($row['DateAdded']) . '</td>';
                $myReturn .= '<td><a href="' . VIRTUAL_PATH . 'surveys/response_view.php?id=' . (int)$row['ResponseID'] . '">' . dbOut($row['DateAdded']) . '</a></td>';
                //$myReturn .= '<td>' . dbOut($row['Description']) .'</td>';
                //$myReturn .= '<td>' . dbOut($row['DateAdded']) . '</td>';
                //$myReturn .= '<td>' . dbOut($row['Survey owner']) . '</td>';
                //$myReturn .= '</tr>';
            }
            $myReturn .= '</tbody>
        </table>
        </div>';
            $myReturn .= $myPager->showNAV(); # show paging nav, only if enough record;
        }else{#no records
            echo "<div align=center>No responses to this survey</div>";
        }
        @mysqli_free_result($result);

        return $myReturn;
    }//end responseList function
}//end SurveyUtil class