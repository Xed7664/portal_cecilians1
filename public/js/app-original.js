(function () {
    "use strict";

    let activeToast = null;

    // Wait for the DOM to be ready
    $(function () {

        $('.toggle-password-button').click(function () {
            var inputPassword = $(this).prev('.toggle-password');
            if (inputPassword.attr('type') === 'password') {
                inputPassword.attr('type', 'text');
            } else {
                inputPassword.attr('type', 'password');
            }
        });

        var positiveAudio = new Audio('./../../assets/snd/positive-btn.mp3');
        $('form.needs-validation').on('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
        });

        $('#logout').click(function () {
            $.ajax({
                type: 'POST',
                url: './../../ajax/login/logout',
                success: function (data) {
                    localStorage.removeItem('token');

                    // Send toast to user
                    createToast("Logout", data.message, "success");

                    setTimeout(function () {
                        window.location.href = './../../account/login';
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    // handle error response here
                }
            });
        });

        $('#switchTheme').click(function () {
            $.ajax({
                type: 'POST',
                url: './../../ajax/switch/theme',
                success: function (data) {
                    // Update the theme icon and text based on the response
                    const themeCode = data.code;
                    const switchThemeIcon = $('#switchTheme i');
                    const switchThemeText = $('#switchTheme span');

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
                    createToast("Switched", data.message, "success");
                },
                error: function (xhr, status, error) {
                    createToast("Error", xhr.responseJSON.message, "danger");
                }
            });
        });



        $('.switchPanels').click(function () {
            $.ajax({
                type: 'POST',
                url: './../../ajax/switch/panel',
                success: function (data) {
                    // Send toast to user
                    createToast("Switched", data.message, "success");

                    setTimeout(function () {
                        window.location.href = './../../index.php';
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    createToast("Error", xhr.responseJSON.message, "danger");
                }
            });
        });

        $(".semester-select").on("click", function () {
            var semId = $(this).data("semid");

            $.ajax({
                type: "POST",
                url: "./../../ajax/switch/semester",
                data: { sem_id: semId },
                success: function (response) {
                    createToast("Switched", response.message, "success");

                    // Refresh the page after 2 seconds
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    createToast("Error", xhr.responseJSON.message, "danger");
                }
            });
        });

        $(".year-select").on("click", function () {
            var syId = $(this).data("syid");

            $.ajax({
                type: "POST",
                url: "./../../ajax/switch/year",
                data: { sy_id: syId },
                success: function (response) {
                    createToast("Switched", response.message, "success");

                    // Refresh the page after 2 seconds
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    createToast("Error", xhr.responseJSON.message, "danger");
                }
            });
        });

        if ($('#changepass-justified').length) {
            // Attach a click event handler to the "Save Changes" button
            $('#saveChanges').click(function (event) {
                event.preventDefault(); // Prevent form submission

                var form = $('form.needs-validation')[0];

                //// Retrieve the input values
                var oldPassword = $('#inputOldPassword').val();
                var newPassword = $('#inputNewPassword').val();
                var retypePassword = $('#inputReNewPassword').val();
                
                //// Check for empty inputs
                if (oldPassword.trim() === '' || newPassword.trim() === '' || retypePassword.trim() === '') {
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text('Please fill in all the fields.')
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
                
                    $('#changepass-justified #response').empty().append(alertDiv);
                
                    // Add 'is-invalid' class to the input fields
                    $('#inputOldPassword').addClass('is-invalid');
                    $('#inputNewPassword').addClass('is-invalid');
                    $('#inputReNewPassword').addClass('is-invalid');
                
                    // Re-validate the form to display error messages
                    form.reportValidity();
                
                    return; // Exit the function early
                }
                
                // Create an object with the data to send via AJAX
                var data = {
                    oldPassword: oldPassword,
                    newPassword: newPassword,
                    retypePassword: retypePassword
                };
                //
                // Perform the AJAX request
                $.ajax({
                    url: './../../ajax/settings/changepass', // Replace with the actual server endpoint
                    type: 'POST', // or 'GET' based on your server implementation
                    data: data,
                    success: function (response) {
                        var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                            .text(response.message)
                            .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                        $('#changepass-justified #response').empty().append(alertDiv);
                    },
                    error: function (error) {
                        // Handle any errors that occur during the AJAX request
                        if (error.responseJSON.code === 10001) {
                            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                                .text(error.responseJSON.message)
                                .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                            $('#changepass-justified #response').empty().append(alertDiv);

                            // Reload the page after 5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 5000);
                        } else if (error.responseJSON.code === 10003) {
                            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                                .text(error.responseJSON.message)
                                .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                            $('#changepass-justified #response').empty().append(alertDiv);

                            // Passwords do not match
                            $('#inputNewPassword').removeClass('is-valid').addClass('is-invalid');
                            $('#inputReNewPassword').removeClass('is-valid').addClass('is-invalid');

                            // Display error messages using invalid-feedback
                            $('#inputNewPassword').siblings('.invalid-feedback').text('Passwords do not match.');
                            $('#inputReNewPassword').siblings('.invalid-feedback').text('Passwords do not match.');

                            // Re-validate the form to display error messages
                            form.reportValidity();
                        } else if (error.responseJSON.code === 10004) {
                            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                                .text(error.responseJSON.message)
                                .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                            $('#changepass-justified #response').empty().append(alertDiv);

                            // Old password is wrong
                            $('#inputOldPassword').removeClass('is-valid').addClass('is-invalid');
                            
                            // Display error message using invalid-feedback
                            $('#inputOldPassword').siblings('.invalid-feedback').text('Old password is incorrect.');

                            // Re-validate the form to display error messages
                            form.reportValidity();
                        } else {
                            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                                .text(error.responseJSON.message)
                                .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                            $('#changepass-justified #response').empty().append(alertDiv);
                        }
                    }
                });
            });

            // Attach input event handlers to the input fields
            $('#inputOldPassword, #inputNewPassword, #inputReNewPassword').on('input', function () {
                var form = $('form.needs-validation')[0];
                
                // Reset custom validity on input change
                //$(this).get(0).setCustomValidity('');
                
                // Clear any existing error messages
                $(this).siblings('.invalid-feedback').text('');
                
                // Add 'is-invalid' class to the input fields
                $(this).removeClass('is-invalid').addClass('is-valid');
                
                // Re-validate the form to remove error messages
                form.reportValidity();
            });
        }

        // Organization
        if ($('.organization').length > 0) {
            // Attach event listeners to each card
            $('.organization .card').each(function () {
                var organizationLink = $(this).find('.organization-link');
                var joinButton = $(this).find('.join-button');
                var organizationImage = $(this).find('.organization-image');

                // Get the data-id value of the card
                var cardId = $(this).data('id');

                // Set the target URL based on the data-id value
                var targetURL = '../organization/' + cardId;

                // Add event listeners to redirect when clicked
                organizationLink.click(function (event) {
                    event.preventDefault();
                    redirectToOrganization(targetURL);
                });

                joinButton.click(function (event) {
                    event.preventDefault();
                    redirectToOrganization(targetURL);
                });

                organizationImage.parent('.image-container').click(function (event) {
                    event.preventDefault();
                    redirectToOrganization(targetURL);
                });
            });

            // Function to handle the redirection
            function redirectToOrganization(targetURL) {
                window.location.href = targetURL;
            }
        }

        $(".notification-item").click(function (e) {
            // Get the notification ID from the data attribute
            var notificationId = $(this).data("notifid");

            // Send AJAX request to mark the notification as read
            $.ajax({
                type: "POST",
                url: "./../../ajax/notification/read", // Adjust the URL to your server-side script
                data: { id: notificationId },    // Data to send, you might need to adjust this
                success: function (response) {
                    // Handle success, for example, update UI to indicate read status
                    // For example, you can remove the dot that indicates unread status:
                    $(".notification-item[data-notifid='" + notificationId + "']").find(".bi-dot").remove();

                    // Continue with the default link behavior after the AJAX request is sent
                    window.location.href = $(e.currentTarget).find("a").attr("href");
                },
                error: function (xhr, status, error) {
                    // Handle error
                    //console.error(error);
                    window.location.href = $(e.currentTarget).find("a").attr("href");
                }
            });

            // Prevent the default link behavior temporarily
            e.preventDefault();
        }); 

        $(".mark-all-read").click(function () {
            // Send AJAX request to mark all notifications as read
            $.ajax({
                type: "POST",
                url: "./../../ajax/notification/read_all", // Adjust the URL to your server-side script
                success: function (response) {

                    $(".notification-item").find(".bi-dot").remove();

                    $(".notification-count").remove();

                    // Send toast to user
                    createToast("Done", response.message, "success");

                },
                error: function (xhr, status, error) {
                    // Handle error
                    console.error(error);
                }
            });
        });


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


        // Attach createToast to the global object (window in a browser environment)
        window.createToast = createToast;

    });

    function performSearch(query) {
        if (query) {
            window.location.href = `./../../../search/${encodeURIComponent(query)}`;
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const searchForm = document.querySelector(".search-form");
        const searchInput = searchForm.querySelector("input[name='query']");
        const searchButton = searchForm.querySelector("button");

        // Handle form submission on "Enter" key press
        searchForm.addEventListener("submit", function (event) {
            event.preventDefault();
            performSearch(searchInput.value.trim());
        });

        // Handle search button click
        searchButton.addEventListener("click", function () {
            performSearch(searchInput.value.trim());
        });
    });
    // ACCOUNT SETTINGS

    // Save Changes button click event listener
    $('#profile-justified #uploadInput').on('change', function () {
        var file = $(this).prop('files')[0];
        // File Type Validation
        var allowedMimeTypes = ['image/jpeg', 'image/png'];
        if (!allowedMimeTypes.includes(file.type)) {
            alert("Invalid file type. Only JPEG and PNG files are allowed.");
            $(this).val('');
            return;
        }
        // File Size Validation
        var maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            alert("File too large. Maximum file size is 2MB.");
            $(this).val('');
            return;
        }
        // Show the image preview
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#profile-justified #profileImage').attr('src', e.target.result);
            $('#profile-justified #isDefaultAvatar').val(1);
            $('#profile-justified #hasAvatarChanges').val(1); // True
        }
        reader.readAsDataURL(file);
    });

    // Remove profile image button click event listener
    $('#profile-justified #removeProfileImage').on('click', function (e) {
        e.preventDefault();
        $('#profile-justified #profileImage').attr('src', $('#profile-justified #originalImageSrc').val());
        $('#profile-justified #isDefaultAvatar').val(0);
        $('#profile-justified #hasAvatarChanges').val(1); // True
    });

    // Save changes button click event listener
    $('.settings #profile-justified #saveChangesBtn').on('click', function () {
        var bio = $('#profile-justified #about').val(); // get bio value
        var formData = new FormData($('#profile-justified #profileForm')[0]);
        formData.append('about', bio); // append bio value to form data
        $.ajax({
            type: 'POST',
            url: './../../ajax/settings/profile',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                createToast("Done", data.message, "success");

                setTimeout(function () {
                    location.reload();
                }, 2000);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.statusText;
                if (xhr.responseJSON) {
                    errorMessage = xhr.responseJSON.message;
                }
                createToast("Done", errorMessage, "danger");
            }
        });
    });
})();
