$(document).ready(() => {
    $('tbody').on('click', 'button', (event) => {
        var id = $(event.currentTarget).attr("id");
        $('#close_job_id').val(id);
        $('#exampleModal').modal('show');
    });
});