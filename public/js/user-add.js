(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {

        var isEmailValid = false;

        $('#verify-justified #btnSearch').click(function () {
            performSearch();
        });

        $('#verify-justified #input-search').keydown(function (event) {
            if (event.which === 13) { // Check if Enter key is pressed
                event.preventDefault(); // Prevent the default form submission behavior
                performSearch();
            }
        });

        $('#verify-justified #resultTable').on('click', '.btnNext', function () {
            var schoolId = $(this).data('schoolid');
            var fullName = $(this).data('fullname');
            var isEmployee = $(this).data('employee');

            // Set the parsed values in input fields
            $('#details-justified #user_sid').val(schoolId);
            $('#details-justified #user_fname').val(fullName);

            // Set the selected option in user_role based on isEmployee
            if (isEmployee === 0) {
                $('#details-justified #user_role').val('student');
            } else {
                $('#details-justified #user_role').val('employee');
            }

            nextTab();
        });

        function emailExistCheck() {
            var emailInput = $('#details-justified #inputEmail'); // Adjust this selector as needed

            // Reset inputs
            emailInput.removeClass('is-valid');
            emailInput.removeClass('is-invalid');

            // Make an AJAX request to check email existence
            $.ajax({
                type: 'POST',
                url: `./../../ajax/login/checkEmail`,
                data: { email: emailInput.val() },
                success: function (response) {
                    if (response.result) {
                        emailInput.addClass('is-invalid');
                        isEmailValid = false;
                    } else {
                        emailInput.addClass('is-valid');
                        isEmailValid = true;
                    }
                },
                error: function () {
                    // Handle error
                }
            });
        }

        $('#permission-justified #createUser').on('click', function (event) {
            event.preventDefault();
            // Get user information from input fields
            var userSid = $('#details-justified #user_sid').val();
            var userFname = $('#details-justified #user_fname').val();
            var userRole = $('#details-justified #user_role').val();
            var inputEmail = $('#details-justified #inputEmail').val();
            var inputPassword = $('#details-justified #inputPassword').val();
            var inputType = $('#details-justified #inputType').val();
            var inputBio = $('#profile-justified #inputBio').val();

            // Implementation of Social
            var inputFacebook = $('#profile-justified #inputFacebook').val();
            var inputInstagram = $('#profile-justified #inputInstagram').val();
            var inputTiktok = $('#profile-justified #inputTiktok').val();
            var inputYoutube = $('#profile-justified #inputYoutube').val();

            // Get profile information from form
            var profileData = new FormData($('#profileForm')[0]);

            // Get turned-on permission numbers
            var permissionNumbers = [];
            $('.permission-checkbox:checked').each(function () {
                var permissionNumber = this.id.replace('permission-', '');
                permissionNumbers.push(permissionNumber);
            });

            // Convert permission numbers array to a comma-separated string
            var permissions = permissionNumbers.join(',');


            // Add user information, permissions array, and permission limitations to the profile data object
            profileData.append('user_sid', userSid);
            profileData.append('user_fname', userFname);
            profileData.append('user_role', userRole);
            profileData.append('inputEmail', inputEmail);
            profileData.append('inputPassword', inputPassword);
            profileData.append('inputType', inputType);
            profileData.append('inputBio', inputBio);
            profileData.append('permissions', permissions);

            // Implement of Social
            profileData.append('inputFacebook', inputFacebook);
            profileData.append('inputInstagram', inputInstagram);
            profileData.append('inputTiktok', inputTiktok);
            profileData.append('inputYoutube', inputYoutube);

            // Loop through dynamic permission inputs and collect their values
            $('[id^="permission-"][id$="-data"]').each(function () {
                var permissionId = this.id.split('-')[1];
                var permissionData = $(this).val();
                profileData.append('permission-' + permissionId + '-data', permissionData);
            });

            $('[id^="permission-"][id$="-id"]').each(function () {
                var permissionId = this.id.split('-')[1];
                var permissionIdData = $(this).val();
                profileData.append('permission-' + permissionId + '-id', permissionIdData);
            });

            // Make AJAX request
            $.ajax({
                url: './../../../ajax/admin/user_create',
                type: 'POST',
                data: profileData,
                processData: false,
                contentType: false,
                success: function (response) {

                    if (response.result) {
                        const userId = response.data.id;
                        // Handle success response
                        createToast("Done", response.message, "success");

                        setTimeout(() => {
                            window.location.href = `./../../../admin/users/${userId}`;
                        }, 2000);
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });
        });

        $('#details-justified #inputEmail').on('blur', emailExistCheck);

        // Attach event listener to the "Next" button
        $('#details-justified .btnNext').on('click', function () {
            var emailInput = $('#details-justified #inputEmail');
            var passwordInput = $('#details-justified #inputPassword');

            // Reset inputs
            emailInput.removeClass('is-valid is-invalid');
            passwordInput.removeClass('is-valid is-invalid');

            // Check if email is empty
            if (emailInput.val() === '') {
                $('#details-justified #emailFeedback').html('Email is required.'); 
                emailInput.addClass('is-invalid');
            }

            // Check if password is empty
            if (passwordInput.val() === '') {
                $('#details-justified #passwordFeedback').html('Password is required.'); 
                passwordInput.addClass('is-invalid');
            }

            // If email and password are not empty, proceed with emailExistCheck
            if (emailInput.val() !== '' && passwordInput.val() !== '') {
                $('#details-justified #emailFeedback').html('Email already in use.'); // Reset error message
                emailExistCheck();
            }

            if (isEmailValid) {
                nextTab();
            }
        });

        $('#profile-justified .btnNext').on('click', function () {
            nextTab();
        });


        function performSearch() {
            var inputValue = $('#verify-justified #input-search').val();
            var tableBody = $('#verify-justified #resultTable tbody');

            // Clear the table before inserting new results
            tableBody.empty();

            // Send the AJAX request
            $.ajax({
                url: './../../ajax/admin/user_query',
                method: 'POST',
                data: { query: inputValue },
                success: function (response) {
                    if (response.data.length === 0) {
                        // Display message when no results are found
                        var tr = $('<tr>');
                        var tdMessage = $('<td colspan="3" class="text-center">').text('Student/Employee cannot be found.');
                        tr.append(tdMessage);
                        tableBody.append(tr);
                    } else {
                        response.data.forEach(function (item) {
                            var tr = $('<tr>');
                            var tdSchoolID = $('<td>').text(item.School_ID);
                            var tdFullName = $('<td>').text(item.FullName);
                            var tdAction = $('<td>');

                            if (item.isRegistered === '1') {
                                tdAction.text('Registered');
                            } else {
                                tdAction.html('<button type="submit" class="btn btn-primary btnNext" data-schoolid="' + item.School_ID + '" data-fullname="' + item.FullName + '" data-employee="' + item.isEmployee + '">Select</button>');
                            }

                            tr.append(tdSchoolID, tdFullName, tdAction);
                            tableBody.append(tr);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    // Display error message in the center of the table
                    var tr = $('<tr>');
                    var tdError = $('<td colspan="3" class="text-center">').text('Student/Employee cannot be found.');
                    tr.append(tdError);
                    tableBody.append(tr);
                },
                complete: function () {
                    //responseElement.scrollIntoView({ behavior: "smooth" });
                }
            });
        }

        function nextTab() {
            // Get the active tab link and content
            var activeTabLink = $('.nav-tabs .active');
            var activeTabContent = $('.tab-content .active');

            // Remove the active class from the current tab link and content
            activeTabLink.removeClass('active');
            activeTabContent.removeClass('active show');

            // Get the next tab link and content
            var nextTabLink = activeTabLink.parent().next('.nav-item').find('a[data-bs-toggle="tab"]');
            var nextTabContent = activeTabContent.next('.tab-pane');

            // Add the active class to the next tab link and content
            nextTabLink.addClass('active');
            nextTabContent.addClass('active show');

            // Manually set the tab content as active
            $(nextTabLink.attr('data-bs-target')).addClass('active show');
        }

        $('.btnPrev').click(function (e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the active tab link and content
            var activeTabLink = $('.nav-tabs .active');
            var activeTabContent = $('.tab-content .active');

            // Remove the active class from the current tab link and content
            activeTabLink.removeClass('active');
            activeTabContent.removeClass('active show');

            // Get the next tab link and content
            var prevTabLink = activeTabLink.parent().prev('.nav-item').find('a[data-bs-toggle="tab"]');
            var prevTabContent = activeTabContent.prev('.tab-pane');

            // Add the active class to the next tab link and content
            prevTabLink.addClass('active');
            prevTabContent.addClass('active show');

            // Manually set the tab content as active
            $(prevTabLink.attr('data-bs-target')).addClass('active show');
        }); 

    });
})();