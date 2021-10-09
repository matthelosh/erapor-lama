// import XLSX from 'xlsx'
const XLSX = require('xlsx')
const saveAs = require('file-saver')

function UnduhExcel(datas,title) {
    var wb = XLSX.utils.book_new()
    wb.Props = {
        Title: title,
        Author: 'noname'
    }

    wb.SheetNames.push(title)

    var ws_data=[]
    let keys = []
    var headers = []
    Object.keys(datas[0]).forEach(k => {
        headers.push(k.toUpperCase())
        keys.push(k)
    })
    ws_data.push(headers)
    for ( var i = 0; i < datas.length; i++ ) {
        var rows = []
        for ( var j=0; j < keys.length; j++ ) {
            rows.push(datas[i][keys[j]])
        }
        ws_data.push(rows)
    }


    var ws = XLSX.utils.aoa_to_sheet(ws_data)
    wb.Sheets[title] = ws

    var wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'})
    var buf = new ArrayBuffer(wbout.length)
    var view = new Uint8Array(buf)
    for ( var i=0; i < wbout.length; i++ ) view[i] = wbout.charCodeAt(i) & 0xFF

    saveAs( new Blob([buf], { type: "application/octet-stream"}), title+".xlsx")

}

function UnduhFormat(datas, headers, title) {
    var wb = XLSX.utils.book_new()
    wb.Props = {
        Title : title,
        Author: 'noname'
    }

    wb.SheetNames.push('Nilai')

    var ws_data = []
    let keys = []
    var headers = headers
    ws_data.push(headers)
    for ( var i = 0; i < datas.length; i++) {
        var rows = []
        for ( var j=0; j < headers.length; j++) {
            rows.push(datas[i][headers[j]])
        }

        ws_data.push(rows)
    }

    var ws = XLSX.utils.aoa_to_sheet(ws_data)
    wb.Sheets['Nilai'] = ws

    var wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'})
    var buf = new ArrayBuffer(wbout.length)
    var view = new Uint8Array(buf)
    for ( var i=0; i < wbout.length; i++ ) view[i] = wbout.charCodeAt(i) & 0xFF

     saveAs( new Blob([buf], { type: "application/octet-stream"}), title+".xlsx")       

}

module.exports = {UnduhExcel, UnduhFormat}