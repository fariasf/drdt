NOTE : On Windows, "cmd" needs to be run with admin privileges.

This is the DRDT Vagrant Box.

Will serve as a base box for the DRDT project.

all environnement will be based off this box.

	Ubuntu 18.04
	apache2
	php 7.1
	MySQL


Requirements/How to Install :
__________________

	What will be installed :

	Fonctional LAMP Server. 
		-apache2
		-Ubuntu 18.04
		-php 7.1
		-MySQL

	Full Wordpres installation of drdt.constructionprotips.com

	Redis cache server.

	1 ) Add "192.168.205.11 drdt.constructionprotips.com" in your /etc/hosts file.

	2 ) Latest version of Vagrant is required in order to setup the private network with ubuntu 18.04.

	3 ) Latest version of VirtualBox

	4 ) The vagrant diskresize plugin is required.

		To install :

		vagrant plugin install vagrant-diskresize

	5 ) You will need a valid copy of the CPT database you can download one here :

		https://storage.googleapis.com/gcs-pantheon-backups/24fd5eae-e272-431c-877b-95c6bbeff62e/dev/1552590008_backup/drdt_dev_2019-03-14T19-00-08_UTC_database.sql.gz?Expires=1552915043&GoogleAccessId=url-signer%40pantheon-backups.iam.gserviceaccount.com&Signature=fdbCeFLhltSR%2Bvl7Yci00ZimvS9B6MWtLmEWas1I%2F5dD%2F53W0fNEdGqwyx%2FuZkSyk%2BrLv3Lsl8QrRSF%2FAJgIcs%2FIBwTIngWu3xmdklahu1J6dARuf6ulQOkkVY46wkj%2BQxZOWBwQh31VmHW3F72oe3hyKXIkvQbob17PmPmkRYl%2BLh5gElxveWfRlat6gclr6cXmcRgwgWNrVQ8waS%2BPGK%2FFxk5RrLY7BXBt3tsk3%2Bjo01E47cB81MGFX%2BkgdMBMMmDt5n3g%2FnEv1p2BqQCSJsj4KQmI7UasT3%2BsV2UBs0lbeMKew4Bx1xPW%2B%2FRtfBQ0H2cpJK67Y03ag5FrAP7%2FjQ%3D%3D

		*Temporary storage, I'm working on finding a good place to store this. Will probably be in one of our s3 storage on AWS. 

		you will need to place that file in the drdt-vagrant/ folder
		
	7 ) cd into the folder drdt-vagrant and do the following command : vagrant up

	8 ) The build will fail at composer. SSH into the box and do the following commands : vagrant ssh

	9 )  cd /vagrant

	10 ) composer install     *Enter your username and authentification token for the required one.

	11 ) exit

	12 ) vagrant provision    *that should complete the build and the site will be up and running.

Access information:
___________________

	IP of the box : 192.168.205.11

	To access the site :

	http://drdt.constructionprotips.com

	You can SSH into the Vagrant Box with the command :

	vagrant ssh

	MySQL credentials :

	UN : admin
	PW : password

	Wordpress user :

	UN : local.admin
	PW : password

	** PLEASE NOTE THAT CURRENTLY THE DRDT CODE IS PULLED FROM THE MASTER GIT BRANCH **


Upcomming features 
___________________

	Different env. setup : (Test, Staging, Prod).

	Build w/ specific branch/pull request from Git.

	Automatic Database download if not present in the folder.

	Script to extract a copy of current working DB to local machine.

	Better Redis default configuration.

	Nginx installation.
	
If you have any questions, request or suggestion, I will be pleased to take a look a them :) just send us a request at :
devops@tmbi.com

