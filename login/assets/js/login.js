 //submit page on enter press
 $("#nid").keyup(function(event) {
     if (event.keyCode === 13) {
         $("#log_button").click();
     }
 });

 $("#pword").keyup(function(event) {
     if (event.keyCode === 13) {
         $("#log_button").click();
     }
 });



 //prevent code inspection
 document.addEventListener('contextmenu', function(e) {
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
     if (e.ctrlKey && e.keyCode == 'C'.charCodeAt(0)) {
         Toastz('danger', 'You Can not Do This!', '');
         return false;
     }

     if (e.ctrlKey && e.keyCode == 'V'.charCodeAt(0)) {
         Toastz('danger', 'You Can not Do This!', '');
         return false;
     }
 }


 //login function
 function login(form_id) {

     //add login page url
     let url = 'login/apis/controllers/access/login.php';

     //initiate loading animation
     $("#response_message").html("<p><center><i class='fa fa-refresh fa-spin'></i> Please wait</center></p>");

     //check pin
     //  const regex = new RegExp(/GHA-([0-9]){9}-([0-9]){1}/);
     //  if (!(regex.test($("#nid").val()))) {
     //      Toastz('danger', 'NID error: ', 'Kindly enter the right Ghana card pin');
     //      $("#response_message").html("");
     //      return;
     //  }

     //get form data
     let form = $('#' + form_id);
     let the_data = [];
     the_data = form.serializeArray();
     let output = [];
     the_data.forEach(function(item) {
         let existing = output.filter(function(v, i) {
             return v.name == item.name;
         });

         if (existing.length) {
             let existingIndex = output.indexOf(existing[0]);
             output[existingIndex].value = output[existingIndex].value.concat(item.value);
         } else {
             if (typeof item.value == 'string') {
                 item.value = [item.value];
                 output.push(item);
             }
         }
     });



     //call send data function from kings library and add the form data
     let response = "";
     response = send_data(url, output);
     if (response) {
         if (response.status == "200") {
             Toastz('success', 'Login Successful', '');
             /*Swal.fire(response.status, 'Login Successful', 'success');*/
             $('#' + form_id)[0].reset();
             $("#response_message").html("");

             if (!(response.data == "")) {
                 window.location.href = response.data;
             } else {
                 Toastz('danger', 'Access not defined', '');
             }

         } else {
             Toastz('danger', response.status_message, '');
             /*Swal.fire(response.status, response.status_message, 'error');*/
             $('#' + form_id)[0].reset();
             $("#response_message").html("");

         }
         return;
     }


 }