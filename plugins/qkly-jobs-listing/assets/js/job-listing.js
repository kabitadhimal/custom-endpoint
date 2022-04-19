/**
 * Prevent dropdown autoclose inside the dropdown
 */
/*jQuery('.js-prevent-close .dropdown-menu').each(function() {
    jQuery(this).on('click', function(e) {
        e.stopPropagation();
    });
});*/

var Qkly_Utility = {
    serializeObject: function(obj) {
        var str = [];
        for (var p in obj)
            if (obj.hasOwnProperty(p)) {
                str.push(p + "=" + obj[p]);
            }
        return str.join("&");
    },
    jsonCopy: function(src){
        return JSON.parse(JSON.stringify(src));
    }
}
/*
 * Opens and close the modal
 *  QklyModal object is created inside the assets/js/qkly-js-modal.js
 */
var qklyModalEvent = {
    click : function() {
        var modalButtons = document.querySelectorAll(".qkly-modal-open");
        var modalCloseButtons = document.querySelectorAll(".qkly-modal-close");
        //var modal = document.querySelector('.qkly-js-modal');
        if (modalButtons) {
            modalButtons.forEach(function (modalButton) {
                modalButton.addEventListener('click', function (event) {
                  //  console.log(event.target, event.currentTarget)
                    var modalId = event.currentTarget.getAttribute('data-id');
                    var $modal = document.querySelector(modalId);

                    QklyModal.open($modal);
                })
            });
        }
        if (modalCloseButtons) {
            modalCloseButtons.forEach(function (modalCloseButton) {
                modalCloseButton.addEventListener('click', function (e) {
                    QklyModal.close();
                });
            });
        }
    }
}

var QklyJobSearch = function(pageUrl, serviceUrl, initialData){
    this._pageUrl = pageUrl;
    this._serviceUrl = serviceUrl;
    this._data = {};
    //this._data = initialData;

    this._resultContainer = document.querySelector('.qkly-js-result');
    this._paginationContainer = document.querySelector('.qkly-js-pagination');
    this._notFound = document.querySelector('.qkly-not-found');
    // this._resultPlaceContainer = document.querySelector('.js-place');

    //  this._mapContainer = document.querySelector('.js-map-block');
    //filter initial data
    if(initialData){
        for(var key in initialData){
            if(initialData[key] !== null){
                this._data[key] = initialData[key];
            }
        }
    }


    this.update = function(key, data, skipSubmit){
        skipSubmit = skipSubmit || false;

        this._data[key] = data;
        if(data == "" || data === null){
            delete this._data[key];
        }

        //reset pagination when main filters are changed
        if(key === 'location'
            //   || key == 'radius'
            || key === 'searchText'
            // || key =='place'
            || key ==='isRemote'

            || key ==='isSalary'
        ){
            delete this._data['currentPage'];
            //  delete this._data['bounds'];
            // delete this._data['zoom'];
        }

        ///  console.log("I am si=kip"+skipSubmit);

        if(skipSubmit === true){
            //
        }else{
            this.submit();
        }
    };

    this.submit = function(){
        this._buildPageUrl();
        this.getJobs();
    };

    /**
     * /[category]/[place]
     * /all/[place]
     * @private
     */
    this._buildPageUrl = function() {
        var params = Qkly_Utility.jsonCopy(this._data);

        //remove defafult values
        //   if(params.radius == 2) delete params['radius'];
        if(params.currentPage == 1) delete params['currentPage'];
        //  if(params.zoom == 0) delete params['zoom'];

      //  console.log(params);

        var pageUrl = this._pageUrl;
        var queryStrings = Qkly_Utility.serializeObject(params);
        if(queryStrings) pageUrl += "?" + queryStrings;

        history.pushState([], "", pageUrl);
    };

    /*
    this.addEventsToPropertyCard = function(){
        var cards = this._resultContainer.querySelectorAll('.js-property');
        if(cards.length < 1) return;

        cards.forEach(function(card){
            card.addEventListener('mouseenter', function(event){
                var id = event.currentTarget.getAttribute('data-id');
                listingMap.showPropertyPopupById(id);
            });
        });
    };
     */

    this.getJobs = function(){
        var param = Qkly_Utility.serializeObject(this._data);

        //encode params so that older browsers can handle utf gracefully...
        param = encodeURI(param);
        var xhr = new XMLHttpRequest();

        // Setup our listener to process completed requests
        //TODO use fetch
        xhr.onload = function () {
            // Process our return data
            if (xhr.status >= 200 && xhr.status < 300) {
                //this._resultContainer.innerHTML = xhr.responseText;
                this._resultContainer.classList.remove('qkly-is-loading');
                var result = JSON.parse(xhr.responseText);
               // console.log(result.content.length);
                if(result.content.length > 0 ) {
                    this._resultContainer.innerHTML = result.content;
                    this._notFound.style.display = "none";
                    //this._notFound.classList.remove('not-found');
                    //this._notFound.style.display = "none";
                    //this._mapContainer.style.removeProperty('display');

                     this._paginationContainer.style.removeProperty('display');
                    this._paginationContainer.innerHTML = result.pagination;
                    /* Click event for modal */
                    qklyModalEvent.click();
                    //Updates the current page
                     this.addEventsToPagination();
                } else {

                  /*  this._resultContainer.innerHTML = '<span class="text-center">No Jobs founds!</span>';
                    this._resultContainer.classList.add('not-found');*/
                    this._resultContainer.innerHTML ="";
                    //this._notFound.style.removeProperty('display');

                    this._notFound.style.display = "block";


                    //this._mapContainer.style.display = "none";
                    //  this._resultCountContainer.innerHTML = result.resultCount;
                    //this._notFound.style.removeProperty('display');
                    this._paginationContainer.style.display = "none";
                    //this._resultPlaceContainer.style.display = "none";
                }


                //set click handler for fav btn

                //this.addEventsToPropertyCard();
            } else {
                console.log('error');
            }
        }.bind(this);

        xhr.open('GET', this._serviceUrl+"?action=jobs&"+param);
        //xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
        //xhr.setRequestHeader("ContentType","application/x-www-form-urlencoded; charset=UTF-8");

        //add loader
        this._resultContainer.classList.add('qkly-is-loading');
      //  this._resultContainer.innerHTML="";

        xhr.send();
    };

    /*
     * Updates the current page
     */
    this.addEventsToPagination = function(){
        var links = document.querySelectorAll('.qkly-js-pagination li a');
        if(!links.length) return;
        links.forEach(function(link){
            link.addEventListener('click', function(event){
                event.preventDefault();
                var value = event.currentTarget.getAttribute('data-value');
                this.update('currentPage', value);
                //scroll page to the top
          /*      setTimeout(function(){
                    this._resultContainer.scrollIntoView({ behavior: 'smooth' });
                }.bind(this), 2000);*/
               // this._resultContainer.scrollIntoView({ behavior: 'smooth' });
            }.bind(this));
        }.bind(this));
    };
};