<div class="row">
    <div class="col-xl-3 col-md-4 mb-4">
        <a href="pelanggan"> <!-- URL -->
            <div class="card border-left-success shadow h-100 py-2" id="card_pelanggan_baru" name="card_pelanggan_baru"> <!-- border-left-success dapat diubah warnanya, cek di https://getbootstrap.com/docs/4.0/components/alerts/#link-color (primary, secondary, success, ..) -->
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-sm-left font-weight-bold text-primary text-uppercase mb-1">Pelanggan Baru</div>
                            <div class="h4 mb-0 font-weight-bold text-black-800" id="jml_pelanggan_baru" name="jml_pelanggan_baru">???</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-500"></i> <!-- ICON fas fa-user-plus disesuaikan dari: https://fontawesome.com/v5/search?q=table&ic=free-collection -->
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-md-4 mb-4">
        <a href="wishlist"> <!-- URL -->
            <div class="card border-left-info shadow h-100 py-2" id="card_wishlist_pelanggan" name="card_wishlist_pelanggan"> <!-- border-left-info dapat diubah warnanya, cek di https://getbootstrap.com/docs/4.0/components/alerts/#link-color (primary, secondary, success, ..) -->
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-sm-left font-weight-bold text-primary text-uppercase mb-1">Wishlist Pelanggan</div>
                            <div class="h4 mb-0 font-weight-bold text-black-800" id="jml_wishlist_pelanggan" name="jml_wishlist_pelanggan">???</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heart fa-2x text-gray-500"></i> <!-- ICON fa-heart disesuaikan dari: https://fontawesome.com/v5/search?q=table&ic=free-collection -->
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<script>
    $(document).ready(function() {
        loadDashboard();
    });

    function loadDashboard() {

        $.ajax({
            url: "<?= base_url('Select/dashboard') ?>",
            method: "POST",
            dataType: "JSON",
            success: function(data) {

                console.log(data); // untuk debug

                $('#jml_pelanggan_baru').text(data.pelanggan_baru);
                $('#jml_wishlist_pelanggan').text(data.wishlist_pelanggan);
            }
        });
    }
</script>