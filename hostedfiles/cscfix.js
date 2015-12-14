
function refreshSearch(){
console.log("Ajax call about to go");
dojo.xhrGet({
// The URL to request
url: "https://csc.example.com/csc/openorders.php",
// The method that handles the request's successful result
// Handle the response any way you'd like!
load: function(newContent) {
dojo.byId("cmcOrderResultsPContent").innerHTML = "<div class=\"atg_commerce_csr_content\">"+newContent+"</div>";
},
// The error handler
error: function() {
// Do nothing -- keep old content there
}
});
}
atg.commerce.csr.order.searchValidate = function(){refreshSearch();};
atg.commerce.csr.order.performSearch = function(){sendForm();};



function sendForm(){
    var xhrArgs = {
      form: dojo.byId("atg_commerce_csr_orderSearchForm"),
      //content: dojo.formToObject(dojo.byId("atg_commerce_csr_orderSearchForm")),
      handleAs: "text",
      url: "https://csc.example.com/csc/cscordersearch.php",
      load: function(newContent) {
      dojo.byId("cmcOrderResultsPContent").innerHTML = "<div class=\"atg_commerce_csr_content\">"+newContent+"</div>";
      },
      error: function(error){
        // We'll 404 in the demo, but that's okay.  We don't have a 'postIt' service on the
        // docs server.
        dojo.byId("cmcOrderResultsPContent").innerHTML  = "<div class=\"atg_commerce_csr_content\">"+error+"</div>";
      }
    }
    // Call the asynchronous xhrPost
    dojo.byId("cmcOrderResultsPContent").innerHTML = "Joe's CSC Hack working... Please Wait"
    var deferred = dojo.xhrPost(xhrArgs);

}
dojo.ready(sendForm);
