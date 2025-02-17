function digitalClock(){
    let datefunction = new Date();
    let hours = datefunction.getHours();
    let minutes = datefunction.getMinutes();
    let seconds = datefunction.getSeconds();
    let timeFormat = 'AM';

    timeFormat = hours >= 12 ? 'PM' : 'AM';
    hours = hours == 0 ? 12 : hours;
    hours = hours > 12 ? hours - 12 : hours;
    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;

    document.querySelector('.hours').innerHTML = hours;
    document.querySelector('.minutes').innerHTML = minutes;
    document.querySelector('.seconds').innerHTML = seconds;
    document.querySelector('.format').innerHTML = timeFormat;
}
setInterval(digitalClock, 1000);

let newDateFunction = new Date();

function renderDate(){
    newDateFunction.setDate(1);
    let day = newDateFunction.getDay();

    let currentDate = new Date(
        newDateFunction.getFullYear(),
        newDateFunction.getMonth() + 1,0
    ).getDate(); // to get the last date of current month

    let prevDate = new Date(
        newDateFunction.getFullYear(),
        newDateFunction.getMonth(), 0
    ).getDate(); // to get the last date of previous month

    let addNextDate = new Date(
        newDateFunction.getFullYear(),
        newDateFunction.getMonth() + 1,0
    ).getDate();

    let addNext = addNextDate + 7;

    let month = newDateFunction.getMonth();
    let year = newDateFunction.getFullYear();
    let monthArr = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById("month").innerHTML = monthArr[month] + " - " + year;

    let today = new Date();
    let weekDay = today.getDay();
    document.getElementById("date").innerHTML = today.toDateString();
    document.querySelector(`.week :nth-child(${weekDay + 1})`).classList.add("active");

    let DATES = "";

    for(let x = day; x > 0; x--){
        DATES += "<div class='prev'>" + (prevDate - x + 1) + "</div>";
    }

    for(let i = 1; i <= currentDate; i++){
        if(i === today.getDate() && newDateFunction.getMonth() == today.getMonth() && newDateFunction.getFullYear() === today.getFullYear()){
            DATES += "<div class='today' onclick='showDateDetails(" + i + ")'>" + i + "</div>";
        } else {
            DATES += "<div onclick='showDateDetails(" + i + ")'>" + i + "</div>";
        }
    }

    for(let k = 1; k <= addNext; k++){
        DATES += "<div class='next'>" + k + "</div>";
    }

    document.querySelector('.dates').innerHTML = DATES;
}

function moveDate(para){
    if(para == 'prev'){
        newDateFunction.setMonth(newDateFunction.getMonth() - 1);
    }
    else if(para == 'next'){
        newDateFunction.setMonth(newDateFunction.getMonth() + 1);
    }
    renderDate();
}

function showDateDetails(date){
    let dateInfo = "Details for " + date + ": This is a dynamic detail for this date.";
    document.getElementById("date-info").innerHTML = dateInfo;
}

renderDate();
