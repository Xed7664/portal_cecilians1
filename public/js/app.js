// app.js

let activeToast = null; // Variable to keep track of the active toast

$(".toggle-sidebar-cstm-btn").on("click", function() {
    $("body").toggleClass("toggle-sidebar");
});
  

// Function to send an AJAX request
function sendAjaxRequest(operation, requestData, successCallback, errorCallback) {
    $.ajax({
        url: '/ajax/event',
        method: 'POST',
        data: {
            op: operation,
            data: requestData, // Include the data parameter
            _token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
        },
        dataType: 'json',
        success: function (response) {
            if (response.result === true) {
                // Call the success callback function if provided
                if (typeof successCallback === 'function') {
                    successCallback(response);
                }
            } else {
                // Call the error callback function if provided
                if (typeof errorCallback === 'function') {
                    errorCallback(response);
                }
            }
        },
        error: function (xhr, status, error) {
            // Handle AJAX errors
            console.error(error);
            // Call the error callback function if provided
            if (typeof errorCallback === 'function') {
                errorCallback({ result: false, message: 'An error occurred.' });
            }
        },
    });
}

function copyToClipboard(text) {
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
}

function scrollToChildAndFocus(parentId, childSelector) {
    const parent = $('#' + parentId);

    if (parent) {
        const child = parent.find(childSelector);
        
        if (child) {
            const scrollTop = child.offset().top - ($(window).height() - child.height()) / 2;

            // Animate the scroll to the calculated position with a smooth effect
            $('html, body').animate({
                scrollTop: scrollTop
            }, 500, function () {
                // After the animation, focus on the child element
                child.focus();
            });
        }
    }
}




// Function to create and display a toast notification
function createToast(title, message, type) {
    // Remove the active toast if it exists
    if (activeToast) {
        activeToast.dispose();
    }

    const toastContainer = document.getElementById("toastContainer");

    const toastElement = document.createElement("div");
    toastElement.classList.add("alert", `alert-${type}`, "alert-dismissible", "fade", "show");
    toastElement.setAttribute("role", "alert");
    toastElement.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    toastContainer.appendChild(toastElement);

    const toast = new bootstrap.Toast(toastElement, {
        animation: true,
        autohide: true,
        delay: 5000 // Hide the toast after 5 seconds (adjust as needed)
    });

    // Set the active toast to the new toast
    activeToast = toast;

    toast.show();

    toastElement.addEventListener("hidden.bs.toast", function () {
        // Remove the active toast when it is hidden
        activeToast = null;
        toastElement.remove();
    });
}

$(".semester-select").on("click", function () {
    var semId = $(this).data("semid");

    sendAjaxRequest(
        'switch_semester', // Operation or URL
        { sem_id: semId }, // Request data
        function (response) {
            createToast("Switched", response.message, "success");

            // Refresh the page after 2 seconds
            setTimeout(function () {
                location.reload();
            }, 2000);
        },
        function (error) {
            createToast("Error", error.message, "danger");
        }
    );
});

$(".year-select").on("click", function () {
    var syId = $(this).data("syid");

    sendAjaxRequest(
        'switch_year', // Operation or URL
        { sy_id: syId }, // Request data
        function (response) {
            createToast("Switched", response.message, "success");

            // Refresh the page after 2 seconds
            setTimeout(function () {
                location.reload();
            }, 2000);
        },
        function (error) {
            createToast("Error", error.message, "danger");
        }
    );
});

$('.switch-theme').click(function () {
    const operation = 'switch_theme'; // Define the operation
    const requestData = {}; // No additional data needed for this operation

    sendAjaxRequest(operation, requestData, function (response) {
        // Log the entire response object for debugging
        console.log('Response:', response);

        // Update the theme icon and text based on the response
        const themeCode = response.code;
        const switchThemeIcon = $('.switch-theme i');
        const switchThemeText = $('.switch-theme span');

        if (themeCode === 1) {
            switchThemeIcon.removeClass('bi-cloud-moon').addClass('bi-cloud-sun-fill');
            switchThemeText.text('Light Mode');
            $('html').attr('data-bs-theme', 'dark');
        } else {
            switchThemeIcon.removeClass('bi-cloud-sun-fill').addClass('bi-cloud-moon');
            switchThemeText.text('Dark Mode');
            $('html').attr('data-bs-theme', 'light');
        }

        // Send toast to user
        createToast('Switched', response.message, 'success');
    }, function (errorMessage) {
        // Display an error toast message
        createToast('Error', errorMessage, 'danger');
    });
});

$('.switch-panel').click(function () {
    const operation = 'switch_panel'; // Define the operation
    const requestData = {}; // No additional data needed for this operation

    sendAjaxRequest(operation, requestData, function (response) {
        // Log the entire response object for debugging
        console.log('Response:', response);

        // Update the theme icon and text based on the response
        const themeCode = response.code;

        if (themeCode === 1) {
            // Send toast to user
            createToast('Switched', response.message + 'User Panel', 'success');
        } else {
            // Send toast to user
            createToast('Switched', response.message + 'Admin Panel', 'success');
        }

        setTimeout(function () {
            location.reload();
        }, 2000);

    }, function (errorMessage) {
        // Display an error toast message
        createToast('Error', errorMessage, 'danger');
    });
});

// Listen for input changes on all elements with the "data-enable-if" attribute
$('[data-enable-if]').on('input', function () {
    // Log to console for debugging
    console.log('Input change event triggered.');

    // Get the associated button ID from the "data-enable-if" attribute
    var buttonId = $(this).data('enable-if');
    console.log('Button ID:', buttonId);

    // Find the associated button and toggle the "visually-hidden" class based on input content
    $('#' + buttonId).toggleClass('visually-hidden', $(this).val().trim() === '');
});

// Listen for input changes on the "commentInput" element
$('#commentInput').on('input', function () {

    // Find the associated button and toggle the "visually-hidden" class based on input content
    $('button[data-enable-if="commentInput"]').toggleClass('visually-hidden', $(this).val().trim() === '');
});