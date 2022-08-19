

var $container = $("#data-hot");
var autosaveNotification;
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
function satRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'CENTER';
    td.style.color = 'red';
    td.style.background = 'rgba(152, 251, 179, 0.30)';
    td.style.fontWeight = 'bold';
    td.style.lineHeight = '25px';
//    td.style.borderTop = '1px solid #aeacac';
}

function sunRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'CENTER';
    td.style.color = 'red';
    td.style.background = 'rgba(152, 251, 179, 0.30)';
    td.style.fontWeight = 'bold';
    td.style.lineHeight = '25px';
}
function nowRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'CENTER';
    td.style.color = '#FFF';
    td.style.background = '#5cb85c';
    td.style.fontWeight = 'bold';
    td.style.borderBottom = '1px solid gray';
    td.style.lineHeight = '40px';
}
function nowAutocompleteRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.AutocompleteCell.renderer.apply(this, arguments);
    td.style.textAlign = 'LEFT';
    td.style.color = '#FFF';
    td.style.background = '#5cb85c';
    td.style.borderBottom = '1px solid gray';
}
function nowLeftRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'LEFT';
    td.style.color = '#FFF';
    td.style.background = '#5cb85c';
    td.style.fontWeight = 'bold';
    td.style.borderBottom = '1px solid gray';
    td.style.lineHeight = '40px';
}
function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = '#000';
    td.style.borderBottom = '1px solid gray';
}
function absentRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'left';
    td.style.color = '#000';
    td.style.background = '#F28A8C';//'rgba(221, 75, 57, 0.54)';
}
function holidaysRemainRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'CENTER';
    if (value > 0) {
        td.style.color = 'orange';
    } else {
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
    minSpareRows: 3,
    rowHeaders: true,
    colHeaders: true,
    contextMenu: false,
    clumnSorting: true,
    multiSelect: true,
    //stretchH: 'last',
    wordWrap: false,
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
            if (oldValue === null || oldValue === undefined) {
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
            url: "libs/php/updateStampNote.php",
            dataType: 'json',
            type: "POST",
            data: data,
            success: function (jsonStr) {

                if (jsonStr.success === false) {
                    if (jsonStr.reason === "YES") {
                        console.log(jsonStr, jsonStr.reason_detail);
                        //$("#reasonModal").modal('show');
                        reasonModel(jsonStr.reason_day, jsonStr.reason_name, jsonStr.reason_detail);
                    }
                } else {
                    nodeJS.socket.emit('timeStampRpt', {message: 'client'});
                    $(".console").text('Auto save ...');
                    autosaveNotification = setTimeout(function () {
                        $(".console").text('Changes will be autosaved');
                    }, 1000);
                }
            }
        });
    },
    beforeRemoveRow: function (index, amount) {
        //----------------------------------------------------
        var selection = $container.handsontable('getSelected');
        if (selection) {
            if (confirm("Press OK to confirm Delete.") == true) {
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
        fixedRowsTop: 1,
        colHeaders: colHeaderRenderer,
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (row === 0) {
                cellProperties.renderer = nowRenderer;
                if (col === 5) {
                    cellProperties.renderer = nowAutocompleteRenderer;
                } else if (col === 6) {
                    cellProperties.renderer = nowLeftRenderer;
                }
            }
            if (this.instance.getDataAtCell(row, 1) === "Sat") {
                cellProperties.renderer = satRenderer;
            } else if (this.instance.getDataAtCell(row, 1) === "Sun") {
                cellProperties.renderer = sunRenderer;
            }
            return cellProperties;
        },
        colWidths: [1, 1, 120, 120, 120, 180, 670],
        columns: [
            {data: "stamp_id"},
            {
                data: "stamp_day",
                renderer: centerRenderer,
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
                data: "stamp_note",
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
function colLogOnRenderer(col) {
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
function updateLogOnSettings() {
    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        fixedRowsTop: 0,
        colHeaders: colLogOnRenderer,
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (this.instance.getDataAtCell(row, 1) === "Sat" || this.instance.getDataAtCell(row, 1) === "Sun") {
                if (prop !== 'gdate') {
                    //cellProperties.renderer = weekend;
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
        colWidths: [1, 1, 90, 240, 100, 100, 100, 100, 100, 150, 380],
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

function updateHolidaySettings() {

    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        fixedRowsTop: 0,
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
            {
                data: "reason_name"
            },
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
