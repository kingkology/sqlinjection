/*
Welcome to the kings library.
This is a dynamic library to take care of all front end to backend communications. 
it works with 
1. json
2. temporal storage
3. sweet alert2
4. jquery toaster and the jquery library

Load all libraries before loading this library. NB: jquery first

Feel free to contribute to it on github @ https://github.com/kingkology/kingslibrary
*/


/*
Note that all responses received from the backend should be in json format.

{"status":201,"status_message":"Category Added Successfully","data":null}

1. status: status code of response
2. status_message: message to display along with the response code
3. data: this can be any form of data that you want to return. This but json is prefered {"message1":"Hello","message2":"World"}

*/






/*
jquery toaster library instantiation
NB: include toater library to use this
*/
function Toastz(x, y, z) 
{
    var priority = x;
    var title = y;
    var message = z;

    $.toaster({ priority: priority, title: title, message: message });
}


/*
function for creating a button with specified parameters
1. a: Display text
2. b: ID for the button
3. c: Name for the button
4. d: Other properties for the button like css and other button properties

*/
function create_button(a, b, c, x, y) 
{
    let button_text = a; 
    let button_id = b; 
    let button_name = c; 
    let other_properties = x;

    let the_button = "";
    the_button = the_button + `<button name="${button_name}" id="${button_id}" ${other_properties} >${button_text}</button>`;

    return the_button;
}


//login function
/*
1. url: this refers to the url/controller/api for processing user login credentials.
2. uname: this refers to the id of the login username/pin/email element
3. pword: this refers to the id of the login password element

*/
function login(url, uname, pword) 
{
    let response = {};
    let username = $('#' + uname).val();
    let password = $('#' + pword).val();
    let login_api = url;

    //check for empty values
    if (username == "") {
        Toastz('warning', "Error: ", "Please Fill In User ID");
        return;
    }

    if (password == "") {
        Toastz('warning', "Error: ", "Please Fill In Password");
        return;
    }


    let data = {... '', "username": username, "password": password };

    //initialise loading
    Swal.fire({
        title: 'Checking Login Credentials',
        allowOutsideClick: false,
        html: '<p><center>Please wait...</center></p>',
        didOpen: () => {
            Swal.showLoading()
        },
        showClass: {
            popup: 'animate__animated animate__bounceInLeft'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    })

    //send values to api
    $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: login_api, // the url where we want to POST
            data: data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            async: true
        })
        // using the done promise callback
        .done(function(data, textStatus, jqXHR) {
            // receive the response after sending the request
            Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);

            if (response.status == "200" || response.status == "201") {

                //console.log(response);
                Toastz('success', 'Login Successful', '');
                $('#' + uname).val("");
                $('#' + pword).val("");

                //redirect to homepage
                if (!(response.data == "")) {
                    window.location.href = response.data;
                } else {
                    Toastz('danger', 'Loin error: ', 'Redirect url not specified');
                }

            } else {
                Swal.fire("Error", response.status_message, "error");
            }
            return;
        })
        // using the fail promise callback
        .fail(function(jqXHR, textStatus, errorThrown) {
            //Server failed to respond - Show an error message
            Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);
            Toastz("danger", "Error", response.status_message);
            return;

        });
}



//logout function
/*
1. url: this refers to the url/controller/api for processing user logout.

*/
function logout(url) {
    let login_api = url;

    //initialise loading
    Swal.fire({
        title: 'Dumping login session',
        allowOutsideClick: false,
        html: '<p><center>Please wait...</center></p>',
        didOpen: () => {
            Swal.showLoading()
        },
        showClass: {
            popup: 'animate__animated animate__bounceInLeft'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    })

    //send values to api
    $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: login_api, // the url where we want to POST
            data: "", // our data object
            dataType: 'json', // what type of data do we expect back from the server
            async: true
        })
        // using the done promise callback
        .done(function(data, textStatus, jqXHR) {
            // receive the response after sending the request
            Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);

            if (response.status == "200" || response.status == "201") {

                //redirect to login page
                if (!(response.data == "")) {
                    window.location.href = response.data;
                } else {
                    Toastz('danger', 'Logout error: ', 'Redirect url not specified');
                }

            } else {
                Swal.fire("Error", response.status_message, "error");
            }

            return;
        })
        // using the fail promise callback
        .fail(function(jqXHR, textStatus, errorThrown) {
            //Server failed to respond - Show an error message
            Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);
            Swal.fire("Error", response.status_message, "error");
            return;

        });
}



/*
This function accepts password data and sends it to your stated api for processing. It takes 1 argument
1. url : the api to receive the password values

*/

