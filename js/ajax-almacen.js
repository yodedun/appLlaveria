'use strict';

$(document).ready(function () {
    var responsiveHelper = undefined;
    var breakpointDefinition = {
        tablet: 1024,
        phone : 480
    };
    var tableElements = $('table');

    tableElements.dataTable({
        "bFilter": false,
        "bSort": false,
        "bPaginate": false,
        "bInfo": false,
        sPaginationType: 'bootstrap',
        oLanguage      : {
            sLengthMenu: '_MENU_ records per page'
        },
        bAutoWidth     : false,
        fnPreDrawCallback: function () {
            // Initialize the responsive datatables helper once.
            if (!this.responsiveHelper) {
                this.responsiveHelper = new ResponsiveDatatablesHelper(this, breakpointDefinition);
            }
        },
        fnRowCallback  : function (nRow) {
            this.responsiveHelper.createExpandIcon(nRow);
        },
        fnDrawCallback : function (oSettings) {
            this.responsiveHelper.respond();
        }
        
    });
     $('div.dataTables_length select').addClass('form-control');
         $('div.dataTables_filter input').addClass('form-control');
});
