class Marker {
    constructor(crd) {
        this.crd = crd;
        this.ukraineEdges = window.ukraineEdges;
    }

    render(crd) {
        return jQuery(`<div class="marker" lat="${this.crd.lat}" long="${this.crd.long}">${classMarkerArgs.markerPattern}</div>`).css(this.getCSSPosition());
    }

    getCSSPosition(crd = false) {
        return {
            'top': ((crd && crd.lat ? crd.lat : this.crd.lat) - this.ukraineEdges.north) * 100 / (this.ukraineEdges.south - this.ukraineEdges.north) + '%',
            'left': ((crd && crd.long ? crd.long : this.crd.long) - this.ukraineEdges.west) * 100 / (this.ukraineEdges.east - this.ukraineEdges.west) + '%',
        }
    }

    append() {
        this.render().appendTo(window.mapWrapper);
    }

    static reInitiateAll() {
        var instance = this;
        jQuery('.marker[lat][long]').each(function () {
            var marker = jQuery(this);
            marker.css(instance.getCSSPosition({
                'lat': marker.attr('lat'),
                'long': marker.attr('lat')
            }))
        })
    }

}
