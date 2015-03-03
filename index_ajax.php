<?php
/**
 * surveys.php functionally survey_list.php
 *
 * survey_list.php works with survey_view.php to create a list/view app
 *
 * based on demo_list_pager.php along with demo_view_pager.php provides a sample web application
 *
 * The difference between demo_list.php and demo_list_pager.php is the reference to the
 * Pager class which processes a mysqli SQL statement and spans records across multiple
 * pages.
 *
 * The associated view page, demo_view_pager.php is virtually identical to demo_view.php.
 * The only difference is the pager version links to the list pager version to create a
 * separate application from the original list/view.
 *
 * @package SurveySez
 * @author Douglas Doner <ddoner01@seattlecentral.edu>
 * @version 1.0 2015/02/03
 * @link http://www.designbydoug.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see survey_view.php
 * @see Pager_inc.php
 * @todo none
 */

# '../' works for a sub-folder.  use './' for the root
require '/var/www/html/wn15/inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

# SQL statement
$sql = "SELECT s.SurveyID, s.Title, s.Description, s.DateAdded, CONCAT(a.FirstName, ' ', a.LastName) 'Survey owner' FROM " . PREFIX . "surveys s, " . PREFIX . "Admin a WHERE s.AdminID = a.AdminID;
";

#Fills <title> tag. If left empty will default to $PageTitle in config_inc.php
$config->titleTag = 'Surveys made with love & PHP in Seattle';

#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
$config->metaDescription = 'Seattle Central\'s ITC250 Class Surveys are made with pure PHP! ' . $config->metaDescription;
$config->metaKeywords = 'Surveys,PHP,Fun,'. $config->metaKeywords;

/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

# END CONFIG AREA ----------------------------------------------------------
$config->loadhead .= '
<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script><!-- latest jQuery script -->

    <script type="text/javascript">
        //the following attaches code to window.onload, the jQuery way!
        $("document").ready(function(){

            //add AJAX call to all links with class of critter
            $(".survey").click(function(e){
                e.preventDefault(); //stop default action of the link
                var this_survey = $(this).attr("href");  //grab critter name from href attribute

                $.get("survey_view_ajax.php", { id: this_survey },
                    function(data) {
                        //alert(data);  //heres an alert if you wish to see the data upon return
    $(".outputDiv").html(data); //upon return load data into myDiv
                    }, "html");
            });
        });
    </script>
'; #load page specific JS
get_header(); #defaults to theme header or header_inc.php
?>
    <h3 align="center">Surveys</h3>
<?php
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
    echo '<div class="surveyList">
            <table>
                <thead>
                    <tr>
                        <th>Survey ID</th>
                        <th>Survey Title</th>
                        <th>Survey Description</th>
                        <th>Date Created</th>
                        <th>Survey Creator</th>
                    </tr>
            </thead>
            <tbody>';
    if($myPager->showTotal()==1){$itemz = "survey";}else{$itemz = "surveys";}  //deal with plural
    echo '<div align="center">We have ' . $myPager->showTotal() . ' ' . $itemz . '!</div>';
    while($row = mysqli_fetch_assoc($result))
    {# process each row
        echo '<tr>';
        echo '<td>' . dbOut($row['Title']) . '</td>';
        //echo '<td><a href="' . VIRTUAL_PATH . 'surveys/survey_view.php?id=' . (int)$row['SurveyID'] . '">' . dbOut($row['Title']) . '</a></td>';
        echo '<td><a href="' . (int)$row['SurveyID'] . '" class="survey">' . dbOut($row['Title']) . '</a></td>';
        echo '<td>' . dbOut($row['Description']) .'</td>';
        echo '<td>' . dbOut($row['DateAdded']) . '</td>';
        echo '<td>' . dbOut($row['Survey owner']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>
        </table>
        </div>';
    echo $myPager->showNAV(); # show paging nav, only if enough record;
}else{#no records
    echo "<div align=center>What! No surveys?  There must be a mistake!!</div>";
}
@mysqli_free_result($result);

echo '
    <div class="outputDiv">
        Survey data will go here
    </div>
';

get_footer(); #defaults to theme footer or footer_inc.php
?>