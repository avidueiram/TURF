# TURF - Top Ultra Rapid Fire
TURF is project for the Riot Games API Challenge.

The front-end consist in a Web App for players to check their recent performance on URF Mode games giving them a score and world ranking.

Also shows the TOP 10 URF players across all regions (or a specific one), sorting them by score, kills, deaths, assists, total damage taken and total damage dealt to champions.

The player can see the champion ranking too, sorting them by average score, ban rate, champion name, pick rate and win rate.

If the player wants to check his friends scores, he can register with facebook and verify his summoner name in three easy steps. Once the player is logged in with facebook and finished the verification process, the system will get the friend list of the player and build a personal friend ranking.

The webapp also shows a "Breaking News" section, with funny news like:
* Teemo death count ascends to 291,236 increasing by 15 each minute - Take precautions if you are playing Teemo.
* WANTED SERIAL KILLER - Killed 80 champions in 59 minutes - Last seen diving enemy fountain .
* New world death record! An anonymous summoner was killed 38 times in 29 minutes - "I am the best BY FAR" he said.
* Frog "George" is now a protected specie - Authorities are trying to stop the increase rate of suicides but just in the last 24 hrs there have been 6,677 cases.
* Summoner's Rift shopkeepers will donate 2.5% of the gold spent on their shops to the Pantheon Bakery Shop Dream Foundation (PBSDF) - Total gold collected: 854,846,821.

## How it works
### Viktor
Viktor is a java desktop app build for capturing the URF game ids from the new api-challenge-v4.1 endpoint and saving them on a database to later be processed.

### Heimerdinger
Heimerdinger is a java desktop app build for taking the game ids captured by Viktor, and process each game, capturing the match data and saving on a database.

### TURF Website
TURF is a web app build with HTML5, CSS, JQuery and PHP that shows data collected by Heimerdinger and the app itself.

## How to install/run app
* Download & install WAMP or similar package (like EasyPHP: http://www.easyphp.org/easyphp-devserver.php)
* Download TURF files: https://github.com/v11398/TURF/archive/master.zip
* Create database called "topurf"
* Execute the sql script located in:
  * `TURF/SQL/topurf.sql` This will create the database tables
* Execute the sql script located in:
  * `TURF/SQL/sample_data.sql` This will insert sample data to the tables
* Move the WebApp folder content to Apache www folder
* Open the PHP file located at:
  * `[Apache WWWW]/TURF/ws/globals.php`: Configure API KEY & MySQL
* Start Apache/MySQL Server and go to http://localhost/TURF

## Screenshots
### Home
![Home not logged in](http://i.imgur.com/fu5BWSR.jpg)

### Check Score
![Check Score](http://i.imgur.com/3u9unYO.png)

### Check Score - View Detail
![View Detail](http://i.imgur.com/H2f47VM.png)

### Friend Ranking
![Friend Ranking](http://i.imgur.com/8WSjjsj.png)

## Video
[![Demo](http://img.youtube.com/vi/BuDXIS-qOyI/0.jpg)](http://www.youtube.com/watch?v=BuDXIS-qOyI)
## Credits

Summoner Name: Towercel

Region: LAS

[Twitter](https://twitter.com/Avidueira)

[Email](mailto:alfredo.vidueiram@mayor.cl)
