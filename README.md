# OnionOrNotTheOnionDrinkingGame
OnionOrNotTheOnionDrinkingGame : A game to play with your friends. Guess. Be surprised. Drink!

## Installation
Put all files within the projectfiles/ folder in the root or a subfolder on a webserver.

If your website is NOT running in the root folder add the following line as the second or third line within the .htaccess file with [YOUR DIRPATH] as the path on the webserver:
RewriteBase [YOUR DIRPATH]

After that you can open the site and will be redirected to the reset page. This is necessary so that the game stores its access to the database and creates any necessary tables. After that it fills them with the predefined data.

(A note: You will be asked for two different database users. Those can be the same, but the at least one of them has to have write access. This is for further security. ;) )

If you get a sucess message you are good to go! Have Fun!

## Getting more data from reddit

Look at projectfiles/php_scripts/data/*.json and at gather_from_reddit/gather_from_reddit.py

The rest should be self explanatory. If not, you are no coder, but 2000 headlines should be enough for now! :D

## Thanks and Have Fun!