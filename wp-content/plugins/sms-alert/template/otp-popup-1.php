<?php
echo '<style>.modal{display:none;position:fixed;z-index:999999999999;padding-top:100px;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:rgb(0,0,0);background-color:rgba(0,0,0,0.4);}.modal-content{position:relative;background-color:#fefefe;margin:auto;padding:0;border:1px solid #888;width:40%;box-shadow:04px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);-webkit-animation-name:zoomIn;-webkit-animation-duration:0.3s;animation-name:zoomIn;animation-duration:0.3s}[name=smsalert_otp_validate_submit]{width:100%}@media  only screen and (max-width: 767px){.modal-content{width:100%}}@-webkit-keyframes zoomIn {from {opacity: 0;-webkit-transform: scale3d(0.3, 0.3, 0.3);transform: scale3d(0.3, 0.3, 0.3);}50% {opacity: 1;}}@keyframes zoomIn {from {opacity: 0;-webkit-transform: scale3d(0.3, 0.3, 0.3);transform: scale3d(0.3, 0.3, 0.3);}50% {opacity: 1;}}.zoomIn {-webkit-animation-name: zoomIn;animation-name: zoomIn;}.modal-header{background-color:#5cb85c;color:white;}.modal-footer{background-color:#5cb85c;color:white;}.close{float:none;text-align: right;font-size: 25px;cursor: pointer;text-shadow: 0 1px 0 #fff;line-height: 1;font-weight: 400;padding: 0px 5px 0px;}.close:hover {color: #999;}.otp_input{margin-bottom:12px;}.otp_input[type="number"]::-webkit-outer-spin-button, .otp_input[type="number"]::-webkit-inner-spin-button {-webkit-appearance: none;margin: 0;}
.otp_input[type="number"] {-moz-appearance: textfield;}.otp_input{width:100%}

</style>';
			echo ' <div id="'.$modalName.'" class="modal"><div class="modal-content"><div class="close" id="close">x</div><div class="modal-body"><div id="'.$alert_msg_div.'" style="margin:1em;">EMPTY</div><div id="smsalert_validate_field" style="margin:1em"><input type="number" name="'.$otp_input_field_nm.'" autofocus="true" placeholder="" id="smsalert_customer_validation_otp_token" class="input-text otp_input" autofocus="true" pattern="[0-9]{3,8}" title="'.$otp_range.'"/>';
			
			echo '<a style="float:right" id="'.$resend_btn_id.'" onclick="'.$resendFunc.'()">'.$RESEND.'</a><span id="'.$timer_div.'" style="min-width:80px; float:right">00:00 sec</span><br /><button type="button" name="smsalert_otp_validate_submit" style="color:grey; pointer-events:none;" id="'.$validate_otp_btn.'" class="button alt" value="'.$VALIDATE_OTP.'">'.$VALIDATE_OTP.'</button>
			</div></div></div></div>';
			
			echo '<script>
			jQuery("#'.$modalName.' #smsalert_customer_validation_otp_token").on("input",function(){
			if(jQuery("#'.$modalName.' #smsalert_customer_validation_otp_token").val().match(/^\d{3,8}$/)) { 
				jQuery("#'.$modalName.' #'.$validate_otp_btn.'").removeAttr("style");
			} else{jQuery("#'.$modalName.' #'.$validate_otp_btn.'").css({"color":"grey","pointer-events":"none"}); }}); var interval; jQuery("#'.$modalName.' #close").click(function(){jQuery("#'.$modalName.'").hide();});';
			
            echo '
			jQuery("form #'.$modalName.'").on("focus", "input[type=number]", function (e) {
				jQuery(this).on("wheel.disableScroll", function (e) {
					e.preventDefault();
				});
			});
			jQuery("form #'.$modalName.'").on("blur", "input[type=number]", function (e) {
			jQuery(this).off("wheel.disableScroll");
			});
			</script>';
?>