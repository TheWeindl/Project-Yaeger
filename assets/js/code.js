/**
 * Created by Dominik on 13.06.2017.
 */
let $pbRes = $("#progressBarRes");

let interval = setInterval(makeProgress, 600);
let val =  $pbRes.attr('aria-valuenow');

function makeProgress() {
    if(val > 100) {
        // console.log("ended");
        // clearInterval(interval);
        $pbRes.addClass("progress-bar-success");
        $pbRes.attr('aria-valuenow', 0);
        $pbRes.css("width", 0+"%");
        val = 1;
    } else {
        $pbRes.removeClass("progress-bar-success");
        $pbRes.attr('aria-valuenow', val);
        $pbRes.css("width", val+"%");
        //$("#progressBarMetal").text(val+"%");
        // $("#metal-head").text("Metal - " + val + "%");
        val++;
        // console.log(val)
    }
}

$("#showHQ").click(function () {
    sessionStorage.setItem("showHQ", "true");
    location.reload();
});

if(sessionStorage.getItem("showHQ") == "true") {
    $('#hqmodal').modal('show');
    sessionStorage.setItem("showHQ", "false");
}
let r = /\d+/;

let $ResNeededArr = $(".res-needed span");
let $woodNeeded = $("span.wood");
let $stoneNeeded = $("span.stone");
let $metalNeeded = $("span.metal");

let $woodAvailable = $(".resource-production .wood").text().match(r)[0];
let $stoneAvailable = $(".resource-production .stone").text().match(r)[0];

let $metalAvailable = $(".resource-production .metal").text().match(r)[0];
let $ResProduction = $(".resource-production .panel-body p");

// console.log($stoneAvailable);

for(let i = 0; i < $ResNeededArr.length; i++) {
    // console.info($($ResNeededArr[i]).text().match(r)[0]);
    if($woodAvailable >= $($woodNeeded[i]).text().match(r)[0] && $stoneAvailable >= $($stoneNeeded[i]).text().match(r)[0] && $metalAvailable >= $($metalNeeded[i]).text().match(r)[0]) {
        // set parent li to green
        // console.log($($woodNeeded[i])[0]);
        $($woodNeeded[i]).parent().removeClass("list-group-item-danger");
        $($woodNeeded[i]).parent().addClass("list-group-item-success");
        // console.info("enough everything");
    }
}
console.info(sessionStorage.getItem("wood"));
