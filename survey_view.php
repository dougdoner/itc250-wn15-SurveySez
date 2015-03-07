<?php
/**
 * survey_view.php works with survey_list.php to create a list/view app
 *
 * Based on demo_view_pager.php along with demo_list_pager.php provides a sample web application
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
 * @version 2.0 2015/02/26
 * @link http://www.designbydoug.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see index.php
 * @see survey_list.php
 * @see Pager_inc.php
 * @todo add class code
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials


//---end config area --------------------------------------------------

$foundRecord = FALSE; # Will change to true, if record found!

/*
if($foundRecord)
{#only load data if record found
    $config->titleTag = $titleName . " surveys made with PHP & love!"; #overwrite PageTitle with Muffin info!
}
*/
# END CONFIG AREA ---------------------------------------------------------- 

# check variable of item passed in - if invalid data, forcibly redirect back to demo_list_pager.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
    $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
    myRedirect(VIRTUAL_PATH . "surveys/surveys.php");
}

/*
  We know that we want results or the survey,
  not both...
  
  we also know we may have no survey at all
  
  if result
    show result
  else 
    if survey
        show survey
  else
    show sorry, not survey
  
  if (result) {
  }
  else {
    if (survey) {
    }
    else {
      echo "Sorry, no survey";
    }
  }
  
*/

$myResult = new Result($myID);
if($myResult->isValid) {
	$config->titleTag = "'Result to " . $myResult->Title . "' Survey!";
}
else {
    $mySurvey = new Survey($myID);

    if($mySurvey->isValid) {
        $config->titleTag = $mySurvey->Title . " Survey";
    }
    else {
       $config->titleTag = "No such survey"; 
    }
}

get_header(); #defaults to theme header or header_inc.php
?>

<h3 align="center"><?= $config->titleTag; ?></h3>

<?php

if($myResult->isValid) {# check to see if we have a valid SurveyID
	echo "Survey Title: <b>" . $myResult->Title . "</b><br />";  //show data on page
	echo "Survey Description: " . $myResult->Description . "<br />";
	$myResult->showGraph() . "<br />";	//showTallies method shows all questions, answers and tally totals!
	echo SurveyUtil::responseList($myID);
    unset($myResult);  //destroy object & release resources
}
else {
	if($mySurvey->isValid) {
    ?>
    <h4>Here is the survey's description</h4>
    <p><?= $mySurvey->Description; ?></p>
    <?= $mySurvey->showQuestions(); ?>
    <?= SurveyUtil::responseList($myID); ?>

    <p><a href="surveys.php">Go back to surveys list</a></p>
    <?php
    }
    else {//no such survey
        ?>
        <p>Please check to see if there is a problem</p>
        <p><a href="surveys.php">Go back to surveys list</a></p>
        <?php
    }
}

get_footer(); #defaults to theme footer or footer_inc.php