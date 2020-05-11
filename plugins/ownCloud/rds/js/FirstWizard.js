(function (OC, window, $, undefined) {
  "use strict";

  OC.rds = OC.rds || {};

  var state = 0;
  var services;

  function reload() {
    $('div[id^="activate"]').prop("disabled", true);
    if (state === 1) {
      $("#activateOwncloud").prop("disabled", false);
    }
    if (state === 2) {
      $("#activateZenodo").prop("disabled", false);
    }
    if (state === 3) {
      $("#activateResearch").prop("disabled", false);
    }
  }

  function openPopup(service) {
    var win = window.open(
      service.authorize_url,
      "oauth2-service-for-rds",
      "width=100%,height=100%,scrollbars=yes"
    );

    var timer = setInterval(function () {
      if (win.closed) {
        clearInterval(timer);
        state += 1;
        reload();
      }
    }, 300);
  }

  function render() {
    var owncloud = undefined;
    var zenodo = undefined;

    services.getServices().foreach(function (service) {
      if (service.servicename === "Owncloud") {
        owncloud = service;
      }

      if (service.servicename === "Zenodo") {
        zenodo = service;
      }
    });

    $("#activateOwncloud").addEventListener("click", openPopup(owncloud));
    $("#activateZenodo").addEventListener("click", openPopup(zenodo));
    $("#activateResearch").addEventListener("click", function () {
      console.log("Create research and open it.");
    });

    reload();
  }

  $(document).ready(function () {
    services = new OC.rds.Services();
    reload();

    services.loadAll().done(function () {
      state += 1;
      render();
      reload();
    });
  });
})(OC, window, jQuery);
