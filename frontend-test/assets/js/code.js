$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    generateCity();

    let $body = $("body");

    /*$body.on("mouseenter","img", function (e) {
        $(e.target).css({"top": parseInt($(this).css('top'), 10)-5});
    });

    $body.on("mouseleave","img", function (e) {
        $(e.target).css({"top": parseInt($(this).css('top'), 10)+5});
    });

    $body.on("mouseup","img", function (e) {
        $(e.target).css({"top": parseInt($(this).css('top'), 10)+5});
    });*/

    $("img.custom-field").on("load",function (e) {
        // console.log(this.height);
        if(this.height > 101) {
            let curtop = parseInt($(this).css('top'), 10);
            let curleft = parseInt($(this).css('left'), 10);
            $(this).css("top", curtop-(this.height - 100)+"px");
            $(this).css("left", curleft-1+"px");
        }
    });

    /*$("button").on("click", function (e) {
        refresh();
    });*/
});

let $city = $("#city");
let layout = {
    "fields": {
        "r1" :[
            {
                "top": "0",
                "left" : "0"
            },
            {
                "top": "0",
                "left" : "132px"
            },
            {
                "top": "0",
                "left" : "265px"
            },
            {
                "top": "0",
                "left" : "398px"
            },
            {
                "top": "0",
                "left" : "531px"
            }
        ],
        "r1.5" :[
            {
                "top": "33px",
                "left" : "66px"
            },
            {
                "top": "33px",
                "left" : "198px"
            },
            {
                "top": "33px",
                "left" : "332px"
            },
            {
                "top": "33px",
                "left" : "465px"
            }],
        "r2" :[
            {
                "top": "66px",
                "left" : "0"
            },
            {
                "top": "66px",
                "left" : "132px"
            },
            {
                "top": "66px",
                "left" : "265px"
            },
            {
                "top": "66px",
                "left" : "398px"
            },
            {
                "top": "66px",
                "left" : "531px"
            }
        ],
        "r2.5" :[
            {
                "top": "99px",
                "left" : "66px"
            },
            {
                "top": "99px",
                "left" : "199px"
            },
            {
                "top": "99px",
                "left" : "332px"
            },
            {
                "top": "99px",
                "left" : "465px"
            }
        ],
        "r3" :[
            {
                "top": "132px",
                "left" : "0"
            },
            {
                "top": "132px",
                "left" : "132px"
            },
            {
                "top": "132px",
                "left" : "265px"
            },
            {
                "top": "132px",
                "left" : "398px"
            },
            {
                "top": "132px",
                "left" : "531px"
            }
        ],
        "r3.5" :[
            {
                "top": "165px",
                "left" : "66px"
            },
            {
                "top": "165px",
                "left" : "199px"
            },
            {
                "top": "165px",
                "left" : "332px"
            },
            {
                "top": "165px",
                "left" : "465px"
            }
        ],
        "r4" :[
            {
                "top": "198px",
                "left" : "0"
            },
            {
                "top": "198px",
                "left" : "132px"
            },
            {
                "top": "198px",
                "left" : "265px"
            },
            {
                "top": "198px",
                "left" : "398px"
            },
            {
                "top": "198px",
                "left" : "531px"
            }
        ],
        "r4.5" :[
            {
                "top": "231px",
                "left" : "66px"
            },
            {
                "top": "231px",
                "left" : "199px"
            },
            {
                "top": "231px",
                "left" : "332px"
            },
            {
                "top": "231px",
                "left" : "465px"
            }
        ],
        "r5" :[
            {
                "top": "264px",
                "left" : "0"
            },
            {
                "top": "264px",
                "left" : "132px"
            },
            {
                "top": "264px",
                "left" : "265px"
            },
            {
                "top": "264px",
                "left" : "398px"
            },
            {
                "top": "264px",
                "left" : "531px"
            }
        ]
    }
};


class Block {

    constructor(top, left, src, ttmessage, picker = false) {
        this.left = left;
        this.top = top;
        this.ttmessage = ttmessage || false;
        this.img = new Image();
        this.img.src = src;
        if(!picker) {
            this.img.className = "custom-field";
        }
        this.$element = $(this.img);
    }

