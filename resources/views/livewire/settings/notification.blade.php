<div class="card">
    <div class="card-body">
        <h5 class="card-title">Notifications</h5>

        <div class="tab-content pt-2" id="myTabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="home-tab">
            
            <form class="row">

                <div class="table-responsive">
                    <table class="table table-striped border-top">
                    <thead>
                        <tr>
                        <th class="text-nowrap">Type</th>
                        <th class="text-nowrap text-center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-nowrap">New for you</td>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" id="defaultCheck1" checked="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">Account activity</td>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" id="defaultCheck4" checked="">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </div>

                <div class="text-start mt-2">
                    <button type="button" class="btn btn-portal" wire:click="submitForm">Save Changes</button>
                    <button type="button" class="btn btn-secondary" wire:click="resetForm">Reset</button>
                </div>


            </form>

        </div>
        
        </div>

    </div>
</div>