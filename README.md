# VR Friends Rankings

This *wonderful* little tool generates a table with all your friends rankings on the "Vendée Globe" race by VirtualRegatta.
It gives official rankings and the rankings among boats without paid credits. Therefore accounts with those credits pack aren't displayed here.
All informations are retrieved from [L'annexe des SO](http://vr-annexe.akroweb.fr).


![Preview](http://raw.github.com/NumEricR/VR-Friends-Rankings/master/Preview.png)



## Configuration

Edit the `config.php` file and add some boats at line 6 as follow :
```
'BOAT_NAME' => array('navigator' => 'PLAYER_FIRSTNAME', 'id' => ID),
```

Replace "BOAT_NAME", "PLAYER_FIRSTNAME" (optionnal) and "ID" strings by the values of a VirtualRegatta account.
You can find players' ID on [your friends list](http://www.virtualregatta.com/profil.php?section=friends) : look end of URL on username links.


## TODO

* Check if boats list is empty
* Add automatic import of friends list
* Check data before cache update
* Check browsers compatibility
* Generate evolution graph for each boat
* Translate UI
* ...


## License

 WTFPL - Do What The Fuck You Want To Public License