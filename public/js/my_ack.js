

AccountKit_OnInteractive = function() {
    AccountKit.init({
        appId: '1692195921084530',
        state: document.getElementById('_token').value,
        version: 'v1.0',
        debug: true
    });
};

function loginCallback(response) {
    
    if (response.status === "PARTIALLY_AUTHENTICATED") {
        $('#loadingDiv').show();
        document.getElementById('code').value = response.code;
        document.getElementById('_token').value = response.state;
        document.getElementById('form').submit();
    }

    else if (response.status === "NOT_AUTHENTICATED") {
        // handle authentication failure
        swal(
          'Error',
          'You are not Authenticated!',
          'error'
        )

    }
    else if (response.status === "BAD_PARAMS") {
        // handle bad parameters
        swal(
          'Error',
          'Wrong Input!',
          'error'
        )
    }
}

function smsLogin() {

    var countryCode = document.getElementById('country_code').value;
    var phoneNumber = document.getElementById('number').value;

    var prefix = phoneNumber.substring(0,1);
    
    if (prefix != 0) {
        phoneNumber = 0 + phoneNumber;
    }

    var valid_number = phoneNumber.substring(2,3);

    if (valid_number == 7) {
        AccountKit.login(
            'PHONE',
            {countryCode: countryCode, phoneNumber: phoneNumber},
            loginCallback
        );
    } else {
        swal(
            'Sorry...',
            'GoGames service is only available on Telenor network'
        )
        document.getElementById('number').value = "";
    }
}


// email form submission handler
// function emailLogin() {
//   var emailAddress = document.getElementById("email").value;
//   AccountKit.login('EMAIL', {emailAddress: emailAddress}, loginCallback);
// }





