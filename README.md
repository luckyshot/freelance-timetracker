## Freelance Timetracker


![Screen](https://raw.githubusercontent.com/renat2985/freelance-timetracker/master/screen3.png)


Freelance Timetracker is a free and super-simple web-based timetracking system. All the information is stored in a JSON file so no database is needed.


### 10 second Setup

1. Copy the files to your web server
2. You are done!

#### Quick notes

Make sure PHP can create/edit files in that path. You should use Freelance Timetracker locally since there is no password protection, everyone could access it and play around with it. Also, make sure not to open several tabs of it since you may overwrite the database. No configuration is necessary although you can customize some things such as saving interval, default rate, currency, database extension and a few other things.


### How to use

Click the checkbox to start timing a task, edit any fields by clicking on them.


### Contents

The code is just one PHP, CSS, JS and JSON file.


### How it works

- Tailwindcss gives it styling
- jQuery/AJAX sends the information to the back-end (every 10 seconds by default)
- PHP stores everything in a JSON file

This setup makes it extremely easy to install Freelance Timetracker with just a copy-paste and start using straight away. It also makes backing up your stuff super-easy.


### License

Freelance Timetracker is authored by Xavi Esteve and is licensed under a MIT License.

Feel free to fork it and send any pull requests.


