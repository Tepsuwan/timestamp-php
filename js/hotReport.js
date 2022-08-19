

var $container = $("#hot");

function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = '#000';
    td.style.borderBottom = '1px solid #000';
}
function weekend(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'center';
    td.style.color = 'red';
    td.style.fontWeight = 'bold';
    //td.style.background = '#00a65a';
    td.style.background = 'rgba(152, 251, 179, 0.30)';
    td.style.lineHeight = '28px';
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
function workshiftRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.AutocompleteCell.renderer.apply(this, arguments);
    td.style.textAlign = 'LEFT';
    td.style.color = 'red';
    td.style.fontWeight = 'bold';
    td.style.background = 'rgba(152, 251, 179, 0.30)';
    //td.style.background = '#00a65a';
}
function leftWeekendRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    td.style.textAlign = 'left';
    td.style.color = 'red';
    td.style.background = 'rgba(152, 251, 179, 0.30)';
    //td.style.background = '#00a65a';
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
    minSpareRows: 10,
    rowHeaders: false,
    colHeaders: true,
    contextMenu: false,
    columnSorting: false,
    //stretchH: 'all',
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
        url = "libs/php/updateLateOtDetail.php";

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
            if (this.instance.getDataAtCell(row, 6) && prop === 'stamp_late') {
                cellProperties.renderer = redRenderer;
            } else if (this.instance.getDataAtCell(row, 7) && prop === 'stamp_ot') {
                cellProperties.renderer = greenRenderer;
            } else if (this.instance.getDataAtCell(row, 8) && prop === 'stamp_before') {
                cellProperties.renderer = redRenderer;
            }
            return cellProperties;
        },
        colHeaders: colHeaderRenderer,
        colWidths: [1, 1, 240, 180, 100, 100, 100, 100, 100, 540],
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
                data: "work_hours",
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
            return '<span class="headerBold">Work Hours</span>';
        case 6:
            return '<span class="headerBold">Late(minute)</span>';
        case 7:
            return '<span class="headerBold">OT(minute) </span>';
        case 8:
            return '<span class="headerBold">Absence</span>';
        case 9:
            return '';
    }
}

function updateSettingsDetail() {
    var hot = $container.handsontable('getInstance');
    hot.updateSettings({
        //minSpareRows: 10,
        colHeaders: colHeaderRendererDetail,
        cells: function (row, col, prop) {
            var cellProperties = {};
            if (this.instance.getDataAtCell(row, 6) === "Total") {
                cellProperties.renderer = totalRenderer;
            } else if (this.instance.getDataAtCell(row, 9) && prop === 'stamp_late') {
                cellProperties.renderer = redRenderer;
            } else if (this.instance.getDataAtCell(row, 10) && prop === 'stamp_ot') {
                cellProperties.renderer = greenRenderer;
            } else if (this.instance.getDataAtCell(row, 11) && prop === 'stamp_before') {
                cellProperties.renderer = redRenderer;
            }
            if (this.instance.getDataAtCell(row, 2) === "Sat" || this.instance.getDataAtCell(row, 2) === "Sun") {
                if (prop === 'stamp_uid' || prop === 'stamp_note') {
                    cellProperties.renderer = leftWeekendRenderer;
                } else if (prop === 'work_shift_id') {
                    cellProperties.renderer = workshiftRenderer;
                } else {
                    cellProperties.renderer = weekend;
                }
            }
            return cellProperties;
        },
        colWidths: [1, 1, 50, 220, 80, 60, 60, 100, 60, 60, 60, 60, 150, 290, 150],
        columns: [
            {data: "stamp_id"},
            {data: "gdate"},
            {
                data: "dText",
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
                data: "work_hours",
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
}
function colHeaderRendererDetail(col) {
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
            return '<span class="headerBold">Work shift</span>';
        case 8:
            return '<span class="headerBold">Work Hours</span>';
        case 9:
            return '<span class="headerBold">Late(m)</span>';
        case 10:
            return '<span class="headerBold">OT(m)</span>';
        case 11:
            return '<span class="headerBold">Absence</span>';
        case 12:
            return '<span class="headerBold">Reason</span>';
        case 13:
            return '<span class="headerBold">Note</span>';
        case 14:
            return '<span class="headerBold">IP</span>';

    }
}
