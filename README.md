# TURF - Top Ultra Rapid Fire
TURF is project for the Riot Games API Challenge.

The front-end consist in a Web App for players to check their recent performance on URF Mode games giving them a score and world ranking.

Also shows the TOP 10 URF players across all regions (or a specific one), sorting them by score, kills, deaths, assists, total damage taken and total damage dealt to champions.

The player can see the champion ranking too, sorting them by average score, ban rate, champion name, pick rate and win rate.

If the player wants to check his friends scores, he can register with facebook and verify his summoner name in three easy steps. Once the player is logged in with facebook and finished the verification process, the system will get the friend list of the player and build a personal friend ranking.

## How it works
### Viktor
Viktor is a java desktop app build for capturing the URF game ids from the new api-challenge-v4.1 endpoint and saving them on a database to later be processed.

### Heimerdinger
Heimerdinger is a java desktop app build for taking the game ids captured by Viktor, and process each game, capturing the match data and saving on a database.

_Under construction_
