<!DOCTYPE html>
<html>
   <head>
      <title>Time Tracker</title>
   </head>
   <center>
   <body>
      <div id="mainstopwatch">
      <div class="time">
         <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
      </div>
      <br>
      <div>
         <input type="text" id="input_field"></input>
      </div>
      <br>
      <div>
         <button id="start" onclick="start">Start</button>
         <button id="stop" onclick="stop">Stop</button>
      </div>
      <div>
         <p style="color:#808080">Write the task you're working on</p>
         <p style="color:#808080">Refresh page to see all your tasks</p>
      </div>
      @if(!empty($results))
      <div>
         <h2>Summary</h2>
         <table class="table">
            <thead>
               <th style="text-align:left">Task Name</th>
               <th style="text-align:right">Total task time</th>
            </thead>
            <tbody>
            @foreach($results as $r)
               <tr>
                  <td style=" padding: 5px 0px 0px 0px;">{{$r['name']}}</td>
                  <td style=" padding: 5px 0px 0px 100px;">{{$r['total_task_time']}}</td>
               </tr>
            @endforeach
            </tbody>
         </table>
         <div><br><h3>Total spent time: {{$total_time}}</h3></div>
      </div>
      @else
         <p style="color:#808080">Oops! No tasks were found!</p>
      @endif
      <script src="js/home.js"></script>
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
   </body>
   <meta name="csrf-token" content="{{ Session::token() }}"> 
</center>
</html>
