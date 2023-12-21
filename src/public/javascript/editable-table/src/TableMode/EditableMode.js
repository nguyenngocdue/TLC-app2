import { TableColGroup } from '../TableColGroup'
import { TableFooterAgg } from '../TableFooterAgg'
import { TableHeader } from '../TableHeader'
import { TableHeaderToolbar } from '../TableHeaderToolbar'
import { TableRows } from '../TableRows/TableRows'

export const EditableMode = (params) => {
    // console.log(params)
    const { headerToolbar } = params

    const tableHeader = TableHeader(params)
    const tableHeaderToolbar = headerToolbar ? TableHeaderToolbar(params) : ``
    const tableRows = TableRows(params)
    const tableFooterAgg = TableFooterAgg(params)
    const tableColGroup = TableColGroup(params)

    const { tableId, maxH, tableWidth } = params.tableParams

    const table = `<div 
        class="table-wrp block overflow-x-auto ${maxH}}"
        style="table-layout: auto; ${tableWidth}"
        >
        <table id="${tableId}" class="w-full border-separate border-spacing-0 whitespace-no-wrap">
            ${tableColGroup}
            ${tableHeader}
            ${tableHeaderToolbar}
            ${tableRows}
            ${tableFooterAgg}
        </table>
        <button type="button" class="border rounded bg-purple-500 p-2 m-1" onclick="console.log(editableTables)">Click Me</button>
    </div>`

    return table
}
