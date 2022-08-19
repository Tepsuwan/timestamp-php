
var serv = require("socket.io");
var io = serv.listen(9090); //use 9019 test 9020
console.log("server start");
var async = require("async");
var dateFormat = require('dateformat');
var mysql = require('mysql');
var connection = mysql.createConnection({
    host: '192.168.5.101',
    user: 'root',
    password: '123456',
    database: 'bz_timestamp'
});
connection.connect(function (err) {
    if (!err) {
        console.log("Database is connected ... \n\n");
    } else {
        console.log("Error connecting database ... \n\n");
    }
});
io.sockets.on('connection', function (socket) {

    console.log('server run ', socket.id);
    socket.on('subscribe', function (channels) {
        socket.join(channels);
    });


    socket.on('timeStampRpt', function (data) {
        getData(data.message);
    });
    socket.on('timeStampStaffRpt', function (data) {

        getDataByUser(data.message);
    });

    //--------------------------------------------------------------------------


});
//------------------------------------------------------------------------------


function getData(data) {

    var monthChecked = data.m;
    var toadyChecked = data.today;
    var team = data.team;
    var staffId = data.staff;
    var fdate = data.fdate;
    var tdate = data.tdate;
    var uid = data.uid;

    var today = new Date();
    var month = dateFormat(today, "yyyy-mm");
    var dateNow = dateFormat(today, "yyyy-mm-dd");
    var objData = [];

    var strSql = "SELECT DATE_FORMAT(a.stamp_date,'%Y-%m-%d') as date, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date,"
            + " concat('" + team + "') as team,concat('" + staffId + "') as staffId"
            + " FROM bz_timestamp.t_stamp a"
            + " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)"
            + " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id = a.stamp_id"
            + " LEFT JOIN bz_timestamp.t_reason f ON f.reason_id = a.reason_id"
            + " LEFT JOIN bz_timestamp.t_work_shift g ON g.work_shift_id = a.work_shift_id"
            + " WHERE a.is_delete = 0";
    if (team) {
        strSql += " AND b.Team = '" + team + "'";
    }
    if (staffId) {
        strSql += " AND b.id = '" + staffId + "'";
    }
    if (monthChecked === true) {
        strSql += " AND DATE_FORMAT(a.stamp_date, '%Y-%m') = '" + month + "'";
    } else if (toadyChecked === true) {
        strSql += " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') = '" + dateNow + "'";
    } else {
        strSql += " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') BETWEEN '" + fdate + "' AND '" + tdate + "'";
    }
    strSql += "GROUP BY a.stamp_date ORDER BY a.stamp_date ASC";
    //console.log('strSql->', strSql);
    connection.query(strSql, function (error, rows) {

        if (rows.length > 0) {
            async.map(rows, processRow, function (error_map, final_result) {

            });
        } else {
            objData.push({gdate: ''});
            io.sockets.in(uid).emit('timeStampRpt', {res: objData, uid: uid});
            //io.sockets.emit('timeStampRpt', {res: objData, uid: uid});
        }
    });
    function processRow(row, callback) {

        var date = row.date;
        var team = row.team;
        var staffId = row.staffId;
        var sqlQuery2 = "SELECT concat('" + date + "') as date,a.uid,concat(b.titlename,b.Name,' ( ',b.NickName,' )') as uname"
                + " FROM bz_timestamp.t_employee_time a"
                + " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.uid USING utf8) "
                + " WHERE a.is_operator=1  ";
        if (team != 'null') {
            sqlQuery2 += " AND b.Team = '" + team + "'";
        }
        if (staffId != 'null') {
            sqlQuery2 += " AND b.id = '" + staffId + "'";
        }
        sqlQuery2 += " ORDER BY b.Name ASC";
        //console.log('sqlQuery2->', sqlQuery2,uid);
        connection.query(sqlQuery2, function (error, rows) {

            async.map(rows, processRow3, function (error_map, final_result) {

                var data1 = final_result.length;
                for (var i = 0; i < data1; i++) {

                    if (i < 1)
                        objData.push({gdate: final_result[i].date});
                    //---------------------------------------------------------- 

                    var data2 = final_result[i].newdata.length;
                    var dh = dateFormat(final_result[i].date, "ddd");
                    for (var ii = 0; ii < data2; ii++) {
                        //console.log(dh, final_result[i].newdata[ii].stamp_date);
                        if (dh === 'Sat' || dh === 'Sun') {
                            if (final_result[i].newdata[ii].stamp_date) {
                                objData.push(final_result[i].newdata[ii]);
                            }
                        } else {
                            objData.push(final_result[i].newdata[ii]);
                        }
                    }
                }
                //console.log(uid);
                io.sockets.in(uid).emit('timeStampRpt', {res: objData, uid: uid});
                //io.sockets.emit('timeStampRpt', {res: objData, uid: uid});
            });
        });
    }

    function processRow3(row, callback) {

        var date = row.date;
        var uid = row.uid;
        var uname = row.uname;
        var sql3 = " SELECT b.stamp_id, DATE_FORMAT(b.stamp_date,'%d/%m/%Y') as stamp_date,DATE_FORMAT(b.stamp_date,'%a') as dText,"
                + " concat(c.titlename,c.Name ,' (',c.NickName,')') as stamp_uid,"
                + " if(b.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_start,'%H:%i:%s')) as stamp_start,"
                + " b.stamp_start_ip,"
                + " if(b.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                + " b.stamp_stop_ip,b.stamp_note,g.reason_name as reason_id"
                + " FROM bz_timestamp.t_stamp b"
                + " INNER JOIN baezenic_people.t_people c ON CONVERT(c.id USING utf8) = CONVERT(b.stamp_uid USING utf8) "
                + " LEFT JOIN bz_timestamp.t_reason g ON g.reason_id = b.reason_id"
                + " WHERE b.is_delete = 0"
                + " AND b.stamp_uid = '" + uid + "'"
                + " AND DATE_FORMAT(b.stamp_date, '%Y-%m-%d') = '" + date + "' ";
        connection.query(sql3, function (error, newdata) {
            if (newdata.length > 0) {
                row.newdata = newdata;
            } else {
                var obj = [{stamp_uid: uname}];
                row.newdata = obj;
            }
            callback(null, row);
        });
    }
}




