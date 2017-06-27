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

$(".glyphicon-home").click(function () {
    sessionStorage.setItem("showHQ", "true");
    location.reload();
});

$("#showHQ").click(function () {
    sessionStorage.setItem("showHQ", "true");
    location.reload();
});

if(sessionStorage.getItem("showHQ") == "true") {
    $('#hqmodal').modal('show');
    sessionStorage.setItem("showHQ", "false");
}

// console.log($(".resource-production .progress-bar"));
for(let j = 0; j < $(".resource-production .progress-bar").length; j++) {
    // console.info($($(".resource-production .progress-bar")[j]).width());
    if(($($(".resource-production .progress-bar")[j]).width() / $($(".resource-production .progress-bar")[j]).parent().width() * 100) >= 70 && ($($(".resource-production .progress-bar")[j]).width() / $($(".resource-production .progress-bar")[j]).parent().width() * 100) < 100) {
        // console.log("more");
        $($(".resource-production .progress-bar")[j]).removeClass("progress-bar-success");
        $($(".resource-production .progress-bar")[j]).addClass("progress-bar-warning");
    } else if(($($(".resource-production .progress-bar")[j]).width() / $($(".resource-production .progress-bar")[j]).parent().width() * 100) == 100) {
        $($(".resource-production .progress-bar")[j]).removeClass("progress-bar-warning");
        $($(".resource-production .progress-bar")[j]).addClass("progress-bar-danger");
    }
}

let r = /\d+/;

let $ResNeededArr = $(".res-needed span");
let $woodNeeded = $("span.wood");
let $stoneNeeded = $("span.stone");
let $metalNeeded = $("span.metal");

let $woodAvailable = $(".resource-production .panel-body .wood").text().match(r)[0];
let $stoneAvailable = $(".resource-production .panel-body .stone").text().match(r)[0];

let $metalAvailable = $(".resource-production .panel-body .metal").text().match(r)[0];
let $ResProduction = $(".resource-production .panel-body p");

// console.log("stone " + $stoneAvailable);

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
// console.info(sessionStorage.getItem("wood"));


