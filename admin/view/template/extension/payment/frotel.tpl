<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-frotel" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
                    <i class="fa fa-save"></i>
                </button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
                    <i class="fa fa-reply"></i>
                </a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li>
                    <a href="<?php echo $breadcrumb['href']; ?>">
                        <?php echo $breadcrumb['text']; ?>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if (isset($error_warning) && $error_warning) { ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i>
            <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (isset($error) && $error) { ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i>
            <?php echo $error; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-pencil"></i>
                    <?php echo $text_edit; ?>
                </h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-frotel" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_api">
                            <?php echo $entry_api; ?>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_api" id="frotel_api" placeholder="<?php echo $entry_api_desc; ?>" value="<?php echo $frotel_api; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_url">
                            <?php echo $entry_url; ?>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_url" id="frotel_url" dir="ltr" placeholder="<?php echo $entry_url_desc; ?>" value="<?php echo $frotel_url; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            <span data-toggle="tooltip" title="<?php echo $entry_pro_code_desc; ?>">
                                <?php echo $entry_pro_code; ?>
                            </span>
                        </label>

                        <div class="col-sm-9">
                            <label class="radio">
                                <input type="radio" name="frotel_pro_code" <?php echo ($frotel_pro_code=='product_id' || !$frotel_pro_code?"checked='checked'":"") ?> value="product_id" />
                                <?php echo $text_product_id; ?>
                            </label>
                            <label class="radio">
                                <input type="radio" name="frotel_pro_code" <?php echo ($frotel_pro_code=='model'?"checked='checked'":"") ?> value="model" />
                                <?php echo $text_product_model; ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo $entry_method_delivery; ?></label>

                        <div class="col-sm-9">
                            <label class="checkbox">
                                <input type="checkbox" name="frotel_express" <?php echo ($frotel_express?"checked='checked'":"") ?> value="1" />
                                <?php echo $entry_express; ?>
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="frotel_registered" <?php echo ($frotel_registered?"checked='checked'":'') ?> value="1" />
                                <?php echo $entry_registered; ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            <?php echo $entry_method_payments; ?>
                        </label>
                        <div class="col-sm-9">
                            <label class="checkbox">
                                <input type="checkbox" name="frotel_online" <?php echo ($frotel_online?"checked='checked'":"") ?> value="1" />
                                <?php echo $entry_online; ?>
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="frotel_cod" <?php echo ($frotel_cod?"checked='checked'":"") ?> value="1" />
                                <?php echo $entry_cod; ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_default_online_express">
                            <span data-toggle="tooltip" title="<?php echo $text_failed_get_price; ?>">
                                <?php echo $entry_default_online_express; ?>
                            </span>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_default_online_express" id="frotel_default_online_express" value="<?php echo $frotel_default_online_express; ?>" />
                            <?php echo $text_rial; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_default_cod_express">
                            <span data-toggle="tooltip" title="<?php echo $text_failed_get_price; ?>">
                                <?php echo $entry_default_cod_express; ?>
                            </span>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_default_cod_express" id="frotel_default_cod_express" value="<?php echo $frotel_default_cod_express; ?>" />
                            <?php echo $text_rial; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_default_online_registered">
                            <span data-toggle="tooltip" title="<?php echo $text_failed_get_price; ?>">
                                <?php echo $entry_default_online_registered; ?>
                            </span>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_default_online_registered" id="frotel_default_online_registered" value="<?php echo $frotel_default_online_registered; ?>" />
                            <?php echo $text_rial; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_default_cod_registered">
                            <span data-toggle="tooltip" title="<?php echo $text_failed_get_price; ?>">
                                <?php echo $entry_default_cod_registered; ?>
                            </span>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_default_cod_registered" id="frotel_default_cod_registered" value="<?php echo $frotel_default_cod_registered; ?>" />
                            <?php echo $text_rial; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_default_weight">
                            <span data-toggle="tooltip" title="<?php echo $text_weight_unit_desc; ?>">
                                <?php echo $entry_default_weight; ?>
                            </span>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_default_weight" id="frotel_default_weight" value="<?php echo $frotel_default_weight; ?>" size="3"/>
                            <?php echo $text_weight_unit; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_product_porsant">
                            <span data-toggle="tooltip" title="<?php echo $entry_product_porsant_desc; ?>">
                                <?php echo $entry_product_porsant; ?>
                            </span>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_product_porsant" id="frotel_product_porsant" value="<?php echo $frotel_product_porsant; ?>" size="3"/> %
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_order_status">
                            <?php echo $entry_order_status; ?>
                        </label>

                        <div class="col-sm-9">
                            <select name="frotel_order_status" id="frotel_order_status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $frotel_order_status) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
                                            <?php echo $order_status['name']; ?>
                                        </option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>">
                                            <?php echo $order_status['name']; ?>
                                        </option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_verify_status">
                            <?php echo $entry_verify_status; ?>
                        </label>

                        <div class="col-sm-9">
                            <select name="frotel_verify_status" id="frotel_verify_status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $frotel_verify_status) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
                                            <?php echo $order_status['name']; ?>
                                        </option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>">
                                            <?php echo $order_status['name']; ?>
                                        </option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_geo_zone">
                            <?php echo $entry_geo_zone; ?>
                        </label>

                        <div class="col-sm-9">
                            <select name="frotel_geo_zone_id" id="frotel_geo_zone" class="form-control">
                                <option value="0">
                                    <?php echo $text_all_zones; ?>
                                </option>
                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                    <?php if ($geo_zone['geo_zone_id'] == $frotel_geo_zone_id) { ?>
                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected">
                                            <?php echo $geo_zone['name']; ?>
                                        </option>
                                    <?php } else { ?>
                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>">
                                            <?php echo $geo_zone['name']; ?>
                                        </option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_status">
                            <?php echo $entry_status; ?>
                        </label>

                        <div class="col-sm-9">
                            <select name="frotel_status" id="frotel_status" class="form-control">
                                <?php if ($frotel_status) { ?>
                                        <option value="1" selected="selected">
                                            <?php echo $text_enabled; ?>
                                        </option>
                                        <option value="0">
                                            <?php echo $text_disabled; ?>
                                        </option>
                                    <?php } else { ?>
                                        <option value="1">
                                            <?php echo $text_enabled; ?>
                                        </option>
                                        <option value="0" selected="selected">
                                            <?php echo $text_disabled; ?>
                                        </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="frotel_sort">
                            <?php echo $entry_sort; ?>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frotel_sort" id="frotel_sort" value="<?php echo $frotel_sort; ?>" size="1"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
