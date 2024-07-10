@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Account Settings</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
          <li class="breadcrumb-item">Settings</li>
          <li class="breadcrumb-item active">Account</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section mt-4">
        <!-- Pills Tabs -->
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $page === 'account' ? 'active' : '' }}" href="{{ route('account.show', ['page' => 'account']) }}" type="button">
                <i class="ri ri-user-3-line me-1"></i> Account</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $page === 'security' ? 'active' : '' }}" href="{{ route('account.show', ['page' => 'security']) }}" type="button">
                <i class="ri ri-lock-line me-1"></i> Security</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $page === 'notification' ? 'active' : '' }}" href="{{ route('account.show', ['page' => 'notification']) }}" type="button">
                  <i class="ri ri-notification-4-line me-1"></i> Notification</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $page === 'connection' ? 'active' : '' }}" href="{{ route('account.show', ['page' => 'connection']) }}" type="button">
                  <i class="ri ri-link me-1"></i> Connection</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $page === 'qrcode' ? 'active' : '' }}" href="{{ route('account.show', ['page' => 'qrcode']) }}" type="button">
                  <i class="ri ri-qr-code-line me-1"></i> My QRCode</a>
            </li>
        </ul>
        <!--/ Pills Tabs -->
        @if($page == 'account')
            @livewire('settings.account', ['user' => auth()->user()])
        @elseif($page == 'notification')
            @livewire('settings.notification', ['user' => auth()->user()])
        @elseif($page == 'security')
            @livewire('settings.security', ['user' => auth()->user()])
        @elseif($page == 'connection')
            @livewire('settings.connection', ['user' => auth()->user()])
        @elseif($page == 'qrcode')
            @livewire('settings.qrcode', ['user' => auth()->user()])
        @endif

        <div class="modal" id="uploadAvatar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="uploadAvatarLabel">Upload Profile</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div id="resizer"></div>

                  <div class="d-flex justify-content-between mb-3">
                    <button class="btn btn-secondary rotate btn-sm" data-deg="90" > 
                      <i class="bx bx-rotate-left"></i>
                    </button>

                    <button class="btn btn-secondary rotate btn-sm" data-deg="-90" > 
                      <i class="bx bx-rotate-right"></i>
                    </button>
                  </div>
                  
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" id="uploadBtn" class="btn btn-primary btn-sm">Upload</button>
              </div>
            </div>
          </div>
        </div>
        

    </section>

  </main><!-- End #main -->

  

<script>
$(function() {

    var croppie = null;
    var el = document.getElementById('resizer');

    $.base64ImageToBlob = function(str) {
        // extract content type and base64 payload from original string
        var pos = str.indexOf(';base64,');
        var type = str.substring(5, pos);
        var b64 = str.substr(pos + 8);
      
        // decode base64
        var imageContent = atob(b64);
      
        // create an ArrayBuffer and a view (as unsigned 8-bit)
        var buffer = new ArrayBuffer(imageContent.length);
        var view = new Uint8Array(buffer);
      
        // fill the view, using the decoded base64
        for (var n = 0; n < imageContent.length; n++) {
          view[n] = imageContent.charCodeAt(n);
        }
      
        // convert ArrayBuffer to Blob
        var blob = new Blob([buffer], { type: type });
      
        return blob;
    }

    $.getImage = function(input, croppie) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {  
                croppie.bind({
                    url: e.target.result,
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#upload").on("change", function(event) {
        $('#uploadAvatar').modal('show');

        croppie = new Croppie(el, {
          enableExif: true,
          viewport: {
              width: 200,
              height: 200,
              type: 'square'
          },
          boundary: {
              width: 250,
              height: 250
          },
          enableOrientation: true,
      });

      $.getImage(event.target, croppie); 
    });

    $("#uploadBtn").on("click", function() {
      croppie.result('base64',  'original', null, 1, false).then(function(base64) {
          $("#uploadAvatar").modal("hide"); 

          //Data to send
          var requestData = {
              profile_picture: base64
          };

          // Define success and error callbacks
          function successCallback(response) {
              if (response.result === true) {
                  $("#profile-pic").attr("src", base64);
                  createToast('Success', response.message, 'success');
              } else {
                  //$("#profile-pic").attr("src", "/images/icon-cam.png");
                  createToast('error', response.message, 'danger');
                  console.log(response.message);
              }
          }

          function errorCallback(response) {
              //$("#profile-pic").attr("src", "/images/icon-cam.png");
              createToast('error', response.message, 'danger');
              console.error(response.message);
          }

          sendAjaxRequest('upload', requestData, successCallback, errorCallback);
      });
  });


    // To Rotate Image Left or Right
    $(".rotate").on("click", function() {
        croppie.rotate(parseInt($(this).data('deg'))); 
    });

    $('#uploadAvatar').on('hidden.bs.modal', function (e) {
        // To ensure that old croppie instance is destroyed on every model close
        setTimeout(function() { croppie.destroy(); }, 100);
    })

});
</script>
@endsection
