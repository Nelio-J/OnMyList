# On My List: Easily manage your music backlog

<div align=center>
  <br>
  <a href="https://github.com/Nelio-J/OnMyList"><img src="https://github.com/Nelio-J/OnMyList/blob/main/assets/images/OnMyListLogoJersey15.png" alt="On My List Logo" width="200"></a>
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

You can take a look at a demo of the application by clicking on the following link: <a href="http://158.101.209.123/" target="_blank">OnMyList</a>
 **(NOTE: I don't have a domain name for this yet. You can still make an account and login, but you won't receive an email to verify your account)**.
  
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

   <h2>1. Install dependencies</h2>

   ```bash
   cd OnMyList/
   composer install
   ```

   <h2>2. Setup the environment variables</h2>

   This application requires a key for the Spotify API, a database connection and a SMTP server to send email. It is recommended to store the access credentials for these in a .env.local file, so they get kept private. There is an example files called .env.local.example to help you setup. Remove the '.example' from the filename to use it.

   <h3>Access to the Spotify Web API can be requested <a href="https://developer.spotify.com/documentation/web-api" target="_blank">on their website</a>.</h4>
   
   **Enter the client credentials in the .env.local file.**
   ```bash
   SPOTIFY_CLIENT_ID=""
   SPOTIFY_CLIENT_SECRET=""
   ```
   
   <h3>Setup database connecting</h2>

   **Choose and connect your own local database in the .env.local file. Example connection lines for databases can be found in .env**
   ```bash
   DATABASE_URL=""
   ```

   <h3>Connect the SMTP server</h3>

   There are a variety of options for an SMTP server, like [Mailtrap](https://mailtrap.io/) and [Mailpit](https://mailpit.axllent.org/docs/install/). When running the application locally, you can use a sandbox environment to capture all outgoing emails. Please refer to the documentation of the SMTP service of your choosing for installation instructions.
   
   **Choose and connect the SMTP server of your choice in the .env.local file.**
   ```bash
   MAILER_DSN=
   ```

<h2>3. Start the app</h2>

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
