
var loading_img_path = jQuery(".wqoecf-pop-up-box").attr("data-loader-path");
jQuery("body").on("click", ".wqoecf_enquiry_button", function () {
    jQuery("body").append(
        '<div class="wqoecf_loading"><img src="' +
        loading_img_path +
        '" class="wqoecf_loader"></div>'
    );
    var loading = jQuery(".wqoecf_loading");
    loading.show();
    var product_title = jQuery(this).attr("data-product-title");

    jQuery(".wqoecf-pop-up-box .wpcf7 > form")[0].reset();
    jQuery("div.wqoecf-pop-up-box .wpcf7 span.product-name input").val(
        product_title
    );
    jQuery('.wqoecf-pop-up-box div.wpcf7>form input[name="product-name"]').attr(
        "readonly",
        true
    );
    loading.remove();
         

    jQuery('.wqoecf-pop-up-box .wpcf7 > form').init(".wqoecf-pop-up-box .wpcf7 > form");        



    jQuery(".wqoecf-pop-up-box").fadeIn();
});

jQuery(document).on('click', '.wqoecf-pop-up-box form.submitting .wpcf7-submit', function(e){
    e.preventDefault();
    return false;
   
});

function wqoecf_hide() {
    jQuery(".wqoecf-pop-up-box").fadeOut();
    jQuery(".wqoecf-pop-up-box .wpcf7-not-valid-tip").css("display", "none");
}
