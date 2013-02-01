# Freelance Timetracker

Freelance Timetracker is a free and simple web-based timetracking system. All the information is stored in a .json file so no MySQL or other database is needed.

![Freelance Timetracking Screenshot](http://xaviesteve.com/wp-content/uploads/2013/02/freelance-timetracker-free.png)

## Installation

1. Copy the files to your web server
2. Make sure PHP can create new files in that path

You should use Freelance Timetracker locally since there is no password protection and everyone could access it and play around with it. Also, make sure not to open several tabs of it since you may overwrite the database. No configuration is necessary although you can customize saving times, database extension and a few other things.

## Tutorial

Change the rate of your task and click the checkbox to start timing it.

## How it works

The code is just a PHP and a JSON file. Uses Twitter Bootstrap for the UI and JS/AJAX/JSON to send the information (every 10 seconds by default) to the back-end. The PHP back-end then saves everything into a file which makes it easy to move around and edit.


## License

Freelance Timetracker is authored by [Xavi](http://xaviesteve.com/) and is licensed under a [Creative Commons Attribution-NonCommercial-ShareAlike license](http://creativecommons.org/licenses/by-nc-sa/3.0/). Feel free to fork it and send any pull requests.