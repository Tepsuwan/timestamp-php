/*
 * 
 */

function nodeJS() {
    var wsUri = 'http://192.168.5.8:9090';//use  

    this.socket = io.connect(wsUri);
}
var nodeJS = new nodeJS();

$(function () {


    nodeJS.socket.on('connect', function () {

        nodeJS.socket.emit('subscribe', JSClass.uid);

    });


    nodeJS.socket.on('timeStampRpt', function (data) {
        if (JSClass.page.val() === 'timestampReport') {

            if (JSClass.uid === data.uid) {
                $("#data-hot").data('handsontable').loadData(data.res);
            }
            if (JSClass.Sync) {
                $('.sync').fadeOut(6000);
            } else {
                $('.loading').fadeOut(1000);
            }
        }
    });
    nodeJS.socket.on('timeStampStaffRpt', function (data) {

        if (JSClass.uid === data.uid) {
            $("#data-hot").data('handsontable').loadData(data.res);
        }
        if (JSClass.Sync) {
            $('.sync').fadeOut(6000);
        } else {
            $('.loading').fadeOut(1000);
        }
    });




});


