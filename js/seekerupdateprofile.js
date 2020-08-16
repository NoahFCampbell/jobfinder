$(document).ready( () => {
    $('#skill_set_tbody').on('click', 'button', (event) => {
        var id = $(event.currentTarget).attr("id");
        $('#delete_skill_id').val(id);
        $('#exampleModal').modal('show');
    });
});