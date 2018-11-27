<script src="https://cdnjs.cloudflare.com/ajax/libs/rangeslider.js/2.3.2/rangeslider.min.js"></script>
<script>
    jQuery(document).ready(function() {
        // percentage per month arrays
        var six_months = [20, 20, 20, 15, 12.5, 12.5];
        var twelve_months = [12.5, 12.5, 12.5, 10, 10, 5, 5, 5, 5, 5, 5, 5];
        var capital_amounts = [];
        var six_month_json_data = {};
        // Display table function

        var get_factor = function(current_amount_val, current_factor_val) {

            for(var i = 2000; i < current_amount_val + 400; i += 400) 
            {
                current_factor_val -= 0.1020408;
            }

            return current_factor_val;
        }

        var get_table_data = function() {

            jQuery('#tableData').empty();

            var month_selection = parseInt(document.getElementById('period').value);
            var current_amount = parseInt(document.getElementById('slider_js').value);
            var current_factor; // Six months default factor.

            if(month_selection == 6)
            {
                current_factor = 40.00;

                if(current_amount >= 1600)
                {
                    current_factor = 35.00;
                }
            } else {
                current_factor = 50;

                if(current_amount >= 1600)
                {
                    current_factor = 40.00;

                }
            }

            var loan_fee = (current_amount/100)*current_factor;
            var capital_loan = current_amount + loan_fee;

            if(month_selection == 6)
            {
                if(current_amount >= 19200)
                {
                    current_factor_value = 26.02;
                } else {
                    current_factor_value = get_factor(current_amount, current_factor);

                }

                for (var i = 0; i < 6; i++) {

                    if(current_amount > 1600) {

                        loan_fee = (current_amount)*current_factor_value/100;
                        capital_loan = current_amount + loan_fee;

                    } 
                    var month = i + 1;
                    if(i % 2 == 0)
                    {
                        jQuery('#tableData').append("<tr class='table-success'><th>" + month + "</th><td>R" + (capital_loan*six_months[i]/100).toFixed(2) + "</td></tr>");

                    } else {
                        jQuery('#tableData').append("<tr><th>" + month + "</th><td>R" + (capital_loan*six_months[i]/100).toFixed(2) + "</td></tr>");

                    }
                }   

            } else {

                for (var i = 0; i < 12; i++) {

                    if(current_amount >= 17200)
                    {
                        current_factor_value = 32.04;
                    } else {
                        current_factor_value = get_factor(current_amount, current_factor);
                    }

                    if(current_amount > 1600) {
           
                        loan_fee = (current_amount)*current_factor_value/100;
                        capital_loan = current_amount + loan_fee;
                    }
                    
                    var month = i + 1;
                    if(i % 2 == 0)
                    {
                        jQuery('#tableData').append("<tr class='table-success'><th>" + month + "</th><td>R" + (capital_loan*twelve_months[i]/100).toFixed(2) + "</td></tr>");

                    } else {
                        jQuery('#tableData').append("<tr><th>" + month + "</th><td>R" + (capital_loan*twelve_months[i]/100).toFixed(2) + "</td></tr>");

                    }
                    
                }  

            }

            jQuery('#total_repayment').html((capital_loan).toFixed(2));
            // jQuery('#factor').html(current_factor_value);
            // jQuery('#loan_fee').html(loan_fee);
            // jQuery('#capital_loan').html(capital_loan);

        }

        var check_selected_button = function()
        {
            var month_selection = parseInt(document.getElementById('period').value);
            var el_six_month = document.getElementById('sixMBtn');
            var el_twelve_month = document.getElementById('twelveMBtn');

            if(month_selection == 6) {
                el_six_month.classList.remove('btn-outline-success');
                el_six_month.classList.add('btn-success');
                el_twelve_month.classList.add('btn-outline-success');

            } else if(month_selection == 12) {
                el_twelve_month.classList.remove('btn-outline-success');
                el_twelve_month.classList.add('btn-success');
                el_six_month.classList.add('btn-outline-success');
            } else {

            }
        }
        // month button functions
        jQuery('#sixMBtn').click(function() {
            document.getElementById('period').value = 6;
            get_table_data();
            check_selected_button();
        });

        jQuery('#twelveMBtn').click(function() {
            document.getElementById('period').value = 12;
            get_table_data();
            check_selected_button();
        });        
        // Slider
        jQuery('input[type="range"]').rangeslider({
            polyfill: false,

            // Default CSS classes
            rangeClass: 'rangeslider',
            disabledClass: 'rangeslider--disabled',
            horizontalClass: 'rangeslider--horizontal',
            verticalClass: 'rangeslider--vertical',
            fillClass: 'rangeslider__fill',
            handleClass: 'rangeslider__handle',

            // Callback function
            onInit: function() {
                var slider_value = document.getElementById('slider_js').value;
                jQuery('#slide_value').html(slider_value);
                get_table_data();
            },

            // Callback function
            onSlide: function(position, value) {
                jQuery('#slide_value').html(value);
                get_table_data();

            },

            // Callback function
            onSlideEnd: function(position, value) {}
        });
 
        jQuery("#ReferAFriend_BoxContainerBody").appendTo("body");
        jQuery("#ReferAFriend_Open").appendTo("body");
        
        jQuery("#ReferAFriend_Open").click(function(event) {
            event.preventDefault();
            jQuery("#ReferAFriend_BoxContainerBody").animate({right: "0px"});
            jQuery(this).animate({right: "-112px"});
        });
        
        jQuery("#TellAFriend_BoxClose").click(function(event) {
            event.preventDefault();
            jQuery("#ReferAFriend_BoxContainerBody").animate({right: "-320px"});
            jQuery("#ReferAFriend_Open").animate({right: "0px"});
        });
        
        jQuery("#wrapper").click(function() {
            jQuery("#ReferAFriend_BoxContainerBody").animate({right: "-320px"});
            jQuery("#ReferAFriend_Open").animate({right: "0px"}); 
        });
 
    });
  </script>