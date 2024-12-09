import { twMerge } from 'tailwind-merge'

export const scrollToBottom = (tableName: string) => {
    const tableId = `${tableName}__container`
    const table = document.getElementById(`${tableId}`)
    if (table) {
        console.log('scrolling to the bottom', table)
        table.scrollTop = table.scrollHeight
    }
}

export const addClassToTr = (tableName: string, rowIndex: number, classList: string) => {
    const tr = document.getElementById(`${tableName}__${rowIndex}`)
    if (tr) {
        const currentClassList = tr.classList.value

        tr.classList.add(twMerge(currentClassList, classList))
    }
}

export const addTrBeforeBtmSpacer = (tableName: string, emptyRow: HTMLTableRowElement | string) => {
    const spacerId = `#${tableName} tbody>tr#spacer-bottom`
    $(spacerId).before(emptyRow)
}

export const addTrAfterTopSpacer = (tableName: string, emptyRow: HTMLTableRowElement | string) => {
    const spacerId = `#${tableName} tbody>tr#spacer-top`
    $(spacerId).after(emptyRow)
}

export const replaceDivWith = (tableName: string, groupName: string, newDiv: HTMLDivElement) => {
    const controlButtonGroup = newDiv
    const controlButtonGroupDiv = document.getElementById(`${tableName}__${groupName}`)
    controlButtonGroupDiv && controlButtonGroupDiv.replaceWith(controlButtonGroup)
}
