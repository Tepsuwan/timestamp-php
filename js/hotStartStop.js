

var $container = $("#data-hot");
function centerBoldRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.fontWeight = 'bold';
    td.style.color = '#000';
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
function myAutocompleteRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.AutocompleteCell.renderer.apply(this, arguments);
    td.style.textAlign = 'LEFT';
    td.style.color = '#000';
}
function holidayRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'CENTER';
    td.style.color = '#000';
    td.style.background = '#e8e8ea';
    td.style.fontWeight = 'bold';
}

$container.handsontable({
    manualColumnResize: true,
    minSpareRows: 3,
    rowHeaders: true,
    colHeaders: true,
    stretchH: 'last',
    contextMenu: false,
    clumnSorting: true,
    multiSelect: false,
    afterChange: function (change, source) {
        if (source === 'loadData') {
            return; //don't save this change
        }
        //////////////////////////////// 
        var hot = $container.data('handsontable');
        var updateId = [];
        var dateId = [];
        for (var i = 0; i <= change.length - 1; i++) {
            var row = change[i][0],
                    prop = change[i][1],
                    oldValue = change[i][2],
                    newValue = change[i][3],
                    id = hot.getDataAtCell(row, 0),
                    d = hot.getDataAtCell(row, 2);
            updateId.push(id);
            dateId.push(d);
            if (oldValue === null) {
                oldValue = '';
            }
        }
        if (oldValue === newValue) {
            return;
        }
        var data = {
            data: JSON.stringify(change),
            updateId: JSON.stringify(updateId),
            dateId: JSON.stringify(dateId)
        };
        $.ajax({
            url: "libs/php/updateStartStopNote.php",
            dataType: 'json',
            type: "POST",
            data: data,
            success: function (jsonStr) {
            }
        });



    }
});

function updateSettings() {
    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        colHeaders: colHeaderRenderer,
        cells: function (row, col, prop) {
            var cellProperties = {};

            if (this.instance.getDataAtCell(row, 1) === "Sat" || this.instance.getDataAtCell(row, 1) === "Sun") {
                cellProperties.renderer = holidayRenderer;
            }

            return cellProperties;
        },
        colWidths: [1, 1, 100, 100, 100, 150, 200],
        columns: [
            {data: "staff_work_id"},
            {
                data: "staff_work_day",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "staff_work_date",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "staff_work_start",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "staff_work_stop",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "reason_id", type: 'autocomplete',
                renderer: myAutocompleteRenderer,
                source: function (query, process) {
                    $.ajax({
                        url: 'libs/php/autocomplete-reason.php',
                        data: {query: query},
                        success: function (response) {
                            process(response);
                        }
                    });
                },
                strict: false
            },
            {
                data: "staff_work_note",
                renderer: leftRenderer
            }
        ]
    });
    return true;
}

function colHeaderRenderer(col) {
    switch (col) {
        case 0:
            return '';
        case 1:
            return '<span class="headerBold">Day</span>';
        case 2:
            return '<span class="headerBold">Date</span>';
        case 3:
            return '<span class="headerBold">Start</span>';
        case 4:
            return '<span class="headerBold">Stop</span>';
        case 5:
            return '<span class="headerBold">Reason</span>';
        case 6:
            return '<span class="headerBold">Note</span>';
    }
}
