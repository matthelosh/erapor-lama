function CetakTable(datas = [], title="Judul Dokumen") {
    // console.log(datas)
    let win = window.open('','_blank','width=800,height=700')
    let theads = ''
    let ks = []
    Object.keys(datas[0]).forEach(k => {
        theads += `<th>${k.toUpperCase()}</th>`
        ks.push(k)
    })
    
    let tr = ''
    for (var i = 0; i < datas.length; i++) {
        var tds = ''
        for ( var j=0; j < ks.length; j++ ) {
            // console.log(ks[j], datas[i][ks[j]])
            tds += `<td>${datas[i][ks[j]]}</td>`
        }
        tr += `<tr>${tds}</tr>`
        
    }


    let html = `
                <!doctype html>
                <html>
                    <header>
                        <title>${title}</title>
                        <style>
                            table {
                                border-collapse: collapse;
                            }
                        </style>
                    </header>
                    <body>
                        <div class="wrapper">
                            <article>
                                <h1>${title}</h1>
                                <h4>${new Date()}</h4>
                                <table border="1">
                                    <thead>
                                        ${theads}
                                    </thead>
                                    <tbody>
                                        ${tr}
                                    </tbody>
                                </table>
                            </article>
                        </div>
                    </body>
                </html>

                `
    win.document.write(html)
    setTimeout(() => {
        win.print()
        win.close()
    }, 500);
}

function CetakLabel() {
    alert('Hi')
}

module.exports  = {CetakTable,CetakLabel}