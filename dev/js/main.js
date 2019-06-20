// Hide URL bar on mobile devices
$(window).load(function () {
    window.scrollTo(0, 1);
});

$(function () {
    'use strict';
    var form = $("#contact-form"); // Replace with correct form id
    form.submit(function (e) {
        f24("send", "form", form, pushToDB); // DO NOT TOUCH This sends form submission through our form capture before submitting
        e.preventDefault();
    });

    function pushToDB() {
        var sendData = form.serializeArray();
        $.ajax({
            url: "includes/pushToFile.php",
            method: "POST", // DO NOT TOUCH
            data: sendData, // DO NOT TOUCH
            success: (function () {
                
            })
        });
    }
});