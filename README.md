<h1>Atomic Craft Website</h1>

A website for a Minecraft game server project with mods that will use SPA architecture
and identify users by cookie tokens.

<h2>From the main what will be implemented in the project.</h2>

* Full SPA, which means no reloading of the web page at all.
* Implementation of game balance in the form of project coins and integration of payment systems.
* Main page with the ability to download the project launcher, with the ability to view
  pictures of the project and instruction on how to start playing on the project.
* Store of game items with the possibility of pagination, search by item name
  and filtering by mods, with a purchase history page and an admin page for adding products.
* Personal account with display of information, loading of various skins and capes
  and reset them to standard, as well as 3D viewing of the character's avatar; With the possibility to replenish
  game balance, purchasing privileges, receiving a daily gift for entering the game, promotional codes,
  and referral system.
* Admin panel with the ability to enter data into the database and tracking various information.
* Server page where you can select a server, view its map, mods, and similar information.
* Project news page, where the user can observe project changes in the form
  posts from the admin with a picture, title and subject. Authorized user will be able to like
  or dislike the post, as well as leave comments.
* And many other things that I forgot to mention here.

<h2>Build</h2>

1. Generate an application key:
```bash
php artisan key:generate
```

2. Open the .env file and set up a database connection.

3. Run the migrations:
```bash
php artisan migrate
```

4. Install PHP dependencies:
```bash
composer i
```

5. Install JavaScript dependencies:
```bash
npm i
```

6. Create a symbolic link to the storage:
```bash
php artisan storage:link
```

7. Build the project:
```bash
npm run build
```

<h2>Develop</h2>

```bash
npm run dev
```

<h2>Other commands for the project to work</h2>

* Run the laravel schedule

```bash
php artisan schedule:run
```
