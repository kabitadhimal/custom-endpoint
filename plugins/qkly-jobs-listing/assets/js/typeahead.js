(function($){
    var locations = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('freeFormAddress'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
      //  prefetch: '../data/films/post_1960.json',
        remote: {
            url: qklyParams.ajaxurl+'?action=qkly_location&location=%_location_',
            wildcard: '%_location_'
        }
    });

    var locationField = document.querySelector("#qkly-job-search [name='location']");

    /**
     * https://twitter.github.io/typeahead.js/
     *
     */
    var locationTypeAhead = $(locationField).typeahead({
        minLength: 3,
        highlight: true
    }, {
        display: function(item){
            //document.getElementById('location_id').value = item.country;
            return (item.state == null)?item.country:item.state;
        },
        source: locations,
        //minLength: 3,
        highlight: true,
        limit: 10,
        templates:{
            suggestion: function(item){
                    return (item.state != null) ?"<div>" + item.state + " - " + item.country + "</div>":"<div></div>";
            },
            notFound: function (){
                return "<div class='qkly-location-not-found'>not found!</div>";
            },
            empty: function (){
                return '<div>No found</div>';
            }
        }
    });

/*    locationTypeAhead.bind('typeahead:select', function(ev ,suggestion ,flag ,dataset) {
        window.testEv = ev;
        console.log(suggestion);
        console.log(dataset);
    })*/;
     /*
      * removing the empty div from the list of suggested location
      */
    locationTypeAhead.bind('typeahead:asyncreceive', function(ev ,query ,dataset) {
        const selectableDiv = document.querySelectorAll('div.tt-selectable');
        selectableDiv.forEach(selectableDiv => {
            if (selectableDiv.innerHTML === '' || selectableDiv.textContent === '') {
                selectableDiv.remove();
            }
        });
    });




/*
    const divs = document.querySelectorAll('div.tt-selectable');
    divs.forEach(div => {
        if (divs.innerHTML === '' || div.textContent === '') {
            div.remove();
        }
    });
*/

    $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
        console.log('Selection: ' + suggestion);
    });


})(jQuery);