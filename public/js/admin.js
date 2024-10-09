(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {
        
        // $('#importStudentModal #importBtn').click(function () {
        //     const operation = 'import_student'; // Define the operation for importing students
        //     const requestData = new FormData(); // Create a new FormData object to send files
        
        //     // Get the files from the dropzone or file input (replace '#fileInput' with the actual selector)
        //     const files = document.querySelector('#dropzone-multi input[type="file"]').files;
        
        //     // Add each file to the FormData object
        //     for (let i = 0; i < files.length; i++) {
        //         requestData.append('files[]', files[i]);
        //     }
        
        //     // Send the AJAX request
        //     sendAjaxRequest(operation, requestData, function (response) {
        //         // Log the entire response object for debugging
        //         console.log('Response:', response);
        
        //         // Check the response and handle success or error accordingly
        //         if (response.success) {
        //             // Perform actions on a successful import
        //             createToast('Import Success', response.message, 'success');
        //         } else {
        //             // Handle errors or display an error message
        //             createToast('Import Error', response.message, 'danger');
        //         }
        //     }, function (errorMessage) {
        //         // Display an error toast message if the AJAX request fails
        //         createToast('Error', errorMessage, 'danger');
        //     });
        // });

       
    });
})();
