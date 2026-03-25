
<button id="doubt-btn">Delete All Data</button>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#doubt-btn").click(function(){
        if(confirm("Are you sure you want to delete all data? This action cannot be undone.")) {
            $.ajax({
                url: "<?= base_url('admin/delete-all-data'); ?>",
                type: "POST",
                success: function(response){
                    alert(response);
                },
                error: function(){
                    alert("Error deleting data.");
                }
            });
        }
    });
});
</script>
