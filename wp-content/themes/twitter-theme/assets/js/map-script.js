window.mapBackground = jQuery(mapScriptArgs.mapPattern);
window.regions = mapScriptArgs.regions;
window.regionsWithTweets = mapScriptArgs.regionsWithTweets;

window.markers = [
    {
        lat: 50.012348,
        long: 36.229080
    }
];


const EARTH_RADIUS = 6372795;
const UKRAINE_PROPORTIONS = 1.5086790039438838;

let calculateTheDistance = (φA, λA, φB, λB) => {

    let lat1 = φA * Math.PI / 180,
        lat2 = φB * Math.PI / 180,
        long1 = λA * Math.PI / 180,
        long2 = λB * Math.PI / 180;


    let cl1 = Math.cos(lat1),
        cl2 = Math.cos(lat2),
        sl1 = Math.sin(lat1),
        sl2 = Math.sin(lat2);

    let delta = long2 - long1,
        cdelta = Math.cos(delta),
        sdelta = Math.sin(delta);


    let y = Math.sqrt(Math.pow(cl2 * sdelta, 2) + Math.pow(cl1 * sl2 - sl1 * cl2 * cdelta, 2));
    let x = sl1 * sl2 + cl1 * cl2 * cdelta;

    let ad = Math.atan2(y, x);
    let dist = ad * EARTH_RADIUS;

    return dist;
}


jQuery(document).ready(function () {

    jQuery('[data-toggle="tooltip"]').tooltip()


    window.mapWrapper = jQuery('[role="map-wrapper"]');

    mapBackground.prependTo(mapWrapper);
    window.mapOverlay = jQuery(mapScriptArgs.mapPattern).attr('id',
        mapBackground.attr('id') + '-text-overlay'
    );

    mapOverlay.find('g[id]').each(function () {
        let group = jQuery(this);
        group.attr('id', "overlay-" + group.attr('id'))
    });

    mapOverlay.insertAfter(mapBackground);

    resizeMap();

    window.ukraineEdges = {
        north: 52.379118,
        east: 40.198056,
        west: 22.137839,
        south: 44.387222,
        getCSSPosition: function (lat, long) {
            return {
                'top': (lat - this.north) * 100 / (this.south - this.north) + '%',
                'left': (long - this.west) * 100 / (this.east - this.west) + '%',
            }
        }
    };

    window.ukraineEdges.width = calculateTheDistance(ukraineEdges.north, ukraineEdges.west, ukraineEdges.north, ukraineEdges.east);
    window.ukraineEdges.height = (mapWrapper.outerHeight() * ukraineEdges.width) / mapWrapper.outerWidth();


    window.regionCircles = [];

    for (let iso in window.regions) {


        let jsIso = (iso < 10) ? `0${iso}` : iso;

        let regionElement = jQuery(`#UA-${jsIso}`);
        let regionOverlayElement = jQuery(`#overlay-UA-${jsIso}`);

        //console.log(1);

        regionOverlayElement.find('text').text(window.regions[iso].label);
        //let value = Math.floor(min + Math.random() * (max + 1 - min));
        //let percentage = Math.round((value - min) * 100 / (max - min));

        //console.log(min, max, value, percentage);

        regionElement.attr('percents', regions[iso].percentage);

        if (regions[iso].hasOwnProperty('circles')) {
            window.regionCircles = window.regionCircles.concat(regions[iso].circles);

            for (let crd of regions[iso].circles) {
                newCircle = new Circle(crd, iso);
                newCircle.append();
            }
        }

    }

    for (let crd of markers) {
        newMarker = new Marker(crd);
        newMarker.append();
    }

    for (let crd of regionCircles) {

    }

})

jQuery(document).on('click', '#ukraine-map-text-overlay g[id] path, g[id] text', function () {
    var $this = jQuery(this),
        group = $this.closest('[id*="UA"]'),
        regionId = group.attr('id').replace('overlay-UA-', '');
        jQuery(`#modal-region-${regionId}`).modal()

}).on('mouseover', '#ukraine-map-text-overlay g[id] path, g[id] text', function () {
    let group = jQuery(this).closest('g[id]'),
        id = group.attr('id').replace(/overlay-/g, "");

    jQuery(`#${id}`).attr('action', 'hover');
}).on('mouseout', '#ukraine-map-text-overlay g[id] path, g[id] text', function () {
    let group = jQuery(this).closest('g[id]'),
        id = group.attr('id').replace(/overlay-/g, "");

    jQuery(`#${id}`).removeAttr('action');
}).on('click', '[role="show-all"]', function () {
    jQuery(this).prev().toggleClass('expanded');
}).on('click', "ul.region-select input[type='checkbox'] + label button", function(){
	jQuery(this).parent().click();
});

jQuery(window).on('resize', function () {
    resizeMap();
});

let resizeMap = () => {
    let windowHeight = window.innerHeight,
        windowWidth = window.innerWidth,
        mapSupposedNewHeight = windowHeight,
        mapSupposedNewWidth = mapSupposedNewHeight * UKRAINE_PROPORTIONS;

    //console.log(mapSupposedNewWidth);

    if (mapSupposedNewWidth > windowWidth) {
        var cssObj = {
            width: '100%',
            height: windowWidth / UKRAINE_PROPORTIONS
        }
    }else{
        var cssObj = {
            width: mapSupposedNewWidth,
            height: mapSupposedNewHeight
        }
    }

    mapWrapper.css({height: jQuery(window).height()})
        .css(cssObj)
        .parent()
        .next()
        .css({'margin-top': cssObj.height+ 30});
}

let getCircles = () => {
    window.circles = [];
    jQuery('.region-circle:not(.rendered[lat][long][radius])').each(function () {
        var circle = jQuery(this),
            wrapper = circle.parent(),
            width = circle.outerWidth(),
            height = circle.outerHeight(),
            left = parseFloat(circle.css('left')),
            top = parseFloat(circle.css('top')),
            wrapperWidth = wrapper.outerWidth(),
            wrapperHeight = wrapper.outerHeight(),
            deltaLat = ukraineEdges.north - ukraineEdges.south,
            deltaLong = ukraineEdges.east - ukraineEdges.west,
            center = {
                top: top + height / 2,
                left: left + width / 2
            };

        circleCoordinates = {
            width: (ukraineEdges.width / 100) * width * 100 / wrapperWidth,
            height: (ukraineEdges.height / 100) * height * 100 / wrapperHeight,
            left: ukraineEdges.west + (deltaLong / 100) * center.left * 100 / wrapperWidth,
            top: ukraineEdges.north - (deltaLat / 100) * center.top * 100 / wrapperHeight,
        }

        circle.attr('lat', circleCoordinates.top).attr('long', circleCoordinates.left).attr('radius', circleCoordinates.width)
        window.circles.push({
            lat: circleCoordinates.top,
            long: circleCoordinates.left,
            radius: circleCoordinates.width
        });

        console.log(deltaLat, circleCoordinates);
    })

}