function force_pwd_change(url="") {
    let response = [];

    

    Swal.fire({
        position: 'center',
        title: 'Change Default Password',
        html: '<div class="row">' +
            '<div class="col-md-12 col-l-12 col-xl-12"><div class="alert alert-danger fade show  animate__animated  animate__fadeInTopLeft" role="alert">Note: It is recommended that you change your password every 3 months and old passwords should not be reused for a period of 9 months.<br> Password should follow the format below<br>Add 9 charachters or more, lowercase letters, uppercase letters, numbers and symbols to make the password really strong!</div><br>' +
            '<div class="position-relative form-group"><label for="old_password" class="">Old Password</label><input name="old_password" id="old_password" placeholder="Old password" type="password" class="form-control"><a href="javascript:void(0)" onclick=toggle_pwd("old_password","toggle_pwd1")><i id="toggle_pwd1" class="fa fa-eye-slash" aria-hidden="true"></i></a></div>' +
            '<div class="position-relative form-group"><label for="new_password" class="">New Password</label><input aria-describedby="passwordHelp" name="new_password" id="new_password" placeholder="New password" type="password" class="form-control password-strength__input" onkeyup="pwd_strength();checknewpass(\'new_password\',\'confirm_password\',\'pwdmess\')"><a href="javascript:void(0)"><i id="toggle_pwd2" class="fa fa-eye-slash" aria-hidden="true"  onclick=toggle_pwd("new_password","toggle_pwd2")></i></a></div>' +
            '<div class="password-strength__bar-block progress mb-4"><div class="password-strength__bar progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>' +
            '<div class="position-relative form-group"><label class="" style="color:red" id="pwdmess"></label></div>' +
            '<div class="position-relative form-group"><label for="confirm_password" class="">Confirm Password</label><input name="confirm_password" id="confirm_password" placeholder="Confirm password" type="password" class="form-control" onkeyup="checknewpass(\'new_password\',\'confirm_password\',\'pwdmess\')"><a href="javascript:void(0)" onclick=toggle_pwd("confirm_password","toggle_pwd3")><i id="toggle_pwd3" class="fa fa-eye-slash" aria-hidden="true"></i></a></div>' +
            '</div>' +
            '</div>',
        allowOutsideClick: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Change',
        showClass: {
            popup: 'animate__animated animate__zoomInUp'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
        /*showLoaderOnConfirm: true,*/
    }).then((result) => {
        if (result.isConfirmed) {

            {
            if (url=="") 
                Toastz("danger", "Error", "Invalid url");
                return;
            }

            let old_password = $("#old_password").val();
            let new_password = $("#new_password").val();
            let confirm_password = $("#confirm_password").val();

            if (old_password=="") {Toastz("danger", "Empty value", "Old password is required");force_pwd_change(url);return;}
            if (new_password=="") {Toastz("danger", "Empty value", "New password is required");force_pwd_change(url);return;}
            if (confirm_password=="") {Toastz("danger", "Empty value", "Password confirmation is required");force_pwd_change(url);return;}
            if (!(new_password===confirm_password)) {Toastz("danger", "Password mismatch", "New password and confirm passowrds do not match");force_pwd_change(url);return;}

            //initialise loading
            Swal.fire({
                title: 'Checking Credentials',
                allowOutsideClick: false,
                html: '<p><center>Please wait...</center></p>',
                didOpen: () => {
                    Swal.showLoading()
                },
                showClass: {
                    popup: 'animate__animated animate__bounceInLeft'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })

            $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: url, // the url where we want to POST
                    data: { 'old_password': old_password, 'new_password': new_password, 'confirm_password': confirm_password }, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    async: true
                })
                // using the done promise callback
                .done(function(rs, textStatus, jqXHR) {
                    // nreceive the response after sending the request
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);

                    //Swal.fire("Success", response.status_message, "success");
                    Toastz('success', response.status, response.status_message);
                    window.location.reload(true);
                    return;

                })
                // using the fail promise callback
                .fail(function(jqXHR, textStatus, errorThrown) {
                    //Server failed to respond - Show an error message
                    response = jqXHR.responseText;
                    response = JSON.parse(response);
                    Toastz('danger', response.status, response.status_message);
                    force_pwd_change(url);
                    return;
                });
        } 
    });
}



