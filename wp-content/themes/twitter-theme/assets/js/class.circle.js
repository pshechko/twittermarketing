class Circle {
    constructor(crd, regionId) {
        this.crd = crd;
        this.regionId = regionId;
        this.ukraineEdges = window.ukraineEdges;
    }

    render() {
		return jQuery(classCircleArgs.circlePattern)
            .attr('data-region-id', this.regionId)
            .attr('lat', this.crd.latitude)
            .attr('long', this.crd.longtitude)
            .attr('radius', this.crd.radius)
            .addClass('rendered')
            .css(this.getCSSPosition());
    }

    getCSSPosition(crd = false) {
		
		if(!crd)
			crd = this.crd;
		
		let radiusHorizontalPercent = crd.radius * 100 / ukraineEdges.width,
		radiusVerticalPercent = crd.radius * 100 / ukraineEdges.height;
        return {
            'top': ((crd.latitude) - this.ukraineEdges.north) * 100 / (this.ukraineEdges.south - this.ukraineEdges.north) - (radiusVerticalPercent/2) + '%',
            'left': ((crd.longtitude) - this.ukraineEdges.west) * 100 / (this.ukraineEdges.east - this.ukraineEdges.west) - (radiusHorizontalPercent/2) + '%',
			'width': `${radiusHorizontalPercent}%`,
			'height': `${radiusVerticalPercent}%`
        }
    }

    append() {
        this.render().appendTo(window.mapWrapper);
    }

    static reInitiateAll() {
        var instance = this;
        jQuery('.region-circle[lat][long][radius]').each(function () {
            var circle = jQuery(this);
            circle.css(instance.getCSSPosition({
                'lat': circle.attr('lat'),
                'long': circle.attr('lat'),
				'radius': circle.attr('radius'),
            }))
        })
    }

}
