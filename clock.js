<!-- 
var timerID = null
var timerRunning = false

function stopclock(){

if(timerRunning)
clearTimeout(timerID)
timerRunning = false
}

function startclock(){

stopclock()
showtime()
}

function showtime(){
var now = new Date()
var hours = now.getHours()
var years = now.getYear()
var month = now.getMonth()+1
var dates = now.getDate()
var minutes = now.getMinutes()
var seconds = now.getSeconds()
var 
    timeValue = "" + ((years < 2000) ? years+1900 : years) + "."
    timeValue += "" + month + "."
    timeValue += "" + dates + "."
    timeValue = (hours >= 12) ? " ¤U¤È " : " ¤W¤È "
    timeValue += "" + ((hours > 12) ? hours - 12 : hours)
    timeValue += ((minutes < 10) ? ":0" : ":") + minutes
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds

    
    
document.clock.face.value = timeValue 
timerID = setTimeout("showtime()",1000)
timerRunning = true
}
//-->