//Change password
/*
This function accepts password data and sends it to your stated api for processing. It takes 1 argument
1. url : the api/link to receive the password values

*/
function change_pwd(url="") {
    let response = [];



    Swal.fire({
        position: 'top-end',
        title: 'Change Password',
        html: '<div class="row">' +
            '<div class="col-md-12 col-l-12 col-xl-12"><div class="alert alert-danger fade show  animate__animated  animate__fadeInTopLeft" role="alert">Note: It is recommended that you change your password every 3 months and old passwords should not be reused for a period of 9 months.<br> Password should follow the format below<br>Add 9 charachters or more, lowercase letters, uppercase letters, numbers and symbols to make the password really strong!</div><br>' +
            '<div class="position-relative form-group"><label for="old_password" class="">Old Password</label><input name="old_password" id="old_password" placeholder="Old password" type="password" class="form-control"><a href="javascript:void(0)" onclick=toggle_pwd("old_password","toggle_pwd1")><i id="toggle_pwd1" class="fa fa-eye-slash" aria-hidden="true"></i></a></div>' +
            '<div class="position-relative form-group"><label for="new_password" class="">New Password</label><input aria-describedby="passwordHelp" name="new_password" id="new_password" placeholder="New password" type="password" class="form-control password-strength__input" onkeyup="pwd_strength();checknewpass(\'new_password\',\'confirm_password\',\'pwdmess\')"><a href="javascript:void(0)"><i id="toggle_pwd2" class="fa fa-eye-slash" aria-hidden="true"  onclick=toggle_pwd("new_password","toggle_pwd2")></i></a></div>' +
            '<div class="password-strength__bar-block progress mb-4"><div class="password-strength__bar progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>' +
            '<div class="position-relative form-group"><label class="" style="color:red" id="pwdmess"></label></div>' +
            '<div class="position-relative form-group"><label for="confirm_password" class="">Confirm Password</label><input name="confirm_password" id="confirm_password" placeholder="Confirm password" type="password" class="form-control" onkeyup="checknewpass(\'new_password\',\'confirm_password\',\'pwdmess\')"><a href="javascript:void(0)" onclick=toggle_pwd("confirm_password","toggle_pwd3")><i id="toggle_pwd3" class="fa fa-eye-slash" aria-hidden="true"></i></a></div>' +
            '</div>' +
            '</div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Change',
        cancelButtonText: "Cancel",
        /*showLoaderOnConfirm: true,*/
    }).then((result) => {
        if (result.isConfirmed) {

            if (url=="") 
            {
                Toastz("danger", "Error", "Invalid url");
                return;
            }

            let old_password = $("#old_password").val();
            let new_password = $("#new_password").val();
            let confirm_password = $("#confirm_password").val();

            if (old_password=="") {Toastz("danger", "Empty value", "Old password is required");change_pwd(url);return;}
            if (new_password=="") {Toastz("danger", "Empty value", "New password is required");change_pwd(url);return;}
            if (confirm_password=="") {Toastz("danger", "Empty value", "Password confirmation is required");change_pwd(url);return;}
            if (!(new_password===confirm_password)) {Toastz("danger", "Password mismatch", "New password and confirm passowrds do not match");change_pwd(url);return;}

            //initialise loading
            Swal.fire({
                title: 'Checking Credentials',
                allowOutsideClick: false,
                html: '<p><center>Please wait...</center></p>',
                didOpen: () => {
                    Swal.showLoading()
                },
                showClass: {
                    popup: 'animate__animated animate__bounceInLeft'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })

            $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: url, // the url where we want to POST
                    data: { 'old_password': old_password, 'new_password': new_password, 'confirm_password': confirm_password }, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    async: true
                })
                // using the done promise callback
                .done(function(rs, textStatus, jqXHR) {
                    // nreceive the response after sending the request
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);

                    Swal.fire("Success", response.status_message, "success");
                    return;

                })
                // using the fail promise callback
                .fail(function(jqXHR, textStatus, errorThrown) {
                    //Server failed to respond - Show an error message
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);
                    Toastz('danger', response.status, response.status_message);
                    change_pwd(url);
                    return;
                });
        } else {
            Swal.fire("Cancelled", "Your password was not modified", "error");
        }
    });
}



//check password match
/*
This function accepts user password inputs and compares them to see if they match and displays reponse if mismatch it to your stated api for processing.
It takes 3 argument
1. x : first password input id
2. y : second password input id
3. z : id of html element to display response

*/
function checknewpass(x, y, z) {
    var pwd1 = document.getElementById(x).value;
    var pwd2 = document.getElementById(y).value;

    if (pwd1 == "" || pwd2 == "") {
        return;
    }

    if (pwd1 != pwd2) {
        document.getElementById(z).innerHTML = '<div class="alert alert-danger" role="alert"><i class="fa fa-frown-o mr-2" aria-hidden="true"></i>Passwords do not match</div>';

    } else {
        document.getElementById(z).innerHTML = "";

    }

}


/*
Show/hide password
This is reliant on the change password function or the force password change function
*/
function toggle_pwd(input_box,toggle_id) 
{
           
            if($('#'+input_box).attr("type") == "text")
            {
                $('#'+input_box).attr('type', 'password');
                $('#'+toggle_id).removeClass( "fa-eye" );
                $('#'+toggle_id).addClass( "fa-eye-slash" );
            }
            else if($('#'+input_box).attr("type") == "password")
            {
                $('#'+input_box).attr('type', 'text');
                $('#'+toggle_id).addClass( "fa-eye" );
                $('#'+toggle_id).removeClass( "fa-eye-slash" );
            }

}



/*
Check password strength
This is reliant on the change password function or the force password change function
It takes no parameter but uses elements with the classes specified in the DOM object in the function
*/

