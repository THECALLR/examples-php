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
<div class="container">
<!-- start readme paste area -->

<h1>
<a id="readme-click-to-call-web-sample" class="anchor" href="#readme-click-to-call-web-sample" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>README Click to Call Web sample</h1>

<h2>
<a id="table-of-contents" class="anchor" href="#table-of-contents" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>Table of contents</h2>

<ul>
<li><a href="#general-information">General information</a></li>
<li>
<a href="#installation">Installation</a>

<ul>
<li><a href="#docker">Docker</a></li>
<li><a href="#docker-compose">docker-compose</a></li>
</ul>
</li>
<li>
<a href="#running">Running</a>

<ul>
<li><a href="#windows">Windows</a></li>
</ul>
</li>
<li><a href="#further-help">Further help</a></li>
</ul>

<hr>

<h2>
<a id="general-information" class="anchor" href="#general-information" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>General Information</h2>

<p>This demo uses environment variables set in the file <code>.htaccess</code> found in the src/ directory, that needs to be defined for your use case</p>

<pre><code>SetEnv CALLR_LOGIN &lt;your callr login&gt;
SetEnv CALLR_PASS &lt;your callr password&gt;
SetEnv CALLR_TARGET &lt;target number&gt;
SetEnv APP_ID &lt;app id&gt;
</code></pre>

<ul>
<li>CALLR_LOGIN is the login you use to access CALLR services</li>
<li>CALLR_PASS is the password you use to access CALLR services</li>
<li>CALLR_TARGET is the target telephone number to forward client calls to, when they submit the web form</li>
<li>APP_ID (optional) is the ID of a predefined click2call application, that will be reused for each client call, otherwise a new one will be
created and stored as in the file <code>click2call.appid</code>
</li>
</ul>

<p>A typical <code>.htaccess</code> for Bob Smith, with a telephone number of '+336123456789' and a predefined click to call application would look like the following:</p>

<pre><code>SetEnv CALLR_LOGIN bobsmith
SetEnv CALLR_PASS mySecr3tp@ssw0rd
SetEnv CALLR_TARGET +336123456789
SetEnv APP_ID H45HC0D3
</code></pre>

<h3>
<a id="if-no-predefined-click-to-call-application-is-being-used-please-leave-app_id-blank-or-remove-the-line-completely" class="anchor" href="#if-no-predefined-click-to-call-application-is-being-used-please-leave-app_id-blank-or-remove-the-line-completely" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>if no predefined click to call application is being used, please leave <code>APP_ID</code> blank, or remove the line completely.</h3>

<hr>

<h2>
<a id="installation" class="anchor" href="#installation" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>Installation</h2>

<h3>
<a id="docker" class="anchor" href="#docker" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>Docker</h3>

<p>Docker is available for download on their website <a href="https://www.docker.com/products/overview">https://www.docker.com/</a></p>

<p>On Windows make sure you install <a href="https://www.docker.com/products/docker-toolbox">Docker toolbox</a>, or <a href="https://www.docker.com/products/docker#/windows">Docker for Windows (Requires Microsoft Windows 10 Professional or Enterprise 64-bit)</a>
and use the <code>Kitematic</code> application to manage your containers and view their logs and output (installed by default)</p>

<h3>
<a id="docker-compose" class="anchor" href="#docker-compose" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>docker-compose</h3>

<p>On Windows and Mac the docker-compose utility is installed automatically with docker-toolbox<br>
For other users, follow the instructions on the official <a href="https://docs.docker.com/compose/install/">Docker website here</a></p>

<hr>

<h2>
<a id="running" class="anchor" href="#running" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>Running</h2>

<ol>
<li><p>Before running the <code>docker-compose up c2cwebdemo</code> command, please modify the <strong>.htaccess</strong> file found in the <strong>src</strong> directory.</p></li>
<li><p>If using MacOS / Windows launch the <code>Docker quickstart terminal</code>, and use the provided console to run this demo. </p></li>
<li>
<p>After installing <code>Docker</code> and <code>docker-compose</code>, run the following command in the same directory as <strong>Dockerfile</strong>, </p>

<pre><code>$ docker-compose up c2cwebdemo
</code></pre>

<p>you will see output similar to the following:   </p>

<pre><code>Building c2cwebdemo
Step 1 : FROM php:5.6-apache
---&gt; 7374b3b98172
Step 2 : RUN apt-get update     &amp;&amp; apt-get install -y zip     &amp;&amp; curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
---&gt; Using cache
---&gt; 82c0a0de5ff7
Step 3 : COPY src/ /var/www/html/
---&gt; a5dd2bea4c5c
Removing intermediate container 5ecde79f4420
Step 4 : COPY composer.json /var/www
---&gt; 52d9fe05d3c7
Removing intermediate container ab5d4eae9847
...
[Thu Jul 01 01:15:00.301234 2016] [mpm_prefork:notice] [pid 10] AH00163: Apache/2.4.10 (Debian) PHP/5.6.23 configured -- resuming normal operations
[Thu Jul 02 01:15:00.301234 2016] [core:notice] [pid 10] AH00094: Command line: '/usr/sbin/apache2 -D FOREGROUND'
</code></pre>
</li>
<li><p>You can then connect to the docker container ip address with http:// to view the website. ( the ip address is displayed with the initial startup of the docker quickstart console )</p></li>
</ol>

<h3>
<a id="windows" class="anchor" href="#windows" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>Windows</h3>

<p>When running on windows, you should use the <code>Kitematic</code> utility to view the container output and website. </p>

<p>After launching the docker-compose command, from the <code>Kitematic</code> utility windows, you are able to click on the running container, view the 'web preview'
 and launch a browser connection to the website.</p>

<hr>

<h2>
<a id="further-help" class="anchor" href="#further-help" aria-hidden="true"><span aria-hidden="true" class="octicon octicon-link"></span></a>Further help</h2>

<ul>
<li>You will find API documentation and snippets here at <a href="http://thecallr.com/docs/">http://thecallr.com/docs/</a>
</li>
<li>Or on github in our repository <a href="https://github.com/THECALLR/">https://github.com/THECALLR/</a>
</li>
</ul>

<p>If you have any further questions or require assistance with these examples, please contact CALLR Support</p>

<ul>
<li><a href="mailto:support@callr.com">support@callr.com</a></li>
<li>FR: +33 (0)1 84 14 00 30 </li>
<li>US: +1 (646) 982-0830</li>
</ul>

<hr>



<!-- end readme paste area -->        
</div>
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