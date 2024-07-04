<div>
    <ul>
        {!!$htmlRenderCurrentUser!!}
        <div class="flex items-center mt-2">
            <input id="checkbox_show_all_children" type="checkbox" @checked($isChecked) class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" onchange='actionCheckboxHiddenChildren()'>
            <label for="checkbox_show_all_children" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Show all staff</label>
        </div>
        <div class="border-t my-2"></div>
        {!!$htmlRenderTree!!}
        <div style="height: 100px;"></div>
      </ul>
</div>
<script>
    const urlUpdateUserSettings = @json($url);
    const type = @json($type);
    const actionCheckboxHiddenChildren = () => {
        var showAllChildren = $('#checkbox_show_all_children').is(":checked");
        $.ajax({
        type: 'put',
        url: urlUpdateUserSettings,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: { entity: type, show_all_children : showAllChildren },
        success: function (response) {
            if (response.success) {
                toastr.success(response.message, 'Updated User Settings');
                $('.none_only_direct_children').slideToggle("slow");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {},
    });
    };
</script>