function getDataByUser(data) {


    var monthChecked = data.m;
    var toadyChecked = data.today;
    var team = data.team;
    var staffId = data.staff;
    var staffText = data.staffText;
    var fdate = data.fdate;
    var tdate = data.tdate;
    var uid = data.uid;

    var start = null;
    var end = null;

    start = new Date(fdate);
    end = new Date(tdate);
    if (monthChecked) {
        start = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
        end = new Date();
    }
    if (toadyChecked) {
        start = new Date();
        end = new Date();
    }

    var objData = [];

    while (start <= end) {

        var date = dateFormat(start, "yyyy-mm-dd");
        returnQuery(date, staffId, staffText, function (err, data) {

            objData.push(data);
            if (start >= end) {
                io.sockets.in(uid).emit('timeStampStaffRpt', {res: objData, uid: uid});
            }
        });

        var newDate = start.setDate(start.getDate() + 1);
        start = new Date(newDate);

    }

    function returnQuery(date, staffId, staffText, callback)
    {

        var sql = " SELECT b.stamp_id, DATE_FORMAT(b.stamp_date,'%d/%m/%Y') as stamp_date,DATE_FORMAT(b.stamp_date,'%a') as dText,"
                + " concat(c.titlename,c.Name ,' (',c.NickName,')') as stamp_uid,"
                + " if(b.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_start,'%H:%i:%s')) as stamp_start,"
                + " b.stamp_start_ip,"
                + " if(b.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                + " b.stamp_stop_ip,b.stamp_note,g.reason_name as reason_id"
                + " FROM bz_timestamp.t_stamp b"
                + " INNER JOIN baezenic_people.t_people c ON CONVERT(c.id USING utf8) = CONVERT(b.stamp_uid USING utf8) "
                + " LEFT JOIN bz_timestamp.t_reason g ON g.reason_id = b.reason_id"
                + " WHERE b.is_delete = 0"
                + " AND b.stamp_uid = '" + staffId + "'"
                + " AND DATE_FORMAT(b.stamp_date, '%Y-%m-%d') = '" + date + "' ";
        //console.log(sql);
        connection.query(sql, function (error, rows) {
            if (rows.length > 0) {
                for (var i = 0; i < rows.length; ++i) {
                    var obj1 = {
                        stamp_id: rows[i].stamp_id,
                        stamp_date: rows[i].stamp_date,
                        dText: rows[i].dText,
                        stamp_uid: rows[i].stamp_uid,
                        stamp_start: rows[i].stamp_start,
                        stamp_start_ip: rows[i].stamp_start_ip,
                        stamp_stop: rows[i].stamp_stop,
                        stamp_stop_ip: rows[i].stamp_stop_ip,
                        stamp_note: rows[i].stamp_note,
                        reason_id: rows[i].reason_id
                    };
                }
            } else {
                var obj1 = {
                    stamp_uid: staffText
                };
            }
            callback(null, obj1);
        });

    }

}
