/**
 * Created by Dominik on 13.06.2017.
 */
let $pbMetal = $("#progressBarMetal");
let $pbWood = $("#progressBarWood");
let $pbStone = $("#progressBarStone");

let interval = setInterval(makeProgress, 600);
let val = 0;

function makeProgress() {
    if(val > 100) {
        console.log("ended");
        //clearInterval(interval);
        val = 0;
    } else {
        $pbMetal.attr('aria-valuenow', val);
        $pbMetal.css("width", val+"%");
        //$("#progressBarMetal").text(val+"%");
        $("#metal-head").text("Metal - " + val + "%");

        $pbWood.attr('aria-valuenow', val);
        $pbWood.css("width", val+"%");
        $("#wood-head").text("Wood - " + val + "%");

        $pbStone.attr('aria-valuenow', val);
        $pbStone.css("width", val+"%");
        $("#stone-head").text("Stone - " + val + "%");
        val++;
        // console.log(val)
    }
}
