// Javascript module wrapping the app
var route = (function () {
  
  // Basic vars
  var map, flightPath, i;
  var locations = [];
  var current = null;

  // Initialize App
  var initialize = function (posts) {

    // Prepare data
    for (i = 0; i < posts.length; i++) {
      
      // Instanciate new location object
      var newLocation = new Location(posts[i]);
      locations.push(newLocation);
    }

    // Sort locations by arrival date and not by the postID
    locations.sort(function(a, b) {
      if (a.arrivalDateSort > b.arrivalDateSort) {
        return -1;
      }
      if (a.arrivalDateSort < b.arrivalDateSort) {
        return 1;
      }
      return 0;
    });

    // Debugging Log
    console.log(locations);

    // Set up the map
    var mapOptions = {
      zoom: 5,
      center: locations[0].position
    };
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    // Event handler: Close info box when clicked somewhere on the map
    google.maps.event.addListener(map, "click", function(event) {
      if(current) {
        current.closeInfoBox();
      }
    });

    // Set up the markers and add the index to the object after sorting
    for (i = 0; i < locations.length; i++) {
      locations[i].addToMap();
      locations[i].ind = i;
    }

    // Draw Flight Path
    var flightPlanCoordinates = [];
    for (i = 0; i < locations.length; i++) {
      flightPlanCoordinates.push(locations[i].position);
    }

    flightPath = new google.maps.Polyline({
      path: flightPlanCoordinates,
      geodesic: false,
      strokeColor: '#FF0000',
      strokeOpacity: 1.0,
      strokeWeight: 2
    });

    flightPath.setMap(map);

    // Open Infobox for current position
    if(!current){
      current = locations[0];
      current.openInfoBox();
      map.panTo(current.position);
    }

    // Create an array of distinct countries & cities throughout all locations
    var countries = [];
    var cities = [];
    for (i = 0; i < locations.length; i++) {
      if (countries.indexOf(locations[i].country) === -1) {
        countries.push(locations[i].country);
      }
      if (cities.indexOf(locations[i].city + locations[i].country) === -1) {
        cities.push(locations[i].city + locations[i].country);
      }
    }

    // Insert content in stats box
    document.getElementById("stats").innerHTML = "<strong>Distance: </strong>" + getTotalTravelDistance() + "km<br /><strong>Locations: </strong>" + cities.length + "<br /><strong>Countries: </strong>" + countries.length;
  };

  // Toggle Route (Flightpath Visibility on/off)
  var toggleRouteVisibility = function() {
    if(flightPath.getVisible()) {
      flightPath.setVisible(false);
    } else {
      flightPath.setVisible(true);
    }
  };

  // Pan to and open next location
  var next = function(){
    current.closeInfoBox();
    // If newest location is selected, start with the oldest one
    if(current === locations[0]) {
      current = locations[locations.length-1];
    }
    else {
      current = locations[current.ind-1];
    }
    current.openInfoBox();
    map.panTo(current.position);
  };

  // Pan to and open previous location 
  var prev = function(){
    current.closeInfoBox();
    // If oldest location is selected, start with newest one
    if(current === locations[locations.length-1]) {     
      current = locations[0];
    }
    else {   
      current = locations[current.ind+1];
    }
    current.openInfoBox();
    map.panTo(current.position);
  };

  // Location object constructor:
  var Location = function(post) {
    
    // Giving the location some base date
    this.city = post.city;
    this.country = post.country;
    this.arrivalDate = post.arrival_date;
    this.arrivalDateSort = post.arrival_date.substring(6,10) + post.arrival_date.substring(3,5) + post.arrival_date.substring(0,2);
    this.departureDate = post.departure_date;
    this.post_link = post.post_link;
    this.photo_link = post.photo_link;
    this.position = new google.maps.LatLng(post.latitude, post.longitude);
    this.lat = post.latitude;
    this.lng = post.longitude;

    // Setting accomoation data
    this.accomodation = {};
    this.accomodation.name = post.accomodation_name;
    this.accomodation.link = post.accomodation_link;

    // Map stuff:
    this.marker = null;
    this.ib = null;

  };

  // Create marker on map
  Location.prototype.addToMap = function(){
    this.marker = new google.maps.Marker({
      position: this.position,
      map: map
    });

    // Preserve context for event handler:
    var self = this;

    // Event Handler: First close current opne box, then open the new one
    google.maps.event.addListener(self.marker, 'mouseover', function() {
      if(current) current.closeInfoBox();
      self.openInfoBox();
      // Save location whose infobox just opened
      current = self;
    });

    // Event Handler: Pan to location on click
    google.maps.event.addListener(self.marker, 'click', function() {
      map.panTo(current.position);
    });

  };

  // Create open info box
  Location.prototype.openInfoBox = function(){
    
    // Only create a new infobox if this location object doesnt have one already
    if(!this.ib){

      // Create HTML and CSS
      var boxText = document.createElement("div");
      boxText.style.cssText = "border: none; background: url('./wp-content/themes/atwt/img/slider-bg.png') repeat scroll left top transparent; text-align:left; border-radius: 1em; padding: 5px; color: white; height: 120px; width: 180px";
      
      // Show a different html for the very first location:
      if(this.departureDate !== locations[locations.length-1].departureDate){
        // Add some vars for the cases that no links to photos or accomodations is given to disable the buttons
        var noPhotoLink = "";
        var noAccomodationLink = "";
        if (this.photo_link === "") { noPhotoLink = " disabled"; }
        if (this.accomodation.link === "") { noAccomodationLink = " disabled"; }
        boxText.innerHTML = "<h3>"+this.city+"</h3><h5 class='country'>"+this.country+"</h5><h5 class='centerme'><strong>"+this.arrivalDate+" to "+this.departureDate+"</strong></h5><div class='centerme'><a class='btn btn-primary btn-sm" + noPhotoLink + "' href='"+this.photo_link+"' target='_self' class='centerme'>Photos</a>&nbsp;&nbsp;&nbsp;<a class='btn btn-primary btn-sm" + noAccomodationLink + "' href='"+this.accomodation.link+"' target='_blank' class='centerme'>Housing</a></div>";
      } else {
        boxText.innerHTML = "<h3>"+this.city+"</h3><h5 class='country'>"+this.country+"</h5><h5 class='centerme'><strong class='inlineme'>Departure: </strong>"+this.departureDate+"</h5>";
      }

      // Info box options
      var myOptions = {
        content: boxText,
        disableAutoPan: false,
        maxWidth: 0,
        pixelOffset: new google.maps.Size( -140, 0 ),
        zIndex: null,
        closeBoxURL: "",
        boxStyle: { width: "280px" },
        infoBoxClearance: new google.maps.Size( 1, 1 ),
        isHidden: false,
        pane: "floatPane",
        enableEventPropagation: false
      };

      // Instantiate InfoBox object
      this.ib = new InfoBox(myOptions);

    }

    // Open InfoBox:
    this.ib.open(map, this.marker);
  };

  // Close the info box
  Location.prototype.closeInfoBox = function(){
    if(this.ib){
      this.ib.close();
    }
  };

  // Function that calculates the distance between two coordinates in rounded metres via the Haversine formula
  // Checkout: http://en.wikipedia.org/wiki/Haversine_formula
  var getDistance = function(p1, p2) {
    var rad = function(x) {
      return x * Math.PI / 180;
    };
    var R = 6378137; // Earth’s mean radius in meter
    var dLat = rad(p2.lat - p1.lat);
    var dLong = rad(p2.lng - p1.lng);
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(rad(p1.lat)) * Math.cos(rad(p2.lat)) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d; // returns the distance in meter
  };

  // Function that calculates the total travelled distance between all locations
  var getTotalTravelDistance = function() {
    var totalTravelDistance = 0;
    var i = 0;
    for (i = 0; i < locations.length-1; i++) {
      var p1 = {lat:locations[i].lat,lng:locations[i].lng};
      var p2 = {lat:locations[i+1].lat,lng:locations[i+1].lng};
      totalTravelDistance += Math.round(getDistance(p1,p2));
    }
    totalTravelDistance = Math.round(totalTravelDistance / 1000);
    return totalTravelDistance;
  };

  // Returning properties to be accessible from outside the module
  return {
    initialize: initialize,
    toggleRouteVisibility: toggleRouteVisibility,
    next: next,
    prev: prev
  };

}());