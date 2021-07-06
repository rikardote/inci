<div class="modal fade zoom" id="form-modal" tabindex="8" role="dialog" aria-labelledby="form-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header panelColor">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="form-modal">{{ $title }}</h4>
            </div>
            <div class="modal-body" >
                @yield('body')
            </div>
        </div>
    </div>
</div>

