<?php
/*!
 *
 ______             _
|  ____|           | |
| |__ _ __ ___  ___| | __ _ _ __   ___ ___
|  __| '__/ _ \/ _ \ |/ _` | '_ \ / __/ _ \
| |  | | |  __/  __/ | (_| | | | | (_|  __/
|_|  |_|  \___|\___|_|\__,_|_| |_|\___\___|
 _______ _                _                  _
|__   __(_)              | |                | |
   | |   _ _ __ ___   ___| |_ _ __ __ _  ___| | _____ _ __
   | |  | | '_ ` _ \ / _ \ __| '__/ _` |/ __| |/ / _ \ '__|
   | |  | | | | | | |  __/ |_| | | (_| | (__|   <  __/ |
   |_|  |_|_| |_| |_|\___|\__|_|  \__,_|\___|_|\_\___|_|

 */

/**
 * Default Settings
 * Set your default settings here
 */
$settings = array(
  'rate' => 20, // default rate per hour
  'currency' => '€',
  'filename' => 'timetracker', // name of the database
  'tasksno' => 5, // TODO: Number of tasks to show
  'saveinterval' => 10 // save every N seconds
);

/**
 * Save database
 */
if (isset($_GET['action'])) {
  if ($_GET['action'] == 'save' && (strlen($_POST['json']) > 2)) {
    $filename = $settings['filename'].".json";
    $filehandle = fopen($filename, 'w') or die("Error: Can't create or save files, please modify folder permissions");
    $fwrite = fwrite($filehandle, stripslashes($_POST['json']));
    fclose($filehandle);
    echo $fwrite;
    die();
  }
}

/**
 * Read database
 */
$data = '';
if (file_exists($settings['filename'].".json")) {
  $data = file_get_contents($settings['filename'].".json");
}

// If no database then generate a blank one
if (strlen($data) < 3) {
  $data = '{';
  for ($i = 0; $i < $settings['tasksno']; $i++) {
    $data .= '"'.$i.'":{"date":"","client":"","task":"","rate":'.$settings['rate'].',"total":0,"desc":"","timed":0}';
    if ($i < $settings['tasksno']-1) {
      $data .= ',';
    }
  }
  $data .= '}';
}

