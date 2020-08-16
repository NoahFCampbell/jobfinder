$(document).ready(() => {

    $("#cmbo").change(function () {
        var type = this.value;
        //console.log(type);

        ajaxJobSearch();

    });

    $("#searchText").keyup(function () {
        var type = this.value;
        //console.log(type);

        ajaxJobSearch();

    });

    function ajaxJobSearch() {

        var type = $('#cmbo').val() == null ? "" : $('#cmbo').val();
        var text = $('#searchText').val();
        var date = $('#date').val();


        $.ajax({
            url: `./../ajax/searchjobajax.php?job_post_type=${type}&job_post_text=${text}&job_post_date=${date}`,
            success: function (result) {

                //console.log(result)

                var obj = JSON.parse(result);

                if (obj != null) {
                    var html = "";

                    obj.forEach(element => {
                        html += `<tr id='${element.id}' >
                            <td>${element.job_title}</td>
                            <td>${element.job_type}</td>
                            <td>${element.company_name}</td>
                            <td>${element.city}, ${element.province}, ${element.country}</td>
                        </tr>`
                    });

                    $("#tbody").html(html);
                } else {
                    $("#tbody").html("");
                }
            }
        });
    }
    
    $('#table').on('click', 'tr', (event) => {
        var id = $(event.currentTarget).attr("id");
        if (id != null) {
            window.location.href = `jobdescription.php?job_post_id=${id}`;
        }
    });
});