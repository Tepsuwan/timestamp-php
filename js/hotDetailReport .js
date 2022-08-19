

var $container = $("#data-hot");

function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = '#000';
//    td.style.background = '#ccc';
    td.style.borderBottom = '1px solid #000';
}
function totalRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = '#000';
    td.style.fontWeight = 'bold';
    td.style.borderTop = '1px solid #000';
}
function redRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = 'red';
}
function greenRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = 'green';
}
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
function strip_tags(input, allowed) {
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
            commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');

    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

function safeHtmlRenderer(instance, td, row, col, prop, value, cellProperties) {
    var escaped = Handsontable.helper.stringify(value);
    escaped = strip_tags(escaped, '<em><b><strong><a><big>'); //be sure you only allow certain HTML tags to avoid XSS threats (you should also remove unwanted HTML attributes)
    td.innerHTML = escaped;

    return td;
}
$container.handsontable({
    manualColumnResize: true,
    minSpareRows: 5,
    rowHeaders: true,
    colHeaders: true,
    contextMenu: false,
    columnSorting: false,
    stretchH: 'last',
    multiSelect: true,
    wordWrap: false,
    afterChange: function (change, source) {
        if (source === 'loadData') {
            return; //don't save this change
        }
        //----------------------------------------------------------------------
        var hot = $container.data('handsontable');
        var updateId = [];
        for (var i = 0; i <= change.length - 1; i++) {
            var row = change[i][0],
                    prop = change[i][1],
                    oldValue = change[i][2],
                    newValue = change[i][3],
                    id = hot.getDataAtCell(row, 0);

            updateId.push(id);
            if (oldValue === null) {
                oldValue = '';
            }
        }
        if (oldValue === newValue) {
            return;
        }

        //----------------------------------------------------------------------
        var url = null;
        if (JSClass.reportDetail === true) {
            url = "libs/php/updateLateOtDetail.php";
        } else {
            url = "libs/php/updateLateOt.php";
        }
        var checked = $("#monthly").is(':checked');
        var data = {
            uid: JSClass.uidDetail,
            data: JSON.stringify(change),
            updateId: JSON.stringify(updateId),
            fdate: JSClass.fdate.val(),
            tdate: JSClass.tdate.val(),
            checked: checked
        };
        $.ajax({
            url: url,
            dataType: 'json',
            type: "POST",
            data: data,
            success: function (jsonStr) {
                if (JSClass.reportDetail === true) {
                    JSClass.loadDataReportDetail(jsonStr.uid);
                }

            }
        });
    }
});

function updateSettings() {
    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (this.instance.getDataAtCell(row, 5) && prop === 'stamp_late') {
                cellProperties.renderer = redRenderer;
            } else if (this.instance.getDataAtCell(row, 6) && prop === 'stamp_ot') {
                cellProperties.renderer = greenRenderer;
            } else if (this.instance.getDataAtCell(row, 7) && prop === 'stamp_before') {
                cellProperties.renderer = redRenderer;
            }

            return cellProperties;
        },
        colHeaders: colHeaderRenderer,
        colWidths: [1, 1, 220, 180, 100, 100, 100, 100],
        columns: [
            {data: "stamp_id"},
            {
                data: "gdate",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_uid",
                renderer: safeHtmlRenderer,
                readOnly: true
            },
            {
                data: "stamp_date",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "work_shift_id",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_late",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_ot",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_before",
                renderer: centerRenderer,
                readOnly: true
            },
            {}

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
            return '<span class="headerBold">Name</span>';
        case 3:
            return '<span class="headerBold">Date</span>';
        case 4:
            return '<span class="headerBold">Work shift</span>';
        case 5:
            return '<span class="headerBold">Late</span>';
        case 6:
            return '<span class="headerBold">OT </span>';
        case 7:
            return '<span class="headerBold">Absent</span>';
        case 8:
            return '';
    }
}

