# PHP Examples

## Table of contents
* [General information](#general-information)
* [Installation](#installation)
    * [Requirements](#requirements)
        * [Docker](#docker)
        * [docker-compose](#docker-compose)
    * [With php-composer](#with-php-composer)
    * [Without php-composer](#with-php-composer)
* [Examples](#examples)
    * [Click to Call Web](#click-to-call-web)
    * [Create scheduled IVR campaign](#create-scheduled-ivr-campaign)
    * [Send SMS](#send-sms)
* [Cleanup](#cleanup)
    * [Container cleanup](#container-cleanup)
* [Further help](#further-help)

- - -

## General information
This repo contains a collection of projects and scripts both stand alone and *dockerized* that make use of the CALLR PHP SDK

- - - 

## Installation    
## Requirements
The installation and usage of the php-sdk requires the following: 
* PHP 5.4+
* php5-curl  

### Docker
The Docker and docker-compose projects require Docker to be installed: 
Docker is available for download on their website [https://www.docker.com/](https://www.docker.com/products/overview)

On Windows make sure you install [Docker toolbox](https://www.docker.com/products/docker-toolbox), 
and use the `Kitematic` application to manage your containers and view their logs and output (installed by default)

### docker-compose  
On Windows and Mac the docker-compose utility is installed automatically with docker-toolbox  
For other users, follow the instructions on the official [Docker website here](https://docs.docker.com/compose/install/)

- - -

## With php-composer
php-composer ([https://getcomposer.org/download/](https://getcomposer.org/download/)) is recommended for use with the sdk and for managing your project dependencies.  
The download page contains instructions and necessary files for installation on Windows and other platforms.  
* if not being installed as root/super user, make sure to use the switch **--install-dir=**  


1. After downloading and installing composer, make sure you have a `composer.json` file located in the document root of your project, it should contain as a minimum the following:  
    ```json
    {
      "require": {
        "callr/sdk-php": "dev-master"
      }
    }
    ```

2. As an alternative, to automatically create the composer.json and install the sdk run `composer require callr/sdk-php:dev-master`

3. In your project source files, be sure to require the file `autoload.php`
    ```php
    <?php
        require 'vendor/autoload.php';
    ```

4. Run `composer update`, which will download the sdk either via git ( if found in the environment ), or a zip and install it into the *vendor* directory. 
    ```bash
    $ composer update
    Loading composer repositories with package information
    Updating dependencies (including require-dev)
    - Installing callr/sdk-php (dev-master 09a2e40)
    Loading from cache

    Writing lock file
    Generating autoload files
    ```
---

## Without php-composer
If you wish to use the sdk without the dependency management of php-composer it is possible with the following steps

1. Download the sdk from the CALLR [php-sdk github](https://github.com/THECALLR/sdk-php/archive/master.zip)

2. Unzip the archive and move the `src` directory into your project structure

3. Require each object source file being used, typically for making all api calls it will be the following: 
    ```php
    // require source objects
    require '../src/CALLR/Api/Client.php';
    require '../src/CALLR/Api/Request.php';
    require '../src/CALLR/Api/Response.php';

    // get api client object 
    $api = new \CALLR\API\Client;

    // set authentication credentials
    $api->setAuthCredentials($login, $password);
    ...
    ```

4. For creating realtime application flows, the libraries needed are the following:
    ```
    // require source objects
    require '../src/CALLR/Realtime/Server.php';
    require '../src/CALLR/Realtime/Request.php';
    require '../src/CALLR/Realtime/Response.php';
    require '../src/CALLR/Realtime/CallFlow.php';
    require '../src/CALLR/Realtime/Command.php';
    require '../src/CALLR/Realtime/Command/Params.php';
    require '../src/CALLR/Realtime/Command/ConferenceParams.php';

    // get callflow object
    $flow = new CallFlow;
    ...
    ``` 

- - -

### Viewing output
#### Windows / Mac
When docker is installed using the docker-toolbox, an application called [*Kitematic*](https://kitematic.com/) 
is installed alongside, to give a graphical interface for managing your docker images and containers.  
If you want to see the output of a *docker-compose* command, you must select the correct container listed on the left side of the interface.

#### Linux
docker-compose can be run in interactive mode under linux, so you should see the output from containers directly, run *docker-compose* commands with **--rm** 
for container cleanup after the script has terminated. 

- - -  

## Examples
#### Things to note
* on Windows, the *docker-compose run* must be launched in detached mode *-d*
* If cloned from the git repository, all docker-compose commands must be run in the same folder as the `Dockerfile`  

### Click to Call web
* Located in /click2call-web, a Docker/docker-compose project that shows off the ClickToCall functionality of the CALLR API.  
See the [project README](click2call-web/README.md) for more information.

### Create scheduled IVR campaign
* Located in /campaign-sendr, a Docker/docker-compose project showing how to create a scheduled campaign that utilises IVR and bridging features of the CALLR **SendR** API.  
See the [project README](campaign-sendr/README.md) for more information.

### Send SMS
* The PHP script `sms.php` located in the repo root, allows you to send an SMS and check its status  
Usage: 

```
$ php sms.php send +33123456789 'Hello from CALLR!'
Starting script sms.php
To: +33123456789
Message: 'Hello from CALLR!'
Returned result from sms.send: H45HC0D3

$ php sms.php status H45HC0D3
stdClass Object
(
    [type] => OUT
    [hash] => H45HC0D3
    [from] =>
    [to] => +33123456789    
    [text] => hello from bob, how are you?
    ...
    [date_received] => 2016-01-01 06:06:06
)
```

- - -

## Cleanup  
### Container cleanup  
On windows, for each run of docker-compose a container will be created, dont forget to remove any unwanted containers using *docker rm*
or the **Kitematic** tool.  
On Linux *docker-compose run script..* can be executed with --rm for after execution cleanup

#### Kitematic
If you would like to delete a container, hover your mouse over the container name and click the round X button.
If you would like to delete a docker image ( to rebuild if you have made a change to a script )

1. Make sure all containers linked to this image have been removed.
2. Select 'My Images' from the Top Right of the interface
3. Click on the '...' button and choose 'Remove Tag'
4. If no containers are using the image, it will be successfully removed.
    
#### Advanced
Advanced users can remove containers and images with *docker rm/rmi*

- - -

# Further help
* You will find API documentation and snippets here at [http://thecallr.com/docs/](http://thecallr.com/docs/)
* Or on github in our repository [https://github.com/THECALLR/](https://github.com/THECALLR/)
 
If you have any further questions or require assistance with these examples, please contact CALLR Support
* support@callr.com
* FR: +33 (0)1 84 14 00 30 

---