/**
 * HTML
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Freelance Timetracker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.1.4/tailwind.min.css" />
  <!--Fonts and icons -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
  <!-- partial:index.partial.html -->

  <div class="h-screen w-screen bg-indigo-400 overflow-hidden absolute flex items-center">
    <div class="w-screen h-64 absolute top-0 opacity-50 left-0 -my-40 -mx-64 bg-indigo-300 rounded-full"></div>
    <div class="w-64 h-64 -mx-32 bg-indigo-300 opacity-50 rounded-full"></div>
    <div class="w-64 h-64 ml-auto relative opacity-50 -mr-32 bg-indigo-300 rounded-full"></div>
    <div class="w-screen h-64 absolute opacity-50 bottom-0 right-0 -my-40 -mx-64 bg-indigo-300 rounded-full"></div>
  </div>
  <div class="container mx-auto h-screen py-16 px-8 relative">
    <div class="flex w-full rounded-lg h-full lg:overflow-hidden overflow-auto lg:flex-row flex-col shadow-2xl">
      <div class="w-full bg-indigo-600 text-white flex flex-col">
        <div class="p-8 bg-indigo-700 flex items-center">
          <i class="fa fa-clock-o fa-5x mr-4" aria-hidden="true"></i>
          <div class="mr-4">
            <h2 id="big-time" class="text-4xl font-bold"></h2>
            <h2 id="big-total" class="text-2xl text-indigo-400 -mt-5"></h2>
          </div>
          <div class="mr-auto border-l-4 border-indigo-500 p-4">
            <h1 class="text-xl leading-none mb-1" id="client-title">Time tracker for freelancer</h1>
            <h2 class="text-indigo-400 text-sm" id="client-task">Click the checkbox to start timing a task, edit any fields by clicking on them.</h2>
          </div>
          <form>
            <button id="save" class="bg-indigo-600 text-white py-2 text-sm px-3 rounded focus:outline-none">Save now</button>
          </form>
        </div>
        <div class="flex -mt-5">
          <div class="flex-1 text-indigo-500 text-xs text-left pl-4">
            0 min.
          </div>
          <div class="flex-1 text-indigo-500 text-xs text-center">
            30 min.
          </div>
          <div class="flex-1 text-indigo-500 text-xs text-right pr-4">
            60 min.
          </div>
        </div>
        <div id="progress" class="nanobar"></div>
        <div class="pt-4 flex flex-1 items-start overflow-auto">
          <div class="flex-1">
            <div class="flex mb-1">
              <table id="table" class="w-auto">
                <thead>
                  <tr>
                    <th class=""></th>
                    <th class="w-1/12">Date</th>
                    <th class=""></th>
                    <th class="w-3/12">Project</th>
                    <th class="w-2/12">Task</th>
                    <th class="w-1/12">Rate</th>
                    <th class="w-1/12">Total</th>
                    <th class="w-3/12">Note</th>
                    <th class="w-1/12">Time</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="fixed h-screen right-0 top-0 items-center flex">
    <div class="p-2 bg-white border-l-4 border-t-4 border-b-4 border-indigo-400 inline-flex items-center rounded-tl-lg shadow-2xl rounded-bl-lg z-10 flex-col">
      <button class="bg-gray-500 w-5 h-5 rounded-full mb-2 outline-none focus:outline-none" theme-button="gray"></button>
      <button class="bg-red-500 w-5 h-5 rounded-full mb-2 outline-none focus:outline-none" theme-button="red"></button>
      <button class="bg-orange-500 w-5 h-5 rounded-full mb-2 outline-none focus:outline-none" theme-button="orange"></button>
      <button class="bg-green-500 w-5 h-5 rounded-full mb-2 outline-none focus:outline-none" theme-button="green"></button>
      <button class="bg-teal-500 w-5 h-5 rounded-full mb-2 outline-none focus:outline-none" theme-button="teal"></button>
      <button class="bg-blue-500 w-5 h-5 rounded-full mb-2 outline-none focus:outline-none" theme-button="blue"></button>
      <button class="bg-indigo-500 w-5 h-5 rounded-full mb-2 outline-none focus:outline-none" theme-button="indigo"></button>
      <button class="bg-purple-500 w-5 h-5 rounded-full mb-2 outline-none focus:outline-none" theme-button="purple"></button>
      <button class="bg-pink-500 w-5 h-5 rounded-full outline-none focus:outline-none" theme-button="pink"></button>
    </div>
  </div>
  <style>
    input {
      background:transparent;
      border:none;
      box-shadow:none
    }
    input[type=checkbox] {
      display: none;
    }
    input[type=checkbox]+label:before {
      font-family: FontAwesome;
      display: inline-block;
      cursor: pointer;
    }
    input[type=checkbox]+label:before {
      content: "\f01d";
      letter-spacing: 10px;
    }
    input[type=checkbox]:checked+label:before {
      letter-spacing: 5px;
      content: "\f28c";
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/nanobar/0.4.2/nanobar.min.js"></script>
  <script>
    var t = {
      config: {
        currency: '<?=$settings['currency'] ?>',
        rate: '<?=number_format($settings['rate'], 2) ?>',
        savenext: <?=$settings['saveinterval']*1000 ?>,
        savedefault: <?=$settings['saveinterval']*1000 ?>,
        initialdata: '<?=$data ?>'
      }
    };
    var options = {
      target: document.getElementById('progress')
    };
    var nanobar = new Nanobar(options);
    $(".bar").addClass("bg-indigo-400");
    nanobar.go(0);
    $('.taskrow').click(function() {
      var CheckBox = $(this).find('input[type="checkbox"]');
      if (CheckBox.attr('checked')) {
        CheckBox.attr('checked', false);
      } else {
        CheckBox.attr('checked', true);
      }
    })
  </script>
  <script src="timetracker.js"></script>
  <audio id="notify" src="#"></audio>
</body>
</html>
