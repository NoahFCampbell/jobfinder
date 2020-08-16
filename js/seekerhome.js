$(document).ready(() => {

    $('#table').on('click', 'tr', (event) => {
        var id = $(event.currentTarget).attr("id");
        if (id != null) {
            window.location.href = `jobdescription.php?job_post_id=${id}`;
        }
    });
});