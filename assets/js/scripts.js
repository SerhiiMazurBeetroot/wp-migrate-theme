(function ($) {
  "use strict";

  let themeBuilder = {
    /**
     * Constructor
     **/
    init: function () {
      this.update();
      this.loadThemeStructure();
    },

    update: function () {
      this.showHideCheckboxItems();
      this.checkUncheckAllCheckbox();
      this.migrateThemeZip();
    },

    showHideCheckboxItems: function () {
      document.querySelectorAll(".show-all").forEach(btn => {
        btn.addEventListener("click", function (e) {
          btn.classList.toggle("hide");

          if (btn.classList.contains("hide")) {
            btn.innerText = "Hide";
            btn.classList.add("btn-danger");
            btn.classList.remove("btn-success");
          } else {
            btn.classList.add("btn-success");
            btn.classList.remove("btn-danger");
            btn.innerText = "Show all";
          }

          btn.parentElement.querySelectorAll(".custom-control").forEach(input => {
            input.classList.toggle("filtered");
          });
        });
      });
    },

    downloadUrlFile: function (url, fileName) {
      fetch(url, { method: "get", mode: "no-cors", referrerPolicy: "no-referrer" })
        .then(res => res.blob())
        .then(res => {
          const aElement = document.createElement("a");
          aElement.setAttribute("download", fileName);
          const href = URL.createObjectURL(res);
          aElement.href = href;
          aElement.setAttribute("target", "_blank");
          aElement.click();
          URL.revokeObjectURL(href);
        });
    },

    checkUncheckAllCheckbox: function () {
      //All
      document.querySelectorAll(".select-all").forEach(all => {
        all.addEventListener("change", function (e) {
          document.querySelectorAll(`.custom-control-input`).forEach(el => {
            el.checked = e.target.checked;
          });
        });
      });

      //By type
      document.querySelectorAll(".select-type").forEach(allCheckbox => {
        allCheckbox.addEventListener("change", function (e) {
          document.querySelectorAll(`[name="${e.target.value}[]"]`).forEach(el => {
            el.checked = e.target.checked;
          });
        });
      });
    },

    loadThemeStructure: function () {
      let optionsThemes = document.querySelector(`#available_theme`);

      if (optionsThemes) {
        optionsThemes.addEventListener("change", function (e) {
          let currentFn = e.target.options[e.target.selectedIndex].value;
          const action = "process_load_theme";
          const container = document.querySelector(".panel-content");

          container.parentElement.classList.add("loading");

          const formData = new FormData();
          formData.append("action", action);
          formData.append("ajax_nonce", wpmt_vars.ajax_nonce);
          formData.append("data", currentFn);

          fetch(wpmt_vars.endpoint + action, {
            method: "POST",
            body: formData,
          })
            .then(response => {
              if (response.ok) {
                return response.text();
              } else {
                throw new Error("Failed to fetch data");
              }
            })
            .then(data => {
              setTimeout(() => {
                container.querySelectorAll("*").forEach(n => n.remove());
                container.insertAdjacentHTML("beforeend", data);
                themeBuilder.update();
                container.parentElement.classList.remove("loading");
              }, 1000);
            })
            .catch(error => {
              console.error("Error: ");
              console.log(error);
            });
        });
      }
    },

    migrateThemeZip: function () {
      const btn = document.querySelector("#generateTheme");

      btn &&
        btn.addEventListener("click", e => {
          e.preventDefault();

          const container = document.querySelector(".panel-content");
          const form = document.querySelector("#generator");
          const inputs = [...form.querySelectorAll(".custom-control-input.single-input:not(:checked)")];
          const exclude = inputs.map(input => input.value);
          const action = "process_migrate_theme";

          let optionsThemes = document.querySelector(`#available_theme`);
          let currentFn = optionsThemes.options[optionsThemes.selectedIndex].value;
          container.parentElement.classList.add("loading");

          const formData = new FormData();
          formData.append("action", action);
          formData.append("ajax_nonce", wpmt_vars.ajax_nonce);
          formData.append("data", exclude);
          formData.append("theme", currentFn);

          fetch(wpmt_vars.endpoint + action, {
            method: "POST",
            body: formData,
          })
            .then(response => {
              if (btn) btn.disabled = false;

              if (response.ok) {
                return response.json();
              } else {
                throw new Error("Failed to fetch data");
              }
            })
            .then(data => {
              if (data.fileUrl) {
                this.downloadUrlFile(data.fileUrl, data.fileName);
                container.parentElement.classList.remove("loading");
              }
            })
            .catch(error => {
              console.error("Error: ");
              console.log(error);
            });
        });
    },
  };

  document.addEventListener("DOMContentLoaded", function () {
    themeBuilder.init();
  });
})(jQuery);
