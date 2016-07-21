# Table of contents

* [Installation](#installation)
    * [Docker installation](#docker-installation)
    * [docker-compose installation](#docker-compose-installation)
* [Running](#running)
    * [With Docker](#with-docker)
    * [Without Docker](#without-docker)
    * [Viewing output](#viewing-output)
    * [Container cleanup](#container-cleanup)
* [Scripts](#scripts)
    * [Usage](#usage)
    * [Workflow examples](#workflow-examples)
* [Further help](#further-help)

- - -

# Installation
## Docker installation  
### Windows / Mac  
Download and run the installation toolbox [docker-toolbox](https://www.docker.com/products/docker-toolbox)

### Linux
Either download and install Docker using your distributions package manager, or follow the instructions on the official [Docker website here](https://docs.docker.com/engine/installation/linux/)

## docker-compose installation
On Windows and Mac the docker-compose utility is installed automatically with docker-toolbox  
For other users, follow the instructions on the official [Docker website here](https://docs.docker.com/compose/install/)

- - -

# Running
## With Docker
##### If you make any changes to a script after running a docker-compose command, you must delete the docker image and all associated containers

1. Modify the file credentials.dist.txt and add your login and password  
2. Save the file as  _**credentials.txt**_  
3. Run your chosen script using _**docker-compose run script &lt;script name and params&gt;**_    

## Without Docker
##### You must have PHP installed
##### You are advised to use [PHP Composer](https://getcomposer.org/download/)  
1. Export your CALLR credentials as environment variables from **credentials.txt** with the following:    

    ```
    // bash
    $ eval $(cat credentials.txt | sed 's/^/export /')

    // windows cmd
    c:\> FOR /F %i in (credentials.txt) do set %i
    ```    
2. Run the following composer command  
    ```
    composer update --prefer-dist
    Loading composer repositories with package information
    Updating dependencies (including require-dev)
    - Installing callr/sdk-php (dev-master 09a2e40)
    Loading from cache

    Writing lock file
    Generating autoload files
    ```
3. Run your chosen script using _**php &lt;script name and params&gt;**_ (see [here](#usage) for available scripts)

## Viewing output
### Windows / Mac
When docker is installed using the docker-toolbox, an application called [*Kitematic*](https://kitematic.com/) 
is installed alongside, to give a graphical interface for managing your docker images and containers.  
If you want to see the output of a *docker-compose* command, you must select the correct container listed on the left side of the interface.

### Linux
docker-compose can be run in interactive mode under linux, so you should see the output from containers directly, run *docker-compose* commands with **--rm** 
for container cleanup after the script has terminated. 

## Container cleanup
### Kitematic
If you would like to delete a container, hover your mouse over the container name and click the round X button.
If you would like to delete a docker image ( to rebuild if you have made a change to a script )

1. Make sure all containers linked to this image have been removed.
2. Select 'My Images' from the Top Right of the interface
3. Click on the '...' button and choose 'Remove Tag'
4. If no containers are using the image, it will be successfully removed.
    
### Advanced
Advanced users can remove containers and images with *docker rm/rmi*

- - -

# Scripts
## Usage  
* If running with docker-compose environment use _**docker-compose run script &lt;script and parameters&gt;**_
* If running with php directly, use _**php &lt;script and parameters&gt;**_  

```
// upload media into library
upload.media.php scripts/samples/message.mp3 'Sample message 1'

// upload addressbook into library 
upload.addressbook.php scripts/samples/addressbook.csv 'Addressbook 1' 'My first addressbook'

// create a scheduled campaign 
create.scheduledcampaign.php "Campaign 1" <addressbook_hash> <bridge_target> <media_id> [media_id...]

// get status of campaign
control.campaign.php status <campaign_hash>

// start / stop / pause / get status of / dump configuration of a campaign
control.campaign.php <start | stop | pause | status | dumpconfig> <campaign_hash>

```

## Workflow examples
### Creating a scheduled campaign
This will lead you though creating a scheduled campaign with IVR, if the callee pushes the 1 key, they will be put in contact ( *bridged* ) with the configured 
**bridge target** phone number.  
If not started immediately, the campaign will auto start if within the scheduled hours (mon-fri, 6pm-8pm).

1. Modify the sample addressbook in scripts/samples/addressbook.csv and replace the telephone numbers with your own test phone numbers

2. Create your media file or use the sample and upload it to our servers

    ```
    $ php upload.media.php scripts/samples/message.mp3 'Sample message 1'
    Pushing media file, job id: 123456789814799592tlqabcdefghi . Done!
    Media file id: 10112211
    Done!
    Successfully imported media file
    ``` 

3. Run the following to upload your addressbook you created or modifed in step 2  
    ```
    $ php upload.addressbook.php scripts/samples/addressbook.csv 'Addressbook 1' 'My first addressbook' 
    Pushing addressbook, job id: 123456789814799592tlqabcdefghi . Done!
    Addressbook id: WABCEDFG3
    Addressbook import job id: 123456789814799592tlqabcdefzzz . Done!
    Successfully imported 5 numbers
    ```
4. Create our scheduled campaign using the hash ids returned from the previous 2 steps, the phone number is the number to be connected to if the callee pushes the 1 key on their telephone.
    ```
    $ php create.scheduledcampaign.php "Campaign 1" WABCEDFG3 +3312345678 10112211
    Campaign created ID: 8J123ABC
    ```
5. We can display the status or dump the config of our newly created campaign
    ```
    $ php control.campaign.php status 8J123ABC
    Campaign 8J123ABC status info:
    {
    "run_id": 1,
    "state": "CREATED",
    "finished_cause": "NONE",
    "finished_cause_data": null,
    ...

    "scheduled": true,
    "started_at": "0000-00-00 00:00:00",
    "ended_at": "0000-00-00 00:00:00",
    "reports": [],
    "state_history": []
    }
    ```
6. For debugging, you can manually launch the campaign:
    ```
    $ php control.campaign.php start 8J123ABC
    Campaign 8J123ABC has been STARTED
    ```
7. The campaign will run until termination, or until it is manually stopped. 
Here we can issue another status command to view our campaign statistics and status
    ```
    $ php control.campaign.php status 8J123ABC
    Campaign 8J123ABC status info:
    {
    "run_id": 1,
    "state": "FINISHED",
    "finished_cause": "DONE",
    "finished_cause_data": null,
    "percent":100,
    ...

    "scheduled": true,
    "started_at": "2016-06-06 09:00:00",
    "ended_at": "2016-06-14 09:50:00",
    "reports": [{
            "hash": "GY12340Y",
            "run_id": 1
        }],
    "state_history": [{
            "old_state": "STARTED",
            "new_state": "FINISHED",
            "origin": "CAMPAIGN",
            "date": "2016-06-06 09:00:00"
        },{
            "old_state": "CREATED",
            "new_state": "STARTED",
            "origin": "USER",
            "date": "2016-06-06 09:50:00"
        }]
    }

    ```

- - -

# Further help
* You will find API documentation and snippets here at [http://thecallr.com/docs/](http://thecallr.com/docs/)
* Or on github in our repository [https://github.com/THECALLR/](https://github.com/THECALLR/)
 
If you have any further questions or require assistance with these examples, please contact CALLR Support
* support@callr.com
* FR: +33 (0)1 84 14 00 30 

---