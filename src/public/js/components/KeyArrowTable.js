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
        const handlePreventDefault = (e) => {
            e.preventDefault()
        }
        const handleKeyUp = (e) => {
            if (!e.ctrlKey && (e.key !== 38 || e.key !== 40)) {
                elm.removeEventListener('keydown', handlePreventDefault)
                elm.removeEventListener('keyup', handlePreventDefault)
            }
        }
        if (elm.type === 'number') {
            elm.addEventListener('keydown', handlePreventDefault)
            elm.addEventListener('keyup', handlePreventDefault)
        }
        elm.onblur = (e) => {
            elm.removeEventListener('keyup', handleKeyUp)
            if (elm.type === 'number') {
                elm.addEventListener('keydown', handlePreventDefault)
                elm.addEventListener('keyup', handlePreventDefault)
            }
        }
        elm.onfocus = (e) => {
            elm.addEventListener('keyup', handleKeyUp);
            const nbRows = table.rows.length
            const nbCells = table.rows[0].cells.length
            let sPos = table.querySelector('.select'),
                tdPos = elm.parentNode
            if (sPos) sPos.classList.remove('select')
            tdPos.classList.add('select')
            document.onkeydown = (e) => {
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
