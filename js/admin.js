jQuery(document).ready(function($){
    var mediaUploader;

    function openMediaUploader(inputField, previewImg, removeBtn) {
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'انتخاب آیکون',
            button: { text: 'انتخاب' },
            multiple: false
        });
        mediaUploader.on('select', function(){
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            inputField.val(attachment.url);
            previewImg.attr('src', attachment.url).show();
            removeBtn.show();
        });
        mediaUploader.open();
    }

    $('#dmm-items').on('click', '.dmm-select-icon', function(){
        var row = $(this).closest('tr');
        var inputField = row.find('.dmm-icon-input');
        var previewImg = row.find('.dmm-icon-preview');
        var removeBtn = row.find('.dmm-remove-icon');
        openMediaUploader(inputField, previewImg, removeBtn);
    });

    $('#dmm-items').on('click', '.dmm-remove-icon', function(){
        var row = $(this).closest('tr');
        row.find('.dmm-icon-input').val('');
        row.find('.dmm-icon-preview').hide().attr('src', '');
        $(this).hide();
    });

    var counter = $('#dmm-items tbody tr').length;
    $('#add-row').on('click', function(){
        var newRow = `<tr class="dmm-item">
            <td><input type="text" name="items[${counter}][title]" required></td>
            <td><input type="url" name="items[${counter}][link]" required></td>
            <td>
                <div class="dmm-icon-wrapper">
                    <img class="dmm-icon-preview" style="max-width:40px; max-height:40px; display:none;">
                    <input type="hidden" name="items[${counter}][icon]" class="dmm-icon-input" value="">
                    <button type="button" class="button dmm-select-icon">انتخاب آیکون</button>
                    <button type="button" class="button dmm-remove-icon" style="display:none;">حذف</button>
                </div>
            </td>
            <td>
                <select name="items[${counter}][icon_position]" class="dmm-icon-position">
                    <option value="before" selected>قبل عنوان</option>
                    <option value="after">بعد عنوان</option>
                    <option value="opposite">روبرو</option>
                </select>
            </td>
            <td><button type="button" class="remove-row button">حذف</button></td>
        </tr>`;
        $('#dmm-items tbody').append(newRow);
        counter++;
    });

    $('#dmm-items').on('click', '.remove-row', function(){
        $(this).closest('tr').remove();
    });

    // مرتب سازی درگ اند دراپ (اختیاری)
    if ($.fn.sortable) {
        $('#dmm-items tbody').sortable({
            axis: 'y',
            containment: 'parent',
            tolerance: 'pointer'
        });
    }
});
