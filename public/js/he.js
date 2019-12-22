
window.addEventListener("DOMContentLoaded", function (event) {

    $('#loadingDiv').show(); 

    var referenceId = makeid()+Date.now();
    var acrurl = "http://acr.telenordigital.com/partner/acr/create?partnerId=miaki&operatorId=TLN-MM&redirect=true&referenceId="+referenceId
    loadhe(referenceId, acrurl);

    function makeid() {
       var text = "";
       var possible = "01234567899876543210";

       for (var i = 0; i < 5; i++)
         text += possible.charAt(Math.floor(Math.random() * possible.length));

       return text;
    }

    function loadhe(referenceId, acrurl) {

        // var settings = {
        //     'cache': false,
        //     'dataType': "jsonp",
        //     "async": true,
        //     "crossDomain": true,
        //     "url": acrurl,
        //     "method": "GET",
        //     "headers": {
        //         "Access-Control-Allow-Origin":"*"
        //     }
        // }

        $.ajax({
            'cache': false,
            'dataType': "jsonp",
            "async": true,
            "crossDomain": true,
            "url": acrurl,
            "method": "GET",
            "headers": {
                "Access-Control-Allow-Origin":"*"
            },
            success : function (data, textStatus, xhr) {
                if (xhr.status === 200) {
                    CustomTimeOut();
                }
            },
            complete: function(xhr, textStatus) {
                if (xhr.status === 200) {
                    CustomTimeOut();
                }
            }
        });
    }

    function CustomTimeOut() {
        setTimeout(function () {
            var subscribeurl=$('#url').attr('data-url');
            hesubscribe(referenceId, subscribeurl, acrurl);
        }, 3000)
    }

    function hesubscribe(referenceId, url, acrurl) {

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            type: 'POST',
            data: {_token: CSRF_TOKEN, refId: referenceId, acrurl: acrurl},
            dataType: 'JSON',
            success: function (response) { 
                console.log(response)
                if (response['status'] === false) {
                    window.location.href = "/telenor";
                } else {
                    window.location.href = response['callback'];
                }
            }
        });
    }

});










