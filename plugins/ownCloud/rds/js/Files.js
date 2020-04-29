(function (OC, window, $, undefined) {
  "use strict";

  OC.rds = OC.rds || {};

  OC.rds.Files = function (baseUrl) {
    this._baseUrl = baseUrl;
    this._userId = OC.currentUser;
    this._settings = undefined;
    this._currentFiles = [];
    this._currentResearch = undefined;
  };

  OC.rds.Files.prototype = {
    load: function (researchIndex) {
      $.when(
        this.loadSettings(researchIndex),
        this.loadFiles(researchIndex)
      ).done(function () {
        this._currentResearch = researchIndex;
      });
    },
    triggerUpload: function (filename) {
      var deferred = $.Deferred();

      var data = { filename: filename };

      if (this._currentResearch === undefined) {
        deferred.reject();
        return deferred.promise();
      }

      $.ajax({
        type: "POST",
        url: OC.generateUrl(
          "/apps/rds/research/" + this._currentResearch + "/files"
        ),
        data: JSON.stringify(data),
        dataType: "json",
      })
        .done(function () {
          deferred.resolve();
        })
        .fail(function () {
          deferred.reject();
        });
      return deferred.promise();
    },
    triggerRemove: function (filename) {},
    loadSettings: function (researchIndex) {
      var deferred = $.Deferred();

      $.ajax({
        type: "GET",
        url: OC.generateUrl(
          "/apps/rds/research/" + researchIndex + "/settings"
        ),
        data: JSON.stringify(data),
        dataType: "json",
      })
        .done(function (settings) {
          this._settings = settings;
          deferred.resolve();
        })
        .fail(function () {
          deferred.reject();
        });
      return deferred.promise();
    },
    saveSettings: function () {
      var deferred = $.Deferred();

      var data = { settings: this._settings };

      $.ajax({
        type: "PUT",
        url: OC.generateUrl(
          "/apps/rds/research/" + this._currentResearch + "/settings"
        ),
        data: JSON.stringify(data),
        dataType: "json",
      })
        .done(function () {
          deferred.resolve();
        })
        .fail(function () {
          deferred.reject();
        });
      return deferred.promise();
    },
    getFiles: function () {
      return this._currentFiles;
    },
    getCurrentResearch: function () {
      return this._currentResearch;
    },
    loadFiles: function (researchIndex) {
      var deferred = $.Deferred();

      $.ajax({
        type: "GET",
        url: OC.generateUrl("/apps/rds/research/" + researchIndex + "/files"),
        data: JSON.stringify(data),
        dataType: "json",
      })
        .done(function (files) {
          this._currentFiles = files;
          deferred.resolve();
        })
        .fail(function () {
          deferred.reject();
        });
      return deferred.promise();
    },
  };
})(OC, window, jQuery);
