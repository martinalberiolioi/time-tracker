let [seconds, minutes, hours] = [0, 0, 0];
const timerRef = document.querySelector(".time");
const task_name = document.getElementById("input_field");
let int = null;
let start_time = null;

/**
 * Receives a task name and resets/restarts the timer from zero
 * Then, it sends the task name and start time to the backend to be saved
 * 
 * Shows an Alert in case the start button is pressed and there's no task name written
 */
document.getElementById("start").addEventListener("click", () => {
    [seconds, minutes, hours] = [0, 0, 0];
    timerRef.innerHTML = "00:00:00";
    
    if(task_name.value !== "") {
        if (int !== null) {
            clearInterval(int);
        }
        int = setInterval(time, 1000); // 1 second
        start_time = new Date().getTime();
        saveTask('start', task_name.value, start_time);
    } else {
        alert("Insert a task");
    }
});

/**
 * Saves the tasks' end time
 * Sends the data to the backend to be saved
 * 
 */
document.getElementById("stop").addEventListener("click", () => {
    end_time = new Date().getTime();
    saveTask('stop', task_name.value, start_time, end_time);
    clearInterval(int);
});

/**
 * Makes the timer work by adding hours/minutes/seconds
 * Formats the numbers to "time"
 * 
 */
function time() {
    seconds += 1;
    if (seconds == 60) {
        seconds = 0;
        minutes++;
        if (minutes == 60) {
            minutes = 0;
            hours++;
        }
    }
    // Formatting the numbers
    // E.g.: 08, 09, 10, 11, etc.
    let h = hours < 10 ? "0" + hours : hours;
    let m = minutes < 10 ? "0" + minutes : minutes;
    let s =
        seconds < 10
            ? "0" + seconds
            : seconds < 10
            ? "0" + seconds
            : seconds;

    timerRef.innerHTML = `${h}:${m}:${s}`;
}

/**
 * Sends the task data to the backend to be saved
 * 
 * Logs to the console in case of an error
 * 
 */
function saveTask(action, task_name, start_time, end_time) {
    $.post('/save', {
        '_token': $('meta[name=csrf-token]').attr('content'),
        action: action,
        task_name: task_name,
        start_time: start_time,
        end_time: end_time
    })
    .error(
        console.log($.responseText)
    )
    .success(
        console.log("Saved successfully")
    );
}