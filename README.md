Author: Andrew Michienzi

The goal of this project is to be able to create spotify playlists of music played on WYCE. This is a personal project to learn more about HTML, php and REST APIs. The final product will allow a user to create a playlist by time and DJ.

WYCE Spotify Playlist Website's back-end is now running.
WARNING: The front-end is UGLY. 

You will need PHP5 and PHP5-curl

To run:
<ol>
<li>Direct browser to home.html</li>
<li>Select Authorize and log in to Spotify</li>
<li>Now you should be directed back to the home page. Select the date you would like and then the DJ from that date. The playlist you are creating will be the setlist of that DJ on that day.</li>
<li>Select 'Get Playlist'. This is going to take a bit, but eventually a list of songs will appear after the 'Get Playlist' button.</li>
<li>This list includes The album, artist, song name and whether or not it was found on spotify.</li> 
<li>Once the song list has been created, you may type in the playlist name and hit 'Submit'</li>
<li>This will create a playlist under your Spotify user name.</li>
</ol>

Plans/ideas for improvement:
<ul>
<li>Create front end by adding easily selected dates and DJs. Make it more flexible by allowing multiple dates and multiple DJs</li>
<li>Create html objects in an order that doesn't cause latency in front-end creation (DJ drop down menu)</li>
<li>Figure out best place to fork processes either in PHP scripts or javascript to speed up process in adding songs to playlist</li>
<li>Experiment with cacheing song and/or DJ information?</li>
</ul>