function pwd_strength() {

    DOM = {
      passwForm: '.password-strength',
      passwErrorMsg: '.password-strength__error',
      passwInput: document.querySelector('.password-strength__input'),
      //passwVisibilityBtn: '.password-strength__visibility',
      //passwVisibility_icon: '.password-strength__visibility-icon',
      strengthBar: document.querySelector('.password-strength__bar'),
      //submitBtn: document.querySelector('.password-strength__submit') 
    };

    //*** MAIN CODE

    const getPasswordVal = input => {
      return input.value;
    };

    const testPasswRegexp = (passw, regexp) => {

      return regexp.test(passw);

    };

    const testPassw = passw => {

      let strength = 'none';

      const moderate = /(?=.*[A-Z])(?=.*[a-z]).{9,}|(?=.*[\d])(?=.*[a-z]).{9,}|(?=.*[\d])(?=.*[A-Z])(?=.*[a-z]).{9,}/g;
      const strong = /(?=.*[A-Z])(?=.*[a-z])(?=.*[\d]).{9,}|(?=.*[\!@#$%^&*()\\[\]{}\-_+=~`|:;"'<>,./?])(?=.*[a-z])(?=.*[\d]).{9,}/g;
      const extraStrong = /(?=.*[A-Z])(?=.*[a-z])(?=.*[\d])(?=.*[\!@#$%^&*()\\[\]{}\-_+=~`|:;"'<>,./?]).{9,}/g;

      if (testPasswRegexp(passw, extraStrong)) {
        strength = 'extra';
      } else if (testPasswRegexp(passw, strong)) {
        strength = 'strong';
      } else if (testPasswRegexp(passw, moderate)) {
        strength = 'moderate';
      } else if (passw.length > 0) {
        strength = 'weak';
      }

      return strength;

    };

    const testPasswError = passw => {

      const errorSymbols = /\s/g;

      return testPasswRegexp(passw, errorSymbols);

    };

    const setStrengthBarValue = (bar, strength) => {

      let strengthValue;

      switch (strength) {
        case 'weak':
          strengthValue = 25;
          bar.setAttribute('aria-valuenow', strengthValue);
          break;

        case 'moderate':
          strengthValue = 50;
          bar.setAttribute('aria-valuenow', strengthValue);
          break;

        case 'strong':
          strengthValue = 75;
          bar.setAttribute('aria-valuenow', strengthValue);
          break;

        case 'extra':
          strengthValue = 100;
          bar.setAttribute('aria-valuenow', strengthValue);
          break;

        default:
          strengthValue = 0;
          bar.setAttribute('aria-valuenow', 0);}


      return strengthValue;

    };

    //also adds a text label based on styles
    const setStrengthBarStyles = (bar, strengthValue) => {

      bar.style.width = `${strengthValue}%`;

      bar.classList.remove('bg-success', 'bg-info', 'bg-warning');

      switch (strengthValue) {
        case 25:
          bar.classList.add('bg-danger');
          bar.textContent = 'Weak';
          break;

        case 50:
          bar.classList.remove('bg-danger');
          bar.classList.add('bg-warning');
          bar.textContent = 'Moderate';
          break;

        case 75:
          bar.classList.remove('bg-danger');
          bar.classList.add('bg-info');
          bar.textContent = 'Strong';
          break;

        case 100:
          bar.classList.remove('bg-danger');
          bar.classList.add('bg-success');
          bar.textContent = 'Extra Strong';
          break;

        default:
          bar.classList.add('bg-danger');
          bar.textContent = '';
          bar.style.width = `0`;}


    };

    const setStrengthBar = (bar, strength) => {

      //setting value
      const strengthValue = setStrengthBarValue(bar, strength);

      //setting styles
      setStrengthBarStyles(bar, strengthValue);
    };



    const passwordStrength = (input, strengthBar) => {

      //getting password
      const passw = getPasswordVal(input);

      //check if there is an error
      const error = testPasswError(passw);

      if (!(error)) 
      {

        //finding strength
        const strength = testPassw(passw);

        //setting strength bar (value and styles)
        setStrengthBar(strengthBar, strength);

      }

    };



    //*** EVENT LISTENERS
    DOM.passwInput.addEventListener('input', () => {
      passwordStrength(DOM.passwInput, DOM.strengthBar);
    });



}




function readURL(input, x) {
    let imagehold = x;

    let fileUpload = input;
    if (typeof(fileUpload.files) != "undefined") {
        var size = parseFloat(fileUpload.files[0].size / 1024).toFixed(2);
        if (size > 3000) {
            Swal.fire('', "This image is larger than 3mb.", 'error');
            document.getElementById("mypic").value = "";
            return;
        }
    } else {
        Swal.fire('', "This browser does not support HTML5.", 'error');
    }
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            $('#' + imagehold).attr('src', e.target.result);
            $('#previewimagehold').attr('src', e.target.result);
            $('#pictid').val('filled');
        };

        reader.readAsDataURL(input.files[0]);
    }
}






function ExportToExcel(x) {
    var tablez = x;
    var htmltable = document.getElementById(x);
    var html = htmltable.outerHTML;
    window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
}



function call_pagelet(url, display_location,callback_function,...callback_parameters) {

    //initialise loading
    Swal.fire({
        title: 'Loading page component',
        allowOutsideClick: false,
        html: '<p><center>Please wait...</center></p>',
        didOpen: () => {
            Swal.showLoading()
        },
        showClass: {
            popup: 'animate__animated animate__bounceInLeft'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    })

    $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: url, // the url where we want to POST
            /*data        : formData, // our data object*/
            dataType: 'html', // what type of data do we expect back from the server
            async: true
        })
        // using the done promise callback
        .done(function(rs, textStatus, jqXHR) {

            Swal.close();
            response = jqXHR.responseText;

            // now you state where to display the component
            document.getElementById(display_location).innerHTML = response;
            if (callback_function) 
            {
                let callback_parameter="";
                let count=0;

                for(const your_parameters of callback_parameters )
                {
                    callback_parameter=callback_parameter+"'"+your_parameters+"',";
                }
                callback_parameter=callback_parameter+"1";
                let call_function=callback_function+"("+callback_parameter+")";

                eval(call_function);

                //window[callback_function](callback_parameters.split(','));
            }   

        })
        // using the fail promise callback
        .fail(function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            try
            {
                response = jqXHR.responseText;
                response = JSON.parse(response);
                Swal.fire("Error", response.status_message, "error");
            }
            catch(e)
            {
                response = jqXHR.responseText;
                console.log(e);
            }
            call_default_pagelet(display_location);
            return;
        });
}




