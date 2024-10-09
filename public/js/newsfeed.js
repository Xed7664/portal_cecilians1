'use strict';

$(document).ready(function () {
    var positiveAudio = new Audio('./../../assets/snd/positive-btn.mp3');
    var popAudio = new Audio('./../../assets/snd/pop.mp3');
    let $focusedEditInput = null;

    // Newsfeed Scroll
    var loading = false;
    var error = false;
    var offset = 20;

    // jQuery function to adjust the textarea height
    function adjustTextareaHeight(textarea) {
        const $textarea = $(textarea); // Wrap the textarea with jQuery

        $textarea.css('height', ''); // Reset the height to auto to calculate the actual scroll height
        const scrollHeight = $textarea[0].scrollHeight;
        const maxHeight = 100; // Maximum height you want to set

        if (scrollHeight > maxHeight) {
            $textarea.css('overflowY', 'auto'); // Enable vertical scrollbar
            $textarea.css('height', `${maxHeight}px`);
        } else {
            $textarea.css('overflowY', 'hidden'); // Hide vertical scrollbar if not needed
            $textarea.css('height', `${scrollHeight}px`);
        }
    }

    // jQuery function to reset textarea height when it loses focus
    function resetTextareaHeight(textarea) {
        textarea.style.height = "40px"; // Reset the height to the default value of 40 pixels
        textarea.style.overflowY = "hidden"; // Hide vertical scrollbar when resetting to default height
    }

    function handlePostClick() {
        const $postBtn = $('.post-btn');
        const $textarea = $('.post');
        const content = $textarea.val().trim();

        if (content === '') {
            return; // Do not proceed with posting if the content is empty
        }

        // Change the button text to "Posting..." and disable it
        $postBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Posting...');

        // Simulate an AJAX request to post the content (Replace this with your actual AJAX request)
        $.ajax({
            url: './../../ajax/post/create', // Replace './../../ajax/post.php' with the actual URL for your AJAX request
            type: 'POST',
            data: { content: content },
            success: function (response) {
                if (response.result) {
                    // Call the createPostCard function and pass the data
                    createPostCard(response.data);

                    // Send toast to user
                    createToast("Done", response.message, "success");

                    const postId = response.data.id;

                    // Reset the textarea height and content
                    $textarea.val('');
                    adjustTextareaHeight($textarea);

                    // Restore the button text to "Post" and enable it
                    $postBtn.prop('disabled', false).text('Post');
                    $postBtn.hide();

                    // Reset the textarea height after a successful post
                    resetTextareaHeight($textarea[0]);

                    // Find the newly added post card and focus on it
                    const $newPostCard = $(`[data-postid="${postId}"]`);

                    // Set focus on the newly added post card
                    $newPostCard[0].scrollIntoView({ behavior: 'smooth', block: 'center' });

                    createToast("Done", response.message, "success");
                    
                } else {
                    createToast("Done", response.message, "warning");
                }
            },
            error: function (response) {
                createToast("Error", response.responseJSON.message, "danger");

                // Restore the button text to "Post" and enable it
                $postBtn.prop('disabled', false).text('Post');
            },
        });
    }

    // Create a post card
    function createPostCard(data, position) {
        var displayCheck = "";

        if (data.author_type === "admin" || data.author_type === "moderator") {
            displayCheck += '<i class="ri ri-checkbox-circle-fill mt-2 text-danger" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>';
        } 

        const postCard = `
        <div class="card newsfeed"  data-postid="${data.id}">
            <div class="newsfeed-body">
                <div class="author-box d-flex align-items-center justify-content-between">
                    <div class="author">
                        <img src="./../../assets/img/profile/${data.author_image}" style="max-height: 40px;" alt="Profile" class="rounded-circle profile-sm">
                        <div class="info ms-2 mt-3">
                            <h5><a href="./../../../profile/${data.createdBy}">${data.author_name}</a> ${displayCheck}</h5>
                            <p>${data.time_ago}</p>
                        </div>
                    </div>
                    <div class="menu">
                        <button type="button" class="btn text-secondary me-25 reload-post">
                          <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
                          <i class="bi bi-arrow-counterclockwise"></i>
                        </button>

                        <button type="button" class="btn text-secondary " data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                            <div class="dropdown-menu dropdown-menu-end" style="overflow: hidden;">
                                <a class="dropdown-item edit-post" href="javascript:void(0);"><i class="bi bi-pencil-square me-1"></i> Edit post</a>
                                <a class="dropdown-item disable-comment" href="javascript:void(0);"><i class="bi bi-toggle-off me-1"></i> Disable comment</a>
                                <a class="dropdown-item delete-post" href="javascript:void(0);"><i class="bi bi-trash me-1"></i> Delete post</a> 
                                <a class="dropdown-item share-post" href="javascript:void(0);"><i class="ri ri-file-copy-line me-1"></i> Copy link</a>
                            </div>
                        </button>
                    </div>
                </div>    
                <p class="prewrap">${data.content}</p>
                <div class="edit-post-section" style="display: none;">
                    <div class="form-group mb-3">
                        <textarea class="form-control" id="editPostContent">${data.content}</textarea>
                    </div>
                    <button type="button" class="btn btn-portal save-edited-post">Save</button>
                    <button type="button" class="btn btn-secondary cancel-edit-post">Cancel</button>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-grid gap-2">
                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                        <button type="button" class="btn btn-newsfeed heart-btn"><i class="bi bi-heart me-1"></i> Love</button>
                        <button type="button" class="btn btn-newsfeed comment-btn"><i class="bi bi-chat-square me-1"></i> Comment</button>
                    </div>
                </div>

                <div class="comment-section mt-2">
                    <div class="form-group">
                        <div class="user-comments"></div>
                        <div class="user-commenting">
                            <div class="input-group p-1">
                                <textarea class="form-control comment" style="height: 40px; resize: none;" spellcheck="false" placeholder="Write a comment..."></textarea>
                                <button type="button" class="btn btn-portal comment-submit" style="display:none;"><i class="ri-send-plane-fill me-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

        if (position == 'buttom') {
            $('.newsfeed-posts').append(postCard);
        } else {
            $('.newsfeed-posts').prepend(postCard);
        }
    }

    // Function to send the AJAX request to post a comment
    function sendCommentAjax(postId, commentContent) {
        $.ajax({
            url: './../../ajax/post/comment', // Replace with your actual URL for posting a comment
            type: 'POST',
            data: {
                post_id: postId,
                content: commentContent
            },
            success: function (response) {
                if (response.result) {
                    var displayCheck = "";

                    if (response.data.isV) {
                        displayCheck += '<i class="ri ri-checkbox-circle-fill mt-2 text-danger" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>';
                    }

                    if (response.data.createdBy === response.data.post_author_id) {
                        displayCheck += '<span class="text-danger ms-2 small" data-bs-toggle="tooltip" data-bs-placement="top" title="This user is the author of the post.">Author</span>';
                    }

                    // Update the comments count in the HTML
                    const commentsCountElement = $('[data-postid="' + postId + '"]').find('.comment-count');
                    commentsCountElement.text(response.data.statistics.totalCount);

                    // Create the new comment HTML
                    const newComment = `
                <li id="comment-${response.data.id}" class="comment-item" data-commentid="${response.data.id}"  data-username="${response.data.username}">
                    <div class="comment-content">
                        <img src="./../../assets/img/profile/${response.data.author_image}" alt="" class="rounded-circle mt-3 profile-sm">
                        <div class="comment-message">
                            <div class="alert comment-box alert-dismissible" style="margin-bottom: 0px;">
                                <h4><a href="./profile/${response.data.createdBy}">${response.data.author_name}</a> ${displayCheck}</h4>
                                <p class="prewrap">${response.data.content_display}</p>
                                <button type="button" class="btn btn-light btn-sm btn-menu" data-bs-toggle="dropdown" aria-expanded="false" >
                                     <i class="bi bi-three-dots"></i>
                                     <div class="dropdown-menu dropdown-menu-end" style="overflow: hidden;">
                                         <a class="dropdown-item delete-comment" href="javascript:void(0);"> Delete</a>                            
                                     </div>
                                </button>
                            </div>
                            <p class="comment-meta">
                                <span class="text-left text-danger heart-container" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="The total hearts received of this comment." style="font-size: 12px; display:none;">
                                              <i class="bi bi-heart-fill text-danger"></i> <span class="react-count">0</span>
                                </span>
                                <span class="ms-2 me-2 text-secondary love-btn">Love</span> 
                                <span class="me-2 text-secondary reply-btn">Reply</span> 
                                ${response.data.time_ago}
                            </p>
                        </div>
                    </div>
                    <ul class="comment-replies list-inline">
                       <li class="reply-item visually-hidden user-replying" data-commentid="${response.data.id}">
                                <div class="reply-content">
                                    <a href="./../../../profile/${response.data.requester.user_id}" tabindex="-1">
                                    <img src="./../../assets/img/profile/${response.data.requester.user_avatar}" alt="" class="rounded-circle mt-3 profile-sm">
                                    </a>
                                    <div class="input-group input-group-sm mt-2">
                                        <textarea class="form-control reply mt-1" style="resize: none; overflow-y: hidden; height: 40px;" spellcheck="false" placeholder="Reply to ${response.data.author_name}.."></textarea><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: auto;" class="dnXmp"></grammarly-extension><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: auto;" class="dnXmp"></grammarly-extension>
                                        <button type="button" class="btn btn-portal reply-submit mt-1" style="display:none;"><i class="ri-send-plane-fill me-1"></i></button>
                                    </div>
                                </div>
                       </li>
                    </ul>
                </li>
            `;

                    // Find the specific .user-comments element within the post
                    const postComments = $(`[data-postid="${postId}"]`).find('.comment-section .user-comments');

                    // Prepend the new comment to the postComments section
                    postComments.prepend(newComment);

                    // Clear the comment textarea
                    $('.comment').val('');

                    // Send toast to user
                    createToast("Success", response.message, "success");

                    // Get the newly added comment element
                    const newlyAddedComment = postComments.find('.comment-item').first()[0];

                    // Scroll the window to the newly added comment element and center it vertically
                    newlyAddedComment.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    const popAudioClone = popAudio.cloneNode(); // Create a new instance of the audio element
                    popAudioClone.play();

                    createToast("Done", response.message, "success");
                } else {
                    createToast("Done", response.message, "warning");
                }
            },
            error: function (response) {
                // Send toast to user
                createToast("Error", response.responseJSON.message, "danger");
            }
        });
    }

    // Function to send the AJAX request to post a reply
    function sendReplyAjax(commentId, replyContent) {
        $.ajax({
            url: './../../ajax/post/reply', // Replace with your actual URL for posting a reply
            type: 'POST',
            data: {
                comment_id: commentId,
                content: replyContent
            },
            success: function (response) {
                if (response.result) {

                    // Update the comments count in the HTML
                    const replyCountElement = $('[data-postid="' + response.data.post_id + '"]').find('.comment-count');
                    replyCountElement.text(response.data.statistics.totalCount);


                    const isPostAuthor = response.data.createdBy === response.data.post_author_id;
                    const authorBadgeHtml = isPostAuthor ? '<span class="text-danger small ms-1 fw-lighter" data-bs-toggle="tooltip" data-bs-placement="top" title="This user is the author of the post.">Author</span>' : '';
                    // Create the new reply HTML
                    const newReply = `<li id="reply-${response.data.id}" class="reply-item" data-replyid="${response.data.id}"  data-username="${response.data.username}">
                                             <div class="reply-content">
                                                 <img src="./../../assets/img/profile/${response.data.author_image}" alt="" class="rounded-circle mt-3 profile-sm">
                                                 <div class="comment-message">
                                                     <div class="alert comment-box alert-dismissible" style="margin-bottom: 0px;">
                                                         <h4>
                                                            <a href="./profile/${response.data.createdBy}">${response.data.author_name}</a>
                                                            ${authorBadgeHtml}
                                                        </h4>
                                                         <p class="prewrap text-wrap">${response.data.content_display}</p>
                                                         <button type="button" class="btn btn-light btn-sm btn-menu" data-bs-toggle="dropdown" aria-expanded="false" >
                                                              <i class="bi bi-three-dots"></i>
                                                              <div class="dropdown-menu dropdown-menu-end" style="overflow: hidden;">
                                                                  <a class="dropdown-item delete-reply" href="javascript:void(0);"> Delete</a>                            
                                                              </div>
                                                         </button>
                                                     </div>
                                                     <p class="comment-meta">
                                                         <span class="text-left text-danger heart-container" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="The total hearts received of this comment." style="font-size: 12px; display:none;">
                                                                       <i class="bi bi-heart-fill text-danger"></i> <span class="react-count">0</span>
                                                         </span>
                                                         <span class="ms-2 me-2 text-secondary love-btn">Love</span>
                                                         <span class="me-2 text-secondary reply-btn">Reply</span> 
                                                         ${response.data.time_ago}
                                                     </p>
                                                 </div>
                                             </div>
                                         </li>
                                     `;

                    // Find the specific .comment-replies element within the comment
                    const commentReplies = $(`[data-commentid="${commentId}"]`).find('.comment-replies');

                    // Find the last reply item in the list
                    const lastReplyItem = commentReplies.find('.reply-item:last');

                    // Insert the new reply before the last reply item
                    $(newReply).insertBefore(lastReplyItem);

                    // Clear the reply textarea
                    const replyTextarea = commentReplies.find('.reply');
                    replyTextarea.val('');

                    // Send toast to user
                    createToast("Success", response.message, "success");

                    const popAudioClone = popAudio.cloneNode(); // Create a new instance of the audio element
                    popAudioClone.play();

                    createToast("Done", response.message, "success");
                } else {
                    createToast("Done", response.message, "warning");
                }
            },
            error: function (response) {
                // Send toast to user
                createToast("Error", response.responseJSON.message, "danger");
            }
        });
    }

    function reloadPost(postId, successCallback) {
        $.ajax({
            url: './../../ajax/post/postData', // Replace with your actual URL for reloading post data
            type: 'POST',
            data: { post_id: postId }, // Include any data needed to identify the post
            success: function (response) {
                // On success (Replace this with your actual success handling)
                //console.log('Post reloaded:', response);

                // Update the post content with the new data
                const $postContainer = $(`.card.newsfeed[data-postid="${postId}"]`);
                const $postContent = $postContainer.find('.newsfeed-body .prewrap');
                $postContent.text(response.data.content_display); // Update the post content

                // Update like count and post
                var $commentButton = $postContainer.find('.btn.btn-newsfeed.comment-btn .comment-count');
                var $reactButton = $postContainer.find('.btn.btn-newsfeed.comment-btn .heart-count');


                // Update comments count
                if (response.data.statistics.totalCount > 0) {
                    $commentButton.text(response.data.statistics.totalCount);
                } else {
                    $commentButton.text('Comment');
                }

                // Update reacts count
                if (response.data.reacts > 0) {
                    $reactButton.text(response.data.reacts);
                } else {
                    $reactButton.text('Love');
                }

                // Update the timestamp with the new date string
                const $timestamp = $postContainer.find('.author p a');
                const newTimestamp = response.data.time_ago;
                $timestamp.text(newTimestamp);

                // Update comments and replies
                const $commentSection = $postContainer.find('.comment-section .user-comments');
                $commentSection.empty(); // Clear existing comments

                
                // Loop through the comments data and append each comment to the comments section
                response.data.comments_data.forEach((comment) => {
                    const isVerified = comment.isVerified;
                    const isPostAuthor = response.data.createdBy === comment.author_id;
                    const isUserOwns = response.data.requester.user_id === comment.author_id;
                    const reactedClass = comment.reacted ? 'text-danger' : '';
                    const authorBadgeHtml = isPostAuthor ? '<span class="text-danger small ms-1 fw-lighter" data-bs-toggle="tooltip" data-bs-placement="top" title="This user is the author of the post.">Author</span>' : '';
                    const reactCountHtml = comment.reacts > 0
                        ? `<span class="text-left text-danger heart-container" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="The total hearts received of this comment." style="font-size: 12px;">
                           <i class="bi bi-heart-fill text-danger"></i> <span class="react-count">${comment.reacts}</span>
                           </span>`
                        : `<span class="text-left text-danger heart-container" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="The total hearts received of this comment." style="font-size: 12px; display: none;">
                           <i class="bi bi-heart-fill text-danger"></i> <span class="react-count">0</span>
                           </span>`;
                    const deleteCommentHtml = `<button type="button" class="btn btn-light btn-sm btn-menu" data-bs-toggle="dropdown" aria-expanded="false" >
                        <i class="bi bi-three-dots"></i>
                        <div class="dropdown-menu dropdown-menu-end" style="overflow: hidden;">
                            <a class="dropdown-item delete-comment" href="javascript:void(0);"> Delete</a>
                        </div>
                    </button>`;

                    const commentHtml = `
                    <li id="comment-${comment.id}" class="comment-item" data-commentid="${comment.id}" data-username="${comment.username}">
                        <div class="comment-content">
                            <img src="./../../assets/img/profile/${comment.author_image}" alt="avatar" class="rounded-circle mt-3 profile-sm">
                            <div class="comment-message">
                                <div class="alert comment-box alert-dismissible" style="margin-bottom: 0px;">
                                    <h4>
                                        <a href="./../../../profile/${comment.createdBy}">${comment.author_name}</a>
                                        ${isVerified ? '<i class="ri ri-checkbox-circle-fill mt-2 text-danger" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>' : ''}
                                        ${authorBadgeHtml}
                                    </h4>
                                    <p class="prewrap text-break">${comment.content_display}</p>
                                    ${isUserOwns ? deleteCommentHtml : ''}
                                </div>
                                <p class="comment-meta">
                                    ${reactCountHtml}
                                    <span class="ms-2 me-2 text-secondary ${reactedClass} love-btn">Love</span> 
                                    <span class="me-2 text-secondary reply-btn">Reply</span> 
                                    <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="${comment.createdDate}">${comment.time_ago}</span>
                                </p>
                            </div>
                        </div>
                        <ul class="comment-replies list-inline">
                            <!-- Reply items will be appended here -->
                            <li class="reply-item visually-hidden user-replying" data-commentid="${comment.id}" data-username="${comment.username}">
                                     <div class="reply-content">
                                         <a href="./../../../profile/${response.data.requester.user_id}" tabindex="-1">
                                         <img src="./../../assets/img/profile/${response.data.requester.user_avatar}" alt="" class="rounded-circle mt-3 profile-sm">
                                         </a>
                                         <div class="input-group input-group-sm mt-2">
                                             <textarea class="form-control reply mt-1 mention-enabled" style="resize: none; overflow-y: hidden; height: 35px;" spellcheck="false" placeholder="Reply to ${comment.author_name}..."></textarea>
                                             <button type="button" class="btn btn-portal reply-submit mt-1" style="display:none;"><i class="ri-send-plane-fill me-1"></i></button>
                                         </div>
                                     </div>
                            </li>
                        </ul>
                    </li>
                `;

                    const $comment = $(commentHtml);
                    $commentSection.append($comment);

                    // Loop through the replies data and append each reply to the comment's replies section
                    const $commentReplies = $comment.find('.comment-replies');
                    comment.replies_data.forEach((reply) => {
                        const isVerified = reply.isVerified;
                        const isPostAuthor = response.data.createdBy === reply.author_id;
                        const isUserOwns = response.data.requester.user_id === reply.author_id;
                        const reactedClass = reply.reacted ? 'text-danger' : '';
                        const authorBadgeHtml = isPostAuthor ? '<span class="text-danger small ms-1 fw-lighter" data-bs-toggle="tooltip" data-bs-placement="top" title="This user is the author of the post.">Author</span>' : '';
                        const reactCountHtml = reply.reacts > 0
                            ? `<span class="text-left text-danger heart-container" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="The total hearts received of this comment." style="font-size: 12px;">
                               <i class="bi bi-heart-fill text-danger"></i> <span class="react-count">${reply.reacts}</span>
                               </span>`
                            : `<span class="text-left text-danger heart-container" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="The total hearts received of this comment." style="font-size: 12px; display: none;">
                               <i class="bi bi-heart-fill text-danger"></i> <span class="react-count">0</span>
                               </span>`;
                        const deleteReplyHtml = `<button type="button" class="btn btn-light btn-sm btn-menu" data-bs-toggle="dropdown" aria-expanded="false" >
                                                    <i class="bi bi-three-dots"></i>
                                                    <div class="dropdown-menu dropdown-menu-end" style="overflow: hidden;">
                                                        <a class="dropdown-item delete-reply" href="javascript:void(0);" tabindex="-1"> Delete</a>                            
                                                    </div>
                                                </button>`;

                        const replyHtml = `
                        <li id="reply-${reply.id}" class="reply-item" data-replyid="${reply.id}" data-username="${reply.username}">
                            <div class="reply-content">
                                <img src="./../../assets/img/profile/${reply.author_image}" alt="avatar" class="rounded-circle mt-3 profile-sm">
                                <div class="comment-message">
                                    <div class="alert comment-box alert-dismissible" style="margin-bottom: 0px;">
                                        <h4>
                                            <a href="./../../../profile/${reply.createdBy}">${reply.author_name}</a>
                                            ${isVerified ? '<i class="ri ri-checkbox-circle-fill mt-2 text-danger" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>' : ''}
                                            ${authorBadgeHtml}
                                        </h4>
                                        <p class="prewrap text-break">${reply.content_display}</p>
                                        ${isUserOwns ? deleteReplyHtml : ''}
                                    </div>
                                    <p class="comment-meta">
                                        ${reactCountHtml}
                                        <span class="ms-2 me-2 text-secondary ${reactedClass} love-btn">Love</span>
                                        <span class="me-2 text-secondary reply-btn">Reply</span> 
                                        <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="${reply.createdDate}">${reply.time_ago}</span>
                                    </p>
                                </div>
                            </div>
                        </li>
                    `;

                        const $reply = $(replyHtml);
                        $commentReplies.prepend($reply);
                    });
                });

                const $commentingSection = $postContainer.find('.user-commenting');
                $commentingSection.empty(); // Clear any existing content

                if (response.data.commentEnabled) {
                    // Commenting is enabled, create the comment input field
                    const commentInputField = `<div class="input-group p-1">
                                                      <textarea class="form-control comment mention-enabled" style="height: 40px; resize: none;" spellcheck="false" placeholder="Write a comment..."></textarea>
                                                      <button type="button" class="btn btn-portal comment-submit" style="display:none;"><i class="ri-send-plane-fill me-1"></i></button>
                                               </div>`;
                    $commentingSection.append(commentInputField);
                } else {
                    // Commenting is disabled, create the message
                    const commentDisabledMessage = `<hr>
                                                        <p class="mt-3 text-center">Commenting is disabled by this author.</p>
                                                    `;
                    $commentingSection.append(commentDisabledMessage);
                }


                // Add your successCallback and other logic here
                successCallback(response);

            },
            error: function (response) {
                // On error (Replace this with your actual error handling)
                console.error('Error reloading post:', postId);

                // Send toast to user
                createToast('Error', response.responseJSON.message, 'danger');

                // Add your error handling logic here
            },
        });
    }

    // Bind click event to the "Heart" button
    $('.newsfeed-posts').on('click', '.heart-btn', function () {
        const $heartBtn = $(this);
        const $heartIcon = $heartBtn.find('.bi');
        const $heartCount = $heartBtn.find('.heart-count');
        const postID = $heartBtn.closest('.card.newsfeed').data('postid');

        // Send AJAX request to react to the post
        $.ajax({
            url: './../../ajax/post/react', 
            type: 'POST',
            data: { post_id: postID },
            success: function (response) {
                // On success (Replace this with your actual success handling)
                console.log('Post reacted:', postID);

                

                // Update the heart button appearance and react count
                if (response.code == 201) {
                    const positiveAudioClone = positiveAudio.cloneNode(); // Create a new instance of the audio element
                    positiveAudioClone.play();

                    $heartBtn.addClass('text-danger'); // Add a class to change the appearance of the heart button if the user has reacted
                    $heartIcon.removeClass('bi-heart').addClass('bi-heart-fill'); // Change the heart icon to filled heart
                } else {
                    $heartBtn.removeClass('text-danger'); // Remove the class if the user has un-reacted
                    $heartIcon.removeClass('bi-heart-fill').addClass('bi-heart'); // Change the heart icon back to outline heart
                }

                // Update the heart count text
                if (response.count == 0) {
                    $heartCount.text('Love'); // Update .heart-count with "Love" when the count is zero
                } else {
                    $heartCount.text(response.count); // Update .heart-count with the response count
                }
                

            },
            error: function (response) {

                // Send toast to user
                createToast("Error", response.responseJSON.message, "danger");

                // On error (Replace this with your actual error handling)
                //console.log('Error reacting to post:', postID);
            },
        });
    });

    $('.newsfeed-posts').on('click', '.comment-btn', function () {
        // Find the textarea within the same .card as the clicked button and focus on it
        $(this).closest('.card').find('.comment').focus();
    });

    // Bind events to the textarea
    $('.post')
        .on('input', function () {
            adjustTextareaHeight(this);
            const content = $(this).val().trim(); // Get the content and remove leading/trailing spaces
            const $postBtn = $('.post-btn');
            if (content === '') {
                $postBtn.hide(); // Hide the post button if the content is empty
            } else if (!$postBtn.prop('disabled')) {
                $postBtn.show(); // Show the post button when textarea is being typed in and not during posting
            }
        })
        .on('blur', function () {
            resetTextareaHeight(this);
        });

    // Bind click event to the "Post" button
    $('.post-btn').on('click', function () {
        handlePostClick();
    });

    $('.newsfeed-posts').on('input', '.comment', function () {
        const $commentTextarea = $(this);
        const commentContent = $commentTextarea.val().trim();
        const $commentSubmitBtn = $commentTextarea.closest('.input-group').find('.comment-submit');

        if (commentContent === '') {
            $commentSubmitBtn.hide(); // Hide the comment-submit button if the comment is empty
        } else {
            $commentSubmitBtn.show(); // Show the comment-submit button if the comment is not empty
        }

        adjustTextareaHeight(this);
    }).on('blur', '.comment', function () {
        resetTextareaHeight(this);
    });

    // Handle click event on the comment-submit button
    $('.newsfeed-posts').on('click', '.comment-submit', function () {
        const commentTextarea = $(this).closest('.input-group').find('.comment');
        const postId = $(this).closest('.card.newsfeed').data('postid');
        const commentContent = commentTextarea.val().trim();

        if (commentContent !== '') {
            sendCommentAjax(postId, commentContent);
        }
    });

    // Handle keypress event on the comment textarea
    $('.newsfeed-posts').on('keypress', '.comment', function (e) {
        if (e.which === 13 && !e.shiftKey) { // Check if Enter key is pressed without Shift key
            e.preventDefault(); // Prevent default behavior (form submission)
            const commentTextarea = $(this);
            const postId = $(this).closest('.card.newsfeed').data('postid');
            const commentContent = commentTextarea.val().trim();

            if (commentContent !== '') {
                sendCommentAjax(postId, commentContent);
            }
        }
    });

    // Adding event handlers for reply textareas
    $('.newsfeed-posts').on('input', '.reply', function () {
        const $replyTextarea = $(this);
        const replyContent = $replyTextarea.val().trim();
        const $replySubmitBtn = $replyTextarea.closest('.input-group').find('.reply-submit');

        if (replyContent === '') {
            $replySubmitBtn.hide(); // Hide the reply-submit button if the reply is empty
        } else {
            $replySubmitBtn.show(); // Show the reply-submit button if the reply is not empty
        }

        // Adjust the textarea height function for replies
        adjustTextareaHeight(this);
    }).on('blur', '.reply', function () {
        // Reset the textarea height function for replies
        resetTextareaHeight(this);
    });

    // Handle keypress event on the reply textarea
    $('.newsfeed-posts').on('keypress', '.reply', function (e) {
        if (e.which === 13 && !e.shiftKey) { // Check if Enter key is pressed without Shift key
            e.preventDefault(); // Prevent default behavior (form submission)
            const replyTextarea = $(this);
            const commentId = $(this).closest('.comment-item').data('commentid');
            const replyContent = replyTextarea.val().trim();

            if (replyContent !== '') {
                sendReplyAjax(commentId, replyContent);
            }
        }
    });

    $('.newsfeed-posts').on('click', '.reply-submit', function () {
        const replyTextarea = $(this).closest('.input-group').find('.reply');
        const commentId = $(this).closest('.comment-item').data('commentid'); // Get the comment ID
        const replyContent = replyTextarea.val().trim();

        if (replyContent !== '') {
            sendReplyAjax(commentId, replyContent); // Send the reply using AJAX
        }
    });



    $('.newsfeed-posts').on('click', '.love-btn', function () {
        const $loveBtn = $(this);
        const $parentLi = $loveBtn.closest('li'); // Get the parent <li> element

        // Check if the parent <li> has class 'comment-item' or 'reply-item'
        if ($parentLi.hasClass('comment-item')) {
            // If it's a comment item, get the data-commentid
            const commentID = $parentLi.data('commentid');

            // Send AJAX request to react to the comment
            $.ajax({
                url: './../../ajax/post/reactComment', // Replace with your actual URL for reacting to a comment
                type: 'POST',
                data: { comment_id: commentID }, // The content is the commentID
                success: function (response) {
                    // On success (Replace this with your actual success handling)
                    console.log('Comment reacted:', commentID);

                    // Update the "Love" button appearance and react count
                    if (response.code === 201) {
                        $loveBtn.addClass('text-danger');

                        const positiveAudioClone = positiveAudio.cloneNode(); // Create a new instance of the audio element
                        positiveAudioClone.play();
                    } else {
                        $loveBtn.removeClass('text-danger');
                    }

                    // Update the react count text
                    const $reactCount = $loveBtn.siblings('.heart-container').find('.react-count');
                    $reactCount.text(response.count);

                    // Hide the heart container if the heart count is 0
                    const $heartContainer = $loveBtn.siblings('.heart-container');
                    if (response.count === "0") {
                        $heartContainer.hide();
                    } else {
                        $heartContainer.show();
                    }
                },
                error: function (response) {
                    // On error (Replace this with your actual error handling)
                    //console.log('Error reacting to comment:', commentID);

                    // Send toast to user
                    createToast("Error", response.responseJSON.message, "danger");
                },
            });
        } else if ($parentLi.hasClass('reply-item')) {
            // If it's a reply item, get the data-replyid
            const replyID = $parentLi.data('replyid');

            // Send AJAX request to react to the reply
            $.ajax({
                url: './../../ajax/post/reactReply', // Replace with your actual URL for reacting to a reply
                type: 'POST',
                data: { reply_id: replyID }, // The content is the replyID
                success: function (response) {
                    // On success (Replace this with your actual success handling)
                    console.log('Reply reacted:', replyID);

                    // Update the "Love" button appearance and react count for the reply
                    if (response.code === 201) {
                        $loveBtn.addClass('text-danger');

                        const positiveAudioClone = positiveAudio.cloneNode(); // Create a new instance of the audio element
                        positiveAudioClone.play();
                    } else {
                        $loveBtn.removeClass('text-danger');
                    }

                    // Update the react count text for the reply
                    const $reactCount = $loveBtn.siblings('.heart-container').find('.react-count');
                    $reactCount.text(response.count);

                    // Hide the heart container if the heart count is 0
                    const $heartContainer = $loveBtn.siblings('.heart-container');
                    if (response.count === "0") {
                        $heartContainer.hide();
                    } else {
                        $heartContainer.show();
                    }
                },
                error: function (response) {
                    // On error (Replace this with your actual error handling)
                    //console.log('Error reacting to reply:', replyID);

                    // Send toast to user
                    createToast("Error", response.responseJSON.message, "danger");
                },
            });
        }
    });

    $('.newsfeed-posts').on('click', '.reply-btn', function () {
        // Find the closest parent li with the class "comment-item" or "reply-item"
        var parentItem = $(this).closest('.comment-item, .reply-item');

        // Find the appropriate reply-item within the same parent item
        var replyItem = parentItem.hasClass('comment-item') ?
            parentItem.find('.reply-item') :
            parentItem.closest('.comment-item').find('.reply-item');

        // Remove the "visually-hidden" class from the reply item
        replyItem.removeClass('visually-hidden');

        // Find the textarea within the reply item
        var textarea = replyItem.find('.reply');

        // Fetch the data-username attribute based on the context
        var commentUsername = parentItem.data('username');

        // Get the current text in the textarea
        var currentText = textarea.val();

        // Add the fetched username to the existing text as "@username"
        textarea.val(currentText + ' @' + commentUsername + ' ');

        // Calculate the vertical scroll position to center the textarea
        var scrollTop = textarea.offset().top - ($(window).height() - textarea.height()) / 2;

        // Animate the scroll to the calculated position with a smooth effect
        $('html, body').animate({
            scrollTop: scrollTop
        }, 500, function () {
            // After the animation, focus on the textarea for quick typing
            textarea.focus();
        });

        // Remove the "Load More Replies" button if it exists in this parent item
        parentItem.find('.load-more-replies-btn').remove();
    });


    // JavaScript to handle "Load More Replies" button click
    $('.newsfeed-posts').on('click', '.load-more-replies-btn', function () {
        // Hide the "Load More Replies" button immediately
        $(this).hide();

        const replyItems = $(this).closest('.comment-item').find('.reply-item.visually-hidden');

        // Display hidden replies
        replyItems.removeClass('visually-hidden');

        // Check if there are any hidden reply items left
        if (replyItems.length === 0) {
            $(this).closest('.load-more-replies').remove();
        }
    });

    $('.newsfeed-posts').on('click', '.share-post', function () {
        // get the domain and the post ID
        const domain = location.protocol + '//' + location.host;
        const postID = $(this).closest('.card.newsfeed').data('postid');

        // create a temporary input element to store the URL
        const input = document.createElement('input');
        input.value = `${domain}/newsfeed/${postID}`;
        document.body.appendChild(input);

        // select the URL text and copy it to the clipboard
        input.select();
        document.execCommand('copy');

        // remove the temporary input element
        document.body.removeChild(input);

        // show a feedback message to the user
        createToast("Success", "Copied successfully", "success");
    });

    // Bind click event to .disable-comment anchor tags
    $('.newsfeed-posts').on('click', 'a.disable-comment', function (event) {
        event.preventDefault(); // Prevent the default behavior of the anchor tag

        const $disableCommentButton = $(this);
        const postId = $disableCommentButton.closest('.card.newsfeed').data('postid');
        const commentEnabled = $disableCommentButton.find('i').hasClass('bi-toggle-on'); // Check if the comment is currently enabled or disabled

        // Send AJAX request to toggle comments for the post
        $.ajax({
            url: './../../ajax/post/toggleComment', // Replace with your actual URL for toggling comments
            type: 'POST',
            data: {
                post_id: postId,
                comment_enabled: commentEnabled // Send the value of commentEnabled to the server
            },
            success: function (response) {
                // On success, update the .disable-comment element based on the response
                if (response && response.data && response.data.commentEnabled) {
                    if (response.data.commentEnabled === "true") {
                        $disableCommentButton.find('i').removeClass('bi-toggle-on text-danger').addClass('bi-toggle-off');
                        createToast('Success', response.message, 'success');
                    } else {
                        $disableCommentButton.find('i').removeClass('bi-toggle-off').addClass('bi-toggle-on text-danger');
                        createToast('Success', response.message, 'success');
                    }

                    // Call the reloadPost function and pass a callback
                    reloadPost(postId, function (response) {
                        if (response) {
                            // Tip: Code here if you want to add more things to do when success.
                        }
                    });
                } else {
                    createToast('Error', 'Failed to toggle comments.', 'danger');
                }
            },
            error: function (response) {
                // On error (Replace this with your actual error handling)
                console.error('Error toggling comments:', response);
                createToast('Error', 'An error occurred while toggling comments.', 'danger');
            },
        });
    });

    $('.newsfeed-posts').on('click', 'button.reload-post', function (event) {
        event.preventDefault(); // Prevent the default behavior of the button

        const $reloadPostButton = $(this);
        const $spinner = $reloadPostButton.find('.spinner-border'); // Get the spinner element
        const $arrowIcon = $reloadPostButton.find('.bi-arrow-counterclockwise'); // Get the arrow icon element

        // Check if the button is already loading (has the loading class)
        if (!$reloadPostButton.hasClass('loading')) {
            $reloadPostButton.addClass('loading'); // Add loading class to the button
            $spinner.removeClass('d-none'); // Show the spinner
            $arrowIcon.addClass('d-none'); // Hide the arrow icon

            // Get the post ID from the data attribute or any other way appropriate to your HTML structure
            const postId = $reloadPostButton.closest('.card.newsfeed').data('postid');

            // Call the reloadPost function and pass a callback
            reloadPost(postId, function (response) {
                // Callback function triggered after reloadPost is complete

                if (response) {
                    // Tip: Code here if you want to add more things to do when success.
                }

                // Remove loading class and hide the spinner, show the arrow icon
                $reloadPostButton.removeClass('loading');
                $spinner.addClass('d-none');
                $arrowIcon.removeClass('d-none');
            });
        }
    });

    $('.newsfeed-posts').on('click', 'a.report-post', function (event) {
        event.preventDefault(); // Prevent the default behavior of the button

        const $reportPostButton = $(this);
        const postId = $reportPostButton.closest('.card.newsfeed').data('postid');

        // Perform the AJAX request to delete the post
        //$.ajax({
        //    method: 'POST',
        //    url: './../../ajax/post/delete', // Replace this with the actual server endpoint
        //    data: { post_id: postId }, // Send the postId to the server
        //    success: function (response) {
        //        $deletePostButton.closest('.card.newsfeed').remove();
        //        createToast("Success", response.message, "success");
        //    },
        //    error: function () {
        //        // Handle the AJAX error (optional)
        //        console.log('Error occurred during post deletion.');
        //    }
        //});

        createToast("Info", "This feature is not yet implemented.", "info");
    });

    $('.newsfeed-posts').on('click', 'a.delete-post', function (event) {
        event.preventDefault(); // Prevent the default behavior of the button

        const $deletePostButton = $(this);
        const postId = $deletePostButton.closest('.card.newsfeed').data('postid');

        // Perform the AJAX request to delete the post
        $.ajax({
            method: 'POST',
            url: './../../ajax/post/delete', // Replace this with the actual server endpoint
            data: { post_id: postId }, // Send the postId to the server
            success: function (response) {
                $deletePostButton.closest('.card.newsfeed').remove();
                createToast("Success", response.message, "success");
            },
            error: function () {
                // Handle the AJAX error (optional)
                console.log('Error occurred during post deletion.');
            }
        });
    });

    $('.newsfeed-posts').on('click', 'a.delete-comment', function (event) {
        event.preventDefault(); // Prevent the default behavior of the button

        const $deletePostButton = $(this);
        const commentId = $deletePostButton.closest('.comment-item').data('commentid');

        // Perform the AJAX request to delete the post
        $.ajax({
            method: 'POST',
            url: './../../ajax/post/deleteComment', // Replace this with the actual server endpoint
            data: { comment_id: commentId }, // Send the postId to the server
            success: function (response) {
                $deletePostButton.closest('.comment-item').remove();
                createToast("Success", response.message, "success");
            },
            error: function () {
                // Handle the AJAX error (optional)
                console.log('Error occurred during post deletion.');
            }
        });
    });

    $('.newsfeed-posts').on('click', 'a.delete-reply', function (event) {
        event.preventDefault(); // Prevent the default behavior of the button

        const $deleteReplyButton = $(this);
        const replyId = $deleteReplyButton.closest('.reply-item').data('replyid');

        // Perform the AJAX request to delete the reply
        $.ajax({
            method: 'POST',
            url: './../../ajax/post/deleteReply', // Replace this with the actual server endpoint
            data: { reply_id: replyId }, // Send the replyId to the server
            success: function (response) {
                $deleteReplyButton.closest('.reply-item').remove();
                createToast("Success", response.message, "success");
            },
            error: function () {
                // Handle the AJAX error (optional)
                console.log('Error occurred during reply deletion.');
            }
        });
    });

    // Event handler for clicking the "Edit post" option
    $('.newsfeed-posts').on('click', '.edit-post', function (event) {
        event.preventDefault();

        const $editPostButton = $(this);
        const $postCard = $editPostButton.closest('.card.newsfeed');
        const $postContent = $postCard.find('.newsfeed-body .prewrap');
        const $editSection = $postCard.find('.edit-post-section');
        const $editInput = $editSection.find('#editPostContent');

        // Cancel editing for the previously focused editPostContent (if any)
        if ($focusedEditInput && $focusedEditInput[0] !== $editInput[0]) {
            cancelEdit($focusedEditInput);
        }

        // Set the currently focused editPostContent
        $focusedEditInput = $editInput;

        // Hide the original post content and show the edit input field
        $postContent.hide();
        $editSection.show();

        // Populate the edit input field with the original post content
        const originalContent = $postContent.text().trim();
        $editInput.val(originalContent);

        // Focus on the edit input field
        $editInput.focus();
    });

    // Event handler for clicking the "Save" button to save the edited post
    $('.newsfeed-posts').on('click', '.save-edited-post', function (event) {
        const $saveButton = $(this);
        const $postCard = $saveButton.closest('.card.newsfeed');
        const $postContent = $postCard.find('.prewrap');
        const $editSection = $postCard.find('.edit-post-section');
        const $editInput = $editSection.find('#editPostContent');

        // Get the edited content from the input field
        const editedContent = $editInput.val().trim();

        // Perform AJAX request to update the post content
        $.ajax({
            method: 'POST',
            url: './../../ajax/post/update', // Replace this with the actual server endpoint for updating the post content
            data: { post_id: $postCard.data('postid'), content: editedContent }, // Send the post ID and edited content to the server
            success: function (response) {
                if (response.result) {
                    createToast("Done", response.message, "success");
                    $postContent.text(editedContent).show();
                    $editSection.hide();
                } else {
                    createToast("Done", response.message, "warning");
                }
            },
            error: function (response) {
                createToast("Error", response.responseJSON.message, "danger");
            }
        });

        // Reset the currently focused editPostContent after saving
        $focusedEditInput = null;
    });

    // Event handler for clicking the "Cancel" button to cancel the edit
    $('.newsfeed-posts').on('click', '.cancel-edit-post', function (event) {
        const $cancelButton = $(this);
        const $postCard = $cancelButton.closest('.card.newsfeed');
        const $postContent = $postCard.find('.prewrap');
        const $editSection = $postCard.find('.edit-post-section');
        const $editInput = $editSection.find('#editPostContent');

        cancelEdit($editInput);
    });

    // Event handler for "Escape" key press to cancel the editing
    $(document).on('keydown', function (event) {
        if (event.key === 'Escape' && $focusedEditInput) {
            cancelEdit($focusedEditInput);
        }
    });

    // Function to cancel editing for a specific editPostContent
    function cancelEdit($editInput) {
        const $postCard = $editInput.closest('.card.newsfeed');
        const $postContent = $postCard.find('.prewrap');
        const $editSection = $postCard.find('.edit-post-section');

        // Hide the edit input field and show the original post content
        $editSection.hide();
        $postContent.show();

        // Reset the currently focused editPostContent
        $focusedEditInput = null;
    }

    // Add a click event listener to the "View Edit History" button
    // Add a click event listener to the "View Edit History" button
    $('.newsfeed-posts').on('click', '.edit-history', function (event) {
        const $editHistoryButton = $(this);
        const $postCard = $editHistoryButton.closest('.card.newsfeed');

        // Get the post ID from the data attribute
        const postId = $postCard.data('postid');

        // Perform an AJAX request to fetch the edit history
        $.ajax({
            method: 'POST',
            url: './../ajax/post/history', // Replace this with the actual server endpoint for fetching edit history
            data: { post_id: postId }, // Send the post ID to the server
            success: function (response) {
                if (response.result) {
                    // Handle the successful response and populate the "Edit History" modal
                    const editHistoryData = response.data.content_history;
                    const $modal = $('#editHistory');
                    const $historyContainer = $modal.find('.history-container');

                    // Clear existing edit history items
                    $historyContainer.empty();

                    const author_image = response.data.author_image;
                    const author_name = response.data.author_name;
                    const author_id = response.data.createdBy;
                    // Populate the modal with the edit history
                    editHistoryData.forEach(function (historyItem) {
                        const historyTimestamp = historyItem.timestamp;
                        const historyContent = historyItem.content;


                        // Create a new edit history item element
                        const $historyItemElement = $('<div class="history-item">');
                        $historyItemElement.html(`
                        <span class="history-date fw-semibold">${historyTimestamp}</span>
                            <div class= "alert alert-light mt-3" role="alert">
                            <div class="d-flex flex-row mb-3">
                                <div>
                                    <a href="./../../../profile/${author_id}"><img src="./../../assets/img/profile/${author_image}" alt="Profile" class="rounded-circle profile-sm me-1"></a>
                 </div>
                                    <div class="align-self-center p-2">
                                        <a href="./../../../profile/${author_id}" class="text-portal fw-medium">${author_name}</a>
                                    </div>
                                </div>
                ${historyContent}
            </div>`);

                        // Append the edit history item to the history container
                        $historyContainer.append($historyItemElement);
                    });

                    // Show the "Edit History" modal
                    $modal.modal('show');
                } else {
                    // Handle the case where there is no edit history or an error occurred
                    console.error('Error fetching edit history:', response.message);
                    // Display an error message or handle it as needed
                }
            },
            error: function (error) {
                console.error('Error fetching edit history:', error);
                // Display an error message or handle it as needed
            }
        });
    });

    //$('.mention-enabled').on('input', function () {
    //    const textarea = $(this);
    //    const text = textarea.val();
    //    const mentionRegex = /@([\w-]+)/g;
    //    const mentions = text.match(mentionRegex);
    //
    //    // Get or create the suggestion container for this specific textarea
    //    let suggestionContainer = textarea.data('suggestionContainer');
    //
    //    if (!suggestionContainer) {
    //        suggestionContainer = $('<div class="user-suggestions"></div>');
    //        textarea.data('suggestionContainer', suggestionContainer);
    //        textarea.after(suggestionContainer);
    //    }
    //
    //    if (mentions) {
    //        const query = mentions[mentions.length - 1].substring(1); // Get the latest mention query
    //
    //        // Make an AJAX request to fetch matching users with avatars
    //        $.ajax({
    //            url: './../../../ajax/user/find', // Update the URL to your server endpoint
    //            method: 'POST', // Use POST method
    //            data: { query: query }, // Send the query to the server
    //            success: function (data) {
    //                const userResults = data.users;
    //
    //                if (userResults && userResults.length > 0) {
    //                    // Create a suggestion list within the container
    //                    const suggestionList = $('<ul class="dropdown-menu show" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 33px);" data-popper-placement="bottom-start"></ul>');
    //
    //                    // Populate the suggestion list with matching users
    //                    userResults.forEach(user => {
    //                        const userElement = $('<li class="dropdown-item d-flex align-items-center"></li>');
    //
    //                        // Attach click event to the suggestion element
    //                        userElement.click(function () {
    //                            // Clean the mention (remove "@") and insert the full name into the textarea at the current caret position
    //                            const cleanedMention = query.replace('@', '');
    //                            insertFullNameIntoTextarea(textarea, cleanedMention, user.full_name);
    //
    //                            // Hide the suggestion container
    //                            suggestionContainer.empty().hide();
    //                        });
    //
    //                        // Add user avatar and full name to the suggestion element
    //                        userElement.append('<img src="./../../assets/img/profile/' + user.avatar + '" style="max-height: 40px;" alt="Profile" class="rounded-circle profile-sm">');
    //                        userElement.append('<h5 class="ms-2 mt-2">' + user.full_name + '</h5>');
    //
    //                        suggestionList.append(userElement);
    //                    });
    //
    //                    // Update the suggestion container content
    //                    suggestionContainer.html(suggestionList);
    //
    //                    // Show the suggestion container
    //                    suggestionContainer.show();
    //                } else {
    //                    // Remove existing suggestions if no matching users found
    //                    suggestionContainer.empty().hide();
    //                }
    //            },
    //            error: function (error) {
    //                console.error('Error fetching user suggestions:', error);
    //            }
    //        });
    //    } else {
    //        // Remove existing suggestions if no mentions
    //        suggestionContainer.empty().hide();
    //    }
    //});
    //
    //// Function to insert a full name into a textarea
    //function insertFullNameIntoTextarea(textarea, mention, fullName) {
    //    const text = textarea.val();
    //    const mentionPos = text.lastIndexOf(mention);
    //    const newText = text.slice(0, mentionPos) + '' + fullName + ' ' + text.slice(mentionPos + mention.length);
    //    textarea.val(newText).focus();
    //}

    $('.newsfeed-posts').on('focus', '.mention-enabled', function () {
        $(this).suggest('@', {
        // Remove the static 'data' property and use AJAX for real-time suggestions
        data: function (query, callback) {
            // Send an AJAX request to fetch suggestions based on the 'query'
            $.ajax({
                type: 'POST',
                url: './../../../ajax/user/find', // Replace with your AJAX endpoint
                data: { query: query }, // Pass the query to the server
                dataType: 'json',
                success: function (response) {
                    if (response.result === true) {
                        // Map the AJAX response to the format expected by bootstrap-suggest
                        var suggestions = response.users.map(function (user) {
                            var disVer = '';
                            if (user.isVerified) {
                                disVer = ' <i class="ri ri-checkbox-circle-fill mt-2 text-danger" style="font-size: 12px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>';
                            }
                            var text = '<div class="d-flex">' +
                                '<img src="./../../assets/img/profile/' + user.avatar + '" style="max-height: 40px;" alt="Profile" class="rounded-circle profile-sm mt-1">' +
                                '<div class="ms-2">' +
                                '<dt>' + user.username + ' ' + disVer + '</dt>' +
                                '<dl><small class="fw-normal">' + user.full_name + '</small></dl>' +
                                '</div>' +
                                '</div>';
                            return {
                                value: user.username,
                                text: text
                            };
                        });

                        // Call the 'callback' function with the suggestions
                        callback(suggestions);
                    } else {
                        console.error('Request was not successful.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
        });
    });

    $(window).on('scroll', function () {
        // Check if the user has scrolled near the bottom
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
            console.log('Scrolled near the bottom. Loading more posts...');

            if (error) {
                $('#postLoading').addClass('visually-hidden');
            } else {
                $('#postLoading').removeClass('visually-hidden');

                loadMorePosts();
            }
            
        }
    });


    // Function to load more posts
    function loadMorePosts() {
        if (loading || error) {
            console.log('Skipping request. Loading:', loading, 'Error:', error);
            return; // If already loading or an error has occurred, don't trigger another request
        }

        loading = true; // Set loading flag

        const container = $('.newsfeed-container');

        $.ajax({
            url: './../../../ajax/post/getPosts',
            type: 'POST',
            data: { offset: offset },
            success: function (response) {

                if (response.result) {

                    // Iterate through the response data and create post cards
                    $.each(response.data, function (index, post) {
                        createPostCard(post, 'buttom');
                    });

                    loading = false;
                    console.log('Posts loaded successfully.');

                    offset += 20; // Update offset for the next request

                    // Add the class 'visually-hidden' to hide the loading indicator
                    $('#postLoading').addClass('visually-hidden');
                }

            },
            error: function () {
                loading = false; // Reset loading flag in case of error
                error = true; // Set the error flag to true

                // Add the class 'visually-hidden' to hide the loading indicator
                $('#postLoading').addClass('visually-hidden');

                // Display the error message and image
                const errorHtml = `
                    <p class="mt-3 text-center fs-5 text fw-semibold">You've reached the bottom of the newsfeed!</p>
                    <p class="mt-3 text-center fs-6 fw-light">But sadly, the bottom of our oceans are filled with harmful pollution. Take action now to help preserve our ocean's beauty and life!</p>
                    <img src="../assets/img/svg/water.jpg" class="img-fluid mb-5" alt="Page Not Found">
                `;

                // Append the error HTML to the '.newsfeed-posts' element
                $('.newsfeed-posts').append(errorHtml);
            }
        });
    }

});
