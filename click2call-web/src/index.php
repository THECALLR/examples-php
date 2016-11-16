<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>PHP Click2Call Web</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="jquery-3.0.0.min.js"></script>
</head>
<body id="preview">
    <div class="container">
        <!-- start editable area -->
        <img src="callr.png"><br>
        <h1>Php Click to Call Web example</h1>

        <form id="clicktocall_form">
            Please call me back urgently! My phone number is: <input type="text" name="customer_phone">
            <input type="submit" value="Call me!"> (format intl: +33..)
<?php 
    if((getenv('CALLR_LOGIN') & getenv('CALLR_PASS') & getenv('CALLR_TARGET')) == ""){ 
?>
    
            <div style="margin-top: 50px; border: 1px solid red; padding: 20px; width: 60%; "> 
                <p>CALLR_LOGIN, CALLR_PASS or CALLR_TARGET environment variables were not found, please provide them below.</p>
                  <div style="width: 100%; display: table; margin: auto;">
                    <div style="display: table-row">
                        <div style="display: table-cell; text-align: right; padding-right: 5px;">
                            CALLR Target (phone number):  
                        </div>
                        <div style="display: table-cell;">
                            <input type="text" name="callr_target">
                        </div>
                    </div>
                    <div style="display: table-row">
                        <div style="display: table-cell; text-align: right; padding-right: 5px;">
                            CALLR Login:
                        </div>
                        <div style="display: table-cell;">
                            <input type="text" name="callr_login">
                        </div>
                    </div>
                    <div style="display: table-row">
                        <div style="display: table-cell; text-align: right; padding-right: 5px;">
                            CALLR Password:
                        </div>
                        <div style="display: table-cell;">
                            <input type="password" name="callr_password">
                        </div>
                    </div>
                </div>
            </div>
<?php 
} 
?>

        </form>
        <div id="result" style="color: green; font-weight: bolder;"></div>
        <div id="error" style="color: red; font-weight: bolder;"></div>
</div><hr>
<script>
$("#clicktocall_form").submit(function(e) {
    $.ajax({
           type: "POST",
           url: "submit.php",
           data: $("#clicktocall_form").serialize(), 
           success: function(data)
           {
               result = JSON.parse(data);
               $("#error").text('');
               $("#result").text('');

               if(result.error){
                    $("#error").text(JSON.stringify(result));    
               } else {
                    $("#result").text(result.ok);
               }
           }
         });
    e.preventDefault();
});
</script>

</body>
</html>