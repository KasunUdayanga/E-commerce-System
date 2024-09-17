<script>
    var msg = "<?php echo $msg ?>"
    const Toast = Swal.mixin(
    {
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    })

        Toast.fire({
        icon: 'success',
        title: msg
    })
</script>
