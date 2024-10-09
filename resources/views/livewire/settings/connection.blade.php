<div class="row">
      <div class="col-md-6 col-12 mb-md-0 mb-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title pb-0">Connect Accounts</h5>
            <p class="mb-4">Link your account to enable faster login.</p>
            <!-- Connections -->
            <div class="d-flex mb-3">
              <div class="flex-shrink-0">
                <img src="{{ asset('img/icons/brands/google.png') }}" alt="google" class="me-3" height="30">
              </div>
              <div class="flex-grow-1 row">
                <div class="col-9">
                  <h6 class="mb-0">Google</h6>
                  @if($connectGoogle)
                    <small class="fw-light text-success">Account is linked with this service.</small>
                  @else
                    <small class="text-muted">Service not linked</small>
                  @endif
                </div>
                <div class="col-3 d-flex align-items-center justify-content-end mt-sm-0 mt-2">
                  @if($connectGoogle)
                    <button class="btn bg-secondary btn-icon" disabled><i class="ri ri-link ri-sm"></i></button>
                  @else
                    <a href="{{ route('login.google', ['link' => 'true']) }}" class="btn bg-secondary btn-icon" disabled><i class="ri ri-link ri-sm"></i></a>
                  @endif
                </div>
              </div>
            </div>
            <!-- /Connections -->
          </div>
        </div>
      </div>
      <div class="col-md-6 col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title pb-0">Social Accounts</h5>
            <p>Connect with others by sharing your social media profiles.</p>
            <!-- Social Accounts -->

            @if($fieldEditing != 'socialFacebook')
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/facebook.png') }}" alt="facebook" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7">
                    <h6 class="mb-0">Facebook</h6>
                    @if (empty($socialFacebook))
                        <small class="text-muted">Not Connected</small>
                    @else
                        <a href="https://www.facebook.com/{{ $socialFacebook }}" target="_blank">{{ '@' . $socialFacebook }}</a>
                    @endif
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn bg-secondary btn-icon" wire:click="editField('socialFacebook')"><i class="ri {{ empty($socialFacebook) ? 'ri-link' : 'ri-edit-box-line' }} ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @else
              <div class="d-flex mb-3 align-items-center">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/facebook.png') }}" alt="instagram" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7 mb-0">
                    <div class="form-floating">
                      <input wire:model="fieldData" type="text" class="form-control @error('fieldData') is-invalid @enderror">
                      @error('fieldData')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @else
                          <div class="invalid-feedback">Please enter your username</div>
                      @enderror
                      <label for="floatingName">Username</label>
                    </div>
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn btn-portal btn-icon" wire:click="saveField('socialFacebook')"><i class="ri ri-save-2-line ri-sm"></i></button>
                    <button class="btn btn-secondary btn-icon" wire:click="cancelEdit('socialFacebook')"><i class="ri ri-delete-bin-2-line ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @endif

            @if($fieldEditing != 'socialTwitter')
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/twitter.png') }}" alt="twitter" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7">
                    <h6 class="mb-0">Twitter</h6>
                    @if (empty($socialTwitter))
                        <small class="text-muted">Not Connected</small>
                    @else
                        <a href="https://twitter.com/{{ $socialTwitter }}" target="_blank">{{ '@' . $socialTwitter }}</a>
                    @endif
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn bg-secondary btn-icon" wire:click="editField('socialTwitter')"><i class="ri {{ empty($socialTwitter) ? 'ri-link' : 'ri-edit-box-line' }} ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @else
              <div class="d-flex mb-3 align-items-center">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/twitter.png') }}" alt="instagram" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7 mb-0">
                    <div class="form-floating">
                      <input wire:model="fieldData" type="text" class="form-control @error('fieldData') is-invalid @enderror">
                      @error('fieldData')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @else
                          <div class="invalid-feedback">Please enter your username</div>
                      @enderror
                      <label for="floatingName">Username</label>
                    </div>
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn btn-portal btn-icon" wire:click="saveField('socialTwitter')"><i class="ri ri-save-2-line ri-sm"></i></button>
                    <button class="btn btn-secondary btn-icon" wire:click="cancelEdit('socialTwitter')"><i class="ri ri-delete-bin-2-line ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @endif

            @if($fieldEditing != 'socialInstagram')
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/instagram.png') }}" alt="twitter" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7">
                    <h6 class="mb-0">Instagram</h6>
                    @if (empty($socialInstagram))
                        <small class="text-muted">Not Connected</small>
                    @else
                        <a href="https://www.instagram.com/{{ $socialInstagram }}" target="_blank">{{ '@' . $socialInstagram }}</a>
                    @endif
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn bg-secondary btn-icon" wire:click="editField('socialInstagram')"><i class="ri {{ empty($socialInstagram) ? 'ri-link' : 'ri-edit-box-line' }} ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @else
              <div class="d-flex mb-3 align-items-center">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/instagram.png') }}" alt="instagram" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7 mb-0">
                    <div class="form-floating">
                      <input wire:model="fieldData" type="text" class="form-control @error('fieldData') is-invalid @enderror">
                      @error('fieldData')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @else
                          <div class="invalid-feedback">Please enter your username</div>
                      @enderror
                      <label for="floatingName">Username</label>
                    </div>
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn btn-portal btn-icon" wire:click="saveField('socialInstagram')"><i class="ri ri-save-2-line ri-sm"></i></button>
                    <button class="btn btn-secondary btn-icon" wire:click="cancelEdit('socialInstagram')"><i class="ri ri-delete-bin-2-line ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @endif

            @if($fieldEditing != 'socialYoutube')
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/youtube.png') }}" alt="twitter" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7">
                    <h6 class="mb-0">Youtube</h6>
                    @if (empty($socialYoutube))
                        <small class="text-muted">Not Connected</small>
                    @else
                        <a href="https://youtube.com/{{ '@'.$socialYoutube }}" target="_blank">{{ '@' . $socialYoutube }}</a>
                    @endif
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn bg-secondary btn-icon" wire:click="editField('socialYoutube')"><i class="ri {{ empty($socialYoutube) ? 'ri-link' : 'ri-edit-box-line' }} ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @else
              <div class="d-flex mb-3 align-items-center">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/youtube.png') }}" alt="instagram" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7 mb-0">
                    <div class="form-floating">
                      <input wire:model="fieldData" type="text" class="form-control @error('fieldData') is-invalid @enderror">
                      @error('fieldData')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @else
                          <div class="invalid-feedback">Please enter your Bio</div>
                      @enderror
                      <label for="floatingName">Username</label>
                    </div>
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn btn-portal btn-icon" wire:click="saveField('socialYoutube')"><i class="ri ri-save-2-line ri-sm"></i></button>
                    <button class="btn btn-secondary btn-icon" wire:click="cancelEdit('socialYoutube')"><i class="ri ri-delete-bin-2-line ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @endif

            @if($fieldEditing != 'socialTiktok')
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/tiktok.png') }}" alt="twitter" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7">
                    <h6 class="mb-0">TikTok</h6>
                    @if (empty($socialTiktok))
                        <small class="text-muted">Not Connected</small>
                    @else
                        <a href="https://www.tiktok.com/{{ '@'.$socialTiktok }}" target="_blank">{{ '@' . $socialTiktok }}</a>
                    @endif
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn bg-secondary btn-icon" wire:click="editField('socialTiktok')"><i class="ri {{ empty($socialTiktok) ? 'ri-link' : 'ri-edit-box-line' }} ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @else
              <div class="d-flex mb-3 align-items-center">
                <div class="flex-shrink-0">
                  <img src="{{ asset('img/icons/brands/tiktok.png') }}" alt="instagram" class="me-3" height="38">
                </div>
                <div class="flex-grow-1 row">
                  <div class="col-7 mb-0">
                    <div class="form-floating">
                      <input wire:model="fieldData" type="text" class="form-control @error('fieldData') is-invalid @enderror">
                      @error('fieldData')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @else
                          <div class="invalid-feedback">Please enter your Bio</div>
                      @enderror
                      <label for="floatingName">Username</label>
                    </div>
                  </div>
                  <div class="col-5 text-end mt-sm-0 mt-2">
                    <button class="btn btn-portal btn-icon" wire:click="saveField('socialTiktok')"><i class="ri ri-save-2-line ri-sm"></i></button>
                    <button class="btn btn-secondary btn-icon" wire:click="cancelEdit('socialTiktok')"><i class="ri ri-delete-bin-2-line ri-sm"></i></button>
                  </div>
                </div>
              </div>
            @endif

           
            
            <!-- /Social Accounts -->
          </div>
        </div>
      </div>
    </div>