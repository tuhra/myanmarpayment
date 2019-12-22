$(document).ready(function(){

    $("#showless").css("display", "none");

    $('#number').intlTelInput({
        geoIpLookup: function(callback) {
            $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        initialCountry: "auto",
        separateDialCode: true,
        utilsScript: "intl-tel-input/build/js/utils.js"
    });


    $(document).on('submit', '#setup', function (event) {
        event.preventDefault();
        var sel=$('div.selected-dial-code').text();
        $('input[name=country_code]').val(sel);
        window.location.href = "https://www.accountkit.com/v1.0/basic/dialog/sms_login?" + $( this ).serialize();
    });


   /**
   *Language Change Ajax
   */

    $(document).on('change', '#language', function () {
        var locale=$(this).val();
        var _token=$("input[name=_token]").val();
        var url=$(this).attr('data-url');
        $.ajax({
            url:url,
            type:"POST",
            data:{locale:locale,_token:_token},
            datatype:'json',
                success:function(data){
                },
                beforeSend:function(){
                },
                complete:function(data){
                   window.location.reload(true);
                }
          });
    });

    $(document).on('click', '#resent', function () {
        $.ajax
        ({
            type: "GET",
            url: "/telenor-otp-resent",
            success: function(response) {
                if (response.status_code === 200) {
                    window.location.href = "/telenor-otp";
                }
            }
        });
    })

    $(document).on('click', '#mpt-resend', function () {
        var url = $('#mpturl').attr('data-url');
        $.ajax({
            type : "GET",
            url : url,
            success: function(response) {
                if (response.status_code === 200) {
                    window.location.href = "/mpt/otp";
                }
            }
        })
    });

    $(document).on('click', '#showall', function () {
        $("#full").css("display", "block");
        $("#showall").css("display", "none");
        $("#showless").css("display", "block");
    })

    $(document).on('click', '#showless', function () {
        $("#full").css("display", "none");
        $("#showall").css("display", "block");
        $("#showless").css("display", "none");
    });

/**
 * Terms and condition button
 */

$('#termscheck').css('opacity', "0.5");
$('#terms').click(function(){
    if($(this).prop('checked') == true){
        $('input[type="submit"]').prop('disabled', false);
        $('#termscheck').css('opacity', "1");
    } else { 
        $('input[type="submit"]').prop('disabled', true);
        $('#termscheck').css('opacity', "0.5");
    }
 });

});
/**
* onclick country code value catch
*/
function flag_box_click(event) {
    var sel=$(event).children('span.dial-code').html();
    $('input[name=country_code]').val(sel);
}

/**
* Different country sample number
*/
function sample_number(country_code) {
    var numbers={'+880':'1847052192'};
    if ((country_code in numbers)) {
        return numbers[country_code];
    }
    return "Enter Your Mobile NO";
}