    out() {
        this.$element.css({"top": this.top, "left": this.left});
        if(this.ttmessage) {
            this.$element.attr("data-toggle", "tooltip");
            this.$element.attr("data-placement", "top");
            this.$element.attr("title",this.ttmessage);
            this.$element.tooltip();
        }
        // console.log(this.$element);
        return this.$element;
    }
}

class Headquarter {
    constructor(top, left, level, picker) {
        this.left = left;
        this.top = top;
        this.level = level;
        this.picker = picker;
    }

    _getBlockForLevel() {
        return new Block(this.top, this.left, "assets/imgs/buildings/headquarter-level-"+this.level+".png", "Headquarter - LEVEL "+this.level, this.picker);
    }

    out() {
        this.block = this._getBlockForLevel();
        return this.block.out();
    }
}

class Storage {
    constructor(top, left, level, picker) {
        this.left = left;
        this.top = top;
        this.level = level;
        this.picker = picker;
    }

    _getBlockForLevel() {
        return new Block(this.top, this.left, "assets/imgs/buildings/storage-level-"+this.level+".png", "Storage - LEVEL "+this.level, this.picker);
    }

    out() {
        this.block = this._getBlockForLevel();
        return this.block.out();
    }
}

class Production {
    constructor(top, left, level, type, picker) {
        this.left = left;
        this.top = top;
        this.level = level;
        this.type = type;
        this.picker = picker;
    }

    _getBlockForLevelAndType() {
        if(this.type === "wood") {
            return new Block(this.top, this.left, "assets/imgs/buildings/woodproduction-level-"+this.level+".png", "Wood-Production - LEVEL "+this.level, this.picker);
        } else if(this.type === "stone") {
            return new Block(this.top, this.left, "assets/imgs/buildings/stoneproduction-level-"+this.level+".png", "Stone-Production - LEVEL "+this.level, this.picker);
        } else {
            return new Block(this.top, this.left, "assets/imgs/buildings/basic.png");
        }

    }

    out() {
        this.block = this._getBlockForLevelAndType();
        return this.block.out();
    }
}

function refresh() {
    $city.css("opacity", "0");
    let oldTOP = $city.css("top");
    $city.css("top", "600px");

    setTimeout(function () {
        $city.html("");
        generateCity();
        $city.css("top", oldTOP);
        loadCorrections();
        $city.css("opacity", "1");
    }, 1000);

}

function loadCorrections() {
    $.each($("img"), function (key, value) {
        if(value.height > 101) {
            if(value.src.indexOf("headquarter") !== -1) {
                // is hq
                let curtop = parseInt($(value).css('top'), 10);
                let curleft = parseInt($(value).css('left'), 10);
                $(value).css("top", curtop-(value.height - 100)+"px");
                $(value).css("left", curleft-1+"px");
            } else {
                let curtop = parseInt($(value).css('top'), 10);
                $(value).css("top", curtop-(value.height - 100)+"px");
            }
        }
    });

}

function loadLayout(layout) {
    // console.log(layout);
    let fields = layout.fields;
    $.each(fields, function (key, value) {
        $.each(value, function (subkey, subvalue) {
            let $temp;
            switch (key) {
                case "r1": // ganz hinten
                    if(subkey == 0 || subkey == 1) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/forrest.png");
                        $city.append($temp.out());
                    } else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic.png");
                        $city.append($temp.out());
                    }

                    break;
                case "r1.5":
                    if(subkey == 0) {
                        $temp = new Production(subvalue.top, subvalue.left, 1, "wood");
                        $city.append($temp.out());
                    } else if(subkey == 1) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_111.png");
                        $city.append($temp.out());
                    } else if(subkey == 3) {
                        $temp = new Production(subvalue.top, subvalue.left, 1, "stone");
                        $city.append($temp.out());
                    }else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic.png");
                        $city.append($temp.out());
                    }
                    break;
                case "r2":
                    if (subkey == 0) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_067.png");
                        $city.append($temp.out());
                    } else if(subkey == 1) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_113.png");
                        $city.append($temp.out());
                    } else if(subkey == 2) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_057.png");
                        $city.append($temp.out());
                    } else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic.png");
                        $city.append($temp.out());
                    }
                    break;
                case "r2.5":
                    if(subkey == 2) {
                        $temp = new Storage(subvalue.top, subvalue.left, 1);
                        $city.append($temp.out());
                    } else if(subkey == 0) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_125.png");
                        $city.append($temp.out());
                    } else if(subkey == 1) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_057.png");
                        $city.append($temp.out());
                    } else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic.png");
                        $city.append($temp.out());
                    }
                    break;
                case "r3": // mitte
                    if (subkey == 0) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_067.png");
                        $city.append($temp.out());
                    } else if(subkey == 2) {
                        $temp = new Headquarter(subvalue.top, subvalue.left, 2);
                        $city.append($temp.out());
                    } else if(subkey == 1) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_038.png");
                        $city.append($temp.out());
                    } else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic.png");
                        $city.append($temp.out());
                    }
                    break;
                case "r3.5":
                    if(subkey == 0) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_057.png");
                        $city.append($temp.out());
                    } else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic.png");
                        $city.append($temp.out());
                    }
                    break;
                case "r4":
                    if(subkey == 0) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_092.png");
                        $city.append($temp.out());
                    } else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic.png");
                        $city.append($temp.out());
                    }
                    break;
                case "r4.5":
                    if(subkey == 0) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_065.png");
                        $city.append($temp.out());
                    } else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic.png");
                        $city.append($temp.out());
                    }
                    break;
                case "r5": // ganz vorne
                    if (subkey == 0) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_067.png");
                        $city.append($temp.out());
                    } else if(subkey == 1) {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/landscape/landscapeTiles_040.png");
                        $city.append($temp.out());
                    } else {
                        $temp = new Block(subvalue.top, subvalue.left, "assets/imgs/city/basic-outer.png");
                        $city.append($temp.out());
                    }

                    break;
            }

        })
    });
}

