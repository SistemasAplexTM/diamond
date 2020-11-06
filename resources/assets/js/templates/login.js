// const wcppGetPrintersTimeout_ms = 100000; //5 sec
// const wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
// var clientPrinters = "";

// function wcpGetPrintersOnSuccess() {
//   // Display client installed printers
//   if (arguments[0].length > 0) {
//     if (JSON) {
//       try {
//         clientPrinters = JSON.parse(arguments[0]);
//         objVue.printers = arguments[0];
//       } catch (e) {
//         alert(e.message);
//       }
//     }
//   } else {
//     // objVue.loading_prints = false;
//     alert("No printers are installed in your system.");
//   }
// }

// function wcpGetPrintersOnFailure() {
//   // Do something if printers cannot be got from the client
//   alert("No printers are installed in your system.");
// }

// const wcppPingDelay_ms = 1000;

// function wcppDetectOnSuccess() {
//   javascript: jsWebClientPrint.getPrintersInfo();
//   objVue.getPrintersSaved();
// WCPP utility is installed at the client side
// redirect to WebClientPrint sample page

// get WCPP version
//   var wcppVer = arguments[0];
//   if (wcppVer.substring(0, 1) == "4") {
//     // window.reload;
//     // $('#msgInProgress').hide();
//     $("#msgInProgress").hide();
//     $("#detected").css("display", "block");
//   } else {
//     console.log("ELSE");
//     wcppDetectOnFailure();
//   } //force to install WCPP v4.0
// }

// function wcppDetectOnFailure() {
//   // It seems WCPP is not installed at the client side
//   // ask the user to install it
//   $("#msgInProgress").hide();
//   $("#msgInstallWCPP").show();
// }

// function wcppDetectOnFailure() {
//   // It seems WCPP is not installed at the client side
//   // ask the user to install it
//   $("#msgInProgress").hide();
//   $("#msgInstallWCPP").show();
// }

var objVue = new Vue({
  el: "#setup",
  created() {
    localStorage.removeItem("menu");
    this.getPrints();
  },
  data: {
    printers: "",
    printers_array: [],
  },
  methods: {
    getPrints() {
      let me = this;
      this.loading_prints = true;
      // javascript: jsWebClientPrint.getPrintersInfo();
      JSPM.JSPrintManager.auto_reconnect = true;
      JSPM.JSPrintManager.start();
      JSPM.JSPrintManager.WS.onStatusChanged = function () {
        if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open) {
          JSPM.JSPrintManager.getPrintersInfo().then(function (e) {
            e.forEach(function (el, index) {
              me.printers_array.push({ id: index, name: el.name, isDefault: el.default });
              // console.log('printers: ', el)
            });
            me.printers = JSON.stringify(me.printers_array);
          });

        } else {
          if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
            console.log('JSPM is not installed or not running!');
          } else {
            if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.BlackListed) {
              console.log('JSPM has blacklisted this website!');
            }
          }
        }
      };
    }
  }
});