//this displays a 404 div incase the specified page is not found
function call_default_pagelet(display_location) {
    /*let the_page = "";
    the_page = "<div class='row'><div class='col-md-12 col-l-12 col-xl-12'> <center><h2>Specified page not found</h2></center> </div></div>";
    document.getElementById(display_location).innerHTML = the_page;
    return;*/

    $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '../404.php', // the url where we want to POST
            /*data        : formData, // our data object*/
            dataType: 'html', // what type of data do we expect back from the server
            async: true
        })
        // using the done promise callback
        .done(function(rs, textStatus, jqXHR) {

            //Swal.close();
            response = jqXHR.responseText;

            // now you state where to display the component
            document.getElementById(display_location).innerHTML = response;
        })
        // using the fail promise callback
        .fail(function(jqXHR, textStatus, errorThrown) {
            //Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);
            Toastz("danger", "Error", response.status_message);
            return;
        });
}




function create_table(url, display_location, start, limit) {

    let response = "";
    let table_header = "";
    let table_body = "";
    let array_response;
    //let old_content = document.getElementById(display_location).innerHTML;
    //document.getElementById(display_location).innerHTML = "";
    //document.getElementById(display_location).innerHTML = "<p><center><i class=\"fa fa-refresh fa-4x fa-spin\"></i></center></p>";

    //initialise loading
    Swal.fire({
        title: 'Fetching data',
        allowOutsideClick: false,
        html: '<p><center>Please wait...</center></p>',
        didOpen: () => {
            Swal.showLoading()
        },
        showClass: {
            popup: 'animate__animated animate__bounceInLeft'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    })

    $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: url, // the url where we want to POST
            data: { start: start, limit: limit }, // our data object*/
            dataType: 'json', // what type of data do we expect back from the server
            async: true
        })
        // using the done promise callback
        .done(function(rs, textStatus, xhr) {
            // nreceive the reference component that will host response and the response after sending the request
            Swal.close();
            let data = rs;
            /*parse response as json object*/
            array_response = (data);
            /*get length of full response object*/
            let response_length = array_response.length;
            /*convert the header index from an object to an array*/
            let header_values = Object.values(array_response[0][0]);
            /*get length of header array*/
            let header_length = header_values.length;
            if (response_length > 1) {
                /*Building table headers from the first array index*/
                table_header = "<thead><tr>";
                for (let i = 0; i < header_length; i++) {
                    table_header = table_header + "<th>" + header_values[i] + "</th>";
                }
                table_header = table_header + "</tr></thead>";
                response = table_header;
                /*Building table body from the rest of the array index*/
                table_body = "<tbody>";
                for (let i = 1; i < response_length; i++) {
                    /*convert the current object index from an object to an array*/
                    let current_index = Object.values(array_response[i]);
                    table_body = table_body + "<tr>";
                    //loop through the td elements
                    for (let j = 0; j < current_index.length; j++) {
                        //convert the td value object to an array inorder to access the properties
                        let td_content = Object.values(current_index[j]);
                        switch (td_content[0]) {
                            case 'text':
                                table_body = table_body + "<td id='" + td_content[2] + "' name='" + td_content[1] + "'>" + td_content[3] + "</td>";
                                break;

                            case 'button':
                                table_body = table_body + "<td>" + create_button(td_content[3], td_content[2], td_content[1], td_content[4]) + "</td>";
                                break;
                        }
                    }
                    table_body = table_body + "</tr>";
                }
                if (start < 1) {
                    let new_start = ((start * 1) + (limit * 1) + (1 * 1));
                    table_body = table_body + '<tr><td colspan=2><button type="button" class="btn btn-rounded btn-icon" style="color:white;background:blue;" onclick="create_table(\'' + url + '\',\'' + display_location + '\',' + new_start + ',' + limit + ')"><i class="fa fa-arrow-right" style="color:white"></i></button></tr></td></tbody>';
                } else {
                    let new_start = ((start * 1) + (limit * 1) + (1 * 1));
                    let old_start = ((start * 1) - (limit * 1) - (1 * 1));
                    table_body = table_body + '<tr><td colspan=2><button type="button" class="btn btn-rounded btn-icon" style="color:white;background:blue;"><i class="fa fa-arrow-left" style="color:white;" onclick="create_table(\'' + url + '\',\'' + display_location + '\',' + old_start + ',' + limit + ')"></i></button><button type="button" class="btn btn-rounded btn-icon" style="color:white;background:blue;" onclick="create_table(\'' + url + '\',\'' + display_location + '\',' + new_start + ',' + limit + ')"><i class="fa fa-arrow-right" style="color:white"></i></button></tr></td></tbody>';
                }

                response = table_header + table_body;
            } else {
                Swal.fire("Error", "No Table Headers Found On Index [0] Of The Returned Data", "error");
                return;
            }
            document.getElementById(display_location).innerHTML = response;
        })
        // using the fail promise callback
        .fail(function(jqXHR, textStatus, errorThrown) {
            //document.getElementById(display_location).innerHTML = old_content;
            //Server failed to respond - Show an error message
            Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);
            Toastz("danger", "Error", response.status_message);
            return;

        });
}



