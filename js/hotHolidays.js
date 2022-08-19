

var $container = $("#data-hot");
var autosaveNotification;


function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = '#000';
    td.style.borderBottom = '1px solid gray';
}
function centerRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = '#000';
}
function leftRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'left';
    td.style.color = '#000';
}
function holidaysRemainRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'CENTER';
    if(value>0){
      td.style.color = 'orange';  
    }else{
        td.style.color = 'red';
    }    
    td.style.fontWeight = 'bold';
}
function holidaysCenterRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'CENTER';
    td.style.color = '#10d22b';
    td.style.fontWeight = 'bold';
}

$container.handsontable({
    manualColumnResize: true,
    minSpareRows: 5,
    rowHeaders: false,
    colHeaders: true,
    contextMenu: false,
    columnSorting: false,
    stretchH: 'last',
    wordWrap: false,
    multiSelect: true

});

function colHolidayRenderer(col) {
    switch (col) {
        case 0:
            return '';
        case 1:
            return '<span class="headerBold">Holidays Name</span>';
        case 2:
            return '<span class="headerBold">Holiday</span>';
        case 3:
            return '<span class="headerBold">Used</span>';
        case 4:
            return '<span class="headerBold">Holiday remain</span>';
        case 5:
            return '<span class="headerBold"></span>';
    }
}

function updateSettings() {

    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        colHeaders: colHolidayRenderer,
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (this.instance.getDataAtCell(row, 3) > 0) {
                if (col === 3) {
                    cellProperties.renderer = holidaysCenterRenderer;
                }
                if (col === 4) {
                    cellProperties.renderer = holidaysRemainRenderer;
                }
            }
            return cellProperties;
        },
        colWidths: [1, 200, 120, 120, 120, 100, 100],
        columns: [
            {},
            {data: "reason_name"},
            {
                data: "reason_day",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "reason_use",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "reason_balance",
                renderer: centerRenderer,
                readOnly: true
            },
            {}
        ]
    });
    return true;
}