function updateSettingsDetail() {
    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        colHeaders: colHeaderRendererDetail,
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (this.instance.getDataAtCell(row, 5) === "Total") {
                cellProperties.renderer = totalRenderer;
            } else if (this.instance.getDataAtCell(row, 7) && prop === 'stamp_late') {
                cellProperties.renderer = redRenderer;
            } else if (this.instance.getDataAtCell(row, 8) && prop === 'stamp_ot') {
                cellProperties.renderer = greenRenderer;
            } else if (this.instance.getDataAtCell(row, 9) && prop === 'stamp_before') {
                cellProperties.renderer = redRenderer;
            }
            return cellProperties;
        },
        colWidths: [1, 1, 220, 80, 80, 80, 100, 90, 90, 90, 100, 220, 170],
        columns: [
            {data: "stamp_id"},
            {data: "gdate"},
            {
                data: "stamp_uid",
                renderer: safeHtmlRenderer,
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
                data: "work_shift_id", type: 'autocomplete',
                renderer: myAutocompleteRenderer,
                source: function (query, process) {
                    $.ajax({
                        url: 'libs/php/autocomplete-work-shift.php',
                        data: {query: query},
                        success: function (response) {
                            process(response);
                        }
                    });
                },
                strict: false
            },
            {
                data: "stamp_late",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_ot",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_before",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "reason_id",
                renderer: leftRenderer,
                readOnly: true
            },
            {
                data: "stamp_note",
                renderer: leftRenderer,
                readOnly: true
            },
            {
                data: "stamp_ip",
                renderer: leftRenderer,
                readOnly: true
            }

        ]
    });
    return true;
}
function colHeaderRendererDetail(col) {
    switch (col) {
        case 0:
            return '';
        case 1:
            return '';
        case 2:
            return '<span class="headerBold">Name</span>';
        case 3:
            return '<span class="headerBold">Date</span>';
        case 4:
            return '<span class="headerBold">Start</span>';
        case 5:
            return '<span class="headerBold">Stop</span>';
        case 6:
            return '<span class="headerBold">Work shift</span>';
        case 7:
            return '<span class="headerBold">Late</span>';
        case 8:
            return '<span class="headerBold">OT</span>';
        case 9:
            return '<span class="headerBold">Absent</span>';
        case 10:
            return '<span class="headerBold">Reason</span>';
        case 11:
            return '<span class="headerBold">Note</span>';
        case 12:
            return '<span class="headerBold">IP</span>';

    }
}
function updateSettingsViewlist() {
    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        colHeaders: colHeaderRendererView,
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (this.instance.getDataAtCell(row, 1)) {
                cellProperties.renderer = dateRenderer;
            }
            return cellProperties;
        },
        colWidths: [1, 100, 220, 100, 100, 100, 100, 100, 150, 100],
        columns: [
            {data: "stamp_id"},
            {
                data: "gdate",
                renderer: centerRenderer,
                readOnly: true
            },
            {
                data: "stamp_uid",
                renderer: safeHtmlRenderer,
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
                data: "reason_id",
                renderer: leftRenderer,
                readOnly: true
            },
            {
                data: "stamp_note",
                renderer: leftRenderer,
                readOnly: true
            },
        ]
    });
    return true;
}

function colHeaderRendererView(col) {
    switch (col) {
        case 0:
            return '';
        case 1:
            return '';
        case 2:
            return '<span class="headerBold">Name</span>';
        case 3:
            return '<span class="headerBold">Date</span>';
        case 4:
            return '<span class="headerBold">Start</span>';
        case 5:
            return '<span class="headerBold">Stop</span>';
        case 6:
            return '<span class="headerBold">Start IP</span>';
        case 7:
            return '<span class="headerBold">Stop IP</span>';
        case 8:
            return '<span class="headerBold">Reason</span>';
        case 9:
            return '<span class="headerBold">Note</span>';


    }
}