function insert_form(url, form_id,callback_function,...callback_parameters) 
{
    let current_name="";
    var form = $('#' + form_id);
    var the_data = [];
    the_data = form.serializeArray();

    let form_data=new FormData(form[0]);    

    var output = [];

    the_data.forEach(function(item) {
        current_name=item.name;
        var existing = output.filter(function(v, i) {
            return v.name == item.name;
        });
        if (existing.length) {
            var existingIndex = output.indexOf(existing[0]);
            output[existingIndex].value = output[existingIndex].value.concat(item.value);
        } 
        else 
        {
            if (typeof item.value == 'string')
            {
                item.value = [item.value];

                form_data.append(current_name, item.value);
            }
        }
    });

    let inputs=document.getElementById(form_id);
    for (var i = 0; i <inputs.length; i++) 
    {
        if (inputs[i].type.toLowerCase()=='file') 
        {
            if (inputs[i].name) 
            {
                //console.log(inputs[i].name);
                var file=document.forms[form_id][inputs[i].name].files[0];
                //console.log(file.name);
                form_data.append(current_name, $('input[type=file]')[0].files[0]);
            }
        }
    }

    //initialise loading
    Swal.fire({
        title: 'Sending data',
        allowOutsideClick: false,
        html: '<p><center>Please wait...</center></p>',
        didOpen: () => {
            Swal.showLoading()
        },
        showClass: {
            popup: 'animate__animated animate__bounceInLeft'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    })

    /*console.log(output);*/
    $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: url, // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            async: true,
            processData: false, //add this
            contentType: false //and this
        })
        // using the done promise callback
        .done(function(data, textStatus, jqXHR) 
        {
            Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);

            if (response.status == "200" || response.status == "201") 
            {
                Swal.fire("Success", response.status_message, "success");
                if (callback_function) 
                        {
                            let callback_parameter="";
                            let count=0;

                            for(const your_parameters of callback_parameters )
                            {
                                callback_parameter=callback_parameter+"'"+your_parameters+"',";
                            }
                            callback_parameter=callback_parameter+"1";
                            let call_function=callback_function+"("+callback_parameter+")";

                            eval(call_function);

                            //window[callback_function](callback_parameters.split(','));
                        }            
            }
            else 
            {
                Swal.fire("Error", response.status_message, "error");
            }
            
            //CLEAR FORM
            $('#' + form_id)[0].reset();
            return;
        })
        // using the fail promise callback
        .fail(function(jqXHR, textStatus, errorThrown) 
        {
            //Server failed to respond - Show an error message
            Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);
            Swal.fire("Error", response.status_message, "error");
            return;
        });
}



function update_form(url, form_id,callback_function,...callback_parameters) {

    Swal.fire({
        title: 'Are you sure?',
        text: "Dear User, you are about to edit this data.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Accept',
        cancelButtonText: "Decline"
    }).then((result) => {
        if (result.isConfirmed) 
        {

            let current_name="";
            var form = $('#' + form_id);
            var the_data = [];
            the_data = form.serializeArray();

            let form_data=new FormData(form[0]);    

            var output = [];

            the_data.forEach(function(item) {
                current_name=item.name;
                var existing = output.filter(function(v, i) {
                    return v.name == item.name;
                });
                if (existing.length) {
                    var existingIndex = output.indexOf(existing[0]);
                    output[existingIndex].value = output[existingIndex].value.concat(item.value);
                } 
                else 
                {
                    if (typeof item.value == 'string')
                    {
                        item.value = [item.value];

                        form_data.append(current_name, item.value);
                    }
                }
            });

            let inputs=document.getElementById(form_id);
            for (var i = 0; i <inputs.length; i++) 
            {
                if (inputs[i].type.toLowerCase()=='file') 
                {
                    if (inputs[i].name) 
                    {
                        //console.log(inputs[i].name);
                        var file=document.forms[form_id][inputs[i].name].files[0];
                        //console.log(file.name);
                        form_data.append(current_name, $('input[type=file]')[0].files[0]);
                    }
                    
                }
            }

            //initialise loading
            Swal.fire({
                title: 'Sending data',
                allowOutsideClick: false,
                html: '<p><center>Please wait...</center></p>',
                didOpen: () => {
                    Swal.showLoading()
                },
                showClass: {
                    popup: 'animate__animated animate__bounceInLeft'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })

            /*console.log(output);*/
            $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: url, // the url where we want to POST
                    data: form_data, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    async: true,
                    processData: false, //add this
                    contentType: false //and this
                })
                // using the done promise callback
                .done(function(data, textStatus, jqXHR) 
                {
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);

                    if (response.status == "200" || response.status == "201") 
                    {
                        Swal.fire("Success", response.status_message, "success");
                        if (callback_function) 
                        {
                            let callback_parameter="";
                            let count=0;

                            for(const your_parameters of callback_parameters )
                            {
                                callback_parameter=callback_parameter+"'"+your_parameters+"',";
                            }
                            callback_parameter=callback_parameter+"1";
                            let call_function=callback_function+"("+callback_parameter+")";

                            eval(call_function);

                            //window[callback_function](callback_parameters.split(','));
                       }                    
                    }
                    else 
                    {
                        Swal.fire("Error", response.status_message, "error");
                    }
                    
                    //CLEAR FORM
                   /* $('#' + form_id)[0].reset();*/
                    return;
                })
                // using the fail promise callback
                .fail(function(jqXHR, textStatus, errorThrown) 
                {
                    //Server failed to respond - Show an error message
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);
                    Swal.fire("Error", response.status_message, "error");
                    return;
                });


        } else {
            Swal.fire("Cancelled", "Your data was not modified", "error");
        }
    });


}


