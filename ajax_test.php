<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AJAX test page</title>
    </head>
    <body>

        <a href="1" class="survey">Show survey</a><br />

    <div class="outputDiv">
        Survey data will go here
    </div>

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
                        //alert(data);  //here's an alert if you wish to see the data upon return
                        $(".outputDiv").html(data); //upon return load data into myDiv
                    }, 'html');
            });
        });
    </script>
    </body>
</html>