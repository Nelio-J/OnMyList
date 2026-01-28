# On My List: Easily manage your music backlog

<div align=center>
  <br>
  <a href="https://github.com/Nelio-J/OnMyList"><img src="https://github.com/Nelio-J/OnMyList/blob/main/assets/images/Logo_transparent.png" alt="On My List Logo" width="200"></a>
  <br>
</div>

<h4 align="center">An app built on top of <a href="https://symfony.com/" target="_blank">Symfony</a> to manage all the music and arist on your backlog.</h4>

<p align=center>
   <a href="#key-features">Key Features</a> •
   <a href="#get-started">Get Started</a> •
   <a href="#credits">Credits</a>
</p>

<br>

As someone that loves music and exploring different genres, I've build up quite a backlog of music that seemed interesting to check out. This backlog was spread out over various places, such as a notepad file, notes on my phone and screenshots.
I wanted an easy way of managing this backlog in one place. The result of this is **On My List**! By integrating the Spotify API, On My List let's you search for albums & artists and add them to a manageable list.
<br>

  
## Key Features

* Centralize your music backlog
  - No more writing down albums in various notepad apps and files. Organize all the albums & artist you want to check out in one place.
  <div align=center>
    <img src="https://github.com/Nelio-J/OnMyList/blob/main/assets/app%20screenshots/ss_backlogs.png" alt="Overview of multiple lists" width="700" >
  </div>

* Search, filter and sort your lists.
  - You can make multiple lists, for example by genre. You can search through each list, filter by artist or album and sort the list alphabetically or by date.
  <div align=center>
    <img src="https://github.com/Nelio-J/OnMyList/blob/main/assets/app%20screenshots/ss_backlog_details.png" alt="Details page of a list, filtered by albums and sorted by name" width="700" >
  </div>
  
* Personal notes
  - You can click on a list item to flip it. Here you can add any notes you have and manage it's status (plan on listening, completed)
  <div align=center>
    <img src="https://github.com/Nelio-J/OnMyList/blob/main/assets/app%20screenshots/ss_item_notes.png" alt="Screenshot showing the notes function" width="700" >
  </div>


## Get started

1. **Install dependencies**

   ```bash
   cd OnMyList/
   composer install
   ```

2. **Setup the environment variables**

   This application requires a key for the Spotify API and a database connection. It is recommended to store the access credentials for these in a .env.local file, so they get kept private. There is an example files called .env.local.example to help you setup. Remove the '.example' from the filename to use it.

   <h4>Access to the Spotify Web API can be requested <a href="https://developer.spotify.com/documentation/web-api" target="_blank">on their website</a>.</h4>

   Enter the client credentials in the .env.local file. 
   ```bash
   SPOTIFY_CLIENT_ID=""
   SPOTIFY_CLIENT_SECRET=""
   ```

   
   <h4>Setup database connecting</h4>

   Choose and connect your own local database in the .env.local file. Example connection lines for databases can be found in .env
   ```bash
   DATABASE_URL=""
   ```

3. **Start the app**

   ```bash
   symfony serve
   ```

You can then view and use the app by visiting http://127.0.0.1:8000/backlog in your browser.
<div align=center>
  <a href="http://127.0.0.1:8000/backlog"><img src="https://github.com/Nelio-J/OnMyList/blob/main/assets/app%20screenshots/ss_home.png" alt="Screenshot of the On My List homepage" width="700" ></a>
</div>
 
## Credits

This software uses the following open source packages:

- [Symfony](https://symfony.com/)
- [Spotify Web API](https://developer.spotify.com/documentation/web-api)
- [MySQL](https://www.mysql.com/)
- [XAMPP](https://www.apachefriends.org/)
