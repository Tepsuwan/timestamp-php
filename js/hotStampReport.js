

var $container = $("#data-hot");
var autosaveNotification;


function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = '#000';
    td.style.borderBottom = '1px solid gray';
}
function weekend(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = '#FFF';
    td.style.background = '#00a65a';//'rgba(152, 251, 179, 0.30)';
}
function absentRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'left';
    td.style.color = '#000';
    td.style.background = '#F28A8C';//'rgba(221, 75, 57, 0.54)';
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
$container.handsontable({
    manualColumnResize: true,
    minSpareRows: 5,
    rowHeaders: false,
    colHeaders: true,
    contextMenu: false,
    columnSorting: false,
    stretchH: 'last',
    wordWrap: false,
    multiSelect: true,
    //tableClassName: ['table', 'table-hover', 'table-striped'],
    contextMenu: {
        callback: function (key, options) {
            if (key === 'edit') {
                updateSettingsCell(options.start.col);
            }
        },
        items: {
            "edit": {name: 'Edit column',
                disabled: function () {
                    // if first row, disable this option
                    if (JSClass.roleKey === "1" || JSClass.roleKey === "2") {
                        return $container.data('handsontable').getSelected()[0] === 0;
                    } else {
                        return $container.data('handsontable').getSelected()[0] >= 0;
                    }
                }
            },
            "remove_row": {
                name: 'Remove this row, ok?',
                disabled: function () {
                    // if first row, disable this option
                    if (JSClass.roleKey === "1" || JSClass.roleKey === "2") {
                        return $container.data('handsontable').getSelected()[0] === 0;
                    } else {
                        return $container.data('handsontable').getSelected()[0] >= 0;
                    }
                }
            },
            "hsep2": "---------",
            "temp1": {'name': ''}
        }
    },
    afterChange: function (change, source) {
        if (source === 'loadData') {
            return; //don't save this change
        }
        //----------------------------------------------------------------------
        var hot = $container.data('handsontable');
        var updateId = [];
        var dateId = [];
        for (var i = 0; i <= change.length - 1; i++) {
            var row = change[i][0],
                    prop = change[i][1],
                    oldValue = change[i][2],
                    newValue = change[i][3],
                    id = hot.getDataAtCell(row, 0),
                    d = hot.getDataAtCell(row, 4);

            updateId.push(id);
            dateId.push(d);
            if (oldValue === null) {
                oldValue = '';
            }
        }
        if (oldValue === newValue) {
            return;
        }
        //----------------------------------------------------------------------
        clearTimeout(autosaveNotification);
        var url = "libs/php/updateTimestampReport.php";
        var data = {
            data: JSON.stringify(change),
            updateId: JSON.stringify(updateId),
            dateId: JSON.stringify(dateId)
        };
        $.ajax({
            url: url,
            dataType: 'json',
            type: "POST",
            data: data,
            success: function () {
                JSClass.Sync = true;

                JSClass.loadDataTimestampReport();

                $(".console").text('Auto save ...');
                autosaveNotification = setTimeout(function () {
                    $(".console").text('Changes will be autosaved');
                }, 1000);
                //JSClass.loadDataTimestampReport();

            }
        });
    },
    beforeRemoveRow: function (index, amount) {
        //----------------------------------------------------
        var selection = $container.handsontable('getSelected');
        if (selection) {
            if (confirm("Press OK to confirm Delete.") === true) {
                count = index + amount;
                rowIndex = index;
                var hot = $container.data('handsontable');
                for (var i = index; i < count; i++) {

                    var getId = hot.getDataAtCell(i, 0);//get id
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "libs/php/delete.php",
                        data: {id: getId},
                        success: function (response) {
                        }
                    });
                }
            }
        }
    }
});

function updateSettings() {
    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        colHeaders: colHeaderRenderer,
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (this.instance.getDataAtCell(row, 1) === "Sat" || this.instance.getDataAtCell(row, 1) === "Sun") {
                if (prop !== 'gdate') {
                    cellProperties.renderer = weekend;
                }
            } else if (this.instance.getDataAtCell(row, 2)) {
                cellProperties.renderer = dateRenderer;
            } else if (this.instance.getDataAtCell(row, 3) && this.instance.getDataAtCell(row, 4) == undefined) {
                if (prop !== 'gdate') {
                    cellProperties.renderer = absentRenderer;
                }
            }
            return cellProperties;
        },
        colWidths: [1, 1, 100, 240, 100, 100, 100, 100, 100, 150, 380],
        columns: [
            {data: "stamp_id"},
            {data: "dText"},
            {
                data: "gdate",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_uid",
                renderer: leftRenderer,
                readOnly: true
            },
            {
                data: "stamp_date",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_start",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_stop",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_start_ip",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_stop_ip",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "reason_id", type: 'autocomplete',
                renderer: myAutocompleteRenderer,
                readOnly: true,
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
                data: "stamp_note",
                renderer: leftRenderer,
                readOnly: true
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
            return '';
        case 2:
            return '';
        case 3:
            return '<span class="headerBold">Name</span>';
        case 4:
            return '<span class="headerBold">Date</span>';
        case 5:
            return '<span class="headerBold">Start</span>';
        case 6:
            return '<span class="headerBold">Stop</span>';
        case 7:
            return '<span class="headerBold">Start IP</span>';
        case 8:
            return '<span class="headerBold">Stop IP</span>';
        case 9:
            return '<span class="headerBold">Reason</span>';
        case 10:
            return '<span class="headerBold">Note</span>';
    }
}
function updateSettingsCell(column) {
    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (this.instance.getDataAtCell(row, 2)) {
                cellProperties.renderer = dateRenderer;
            }
            if (column === col) {
                if (column === 5 || column === 6 || column === 9 || column === 10) {
                    cellProperties.readOnly = false;
                }
            }

            if (this.instance.getDataAtCell(row, 1) === "Sat" || this.instance.getDataAtCell(row, 1) === "Sun") {
                if (prop !== 'gdate') {
                    cellProperties.renderer = weekend;
                }
            } else if (this.instance.getDataAtCell(row, 2)) {
                cellProperties.renderer = dateRenderer;
            } else if (this.instance.getDataAtCell(row, 3) && this.instance.getDataAtCell(row, 4) == undefined) {
                if (prop !== 'gdate') {
                    cellProperties.renderer = absentRenderer;
                }
            }

            return cellProperties;
        }



    });
    return true;
}