function update_values(url,callback_function,...callback_parameters) 
{

    /*alert(callback_function);
    return;*/

    Swal.fire({
        title: 'Are you sure?',
        text: "Dear User, you are about to edit this data.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Accept',
        cancelButtonText: "Decline"
    }).then((result) => {
        if (result.isConfirmed) {


            //initialise loading
            Swal.fire({
                title: 'Sending data',
                allowOutsideClick: false,
                html: '<p><center>Please wait...</center></p>',
                didOpen: () => {
                    Swal.showLoading()
                },
                showClass: {
                    popup: 'animate__animated animate__bounceInLeft'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })
            
            /*console.log(output);*/
            $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: url, // the url where we want to POST
                    /*data        : the_values,*/ // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    async: true
                })
                // using the done promise callback
                .done(function(data, textStatus, jqXHR) {
                    // nreceive the response after sending the request
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);

                    if (response.status == "200" || response.status == "201") 
                    {
                        Toastz("success", "", response.status_message);
                        if (callback_function) 
                        {
                            let callback_parameter="";
                            let count=0;

                            for(const your_parameters of callback_parameters )
                            {
                                callback_parameter=callback_parameter+"'"+your_parameters+"',";
                            }
                            callback_parameter=callback_parameter+"1";
                            let call_function=callback_function+"("+callback_parameter+")";

                            eval(call_function);

                            //window[callback_function](callback_parameters.split(','));
                        }
                        
                    }
                    else 
                    {
                        Swal.fire("Error", response.status_message, "error");
                    }
                })
                // using the fail promise callback
                .fail(function(jqXHR, textStatus, errorThrown) {
                    //Server failed to respond - Show an error message
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);
                    Swal.fire("Error", response.status_message, "error");
                    return;
                });
        } else {
            Swal.fire("Cancelled", "Your data was not modified", "error");
        }
    });

}



function background_update_values(url,callback_function,...callback_parameters) 
{

 
    /*console.log(output);*/
    $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: url, // the url where we want to POST
            /*data        : the_values,*/ // our data object
            dataType: 'json', // what type of data do we expect back from the server
            async: true
        })
        // using the done promise callback
        .done(function(data, textStatus, jqXHR) {
            // nreceive the response after sending the request
            //Swal.close();
            response = jqXHR.responseText;
            response = JSON.parse(response);

            if (response.status == "200" || response.status == "201") 
            {
                console.log(response.status_message);
                              
            }
            else 
            {
                console.log(response.status_message);
            }
        })
        // using the fail promise callback
        .fail(function(jqXHR, textStatus, errorThrown) 
        {
            //Server failed to respond - Show an error message
            response = jqXHR.responseText;
            response = JSON.parse(response);
            console.log(response.status_message);
            return;
        });

}



