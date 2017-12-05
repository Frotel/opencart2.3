<div class="buttons">
    <div class="pull-right">
        <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
    </div>
</div>
<div class="result">
    <div id="webservice_result"></div>
</div>
<script type="text/javascript"><!--
$(document).on('click','#button-confirm', function() {
    var t=$(this);
    if(t.attr('disabled'))  return false;
    t.attr('disabled',true);
    var r=$('#webservice_result');
    r.html('');
	$.ajax({
		url: 'index.php?route=extension/payment/frotel/confirm',
        dataType:'json',
		success: function(d) {
			var url = '<?php echo $continue; ?>';
            if(d.error!=undefined) {
                if(d.error==0){
                    if(d.url!=undefined && d.url)
                        url = d.url;


                    window.location = url;
                } else {
                    r.html('<div class="alert alert-danger">'+d.message+'</div>');
                }
            } else {
                r.html('<div class="alert alert-danger">در دریافت اطلاعات خطایی رخ داده است . لطفا مجددا تلاش کنید.</div>');
            }
		},
        complete:function(){
            t.removeAttr('disabled');
        }
	});
});
//--></script> 
