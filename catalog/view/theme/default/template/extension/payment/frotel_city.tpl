<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div class="container">
    <?php echo $content_top; ?>

    <h3><?php echo $text_title_select_city; ?></h3>
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="form-group">
                <?php echo $text_select_city_desc; ?>
            </div>
            <form action="<?php echo $url ?>" method="post">
                <div class="row form-group">
                    <div class="col-sm-3">
                        <label for="province">
                            <span class="required">*</span> <?php echo $entry_province; ?> :
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <select id="province" class="form-control" name="province_id" style="width: 100%" onchange="ldMenu(this.value,'city');"></select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-3">
                        <label for="city">
                            <span class="required">*</span> <?php echo $entry_city; ?> :
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <select id="city" class="form-control" name="city_id" style="width: 100%"></select>
                    </div>
                </div>
                <?php if($error) { ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
                <?php } ?>
                <div class="buttons">
                    <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
                </div>
            </form>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        loadOstan('province');
        var province = <?php echo isset($province_id)?$province_id:'undefined'; ?>;
        var city = <?php echo isset($city_id)?$city_id:'undefined'; ?>;
        if (province) {
            $('#province').val(province);
            ldMenu(province,'city');
            if (city) {
                $('#city').val(city);
            }
        }
    });
</script>
<?php echo $footer; ?>