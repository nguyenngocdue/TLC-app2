const keyArrowTable = (idTable) => {
    const table = document.querySelector(idTable)
    movKey = {
        ArrowUp: (p, nbRows) => (p.r = p.r !== 2 ? --p.r - 2 : nbRows - 1),
        ArrowDown: (p, nbRows) => (p.r = p.r !== nbRows + 1 ? ++p.r - 2 : 0),
    }
    const array = [
        ...table.querySelectorAll('select'),
        ...table.querySelectorAll('input'),
        ...table.querySelectorAll('textarea'),
        ...table.querySelectorAll('check'),
    ]
    array.forEach((elm) => {
        if (elm.type === 'number') {
            elm.addEventListener('keydown', function (event) {
                event.preventDefault()
            })
            elm.addEventListener('keyup', function (event) {
                event.preventDefault()
            })
        }
        elm.onfocus = (e) => {
            const nbRows = table.rows.length
            const nbCells = table.rows[0].cells.length
            let sPos = table.querySelector('.select'),
                tdPos = elm.parentNode
            if (sPos) sPos.classList.remove('select')
            tdPos.classList.add('select')
            document.onkeydown = (e) => {
                elm.select()
                let sPos = table.querySelector('.select')
                evt = e == null ? event : e
                o = e.srcElement || e.target
                if (!o) {
                    return
                }
                if (
                    o.tagName !== 'TEXTAREA' &&
                    o.tagName !== 'INPUT' &&
                    o.tagName !== 'SELECT'
                ) {
                    return
                }
                pos = {
                    r: sPos ? sPos.parentNode.rowIndex : -1,
                    c: sPos ? sPos.cellIndex : -1,
                }
                if (sPos && evt.ctrlKey && movKey[evt.code]) {
                    let loop = true,
                        nxFocus = null,
                        cell = null
                    do {
                        movKey[evt.code](pos, nbRows)
                        cell = table.rows[pos.r].cells[pos.c]
                        nxFocus =
                            cell.querySelector('input') ||
                            cell.querySelector('select') ||
                            cell.querySelector('textarea') // get focussable element of <td>
                        if (
                            nxFocus &&
                            cell.style.display !== 'none' &&
                            cell.parentNode.style.display !== 'none'
                        ) {
                            // nxFocus.addEventListener('focus', function () {
                            //     nxFocus.select()
                            // })
                            nxFocus.focus()
                            loop = false
                        }
                    } while (loop)
                }
            }
        }
    })
}
