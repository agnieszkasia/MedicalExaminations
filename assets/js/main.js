$(function () {
    $('#add-button').on('click', function () {
        $('#add-element').show();
    });

    $('#remove-button').on('click', function () {
        $('#add-element').hide();
    });

    $('form[name="exam"] #save-button').on('click', function () {
        $('form[name="exam"]').submit(function (event) {
            event.preventDefault();
            $('#add-element').hide();

            $.post("/exam/new", $('form[name="exam"]').serialize())
                .done(function (data) {
                    var description = data.description ? data.description : '-';
                    var html = '<tr>' +
                        '<td>' + data.createDt + '</td>' +
                        '<td>' + data.name + '</td>' +
                        '<td>' + description + '</td>' +
                        '<td>' +
                        '<a class="btn btn-success" role="button" href="/exam/' + data.id + '">Pokaż</a>' +
                        '<input type="hidden" class="elem-id" value="' + data.id + '">' +
                        '<button type="button" class="btn btn-danger delete-button">Usuń</button>' +
                        '</td>' +
                        '</tr>';

                    $(html).insertAfter('tbody tr:first');
                    $('#exam_name').val('');
                    $('#exam_description').val('');
                });
        });
    });

    $('form[name="param"] #save-button').on('click', function () {
        $('form[name="param"]').submit(function (event) {
            event.preventDefault();
            $('#add-element').hide();
            var element = $(this)
            $.post("/param/new", $('form[name="param"]').serialize())
                .done(function (data) {
                    var html = '<tr>' +
                        '<td>' + data.name + '</td>' +
                        '<td>' + data.value + '</td>' +
                        '<td>' +
                        '<input type="hidden" class="elem-id" value="' + data.id + '">' +
                        '<button type="button" class="btn btn-danger delete-button">Usuń</button>' +
                        '</td>' +
                        '</tr>';

                    $(html).insertAfter('tbody tr:first');
                    $('#param_name').val('');
                    $('#param_value').val('');
                });
        });
    });

    $(document).on('click', 'form[name="exam"] .delete-button', function () {
        var id = $(this).siblings('.elem-id').val();
        var element = $(this)
        $.post("/exam/" + id + "/delete", {})
            .done(function () {
                element.parents('tr').remove();
            });
    });

    $(document).on('click', 'form[name="param"] .delete-button', function () {
        var id = $(this).siblings('.elem-id').val();
        var element = $(this)
        $.post("/param/" + id + "/delete", {})
            .done(function (response) {
                element.parents('tr').remove();
            });
    });
});