function remove(url, item_id,form_id,callback_function,...callback_parameters) {

    Swal.fire({
        title: 'Are you sure?',
        text: "Dear User, you are about to delete this data.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Accept',
        cancelButtonText: "Decline"
    }).then((result) => {
        if (result.isConfirmed) {
            //initialise loading
            Swal.fire({
                title: 'Deleting data',
                allowOutsideClick: false,
                html: '<p><center>Please wait...</center></p>',
                didOpen: () => {
                    Swal.showLoading()
                },
                showClass: {
                    popup: 'animate__animated animate__bounceInLeft'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })

            $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: url, // the url where we want to POST
                    data: { 'id': item_id }, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    async: true
                })
                // using the done promise callback
                .done(function(data, textStatus, jqXHR) {
                    // nreceive the response after sending the request
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);

                    if (response.status == "200" || response.status == "201") 
                    {
                        //CLEAR FORM
                        $('#' + form_id)[0].reset();
                        Toastz("success","", response.status_message);
                        if (callback_function) 
                        {
                            let callback_parameter="";
                            let count=0;

                            for(const your_parameters of callback_parameters )
                            {
                                callback_parameter=callback_parameter+"'"+your_parameters+"',";
                            }
                            callback_parameter=callback_parameter+"1";
                            let call_function=callback_function+"("+callback_parameter+")";

                            eval(call_function);

                            //window[callback_function](callback_parameters.split(','));
                        }                        
                    }
                    else 
                    {
                        Swal.fire("Error", response.status_message, "error");
                    }
                })
                // using the fail promise callback
                .fail(function(jqXHR, textStatus, errorThrown) 
                {
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);
                    Swal.fire("Error", response.status_message, "error");
                    //Server failed to respond - Show an error message
                    
                    
                    return;
                });
        } 
        else 
        {
            Swal.fire("Cancelled", "Your data was not modified", "error");
        }
    });
}


function remove_row(url, item_id, table_id, r,callback_function,...callback_parameters) {

    Swal.fire({
        title: 'Are you sure?',
        text: "Dear User, you are about to delete this data.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Accept',
        cancelButtonText: "Decline"
    }).then((result) => {
        if (result.isConfirmed) 
        {
            //initialise loading
            Swal.fire({
                title: 'Deleting data',
                allowOutsideClick: false,
                html: '<p><center>Please wait...</center></p>',
                didOpen: () => {
                    Swal.showLoading()
                },
                showClass: {
                    popup: 'animate__animated animate__bounceInLeft'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })

            $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: url, // the url where we want to POST
                    data: { 'id': item_id }, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    async: true
                })
                // using the done promise callback
                .done(function(data, textStatus, jqXHR) 
                {
                    // nreceive the response after sending the request
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);

                    if (response.status == "200" || response.status == "201") 
                    {
                        //delete row from interface
                        let i = r.parentNode.parentNode.rowIndex;
                        document.getElementById(table_id).deleteRow(i);
                        Toastz("success","", response.status_message);
                        if (callback_function) 
                        {
                            let callback_parameter="";
                            let count=0;

                            for(const your_parameters of callback_parameters )
                            {
                                callback_parameter=callback_parameter+"'"+your_parameters+"',";
                            }
                            callback_parameter=callback_parameter+"1";
                            let call_function=callback_function+"("+callback_parameter+")";

                            eval(call_function);

                            //window[callback_function](callback_parameters.split(','));
                        }                        return;
                    }
                    else 
                    {
                        Swal.fire("Error", response.status_message, "error");
                    }
                })
                // using the fail promise callback
                .fail(function(jqXHR, textStatus, errorThrown) 
                {
                    //Server failed to respond - Show an error message
                    Swal.close();
                    response = jqXHR.responseText;
                    response = JSON.parse(response);
                    Swal.fire("Error", response.status_message, "error");
                    return;
                });
                
        } 
        else 
        {
            Swal.fire("Cancelled", "Your data was not modified", "error");
        }
    });
}




/* 
This prints the contents of a specified div
It takes 2 arguements
1. div_id: the id of the div/element which you want to print its content
2. ..all_css this is a rest paramenter that can accept more than one input it accepts the css used for the page inorder to maintain the style on the table
eg;print_div('print_waybill','<link href=\'../assets/libraries/font-awesome-4.7.0/css/font-awesome.min.css\' type=\'text/css\' rel=\'stylesheet\'>','<link href=\'main.css\' type=\'text/css\' rel=\'stylesheet\'>');
*/ 
function print_div(div_id, ...all_css) 
{

      let printid=div_id;
      let DocumentContainer = document.getElementById(printid);
      let WindowObject = window.open("", "PrintWindow","top=70,toolbars=no,scrollbars=yes,status=no,resizable=yes");

      for(const your_css of all_css )
      {
        WindowObject.document.write('<link href="'+your_css+'" type="text/css" rel="stylesheet">')
      }
      WindowObject.document.writeln(DocumentContainer.innerHTML);
      WindowObject.document.close();
      setTimeout(function(){
      WindowObject.focus();
      WindowObject.print();
      WindowObject.close();
    },5000);

}



function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}











//prevent code inspection
/*document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    Toastz('danger', 'You Can not Do This!', '');
});

document.onkeydown = function(e) {

    if (event.keyCode == 123) {
        Toastz('danger', 'You Can not Do This!', '');
        return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
        Toastz('danger', 'You Can not Do This!', '');
        return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
        Toastz('danger', 'You Can not Do This!', '');
        return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
        Toastz('danger', 'You Can not Do This!', '');
        return false;
    }
    if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
        Toastz('danger', 'You Can not Do This!', '');
        return false;
    }
    /*if (e.ctrlKey && e.keyCode == 'C'.charCodeAt(0)) {
        Toastz('danger', 'You Can not Do This!', '');
        return false;
    }

    if (e.ctrlKey && e.keyCode == 'V'.charCodeAt(0)) {
        Toastz('danger', 'You Can not Do This!', '');
        return false;
    }*/
//}