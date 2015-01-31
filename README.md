# Spotify In Sight

A project for the course [PHPMVC](http://dbwebb.se/phpmvc/) at [BTH](http://www.bth.se/), based on [Anax-MVC](https://github.com/mosbth/Anax-MVC).

By Marcus Törnroth.


## How to install

### Install the Software

It's easy to get your own copy of Spotify In Sigth! Start to clone the entire repository from [GitHub](https://github.com/rcus/spot).

    git clone https://github.com/rcus/spot


Installing dependencies with Composer.

    git clone https://github.com/rcus/spot


If your address to Spotify In Sight is located in a directory, you need to configure the file `.htaccess`. Un-comment the line below and edit to your directory. 

    RewriteBase /~matg12/phpmvc/spot/webroot/


### Setup the database

Edit the file `app/config/database_mysql.php`. Replace `HOST`, `DBNAME`, `USERNAME` and `PASSWORD` with your data.

    return [
        'dsn'            => "mysql:host=HOST;dbname=DBNAME;",
        'username'       => "USERNAME",
        'password'       => "PASSWORD",
    ...


Now are you able to create tables and views in the database. Just point your browser to `http://YOUR_PATH_TO_PROJECT/setup`, like:

    http://www.student.bth.se/~matg12/phpmvc/spot/setup


The setup install some demo data. If you would like to have a clean install, you have to remove - or comment - the following line (42) in `webroot/index.php` and then point your browser to setup: 

    $setup->addDemo();


Usernames and passwords in demo are like the users first name, like user `john` wtih password `john`. Well, all users except mine ;)


### Enjoy your site

Now you have your own version of Spotify In Sight. Enjoy!
