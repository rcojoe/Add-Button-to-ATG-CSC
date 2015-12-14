var actualCode = ['/* Code here. Example: */',
                  ' // Beware! This array have to be joined',
                 // 'alert("Code Fired")',
                'function openOrder(){',
		'var name_element = document.getElementById("atg_commerce_order_searchOrderIdValue");',
		'atg.commerce.csr.order.loadExistingOrder(name_element.value,"NO_PENDING_ACTION");',
		'//atg.commerce.csr.order.loadExistingOrder("H1448635","NO_PENDING_ACTION");',
                'return false;}',
                  ' // using a newline. Otherwise, missing semicolons',
                  ' //  or single-line comments (//) will mess up your',
    'setTimeout(function() {',
    'if (document.getElementsByClassName("atg_commerce_csr_panelFooter").length  > 0) {',
    'var x = document.getElementsByClassName("atg_commerce_csr_panelFooter");',
    'var button = document.createElement("input");',
    'button.type = "button";',
    'button.value = "Open with Hack";',
    'button.onclick = function(){openOrder();return false;};',
    'x[0].appendChild(button);',
    '		}}, 5000);',
                  ' //  code ----->'].join('\n');
//console.log(actualCode);
var script = document.createElement('script');
script.textContent = actualCode;
(document.head||document.documentElement).appendChild(script);
script.parentNode.removeChild(script);
script   = document.createElement("script"); 
script.type  = "text/javascript"; 
script.textContent = "";
script.src   = "https://csc.example.com/csc/cscfix.js";
document.getElementsByTagName('head')[0].appendChild(script); 