function generateCity() {
    // loadLayout(layout);
    generateLayout(11,5, $city, 0,66,33,132);
    generatePicker($city.css("width"), {"hq": {"level": 2, "top": 5, "left": 2}});
}

function generatePicker(width, buildings = {}) {
    let $picker = $("<div class='custom-block-picker'></div>");

    let hq = new Headquarter(0,0,buildings.hq.level, true);
    let $hq = hq.out();
    $hq.addClass("custom-block");
    $picker.append($hq);

    let storage = new Storage(0,0,1, true);
    let $storage = storage.out();
    $storage.addClass("custom-block");
    $picker.append($storage);

    let pro1 = new Production(0,0,1,"wood", true);
    let $pro1 = pro1.out();
    $pro1.addClass("custom-block");
    $picker.append($pro1);

    let pro2 = new Production(0,0,1,"stone", true);
    let $pro2 = pro2.out();
    $pro2.addClass("custom-block");
    $picker.append($pro2);

    let block = new Block(0,0,"assets/imgs/buildings/buildingTiles_003.png", false, true);
    let $block = block.out();
    $block.addClass("custom-block");
    $picker.append($block);

    let block2 = new Block(0,0,"assets/imgs/buildings/buildingTiles_003.png", false, true);
    let $block2 = block2.out();
    $block2.addClass("custom-block");
    $picker.append($block2);

    let block3 = new Block(0,0,"assets/imgs/buildings/buildingTiles_003.png", false, true);
    let $block3 = block3.out();
    $block3.addClass("custom-block");
    $picker.append($block3);


    $("nav").after($picker);
}

function generateLayout(rows, cols, $parent , normStart = 0, altStart = 66, rowint = 33, colint = 132) {
    $parent.css("width", colint * cols);
    $parent.css("height", rowint * rows);

    for(let i = 0; i < rows; i++) {
        for(let j = 0; j < cols; j++) {

            // todo build wright block
            let block;
            let overwritten = false;

            /*if(i === rows-1 || ((j === 0 || j === cols-1) && i % 2 === 0)) {
                block = new Block(0,0, "assets/imgs/city/basic-outer.png", "outer");
                overwritten = true;
            }

            if(i === 5 && j === 2) {
                block = new Headquarter(0,0,1);
                overwritten = true;
            }*/

            if(!overwritten) {
                block = new Block(0,0, "assets/imgs/city/basic.png");
            }

            // block = new Block(0,0, "assets/imgs/city/basic.png");

            if(i != 0 && i % 2 != 0) {
                // odd
                if(j != cols-1) {
                    block.top = rowint * i;
                    block.left = colint * j + altStart;
                    $parent.append(block.out());
                }

            } else {
                // even
                block.top = rowint * i;
                block.left = colint * j;
                $parent.append(block.out());
            }

        }
    }
}