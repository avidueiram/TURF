# TURF
TURF is a Web App for players to check their recent performance on URF Mode games giving them a score and world ranking.

Also shows the TOP 10 URF players across all regions (or a specific one), sorting them by score, kills, deaths, assists, total damage taken and total damage dealt to champions.

The player can see the champion ranking too, sorting them by ban rate, champion name, pick rate and win rate.

## How it works
### Viktor
Viktor is a java desktop app build for capturing the URF game ids from the new api-challenge-v4.1 endpoint and saving them on a database to later be processed.

### Heimerdinger
Heimerdinger is a java desktop app build for taking the game ids captured by Viktor, and process each game, capturing the match data and saving on a database.

_Under construction_