<?php
include('database_connection.php');

if(!isset($_SESSION["type"])) {
    header('location:login.php');
}

include('header.php');
?>

<script>
$(document).ready(function() {
    $('#search').keyup(function() {
        var query = $(this).val();
        if(query != '') {
            $.ajax({
                url: "search_bb1.php",
                method: "POST",
                data: {query: query},
                success: function(data) {
                    $('#searchList').fadeIn();
                    $('#searchList').html(data);
                }
            });
        } else {
            $('#searchList').fadeOut();
        }
    });

    $(document).on('click', 'li', function() {
        $('#search').val($(this).text());
        $('#searchList').fadeOut();
    });
});
</script>

<style>
  #inputText {
    text-transform: uppercase;
  }
</style>
<script>
function convertToUppercase() {
    var input = document.getElementById("search");
    input.value = input.value.toUpperCase();
}
</script>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Generate Label</strong></div>
        <div class="panel-body" align="center">
            <form method="get" id="name" action="generateitem.php">
                <table width="100%" border="0">
                    <tr>
                        <td width="28%">&nbsp;</td>
                        <td width="2%">&nbsp;</td>
                        <td width="70%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label>Masukan Kode BPB</label>
                                <input type="text" name="bpb" id="search" class="form-control" oninput="convertToUppercase();" required />
                                <ul id="searchList"></ul>
                            </div>
                        </td>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="proses" value="Proses Data" class="btn btn-success"/></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
