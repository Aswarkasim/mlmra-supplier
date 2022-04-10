<script id="requirements-item-colors-template" type="x-tmpl-mustache">
    <li class="item-requirements">
        <span class="handle">
            <i class="fa fa-ellipsis-v"></i>
            <i class="fa fa-ellipsis-v"></i>
        </span>
        <input type="hidden" class="js-colors-requirements" name="requirements_label[]" value="@{{requirementsLabel}}">
        <span class="text js-colors-requirements-label">@{{requirementsLabel}}</span>
        <div class="tools">
            <i class="fa fa-edit" data-toggle="modal" data-target="#modal-colors-requirements"></i>
            <i class="fa fa-trash-o js-remove-requirements-content"></i>
        </div>
    </li>
</script>

